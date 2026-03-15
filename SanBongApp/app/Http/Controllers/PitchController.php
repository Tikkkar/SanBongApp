<?php

namespace App\Http\Controllers;

use App\Models\Pitch;
use Illuminate\Http\Request;

class PitchController extends Controller
{
    public function index(Request $request)
    {
        $query = Pitch::where('is_active', true);
        
        if ($request->has('search') && $request->search != '') {
            $query->where('name', 'like', '%' . $request->search . '%');
        }
        
        $pitches = $query->get();
        return view('pitches.index', compact('pitches'));
    }

    public function show(Request $request, Pitch $pitch)
    {
        $date = $request->get('date', date('Y-m-d'));
        
        // Fetch all bookings for this pitch on the selected date that are not cancelled
        $bookings = \App\Models\Booking::where('pitch_id', $pitch->id)
            ->whereDate('start_time', $date)
            ->where('status', '!=', 'cancelled')
            ->get();
            
        // Generate daily slots from 06:00 to 22:30 (45 mins each)
        $timeSlots = [];
        $start = strtotime('06:00');
        $end = strtotime('22:30');
        
        // Fetch custom prices for this pitch
        $customPrices = $pitch->customPrices()->get();

        while ($start <= $end) {
            $slotTimeStr = date('H:i', $start);
            $slotStartTime = \Carbon\Carbon::parse($date . ' ' . $slotTimeStr . ':00');
            $slotEndTime = $slotStartTime->copy()->addMinutes(45);
            
            $isBooked = false;
            foreach ($bookings as $booking) {
                // Check for overlap
                if ($booking->start_time < $slotEndTime && $booking->end_time > $slotStartTime) {
                    $isBooked = true;
                    break;
                }
            }
            
            // Determine price for this slot
            // Convert to simple time strings for comparison like "14:00:00"
            $slotStartOnlyTime = $slotStartTime->format('H:i:s');
            $slotEndOnlyTime = $slotEndTime->format('H:i:s');
            
            $applicablePriceHour = $pitch->price_per_hour; // fallback
            
            foreach ($customPrices as $customPrice) {
                // simple logical check if our slot falls ANYWHERE into this custom time bracket
                // For simplicity, we check if the slot's start time falls within the configured bracket
                if ($slotStartOnlyTime >= $customPrice->start_time && $slotStartOnlyTime < $customPrice->end_time) {
                    $applicablePriceHour = $customPrice->price_per_hour;
                    break;
                }
            }
            
            // 45 mins price is now flat per match
            $slotPrice = $applicablePriceHour;

            $timeSlots[] = [
                'time' => $slotTimeStr,
                'is_booked' => $isBooked,
                'is_past' => $slotStartTime->isPast(),
                'price' => $slotPrice,
                'price_hour' => $applicablePriceHour
            ];
            
            $start = strtotime('+45 minutes', $start);
        }

        return view('pitches.show', compact('pitch', 'date', 'timeSlots'));
    }

    // Admin methods
    public function create()
    {
        return view('admin.pitches.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'price_per_hour' => 'required|numeric|min:0',
            'capacity' => 'nullable|integer|min:1',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048', 
        ]);

        if ($request->hasFile('image')) {
            $imageName = time() . '.' . $request->image->extension();
            $request->image->move(public_path('uploads/pitches'), $imageName);
            $validated['image'] = '/uploads/pitches/' . $imageName;
        }

        Pitch::create($validated);
        return redirect()->route('admin.dashboard')->with('success', 'Thêm sân bóng thành công.');
    }

    public function edit(Pitch $pitch)
    {
        return view('admin.pitches.edit', compact('pitch'));
    }

    public function update(Request $request, Pitch $pitch)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'price_per_hour' => 'required|numeric|min:0',
            'capacity' => 'nullable|integer|min:1',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'is_active' => 'boolean',
        ]);

        if ($request->hasFile('image')) {
            $imageName = time() . '.' . $request->image->extension();
            $request->image->move(public_path('uploads/pitches'), $imageName);
            $validated['image'] = '/uploads/pitches/' . $imageName;
        }

        // Deal with checkbox missing from request when unchecked
        $validated['is_active'] = $request->has('is_active');

        $pitch->update($validated);
        return redirect()->route('admin.dashboard')->with('success', 'Cập nhật sân bóng thành công.');
    }

    public function destroy(Pitch $pitch)
    {
        $pitch->delete();
        return redirect()->route('admin.dashboard')->with('success', 'Đã xóa sân bóng.');
    }

    public function toggleActive(Pitch $pitch)
    {
        $pitch->update(['is_active' => !$pitch->is_active]);
        $status = $pitch->is_active ? 'Đã kích hoạt' : 'Đã tạm đóng';
        return back()->with('success', "{$status} sân bóng: {$pitch->name}");
    }

    // Pitch Price Management
    public function pricesIndex(Pitch $pitch)
    {
        return view('admin.pitches.prices', compact('pitch'));
    }

    public function storePrice(Request $request, Pitch $pitch)
    {
        $validated = $request->validate([
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'price_per_hour' => 'required|numeric|min:0',
        ]);

        $pitch->customPrices()->create($validated);
        return back()->with('success', 'Đã thêm khung giá mới thành công.');
    }

    public function destroyPrice(Pitch $pitch, \App\Models\PitchPrice $price)
    {
        $price->delete();
        return back()->with('success', 'Đã xóa cấu hình giá.');
    }
}
