<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
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
    Route::post('/exercises/{drill}/submit', [\App\Http\Controllers\ExerciseController::class, 'submit'])->name('exercises.submit');
});

// Progress routes (placeholder for future implementation)
Route::middleware('auth')->group(function () {
    Route::get('/progress', function () {
        return redirect()->route('dashboard')->with('info', 'Progress tracking feature coming soon!');
    })->name('progress.index');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::patch('/profile/study-preferences', [ProfileController::class, 'updateStudyPreferences'])->name('profile.study-preferences.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
