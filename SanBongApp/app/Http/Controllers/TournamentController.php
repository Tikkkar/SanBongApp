<?php

namespace App\Http\Controllers;

use App\Models\Tournament;
use Illuminate\Http\Request;

class TournamentController extends Controller
{
    public function index()
    {
        $tournaments = Tournament::where('status', '!=', 'completed')
            ->orderBy('start_date', 'asc')
            ->get();
        return view('tournaments.index', compact('tournaments'));
    }

    public function show(Tournament $tournament)
    {
        $tournament->load(['teams', 'matches.homeTeam', 'matches.awayTeam']);
        
        $approvedTeams = $tournament->teams->where('status', 'approved');
        $pendingTeams = $tournament->teams->where('status', 'pending');
        
        $leaderboard = $approvedTeams->map(function ($team) use ($tournament) {
            $played = 0; $won = 0; $drawn = 0; $lost = 0;
            $gf = 0; $ga = 0;

            foreach ($tournament->matches as $match) {
                if ($match->status !== 'finished') continue;
                
                if ($match->home_team_id == $team->id) {
                    $played++;
                    $gf += $match->home_score;
                    $ga += $match->away_score;
                    if ($match->home_score > $match->away_score) $won++;
                    elseif ($match->home_score == $match->away_score) $drawn++;
                    else $lost++;
                } elseif ($match->away_team_id == $team->id) {
                    $played++;
                    $gf += $match->away_score;
                    $ga += $match->home_score;
                    if ($match->away_score > $match->home_score) $won++;
                    elseif ($match->away_score == $match->home_score) $drawn++;
                    else $lost++;
                }
            }

            $points = ($won * 3) + ($drawn * 1);
            $gd = $gf - $ga;

            return (object) [
                'team' => $team,
                'played' => $played,
                'won' => $won,
                'drawn' => $drawn,
                'lost' => $lost,
                'gf' => $gf,
                'ga' => $ga,
                'gd' => $gd,
                'points' => $points,
            ];
        })->sortByDesc(function ($item) {
            return [$item->points, $item->gd, $item->gf];
        })->values();

        $leaderboard = $leaderboard->groupBy(function($item) {
            return $item->team->group_name ?: 'BXH Chung';
        })->sortKeys();

        return view('tournaments.show', compact('tournament', 'leaderboard', 'pendingTeams'));
    }

    // Admin routes
    public function create()
    {
        return view('admin.tournaments.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'description' => 'nullable|string',
            'status' => 'in:upcoming,ongoing,completed',
            'logo' => 'nullable|image|max:2048',
            'format' => 'nullable|string|max:50',
            'max_teams' => 'nullable|integer|min:2',
            'registration_fee' => 'nullable|numeric|min:0',
            'prize_pool' => 'nullable|string|max:255',
            'registration_deadline' => 'nullable|date|before_or_equal:start_date',
            'pitch_type' => 'nullable|string|max:50',
        ]);

        $validated['requires_approval'] = $request->has('requires_approval');

        if ($request->hasFile('logo')) {
            $path = $request->file('logo')->store('tournaments', 'public');
            $validated['logo'] = '/storage/' . $path;
        }

        Tournament::create($validated);
        return redirect()->route('admin.dashboard')->with('success', 'Tạo giải đấu thành công.');
    }

    public function edit(Tournament $tournament)
    {
        return view('admin.tournaments.edit', compact('tournament'));
    }

    public function update(Request $request, Tournament $tournament)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'description' => 'nullable|string',
            'status' => 'in:upcoming,ongoing,completed',
            'logo' => 'nullable|image|max:2048',
            'format' => 'nullable|string|max:50',
            'max_teams' => 'nullable|integer|min:2',
            'registration_fee' => 'nullable|numeric|min:0',
            'prize_pool' => 'nullable|string|max:255',
            'registration_deadline' => 'nullable|date|before_or_equal:start_date',
            'pitch_type' => 'nullable|string|max:50',
        ]);

        $validated['requires_approval'] = $request->has('requires_approval');

        if ($request->hasFile('logo')) {
            $path = $request->file('logo')->store('tournaments', 'public');
            $validated['logo'] = '/storage/' . $path;
        }

        $tournament->update($validated);
        return redirect()->route('admin.dashboard')->with('success', 'Cập nhật giải đấu thành công.');
    }

    public function destroy(Tournament $tournament)
    {
        $tournament->delete();
        return redirect()->route('admin.dashboard')->with('success', 'Đã xóa giải đấu.');
    }
}
