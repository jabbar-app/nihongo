# Task 22: Activity Tracking System Implementation

## Overview
Implemented a comprehensive activity tracking system using Laravel model observers to automatically log study activities, update daily streaks, award XP, and track user progress.

## Components Implemented

### 1. Model Observers

Created three observers to track different types of learning activities:

#### FlashcardReviewObserver (`app/Observers/FlashcardReviewObserver.php`)
- Triggers when a `FlashcardReview` is created
- Logs study activity with XP based on rating (1-4 = 2-7 XP)
- Updates daily streak record
- Awards XP to user profile
- Updates lesson progress if flashcard is linked to a phrase

#### ExerciseAttemptObserver (`app/Observers/ExerciseAttemptObserver.php`)
- Triggers when an `ExerciseAttempt` is created
- Calculates XP based on score (score-based multiplier with minimum 10 XP)
- Logs study activity
- Updates daily streak record
- Awards XP to user profile
- Updates lesson progress for drill completion

#### ShadowingCompletionObserver (`app/Observers/ShadowingCompletionObserver.php`)
- Triggers when a `ShadowingCompletion` is created
- Awards 50 XP base + 10 XP bonus if recording included
- Logs study activity
- Updates daily streak record
- Awards XP to user profile
- Updates lesson progress for shadowing completion

### 2. Observer Registration

Updated `app/Providers/AppServiceProvider.php` to register all three observers in the `boot()` method:

```php
FlashcardReview::observe(FlashcardReviewObserver::class);
ExerciseAttempt::observe(ExerciseAttemptObserver.php);
ShadowingCompletion::observe(ShadowingCompletionObserver.class);
```

### 3. Activity Tracking Logic

Each observer follows this pattern:

1. **Create StudyActivity Record**: Logs the activity with type, duration, and XP earned
2. **Update DailyStreak**: Creates or updates the daily streak record for today
   - Increments activities_count
   - Adds study time (converted to minutes)
   - Adds XP earned
   - Uses DB transactions with row locking to prevent race conditions
3. **Award XP**: Calls `GamificationService::awardXP()` to update user profile
4. **Update Progress**: Calls `ProgressService::updateProgress()` to track lesson completion
5. **Update Streak Counter**: Calls `ProgressService::updateStreak()` to maintain streak count in user profile

### 4. XP Calculation

Different activities award different amounts of XP:

- **Flashcard Review**: 2-7 XP based on rating
  - Again (1): 2 XP
  - Hard (2): 3 XP
  - Good (3): 5 XP
  - Easy (4): 7 XP

- **Exercise Attempt**: 10-30 XP based on score
  - Base XP: 30 (from `GamificationService::XP_EXERCISE_COMPLETION`)
  - Multiplied by score percentage (0.0-1.0)
  - Minimum 10 XP for attempting

- **Shadowing Completion**: 50-60 XP
  - Base: 50 XP (from `GamificationService::XP_SHADOWING_COMPLETION`)
  - Bonus: +10 XP if recording included

### 5. Daily Streak Management

The `updateDailyStreak()` method in each observer:

- Uses DB transactions to prevent race conditions
- Locks the row for update before checking existence
- Creates new record if none exists for today
- Increments counters if record already exists
- Tracks:
  - `activities_count`: Number of activities completed today
  - `study_minutes`: Total study time in minutes (rounded up)
  - `xp_earned`: Total XP earned today

### 6. Progress Service Integration

Modified `ProgressService::updateStreak()` to:

- Remove the `firstOrCreate` call that was causing unique constraint violations
- Only update the user profile's streak counter
- Check if user has activity today (via StudyActivity records)
- Increment or reset streak based on consecutive days of activity

## Testing

Created comprehensive test suite in `tests/Feature/ActivityTrackingTest.php`:

1. **Flashcard Review Test**: Verifies activity logging, XP award, and daily streak update
2. **Exercise Attempt Test**: Verifies score-based XP calculation
3. **Shadowing Completion Test**: Verifies base XP and recording bonus
4. **Multiple Activities Test**: Verifies accumulation in daily streak
5. **Different Activity Types Test**: Verifies all three activity types are tracked separately

## Database Factories

Created factories for testing:

- `DrillFactory.php`: Generates test drill records
- `ShadowingExerciseFactory.php`: Generates test shadowing exercise records

## Requirements Satisfied

✅ **5.4**: Track activity type, duration, and XP earned via StudyActivity model  
✅ **5.5**: Update DailyStreak records for current date with activities count, study time, and XP  
✅ **5.8**: Calculate and store daily study time in minutes

## Integration Points

The activity tracking system integrates with:

1. **GamificationService**: For XP awards and level calculations
2. **ProgressService**: For lesson progress tracking and streak management
3. **StudyActivity Model**: For logging all learning activities
4. **DailyStreak Model**: For tracking daily statistics
5. **UserProgress Model**: For lesson completion tracking
6. **UserProfile Model**: For XP and streak counters

## Usage

The system works automatically - no manual calls needed:

```php
// Creating any of these records automatically triggers tracking:

// Flashcard review
FlashcardReview::create([...]);

// Exercise attempt
ExerciseAttempt::create([...]);

// Shadowing completion
ShadowingCompletion::create([...]);
```

## Race Condition Handling

The implementation uses DB transactions with row locking to handle concurrent activities:

```php
\DB::transaction(function () use ($user, $today, $durationSeconds, $xpEarned) {
    $dailyStreak = DailyStreak::where('user_id', $user->id)
        ->where('date', $today)
        ->lockForUpdate()
        ->first();
    
    // Create or update logic...
});
```

This ensures that multiple simultaneous activities don't create duplicate daily streak records.

## Future Enhancements

Potential improvements for future tasks:

1. Add activity type breakdown in daily stats
2. Implement weekly/monthly streak tracking
3. Add achievement triggers based on activity milestones
4. Create activity history visualization
5. Add activity recommendations based on patterns
