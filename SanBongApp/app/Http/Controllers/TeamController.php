<?php

namespace App\Http\Controllers;

use App\Models\Team;
use App\Models\Tournament;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TeamController extends Controller
{
    public function create(Tournament $tournament)
    {
        return view('teams.create', compact('tournament'));
    }

    public function store(Request $request, Tournament $tournament)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'contact_phone' => 'required|string|max:20',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        $validated['tournament_id'] = $tournament->id;
        $validated['manager_id'] = Auth::id();

        if ($request->hasFile('logo')) {
            $logoName = time() . '.' . $request->logo->extension();
            $request->logo->move(public_path('uploads/teams'), $logoName);
            $validated['logo'] = '/uploads/teams/' . $logoName;
        }

        Team::create($validated);
        return redirect()->route('tournaments.show', $tournament->id)->with('success', 'Đăng ký đội bóng thành công! Vui lòng chờ quản trị viên phê duyệt.');
    }

    public function approve(Team $team)
    {
        $team->update(['status' => 'approved']);
        return back()->with('success', 'Đã phê duyệt đội bóng: ' . $team->name);
    }

    public function reject(Team $team)
    {
        $team->update(['status' => 'rejected']);
        return back()->with('success', 'Đã từ chối đội bóng: ' . $team->name);
    }

    // Admin manual team creation
    public function adminCreate(Tournament $tournament)
    {
        return view('admin.teams.create', compact('tournament'));
    }

    public function adminStore(Request $request, Tournament $tournament)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'contact_phone' => 'nullable|string|max:20',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'group_name' => 'nullable|string|max:50',
        ]);

        $validated['tournament_id'] = $tournament->id;
        $validated['manager_id'] = Auth::id(); // Assign admin as manager for now
        $validated['status'] = 'approved'; // Auto-approve for admins

        if ($request->hasFile('logo')) {
            $logoName = time() . '.' . $request->logo->extension();
            $request->logo->move(public_path('uploads/teams'), $logoName);
            $validated['logo'] = '/uploads/teams/' . $logoName;
        }

        Team::create($validated);
        return redirect()->route('tournaments.show', $tournament->id)->with('success', 'Đã thêm đội bóng thủ công vào giải đấu.');
    }

    public function eliminate(Team $team)
    {
        $team->update(['is_eliminated' => true]);
        return back()->with('success', 'Đã đánh dấu loại đội bóng: ' . $team->name);
    }

    public function restore(Team $team)
    {
        $team->update(['is_eliminated' => false]);
        return back()->with('success', 'Đã khôi phục đội bóng: ' . $team->name);
    }

    public function assignGroup(Request $request, Team $team)
    {
        $validated = $request->validate([
            'group_name' => 'nullable|string|max:50',
        ]);
        
        $team->update(['group_name' => $validated['group_name']]);
        return back()->with('success', 'Đã gán '. $team->name .' vào Bảng: ' . ($validated['group_name'] ?: 'Chung'));
    }
}
