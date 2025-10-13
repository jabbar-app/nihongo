<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // Relationships
    public function profile()
    {
        return $this->hasOne(UserProfile::class);
    }

    public function flashcards()
    {
        return $this->hasMany(Flashcard::class);
    }

    public function flashcardReviews()
    {
        return $this->hasManyThrough(FlashcardReview::class, Flashcard::class);
    }

    public function exerciseAttempts()
    {
        return $this->hasMany(ExerciseAttempt::class);
    }

    public function shadowingCompletions()
    {
        return $this->hasMany(ShadowingCompletion::class);
    }

    public function recordings()
    {
        return $this->hasMany(UserRecording::class);
    }

    public function progress()
    {
        return $this->hasMany(UserProgress::class);
    }

    public function studyActivities()
    {
        return $this->hasMany(StudyActivity::class);
    }

    public function dailyStreaks()
    {
        return $this->hasMany(DailyStreak::class);
    }

    public function dailyPlans()
    {
        return $this->hasMany(DailyPlan::class);
    }

    public function achievements()
    {
        return $this->belongsToMany(Achievement::class, 'user_achievements')
            ->using(UserAchievement::class)
            ->withPivot('progress', 'earned_at')
            ->withTimestamps();
    }

    public function levelUps()
    {
        return $this->hasMany(LevelUp::class);
    }

    public function bookmarks()
    {
        return $this->hasMany(Bookmark::class);
    }
}
