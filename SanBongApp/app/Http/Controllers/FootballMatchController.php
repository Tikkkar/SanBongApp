<?php

namespace App\Http\Controllers;

use App\Models\FootballMatch;
use App\Models\Tournament;
use Illuminate\Http\Request;

class FootballMatchController extends Controller
{
    public function create(Tournament $tournament)
    {
        $teams = $tournament->teams()->where('status', 'approved')->get();
        return view('admin.matches.create', compact('tournament', 'teams'));
    }

    public function store(Request $request, Tournament $tournament)
    {
        $validated = $request->validate([
            'home_team_id' => 'required|exists:teams,id|different:away_team_id',
            'away_team_id' => 'required|exists:teams,id',
            'match_time' => 'required|date',
            'round_name' => 'nullable|string|max:50',
        ]);

        $validated['tournament_id'] = $tournament->id;
        $validated['status'] = 'scheduled';
        $validated['home_score'] = 0;
        $validated['away_score'] = 0;

        FootballMatch::create($validated);
        return redirect()->route('tournaments.show', $tournament->id)->with('success', 'Đã thêm lịch thi đấu mới.');
    }

    public function generateFixtures(Request $request, Tournament $tournament)
    {
        // Get all approved teams
        $teams = $tournament->teams()->where('status', 'approved')->pluck('id')->toArray();
        if (count($teams) < 2) {
            return back()->with('error', 'Cần ít nhất 2 đội bóng đã được duyệt để bốc thăm.');
        }

        // Get all existing matches in this tournament to check for existing pairings
        $existingMatches = FootballMatch::where('tournament_id', $tournament->id)
            ->get()
            ->map(function ($match) {
                // Ensure smaller ID is always first for consistent pairing comparison
                $home = min($match->home_team_id, $match->away_team_id);
                $away = max($match->home_team_id, $match->away_team_id);
                return "{$home}-{$away}";
            })
            ->toArray();

        // Generate all possible valid pairs (Round-Robin style where team A vs Team B occurs once)
        $possiblePairs = [];
        $teamCount = count($teams);
        for ($i = 0; $i < $teamCount - 1; $i++) {
            for ($j = $i + 1; $j < $teamCount; $j++) {
                $home = min($teams[$i], $teams[$j]);
                $away = max($teams[$i], $teams[$j]);
                $pairStr = "{$home}-{$away}";

                // Only add to possible list if they haven't played each other yet
                if (!in_array($pairStr, $existingMatches)) {
                    $possiblePairs[] = ['home_team_id' => $teams[$i], 'away_team_id' => $teams[$j]]; // We can keep original assignment for Home/Away unpredictability though the string key is normalized
                }
            }
        }

        if (empty($possiblePairs)) {
            return back()->with('info', 'Tất cả các đội đã được ghép cặp thi đấu với nhau (Hoàn tất vòng bảng).');
        }

        // Pick 1 random pair from the available ones
        $randomPairIndex = array_rand($possiblePairs);
        $selectedPair = $possiblePairs[$randomPairIndex];

        // Determine match time - default to +1 day from the latest match, or tomorrow 8 AM if no matches exist
        $latestMatch = FootballMatch::where('tournament_id', $tournament->id)->latest('match_time')->first();
        $matchTime = $latestMatch ? $latestMatch->match_time->addDays(1)->setHour(8)->setMinute(0) : now()->addDays(1)->setHour(8)->setMinute(0);

        // Add the single random match
        FootballMatch::create([
            'tournament_id' => $tournament->id,
            'home_team_id' => $selectedPair['home_team_id'],
            'away_team_id' => $selectedPair['away_team_id'],
            'match_time' => $matchTime,
            'home_score' => 0,
            'away_score' => 0,
            'status' => 'scheduled'
        ]);

        return redirect()->route('tournaments.show', $tournament->id)
            ->with('success', "Đã bốc thăm ngẫu nhiên thành công 1 trận đấu mới! Hãy vào cập nhật ngày giờ nếu cần.");
    }

    public function editScore(FootballMatch $match)
    {
        $match->load(['tournament', 'homeTeam', 'awayTeam']);
        return view('admin.matches.edit_score', compact('match'));
    }

    public function updateScore(Request $request, FootballMatch $match)
    {
        $validated = $request->validate([
            'home_score' => 'required|integer|min:0',
            'away_score' => 'required|integer|min:0',
            'status' => 'required|in:scheduled,playing,finished',
        ]);

        $match->update($validated);
        return redirect()->route('tournaments.show', $match->tournament_id)->with('success', 'Đã cập nhật kết quả trận đấu.');
    }
}
