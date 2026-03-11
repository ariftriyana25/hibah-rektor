<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\PracticeSession;

class HistoryController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        
        $query = PracticeSession::where('user_id', $user->id);

        // Filter by karakter
        if ($request->filled('karakter')) {
            $query->where('karakter', $request->karakter);
        }

        // Filter by time
        if ($request->filled('period')) {
            switch ($request->period) {
                case 'today':
                    $query->whereDate('created_at', today());
                    break;
                case 'week':
                    $query->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()]);
                    break;
                case 'month':
                    $query->whereMonth('created_at', now()->month);
                    break;
            }
        }

        $sessions = $query->orderBy('created_at', 'desc')->paginate(10);

        // Stats
        $totalSessions = PracticeSession::where('user_id', $user->id)->count();
        $avgScore = PracticeSession::where('user_id', $user->id)->avg('total_score') ?? 0;
        $totalMinutes = PracticeSession::where('user_id', $user->id)->sum('duration') / 60;
        $bestScore = PracticeSession::where('user_id', $user->id)->max('total_score') ?? 0;

        return view('history', compact('sessions', 'totalSessions', 'avgScore', 'totalMinutes', 'bestScore'));
    }

    public function show($id)
    {
        $session = PracticeSession::where('user_id', Auth::id())
            ->findOrFail($id);

        // Get previous session for comparison
        $previousSession = PracticeSession::where('user_id', Auth::id())
            ->where('karakter', $session->karakter)
            ->where('id', '<', $session->id)
            ->orderBy('id', 'desc')
            ->first();

        return view('session-detail', compact('session', 'previousSession'));
    }
}
