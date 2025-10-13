<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PhraseReviewQueue extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'phrase_id',
        'drill_id',
        'incorrect_count',
        'last_incorrect_at',
        'reviewed_at',
    ];

    protected function casts(): array
    {
        return [
            'incorrect_count' => 'integer',
            'last_incorrect_at' => 'datetime',
            'reviewed_at' => 'datetime',
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

    public function drill(): BelongsTo
    {
        return $this->belongsTo(Drill::class);
    }
}
