<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Achievement extends Model
{
    use HasFactory;

    protected $fillable = [
        'slug',
        'name',
        'description',
        'icon',
        'xp_reward',
        'requirement_type',
        'requirement_value',
    ];

    protected function casts(): array
    {
        return [
            'xp_reward' => 'integer',
            'requirement_value' => 'integer',
        ];
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'user_achievements')
            ->using(UserAchievement::class)
            ->withPivot('progress', 'earned_at')
            ->withTimestamps();
    }
}
