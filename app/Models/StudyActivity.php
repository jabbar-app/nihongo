<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class StudyActivity extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'activity_type',
        'activityable_type',
        'activityable_id',
        'duration_seconds',
        'xp_earned',
    ];

    protected function casts(): array
    {
        return [
            'duration_seconds' => 'integer',
            'xp_earned' => 'integer',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function activityable(): MorphTo
    {
        return $this->morphTo();
    }
}
