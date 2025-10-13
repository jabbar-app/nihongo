<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Cache;

class CacheService
{
    /**
     * Clear all user-specific caches
     */
    public function clearUserCache(User $user): void
    {
        $userId = $user->id;
        
        // Clear dashboard caches
        Cache::forget("user.{$userId}.cards_due_today");
        Cache::forget("user.{$userId}.new_cards_available");
        Cache::forget("user.{$userId}.upcoming_reviews");
        Cache::forget("user.{$userId}.recent_lessons");
        Cache::forget("user.{$userId}.completed_lessons");
    }

    /**
     * Clear flashcard-related caches for a user
     */
    public function clearFlashcardCache(User $user): void
    {
        $userId = $user->id;
        
        Cache::forget("user.{$userId}.cards_due_today");
        Cache::forget("user.{$userId}.new_cards_available");
        Cache::forget("user.{$userId}.upcoming_reviews");
    }

    /**
     * Clear progress-related caches for a user
     */
    public function clearProgressCache(User $user): void
    {
        $userId = $user->id;
        
        Cache::forget("user.{$userId}.recent_lessons");
        Cache::forget("user.{$userId}.completed_lessons");
    }

    /**
     * Clear lesson content cache
     */
    public function clearLessonCache(?string $slug = null): void
    {
        if ($slug) {
            Cache::forget("lesson.{$slug}.full");
        } else {
            Cache::forget('lessons.all');
        }
    }

    /**
     * Clear all lesson caches
     */
    public function clearAllLessonCaches(): void
    {
        Cache::forget('lessons.all');
        Cache::forget('total_lessons_count');
    }
}
