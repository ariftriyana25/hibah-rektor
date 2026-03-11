<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Leaderboard;
use App\Models\PracticeSession;

class LeaderboardController extends Controller
{
    public function index(Request $request)
    {
        $selectedKarakter = $request->get('karakter', 'all');
        
        // Get top 3 for podium
        $query = $this->getLeaderboardQuery($selectedKarakter);
        $topThree = $query->take(3)->get();

        // Get all rankings
        $query = $this->getLeaderboardQuery($selectedKarakter);
        $allRankings = $query->paginate(20);

        // Get current user's rank
        $userRank = null;
        if (Auth::check()) {
            $user = Auth::user();
            if ($selectedKarakter === 'all') {
                $userRank = User::where('total_score', '>', $user->total_score)->count() + 1;
            } else {
                $entry = Leaderboard::where('user_id', $user->id)
                    ->where('karakter', $selectedKarakter)
                    ->first();
                $userRank = $entry ? $entry->rank : null;
            }
        }

        // Available karakters
        $karakters = [
            'all' => ['name' => 'Semua', 'icon' => '🏆'],
            'panji' => ['name' => 'Panji', 'icon' => '🎭'],
            'samba' => ['name' => 'Samba', 'icon' => '👹'],
            'rumyang' => ['name' => 'Rumyang', 'icon' => '🌸'],
            'tumenggung' => ['name' => 'Tumenggung', 'icon' => '⚔️'],
            'klana' => ['name' => 'Klana', 'icon' => '👺'],
        ];

        return view('leaderboard', compact('topThree', 'allRankings', 'userRank', 'karakters', 'selectedKarakter'));
    }

    private function getLeaderboardQuery($karakter)
    {
        if ($karakter === 'all') {
            return User::select('id', 'name', 'avatar', 'total_score', 'level', 'practice_count')
                ->orderBy('total_score', 'desc');
        } else {
            return Leaderboard::where('karakter', $karakter)
                ->with(['user:id,name,avatar,level'])
                ->orderBy('best_score', 'desc');
        }
    }

    public function getData(Request $request)
    {
        $karakter = $request->get('karakter', 'all');
        $limit = $request->get('limit', 10);

        $query = $this->getLeaderboardQuery($karakter);
        $data = $query->take($limit)->get();

        if ($karakter === 'all') {
            $data = $data->map(function ($user, $index) {
                return [
                    'rank' => $index + 1,
                    'name' => $user->name,
                    'avatar' => $user->avatar,
                    'score' => $user->total_score,
                    'level' => $user->level,
                    'sessions' => $user->practice_count,
                ];
            });
        } else {
            $data = $data->map(function ($entry, $index) {
                return [
                    'rank' => $index + 1,
                    'name' => $entry->user->name,
                    'avatar' => $entry->user->avatar,
                    'score' => $entry->best_score,
                    'level' => $entry->user->level,
                ];
            });
        }

        return response()->json([
            'success' => true,
            'karakter' => $karakter,
            'data' => $data,
        ]);
    }

    public function weeklyLeaderboard()
    {
        $startOfWeek = now()->startOfWeek();
        $endOfWeek = now()->endOfWeek();

        $rankings = PracticeSession::whereBetween('created_at', [$startOfWeek, $endOfWeek])
            ->selectRaw('user_id, SUM(total_score) as weekly_score, COUNT(*) as sessions')
            ->groupBy('user_id')
            ->orderBy('weekly_score', 'desc')
            ->with('user:id,name,avatar,level')
            ->take(10)
            ->get();

        return response()->json([
            'success' => true,
            'period' => [
                'start' => $startOfWeek->format('Y-m-d'),
                'end' => $endOfWeek->format('Y-m-d'),
            ],
            'data' => $rankings,
        ]);
    }
}