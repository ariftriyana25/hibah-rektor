<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\PracticeSession;
use App\Models\Leaderboard;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        // User stats
        $stats = [
            'total_score' => $user->total_score ?? 0,
            'practice_count' => $user->practice_count ?? 0,
            'level' => $user->level ?? 'Pemula',
            'total_minutes' => PracticeSession::where('user_id', $user->id)->sum('duration') / 60,
        ];

        // Weekly progress (last 7 days)
        $weeklyProgress = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            $dayScore = PracticeSession::where('user_id', $user->id)
                ->whereDate('created_at', $date)
                ->avg('total_score') ?? 0;
            $weeklyProgress[] = [
                'day' => $date->format('D'),
                'date' => $date->format('d M'),
                'score' => round($dayScore, 1),
            ];
        }

        // Character mastery
        $characterMastery = [];
        $karakters = ['panji', 'samba', 'rumyang', 'tumenggung', 'klana'];
        $icons = ['🎭', '👹', '🌸', '⚔️', '👺'];
        
        foreach ($karakters as $index => $karakter) {
            $sessions = PracticeSession::where('user_id', $user->id)
                ->where('karakter', $karakter)
                ->get();
            
            $avgScore = $sessions->avg('total_score') ?? 0;
            $sessionCount = $sessions->count();
            
            $characterMastery[] = [
                'name' => ucfirst($karakter),
                'icon' => $icons[$index],
                'score' => round($avgScore, 1),
                'sessions' => $sessionCount,
                'mastery' => min(100, ($avgScore * 0.7) + ($sessionCount * 2)),
            ];
        }

        // Recent sessions
        $recentSessions = PracticeSession::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        // User rank
        $userRank = Leaderboard::where('best_score', '>', $user->total_score)->count() + 1;

        // Quick actions
        $quickActions = [
            ['title' => 'Mulai Latihan', 'icon' => '🎭', 'route' => 'practice', 'color' => '#E85A20'],
            ['title' => 'Tutorial', 'icon' => '📚', 'route' => 'tutorial', 'color' => '#3B82F6'],
            ['title' => 'Leaderboard', 'icon' => '🏆', 'route' => 'leaderboard', 'color' => '#22C55E'],
            ['title' => 'Riwayat', 'icon' => '📋', 'route' => 'history', 'color' => '#8B5CF6'],
        ];

        return view('dashboard', compact(
            'user', 'stats', 'weeklyProgress', 'characterMastery', 
            'recentSessions', 'userRank', 'quickActions'
        ));
    }

    public function getStats()
    {
        $user = Auth::user();
        
        return response()->json([
            'total_score' => $user->total_score ?? 0,
            'practice_count' => $user->practice_count ?? 0,
            'level' => $user->level ?? 'Pemula',
            'rank' => Leaderboard::where('best_score', '>', $user->total_score)->count() + 1,
        ]);
    }
}