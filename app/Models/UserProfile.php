<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class UserProfile extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'level',
        'total_xp',
        'current_streak',
        'longest_streak',
        'study_goal_minutes',
        'cards_per_day_goal',
    ];

    protected function casts(): array
    {
        return [
            'level' => 'integer',
            'total_xp' => 'integer',
            'current_streak' => 'integer',
            'longest_streak' => 'integer',
            'study_goal_minutes' => 'integer',
            'cards_per_day_goal' => 'integer',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function achievements(): BelongsToMany
    {
        return $this->belongsToMany(Achievement::class, 'user_achievements')
            ->withPivot('progress', 'earned_at')
            ->withTimestamps();
    }
}
