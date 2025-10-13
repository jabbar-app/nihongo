<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LevelUp extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'old_level',
        'new_level',
        'xp_at_levelup',
        'bonus_xp',
    ];

    protected function casts(): array
    {
        return [
            'old_level' => 'integer',
            'new_level' => 'integer',
            'xp_at_levelup' => 'integer',
            'bonus_xp' => 'integer',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
