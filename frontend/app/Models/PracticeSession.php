<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PracticeSession extends Model
{
    protected $fillable = [
        'user_id',
        'karakter',
        'wiraga_score',
        'wirama_score',
        'wirasa_score',
        'total_score',
        'duration',
        'feedback',
        'pose_data',
    ];

    protected function casts(): array
    {
        return [
            'wiraga_score' => 'float',
            'wirama_score' => 'float',
            'wirasa_score' => 'float',
            'total_score' => 'float',
            'duration' => 'integer',
            'feedback' => 'array',
            'pose_data' => 'array',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
