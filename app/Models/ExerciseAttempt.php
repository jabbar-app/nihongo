<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ExerciseAttempt extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'drill_id',
        'answers',
        'score',
        'duration_seconds',
        'completed_at',
    ];

    protected function casts(): array
    {
        return [
            'answers' => 'array',
            'score' => 'decimal:2',
            'duration_seconds' => 'integer',
            'completed_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function drill(): BelongsTo
    {
        return $this->belongsTo(Drill::class);
    }
}
