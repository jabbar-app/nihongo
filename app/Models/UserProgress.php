<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Builder;

class UserProgress extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'lesson_id',
        'phrases_viewed',
        'dialogues_viewed',
        'drills_completed',
        'shadowing_completed',
        'completion_percentage',
        'last_accessed_at',
    ];

    protected function casts(): array
    {
        return [
            'phrases_viewed' => 'integer',
            'dialogues_viewed' => 'integer',
            'drills_completed' => 'integer',
            'shadowing_completed' => 'integer',
            'completion_percentage' => 'decimal:2',
            'last_accessed_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function lesson(): BelongsTo
    {
        return $this->belongsTo(Lesson::class);
    }

    // Scope for completed lessons
    public function scopeCompleted(Builder $query): Builder
    {
        return $query->where('completion_percentage', '>=', 100);
    }

    // Scope for in-progress lessons
    public function scopeInProgress(Builder $query): Builder
    {
        return $query->where('completion_percentage', '>', 0)
            ->where('completion_percentage', '<', 100);
    }
}
