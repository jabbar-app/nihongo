<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FlashcardReview extends Model
{
    use HasFactory;

    protected $fillable = [
        'flashcard_id',
        'rating',
        'duration_seconds',
        'previous_interval',
        'new_interval',
        'reviewed_at',
    ];

    protected function casts(): array
    {
        return [
            'rating' => 'integer',
            'duration_seconds' => 'integer',
            'previous_interval' => 'integer',
            'new_interval' => 'integer',
            'reviewed_at' => 'datetime',
        ];
    }

    public function flashcard(): BelongsTo
    {
        return $this->belongsTo(Flashcard::class);
    }
}
