<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\PracticeSession;
use App\Models\Leaderboard;
use App\Models\MaestroReference;

class ApiController extends Controller
{
    /**
     * Get current user stats
     */
    public function getUserStats()
    {
        $user = Auth::user();
        
        return response()->json([
            'success' => true,
            'data' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'avatar' => $user->avatar,
                'level' => $user->level ?? 'Pemula',
                'total_score' => $user->total_score ?? 0,
                'practice_count' => $user->practice_count ?? 0,
                'rank' => Leaderboard::where('best_score', '>', $user->total_score ?? 0)->count() + 1,
            ]
        ]);
    }

    /**
     * Get practice history
     */
    public function getPracticeHistory(Request $request)
    {
        $user = Auth::user();
        $limit = $request->get('limit', 10);
        $karakter = $request->get('karakter');

        $query = PracticeSession::where('user_id', $user->id)
            ->orderBy('created_at', 'desc');

        if ($karakter) {
            $query->where('karakter', $karakter);
        }

        $sessions = $query->take($limit)->get();

        return response()->json([
            'success' => true,
            'data' => $sessions
        ]);
    }

    /**
     * Get maestro references
     */
    public function getMaestroReferences(Request $request)
    {
        $karakter = $request->get('karakter');

        $query = MaestroReference::query();

        if ($karakter) {
            $query->where('karakter', $karakter);
        }

        $references = $query->get();

        return response()->json([
            'success' => true,
            'data' => $references
        ]);
    }

    /**
     * Get leaderboard data
     */
    public function getLeaderboard(Request $request)
    {
        $karakter = $request->get('karakter', 'all');
        $limit = $request->get('limit', 10);

        if ($karakter === 'all') {
            $data = User::select('id', 'name', 'avatar', 'total_score', 'level', 'practice_count')
                ->orderBy('total_score', 'desc')
                ->take($limit)
                ->get()
                ->map(function ($user, $index) {
                    return [
                        'rank' => $index + 1,
                        'id' => $user->id,
                        'name' => $user->name,
                        'avatar' => $user->avatar,
                        'score' => $user->total_score,
                        'level' => $user->level,
                        'sessions' => $user->practice_count,
                    ];
                });
        } else {
            $data = Leaderboard::where('karakter', $karakter)
                ->with('user:id,name,avatar,level')
                ->orderBy('best_score', 'desc')
                ->take($limit)
                ->get()
                ->map(function ($entry, $index) {
                    return [
                        'rank' => $index + 1,
                        'id' => $entry->user->id,
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
            'data' => $data
        ]);
    }

    /**
     * Get weekly progress
     */
    public function getWeeklyProgress()
    {
        $user = Auth::user();
        $progress = [];

        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $dayScore = PracticeSession::where('user_id', $user->id)
                ->whereDate('created_at', $date)
                ->avg('total_score') ?? 0;
            
            $progress[] = [
                'day' => $date->format('D'),
                'date' => $date->format('Y-m-d'),
                'score' => round($dayScore, 1),
                'sessions' => PracticeSession::where('user_id', $user->id)
                    ->whereDate('created_at', $date)
                    ->count(),
            ];
        }

        return response()->json([
            'success' => true,
            'data' => $progress
        ]);
    }

    /**
     * Get character mastery
     */
    public function getCharacterMastery()
    {
        $user = Auth::user();
        $karakters = ['panji', 'samba', 'rumyang', 'tumenggung', 'klana'];
        $icons = ['🎭', '👹', '🌸', '⚔️', '👺'];
        $mastery = [];

        foreach ($karakters as $index => $karakter) {
            $sessions = PracticeSession::where('user_id', $user->id)
                ->where('karakter', $karakter)
                ->get();

            $avgScore = $sessions->avg('total_score') ?? 0;
            $sessionCount = $sessions->count();

            $mastery[] = [
                'karakter' => $karakter,
                'name' => ucfirst($karakter),
                'icon' => $icons[$index],
                'avg_score' => round($avgScore, 1),
                'sessions' => $sessionCount,
                'mastery' => min(100, round(($avgScore * 0.7) + ($sessionCount * 2))),
            ];
        }

        return response()->json([
            'success' => true,
            'data' => $mastery
        ]);
    }

    /**
     * Save practice session
     */
    public function savePracticeSession(Request $request)
    {
        $request->validate([
            'karakter' => 'required|string',
            'wiraga_score' => 'required|numeric',
            'wirama_score' => 'required|numeric',
            'wirasa_score' => 'required|numeric',
            'duration' => 'required|integer',
        ]);

        $user = Auth::user();

        $totalScore = ($request->wiraga_score * 0.4) +
                      ($request->wirama_score * 0.3) +
                      ($request->wirasa_score * 0.3);

        $session = PracticeSession::create([
            'user_id' => $user->id,
            'karakter' => $request->karakter,
            'wiraga_score' => $request->wiraga_score,
            'wirama_score' => $request->wirama_score,
            'wirasa_score' => $request->wirasa_score,
            'total_score' => round($totalScore, 1),
            'duration' => $request->duration,
            'feedback' => $request->feedback ?? [],
        ]);

        // Update user stats
        $user->practice_count = ($user->practice_count ?? 0) + 1;
        $user->total_score = ($user->total_score ?? 0) + round($totalScore);
        $user->save();
        $user->updateLevel();

        // Update leaderboard
        $entry = Leaderboard::firstOrCreate(
            ['user_id' => $user->id, 'karakter' => $request->karakter],
            ['best_score' => 0]
        );

        if ($totalScore > $entry->best_score) {
            $entry->best_score = $totalScore;
            $entry->save();
        }

        return response()->json([
            'success' => true,
            'data' => $session,
            'grade' => $this->getGrade($totalScore),
        ]);
    }

    private function getGrade($score)
    {
        if ($score >= 90) return 'A';
        if ($score >= 80) return 'B';
        if ($score >= 70) return 'C';
        if ($score >= 60) return 'D';
        return 'E';
    }
}
