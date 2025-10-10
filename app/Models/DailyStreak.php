<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Builder;

class DailyStreak extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'date',
        'activities_count',
        'study_minutes',
        'xp_earned',
    ];

    protected function casts(): array
    {
        return [
            'date' => 'date',
            'activities_count' => 'integer',
            'study_minutes' => 'integer',
            'xp_earned' => 'integer',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // Scope for active streaks (recent dates)
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('date', '>=', now()->subDays(1)->startOfDay());
    }

    // Scope for a specific date range
    public function scopeDateRange(Builder $query, $startDate, $endDate): Builder
    {
        return $query->whereBetween('date', [$startDate, $endDate]);
    }
}
