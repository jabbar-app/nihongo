<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class UserAchievement extends Pivot
{
    protected $table = 'user_achievements';

    public $incrementing = true;

    protected $fillable = [
        'user_id',
        'achievement_id',
        'progress',
        'earned_at',
    ];

    protected function casts(): array
    {
        return [
            'progress' => 'integer',
            'earned_at' => 'datetime',
        ];
    }
}
