<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Lesson extends Model
{
    use HasFactory;

    protected $fillable = [
        'slug',
        'title',
        'description',
        'content_path',
        'order',
    ];

    public function phrases(): HasMany
    {
        return $this->hasMany(Phrase::class)->orderBy('order');
    }

    public function dialogues(): HasMany
    {
        return $this->hasMany(Dialogue::class)->orderBy('order');
    }

    public function drills(): HasMany
    {
        return $this->hasMany(Drill::class)->orderBy('order');
    }

    public function shadowingExercises(): HasMany
    {
        return $this->hasMany(ShadowingExercise::class)->orderBy('order');
    }

    public function userProgress(): HasMany
    {
        return $this->hasMany(UserProgress::class);
    }
}
