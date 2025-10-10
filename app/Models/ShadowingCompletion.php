<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ShadowingCompletion extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'shadowing_exercise_id',
        'duration_seconds',
        'recording_id',
        'completed_at',
    ];

    protected function casts(): array
    {
        return [
            'duration_seconds' => 'integer',
            'completed_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function exercise(): BelongsTo
    {
        return $this->belongsTo(ShadowingExercise::class, 'shadowing_exercise_id');
    }

    public function recording(): BelongsTo
    {
        return $this->belongsTo(UserRecording::class, 'recording_id');
    }
}
