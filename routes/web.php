<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('landing');
});

Route::get('/dashboard', [\App\Http\Controllers\DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

// Lesson routes
Route::middleware('auth')->group(function () {
    Route::get('/lessons', [\App\Http\Controllers\LessonController::class, 'index'])->name('lessons.index');
    Route::get('/lessons/{lesson:slug}', [\App\Http\Controllers\LessonController::class, 'show'])->name('lessons.show');
});

// Flashcard routes
Route::middleware('auth')->group(function () {
    Route::get('/flashcards', [\App\Http\Controllers\FlashcardController::class, 'index'])->name('flashcards.index');
    Route::get('/flashcards/create', [\App\Http\Controllers\FlashcardController::class, 'create'])->name('flashcards.create');
    Route::post('/flashcards', [\App\Http\Controllers\FlashcardController::class, 'store'])->name('flashcards.store');
    Route::delete('/flashcards/{flashcard}', [\App\Http\Controllers\FlashcardController::class, 'destroy'])->name('flashcards.destroy');
    Route::get('/flashcards/review', [\App\Http\Controllers\FlashcardController::class, 'review'])->name('flashcards.review');
    Route::post('/flashcards/rate', [\App\Http\Controllers\FlashcardController::class, 'rate'])->name('flashcards.rate');
});

// Exercise routes
Route::middleware('auth')->group(function () {
    Route::get('/exercises/{drill}', [\App\Http\Controllers\ExerciseController::class, 'show'])->name('exercises.show');
    Route::get('/exercises/{drill}/attempt', [\App\Http\Controllers\ExerciseController::class, 'attempt'])->name('exercises.attempt');
    Route::post('/exercises/{drill}/submit', [\App\Http\Controllers\ExerciseController::class, 'submit'])->name('exercises.submit');
    Route::post('/exercises/{drill}/complete', [\App\Http\Controllers\ExerciseController::class, 'complete'])->name('exercises.complete');
    Route::get('/exercises-history', [\App\Http\Controllers\ExerciseController::class, 'history'])->name('exercises.history');
});

// Shadowing routes
Route::middleware('auth')->group(function () {
    Route::get('/shadowing/{shadowingExercise}', [\App\Http\Controllers\ShadowingController::class, 'show'])->name('shadowing.show');
    Route::post('/shadowing/{shadowingExercise}/complete', [\App\Http\Controllers\ShadowingController::class, 'complete'])->name('shadowing.complete');
});

// Recording routes
Route::middleware('auth')->group(function () {
    Route::post('/recordings', [\App\Http\Controllers\RecordingController::class, 'store'])->name('recordings.store');
    Route::get('/recordings/{recording}', [\App\Http\Controllers\RecordingController::class, 'serve'])->name('recordings.serve');
    Route::delete('/recordings/{recording}', [\App\Http\Controllers\RecordingController::class, 'destroy'])->name('recordings.destroy');
    Route::get('/recordings-usage', [\App\Http\Controllers\RecordingController::class, 'storageUsage'])->name('recordings.usage');
});

// Progress routes
Route::middleware('auth')->group(function () {
    Route::get('/progress', [\App\Http\Controllers\ProgressController::class, 'index'])->name('progress.index');
});

// Analytics API routes
Route::middleware('auth')->group(function () {
    Route::get('/api/analytics', [\App\Http\Controllers\AnalyticsController::class, 'index'])->name('api.analytics');
});

// Achievement routes
Route::middleware('auth')->group(function () {
    Route::get('/achievements', [\App\Http\Controllers\AchievementController::class, 'index'])->name('achievements.index');
});

// Study Plan routes
Route::middleware('auth')->group(function () {
    Route::get('/study-plan', [\App\Http\Controllers\StudyPlanController::class, 'show'])->name('study-plan.show');
    Route::post('/study-plan/complete/{activityId}', [\App\Http\Controllers\StudyPlanController::class, 'complete'])->name('study-plan.complete');
});

// Notification API routes
Route::middleware('auth')->group(function () {
    Route::post('/api/notification-permission', [\App\Http\Controllers\NotificationController::class, 'updatePermission'])->name('api.notification-permission');
    Route::get('/api/daily-plan-summary', [\App\Http\Controllers\NotificationController::class, 'dailyPlanSummary'])->name('api.daily-plan-summary');
});

// Streak routes
Route::middleware('auth')->group(function () {
    Route::get('/streak', [\App\Http\Controllers\StreakController::class, 'index'])->name('streak.index');
    Route::post('/streak/recover', [\App\Http\Controllers\StreakController::class, 'recover'])->name('streak.recover');
});

// Search routes
Route::middleware('auth')->group(function () {
    Route::get('/search', [\App\Http\Controllers\SearchController::class, 'index'])->name('search.index');
});

// Bookmark routes
Route::middleware('auth')->group(function () {
    Route::get('/bookmarks', [\App\Http\Controllers\BookmarkController::class, 'index'])->name('bookmarks.index');
    Route::post('/bookmarks/toggle', [\App\Http\Controllers\BookmarkController::class, 'toggle'])->name('bookmarks.toggle');
    Route::patch('/bookmarks/{bookmark}/notes', [\App\Http\Controllers\BookmarkController::class, 'updateNotes'])->name('bookmarks.update-notes');
    Route::delete('/bookmarks/{bookmark}', [\App\Http\Controllers\BookmarkController::class, 'destroy'])->name('bookmarks.destroy');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::patch('/profile/study-preferences', [ProfileController::class, 'updateStudyPreferences'])->name('profile.study-preferences.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
