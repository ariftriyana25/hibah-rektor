<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\PracticeSession;
use App\Models\Leaderboard;

class ProfileController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        // Get user rank
        $rank = Leaderboard::where('best_score', '>', $user->total_score)->count() + 1;
        
        // Get recent activities
        $recentSessions = PracticeSession::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        // Character mastery
        $characterMastery = PracticeSession::where('user_id', $user->id)
            ->selectRaw('karakter, AVG(total_score) as avg_score, COUNT(*) as sessions')
            ->groupBy('karakter')
            ->get()
            ->keyBy('karakter');

        return view('profile', compact('user', 'rank', 'recentSessions', 'characterMastery'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'password' => 'nullable|min:6',
        ]);

        $user = Auth::user();
        $user->name = $request->name;

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        return redirect()->route('profile')->with('success', 'Profil berhasil diperbarui!');
    }

    public function uploadAvatar(Request $request)
    {
        $request->validate([
            'avatar' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $user = Auth::user();
        
        if ($request->hasFile('avatar')) {
            $file = $request->file('avatar');
            $filename = time() . '_' . $user->id . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('avatars'), $filename);
            
            $user->avatar = $filename;
            $user->save();
        }

        return redirect()->route('profile')->with('success', 'Avatar berhasil diperbarui!');
    }
}
