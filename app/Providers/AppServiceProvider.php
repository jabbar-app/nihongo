<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\FlashcardReview;
use App\Models\ExerciseAttempt;
use App\Models\ShadowingCompletion;
use App\Observers\FlashcardReviewObserver;
use App\Observers\ExerciseAttemptObserver;
use App\Observers\ShadowingCompletionObserver;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Register model observers for activity tracking
        FlashcardReview::observe(FlashcardReviewObserver::class);
        ExerciseAttempt::observe(ExerciseAttemptObserver::class);
        ShadowingCompletion::observe(ShadowingCompletionObserver::class);
    }
}
