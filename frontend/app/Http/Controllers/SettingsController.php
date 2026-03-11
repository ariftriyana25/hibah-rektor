<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class SettingsController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $settings = json_decode($user->settings ?? '{}', true);
        
        return view('settings', compact('settings'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();
        
        $settings = [
            'camera' => $request->input('camera', 'default'),
            'videoQuality' => $request->input('videoQuality', 'medium'),
            'showSkeleton' => $request->boolean('showSkeleton'),
            'mirrorMode' => $request->boolean('mirrorMode'),
            'musicVolume' => $request->input('musicVolume', 70),
            'feedbackVolume' => $request->input('feedbackVolume', 50),
            'soundFeedback' => $request->boolean('soundFeedback'),
            'difficulty' => $request->input('difficulty', 'medium'),
            'countdown' => $request->input('countdown', 3),
            'showMaestro' => $request->boolean('showMaestro'),
            'autoSave' => $request->boolean('autoSave'),
            'reminderEnabled' => $request->boolean('reminderEnabled'),
            'leaderboardNotify' => $request->boolean('leaderboardNotify'),
            'achievementNotify' => $request->boolean('achievementNotify'),
        ];

        $user->settings = json_encode($settings);
        $user->save();

        return redirect()->route('settings')->with('success', 'Pengaturan berhasil disimpan!');
    }

    public function resetProgress()
    {
        $user = Auth::user();
        
        // Reset user stats
        $user->total_score = 0;
        $user->practice_count = 0;
        $user->level = 'Pemula';
        $user->progress = null;
        $user->save();

        // Delete practice sessions
        $user->practiceSessions()->delete();
        
        // Delete leaderboard entries
        $user->leaderboardEntries()->delete();

        return redirect()->route('settings')->with('success', 'Progress telah direset.');
    }

    public function deleteAccount()
    {
        $user = Auth::user();
        
        // Delete all related data
        $user->practiceSessions()->delete();
        $user->leaderboardEntries()->delete();
        
        // Logout and delete user
        Auth::logout();
        $user->delete();

        return redirect('/')->with('success', 'Akun berhasil dihapus.');
    }
}
