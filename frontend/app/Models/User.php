<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'avatar',
        'level',
        'total_score',
        'practice_count',
        'progress',
        'settings',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'progress' => 'array',
            'total_score' => 'integer',
            'practice_count' => 'integer',
        ];
    }

    protected $attributes = [
        'level' => 'Pemula',
        'total_score' => 0,
        'practice_count' => 0,
    ];

    public function practiceSessions()
    {
        return $this->hasMany(PracticeSession::class);
    }

    public function leaderboardEntries()
    {
        return $this->hasMany(Leaderboard::class);
    }

    public function updateLevel()
    {
        if ($this->practice_count < 5) {
            $this->level = 'Pemula';
        } elseif ($this->practice_count < 15 && $this->total_score < 1000) {
            $this->level = 'Dasar';
        } elseif ($this->practice_count < 30 && $this->total_score < 3000) {
            $this->level = 'Menengah';
        } elseif ($this->practice_count < 50 && $this->total_score < 6000) {
            $this->level = 'Mahir';
        } else {
            $this->level = 'Master';
        }
        $this->save();
    }
}
