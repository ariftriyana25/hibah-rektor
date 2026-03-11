<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MaestroReference extends Model
{
    protected $fillable = [
        'karakter',
        'gerakan_name',
        'video_path',
        'pose_keyframes',
        'audio_path',
        'beat_timestamps',
        'description',
        'difficulty',
    ];

    protected function casts(): array
    {
        return [
            'pose_keyframes' => 'array',
            'beat_timestamps' => 'array',
        ];
    }
}
