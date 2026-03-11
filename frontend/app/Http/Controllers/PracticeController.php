<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\PracticeSession;
use App\Models\MaestroReference;
use App\Models\Leaderboard;

class PracticeController extends Controller
{
    public function index(Request $request)
    {
        // Get available karakters and their gerakan
        $karakters = [
            'panji' => [
                'name' => 'Panji',
                'icon' => '🎭',
                'description' => 'Karakter halus, lemah lembut',
                'difficulty' => 'Mudah'
            ],
            'samba' => [
                'name' => 'Samba',
                'icon' => '👹',
                'description' => 'Karakter jenaka, lucu',
                'difficulty' => 'Mudah'
            ],
            'rumyang' => [
                'name' => 'Rumyang',
                'icon' => '🌸',
                'description' => 'Karakter anggun, feminin',
                'difficulty' => 'Menengah'
            ],
            'tumenggung' => [
                'name' => 'Tumenggung',
                'icon' => '⚔️',
                'description' => 'Karakter gagah, berwibawa',
                'difficulty' => 'Menengah'
            ],
            'klana' => [
                'name' => 'Klana',
                'icon' => '👺',
                'description' => 'Karakter dinamis, kuat',
                'difficulty' => 'Sulit'
            ]
        ];

        // Get maestro references
        $maestroReferences = MaestroReference::all()->groupBy('karakter');

        // Selected karakter (default: panji)
        $selectedKarakter = $request->get('karakter', 'panji');

        // Backend API URL for WebSocket connection
        $backendUrl = config('app.backend_url', 'http://localhost:5000');

        return view('practice', compact('karakters', 'maestroReferences', 'selectedKarakter', 'backendUrl'));
    }

    public function start(Request $request)
    {
        $request->validate([
            'karakter' => 'required|string|in:panji,samba,rumyang,tumenggung,klana',
            'gerakan' => 'nullable|string',
        ]);

        $user = Auth::user();

        // Create a new practice session
        $session = PracticeSession::create([
            'user_id' => $user->id,
            'karakter' => $request->karakter,
            'wiraga_score' => 0,
            'wirama_score' => 0,
            'wirasa_score' => 0,
            'total_score' => 0,
            'duration' => 0,
        ]);

        return response()->json([
            'success' => true,
            'session_id' => $session->id,
            'message' => 'Sesi latihan dimulai',
        ]);
    }

    public function end(Request $request)
    {
        $request->validate([
            'session_id' => 'required|integer',
            'wiraga_score' => 'required|numeric|min:0|max:100',
            'wirama_score' => 'required|numeric|min:0|max:100',
            'wirasa_score' => 'required|numeric|min:0|max:100',
            'duration' => 'required|integer|min:0',
            'feedback' => 'nullable|array',
        ]);

        $user = Auth::user();

        $session = PracticeSession::where('id', $request->session_id)
            ->where('user_id', $user->id)
            ->firstOrFail();

        // Calculate total score (weighted average)
        $totalScore = ($request->wiraga_score * 0.4) + 
                      ($request->wirama_score * 0.3) + 
                      ($request->wirasa_score * 0.3);

        // Update session
        $session->update([
            'wiraga_score' => $request->wiraga_score,
            'wirama_score' => $request->wirama_score,
            'wirasa_score' => $request->wirasa_score,
            'total_score' => round($totalScore, 1),
            'duration' => $request->duration,
            'feedback' => $request->feedback ?? [],
        ]);

        // Update user stats
        $user->practice_count += 1;
        $user->total_score += round($totalScore);
        $user->updateLevel();

        // Update leaderboard
        $this->updateLeaderboard($user, $session->karakter, $totalScore);

        // Determine grade
        $grade = $this->getGrade($totalScore);

        return response()->json([
            'success' => true,
            'session' => $session,
            'grade' => $grade,
            'message' => 'Sesi latihan selesai!',
        ]);
    }

    private function updateLeaderboard($user, $karakter, $score)
    {
        $entry = Leaderboard::firstOrCreate(
            ['user_id' => $user->id, 'karakter' => $karakter],
            ['best_score' => 0]
        );

        if ($score > $entry->best_score) {
            $entry->best_score = $score;
            $entry->save();
        }

        // Update ranks
        $entries = Leaderboard::where('karakter', $karakter)
            ->orderBy('best_score', 'desc')
            ->get();

        foreach ($entries as $index => $e) {
            $e->rank = $index + 1;
            $e->save();
        }
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