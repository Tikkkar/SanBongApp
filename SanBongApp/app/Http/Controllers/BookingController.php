<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Pitch;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BookingController extends Controller
{
    public function index()
    {
        // customer watching their own bookings
        $bookings = Auth::user()->bookings()->with('pitch')->orderBy('created_at', 'desc')->get();
        return view('bookings.index', compact('bookings'));
    }

    public function create(Request $request)
    {
        $pitchId = $request->get('pitch_id');
        $pitch = Pitch::findOrFail($pitchId);
        
        $customPrices = $pitch->customPrices()->get();
        // precalculate price logic data to pass to JS
        $pricesJson = $customPrices->map(function ($cp) {
            return [
                'start' => $cp->start_time,
                'end' => $cp->end_time,
                'price' => $cp->price_per_hour
            ];
        })->toJson();
        
        return view('bookings.create', compact('pitch', 'pricesJson'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'pitch_id' => 'required|exists:pitches,id',
            'customer_name' => 'required|string|max:255',
            'customer_phone' => 'required|string|max:20',
            'booking_date' => 'required|date|after_or_equal:today',
            'time_slot' => 'required|string', // e.g., "06:00"
        ]);

        $start_time_str = $request->booking_date . ' ' . $request->time_slot . ':00';
        $start_time = \Carbon\Carbon::parse($start_time_str);
        // Each match is exactly 45 minutes
        $end_time = $start_time->copy()->addMinutes(45);

        // Basic overlap check
        $overlap = Booking::where('pitch_id', $request->pitch_id)
            ->where('status', '!=', 'cancelled')
            ->where(function ($query) use ($start_time, $end_time) {
                // Check if any booking overlaps with this 45-min interval
                $query->where(function($q) use ($start_time, $end_time) {
                    $q->where('start_time', '<', $end_time)
                      ->where('end_time', '>', $start_time);
                });
            })->exists();

        if ($overlap) {
            return back()->withErrors(['time_slot' => 'Khung giờ này đã có người đặt.'])->withInput();
        }

        $pitch = Pitch::find($request->pitch_id);
        
        $slotStartOnlyTime = $start_time->format('H:i:s');
        $applicablePriceHour = $pitch->price_per_hour;
        
        $customPrices = $pitch->customPrices()->get();
        foreach ($customPrices as $customPrice) {
            if ($slotStartOnlyTime >= $customPrice->start_time && $slotStartOnlyTime < $customPrice->end_time) {
                $applicablePriceHour = $customPrice->price_per_hour;
                break;
            }
        }
        
        // Price is strictly per match, no multiplier needed
        $totalPrice = $applicablePriceHour;

        Booking::create([
            'user_id' => Auth::id(),
            'pitch_id' => $request->pitch_id,
            'customer_name' => $request->customer_name,
            'customer_phone' => $request->customer_phone,
            'start_time' => $start_time,
            'end_time' => $end_time,
            'total_price' => $totalPrice,
            'status' => 'pending',
        ]);

        return redirect()->route('bookings.index')->with('success', 'Đặt sân thành công, vui lòng chờ duyệt.');
    }

    // Admin approval
    public function approve(Booking $booking)
    {
        $booking->update(['status' => 'confirmed']);
        return back()->with('success', 'Đã duyệt đơn đặt sân.');
    }

    public function cancel(Booking $booking)
    {
        if (Auth::id() === $booking->user_id || Auth::user()->role === 'admin') {
            $booking->update(['status' => 'cancelled']);
            return back()->with('success', 'Đã hủy đơn đặt sân.');
        }
        abort(403);
    }

    // Admin Bookings List
    public function adminIndex(Request $request)
    {
        $query = Booking::with(['user', 'pitch'])->orderBy('start_time', 'desc');

        if ($request->has('date') && $request->date != '') {
            $query->whereDate('start_time', $request->date);
        }

        if ($request->has('pitch_id') && $request->pitch_id != '') {
            $query->where('pitch_id', $request->pitch_id);
        }
        
        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }

        $bookings = $query->paginate(20)->withQueryString();
        $pitches = Pitch::all();
        
        return view('admin.bookings.index', compact('bookings', 'pitches'));
    }

    // Admin manual booking
    public function adminCreate(Request $request)
    {
        $pitches = Pitch::where('is_active', true)->get();
        $date = $request->get('date', date('Y-m-d'));
        
        $selectedPitch = null;
        $timeSlots = [];
        
        if ($request->has('pitch_id') && $request->pitch_id != '') {
            $selectedPitch = Pitch::find($request->pitch_id);
            if ($selectedPitch) {
                $bookings = \App\Models\Booking::where('pitch_id', $selectedPitch->id)
                    ->whereDate('start_time', $date)
                    ->where('status', '!=', 'cancelled')
                    ->get();
                    
                $start = strtotime('06:00');
                $end = strtotime('22:30');
                $customPrices = $selectedPitch->customPrices()->get();
                
                while ($start <= $end) {
                    $slotTimeStr = date('H:i', $start);
                    $slotStartTime = \Carbon\Carbon::parse($date . ' ' . $slotTimeStr . ':00');
                    $slotEndTime = $slotStartTime->copy()->addMinutes(45);
                    
                    $isBooked = false;
                    foreach ($bookings as $booking) {
                        if ($booking->start_time < $slotEndTime && $booking->end_time > $slotStartTime) {
                            $isBooked = true;
                            break;
                        }
                    }
                    
                    $slotStartOnlyTime = $slotStartTime->format('H:i:s');
                    $applicablePriceHour = $selectedPitch->price_per_hour;
                    foreach ($customPrices as $customPrice) {
                        if ($slotStartOnlyTime >= $customPrice->start_time && $slotStartOnlyTime < $customPrice->end_time) {
                            $applicablePriceHour = $customPrice->price_per_hour;
                            break;
                        }
                    }
                    
                    $timeSlots[] = [
                        'time' => $slotTimeStr,
                        'is_booked' => $isBooked,
                        'is_past' => $slotStartTime->isPast(),
                        'price' => $applicablePriceHour
                    ];
                    
                    $start = strtotime('+45 minutes', $start);
                }
            }
        }
        
        return view('admin.bookings.create', compact('pitches', 'selectedPitch', 'date', 'timeSlots'));
    }

    public function adminStore(Request $request)
    {
        $request->validate([
            'pitch_id' => 'required|exists:pitches,id',
            'customer_name' => 'required|string|max:255',
            'customer_phone' => 'nullable|string|max:20',
            'booking_date' => 'required|date',
            'time_slot' => 'required|string',
            'total_price' => 'required|numeric|min:0',
        ]);

        $start_time = \Carbon\Carbon::parse($request->booking_date . ' ' . $request->time_slot . ':00');
        $end_time = $start_time->copy()->addMinutes(45);
        
        $overlap = Booking::where('pitch_id', $request->pitch_id)
            ->where('status', '!=', 'cancelled')
            ->where(function ($query) use ($start_time, $end_time) {
                $query->where(function($q) use ($start_time, $end_time) {
                    $q->where('start_time', '<', $end_time)
                      ->where('end_time', '>', $start_time);
                });
            })->exists();

        if ($overlap) {
            return back()->withErrors(['time_slot' => 'Khung giờ này đã có người đặt, không thể chèn lịch.'])->withInput();
        }

        Booking::create([
            'user_id' => Auth::id(), // Use admin's ID
            'pitch_id' => $request->pitch_id,
            'customer_name' => $request->customer_name,
            'customer_phone' => $request->customer_phone ?? 'Không cung cấp',
            'start_time' => $start_time,
            'end_time' => $end_time,
            'total_price' => $request->total_price,
            'status' => 'confirmed', // Auto-confirm
        ]);

        return redirect()->route('admin.dashboard')->with('success', 'Đã lưu lịch đặt sân thủ công cho khách vãng lai.');
    }
}
