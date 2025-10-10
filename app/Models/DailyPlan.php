<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DailyPlan extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'date',
        'plan_data',
        'completed_activities',
        'total_activities',
        'completed_at',
    ];

    protected function casts(): array
    {
        return [
            'date' => 'date',
            'plan_data' => 'array',
            'completed_activities' => 'integer',
            'total_activities' => 'integer',
            'completed_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
