<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Builder;

class Flashcard extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'phrase_id',
        'front',
        'back',
        'romaji',
        'ease_factor',
        'interval',
        'repetitions',
        'next_review_at',
    ];

    protected function casts(): array
    {
        return [
            'ease_factor' => 'decimal:2',
            'interval' => 'integer',
            'repetitions' => 'integer',
            'next_review_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function phrase(): BelongsTo
    {
        return $this->belongsTo(Phrase::class);
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(FlashcardReview::class);
    }

    // Scope for cards due for review
    public function scopeDue(Builder $query): Builder
    {
        return $query->where('next_review_at', '<=', now());
    }

    // Scope for new cards (never reviewed)
    public function scopeNew(Builder $query): Builder
    {
        return $query->where('repetitions', 0);
    }

    // Scope for mastered cards (high ease factor and long interval)
    public function scopeMastered(Builder $query): Builder
    {
        return $query->where('ease_factor', '>=', 2.5)
            ->where('interval', '>=', 21);
    }
}
