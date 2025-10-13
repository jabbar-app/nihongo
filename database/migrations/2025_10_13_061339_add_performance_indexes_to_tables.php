<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Flashcards table - frequently queried for due cards and user cards
        Schema::table('flashcards', function (Blueprint $table) {
            $table->index(['user_id', 'next_review_at'], 'flashcards_user_review_idx');
            $table->index(['user_id', 'repetitions'], 'flashcards_user_reps_idx');
        });

        // User Progress table - frequently queried by user and lesson
        Schema::table('user_progress', function (Blueprint $table) {
            $table->index(['user_id', 'last_accessed_at'], 'user_progress_user_accessed_idx');
            $table->index(['user_id', 'completion_percentage'], 'user_progress_user_completion_idx');
        });

        // Study Activities table - frequently queried for analytics
        Schema::table('study_activities', function (Blueprint $table) {
            $table->index(['user_id', 'created_at'], 'study_activities_user_created_idx');
            $table->index(['user_id', 'activity_type'], 'study_activities_user_type_idx');
        });

        // Daily Streaks table - frequently queried by user and date
        Schema::table('daily_streaks', function (Blueprint $table) {
            $table->index(['user_id', 'date'], 'daily_streaks_user_date_idx');
        });

        // Flashcard Reviews table - for analytics and history
        Schema::table('flashcard_reviews', function (Blueprint $table) {
            $table->index(['flashcard_id', 'reviewed_at'], 'flashcard_reviews_card_reviewed_idx');
        });

        // Exercise Attempts table - for progress tracking
        Schema::table('exercise_attempts', function (Blueprint $table) {
            $table->index(['user_id', 'completed_at'], 'exercise_attempts_user_completed_idx');
            $table->index(['drill_id', 'user_id'], 'exercise_attempts_drill_user_idx');
        });

        // Shadowing Completions table - for progress tracking
        Schema::table('shadowing_completions', function (Blueprint $table) {
            $table->index(['user_id', 'completed_at'], 'shadowing_completions_user_completed_idx');
            $table->index(['shadowing_exercise_id', 'user_id'], 'shadowing_completions_exercise_user_idx');
        });

        // User Achievements table - for achievement tracking
        Schema::table('user_achievements', function (Blueprint $table) {
            $table->index(['user_id', 'earned_at'], 'user_achievements_user_earned_idx');
        });

        // Lessons table - for ordering
        Schema::table('lessons', function (Blueprint $table) {
            $table->index('order', 'lessons_order_idx');
        });

        // Phrases, Dialogues, Drills, Shadowing Exercises - for lesson content queries
        Schema::table('phrases', function (Blueprint $table) {
            $table->index(['lesson_id', 'order'], 'phrases_lesson_order_idx');
        });

        Schema::table('dialogues', function (Blueprint $table) {
            $table->index(['lesson_id', 'order'], 'dialogues_lesson_order_idx');
        });

        Schema::table('drills', function (Blueprint $table) {
            $table->index(['lesson_id', 'order'], 'drills_lesson_order_idx');
        });

        Schema::table('shadowing_exercises', function (Blueprint $table) {
            $table->index(['lesson_id', 'order'], 'shadowing_exercises_lesson_order_idx');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('flashcards', function (Blueprint $table) {
            $table->dropIndex('flashcards_user_review_idx');
            $table->dropIndex('flashcards_user_reps_idx');
        });

        Schema::table('user_progress', function (Blueprint $table) {
            $table->dropIndex('user_progress_user_accessed_idx');
            $table->dropIndex('user_progress_user_completion_idx');
        });

        Schema::table('study_activities', function (Blueprint $table) {
            $table->dropIndex('study_activities_user_created_idx');
            $table->dropIndex('study_activities_user_type_idx');
        });

        Schema::table('daily_streaks', function (Blueprint $table) {
            $table->dropIndex('daily_streaks_user_date_idx');
        });

        Schema::table('flashcard_reviews', function (Blueprint $table) {
            $table->dropIndex('flashcard_reviews_card_reviewed_idx');
        });

        Schema::table('exercise_attempts', function (Blueprint $table) {
            $table->dropIndex('exercise_attempts_user_completed_idx');
            $table->dropIndex('exercise_attempts_drill_user_idx');
        });

        Schema::table('shadowing_completions', function (Blueprint $table) {
            $table->dropIndex('shadowing_completions_user_completed_idx');
            $table->dropIndex('shadowing_completions_exercise_user_idx');
        });

        Schema::table('user_achievements', function (Blueprint $table) {
            $table->dropIndex('user_achievements_user_earned_idx');
        });

        Schema::table('lessons', function (Blueprint $table) {
            $table->dropIndex('lessons_order_idx');
        });

        Schema::table('phrases', function (Blueprint $table) {
            $table->dropIndex('phrases_lesson_order_idx');
        });

        Schema::table('dialogues', function (Blueprint $table) {
            $table->dropIndex('dialogues_lesson_order_idx');
        });

        Schema::table('drills', function (Blueprint $table) {
            $table->dropIndex('drills_lesson_order_idx');
        });

        Schema::table('shadowing_exercises', function (Blueprint $table) {
            $table->dropIndex('shadowing_exercises_lesson_order_idx');
        });
    }
};
