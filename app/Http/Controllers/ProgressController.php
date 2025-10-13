<?php

namespace App\Http\Controllers;

use App\Services\ProgressService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Lesson;
use App\Models\Flashcard;
use App\Models\FlashcardReview;
use App\Models\ExerciseAttempt;
use App\Models\Drill;
use Illuminate\Support\Facades\DB;

class ProgressController extends Controller
{
    protected ProgressService $progressService;

    public function __construct(ProgressService $progressService)
    {
        $this->progressService = $progressService;
    }

    /**
     * Display the progress overview page
     */
    public function index(Request $request)
    {
        $user = Auth::user();

        // Get overall progress
        $overallProgress = $this->progressService->getOverallProgress($user);

        // Get weekly stats
        $weeklyStats = $this->progressService->getWeeklyStats($user);

        // Get flashcard statistics
        $flashcardStats = $this->getFlashcardStatistics($user);

        // Get exercise statistics
        $exerciseStats = $this->getExerciseStatistics($user);

        // Get progress by content type
        $contentTypeProgress = $this->getContentTypeProgress($user);

        // Get weak areas (lessons with low scores)
        $weakAreas = $this->getWeakAreas($user);

        return view('progress.index', compact(
            'overallProgress',
            'weeklyStats',
            'flashcardStats',
            'exerciseStats',
            'contentTypeProgress',
            'weakAreas'
        ));
    }

    /**
     * Get flashcard statistics
     */
    protected function getFlashcardStatistics($user): array
    {
        $totalCards = Flashcard::where('user_id', $user->id)->count();
        $masteredCards = Flashcard::where('user_id', $user->id)->mastered()->count();
        $newCards = Flashcard::where('user_id', $user->id)->new()->count();
        $dueCards = Flashcard::where('user_id', $user->id)->due()->count();

        // Calculate retention rate from recent reviews
        $recentReviews = FlashcardReview::whereHas('flashcard', function ($query) use ($user) {
            $query->where('user_id', $user->id);
        })
            ->where('reviewed_at', '>=', now()->subDays(30))
            ->get();

        $totalReviews = $recentReviews->count();
        $successfulReviews = $recentReviews->where('rating', '>=', 3)->count(); // Good or Easy

        $retentionRate = $totalReviews > 0 
            ? round(($successfulReviews / $totalReviews) * 100, 2) 
            : 0;

        // Average review time
        $avgReviewTime = $recentReviews->avg('duration_seconds') ?? 0;

        return [
            'total_cards' => $totalCards,
            'mastered_cards' => $masteredCards,
            'new_cards' => $newCards,
            'due_cards' => $dueCards,
            'retention_rate' => $retentionRate,
            'total_reviews' => $totalReviews,
            'avg_review_time' => round($avgReviewTime, 2),
        ];
    }

    /**
     * Get exercise statistics
     */
    protected function getExerciseStatistics($user): array
    {
        $attempts = ExerciseAttempt::where('user_id', $user->id)
            ->with('drill')
            ->get();

        $totalAttempts = $attempts->count();
        $avgScore = $attempts->avg('score') ?? 0;

        // Group by drill type
        $scoresByType = $attempts->groupBy(function ($attempt) {
            return $attempt->drill->type ?? 'unknown';
        })->map(function ($group) {
            return [
                'count' => $group->count(),
                'avg_score' => round($group->avg('score'), 2),
                'best_score' => round($group->max('score'), 2),
                'worst_score' => round($group->min('score'), 2),
            ];
        })->toArray();

        // Recent performance trend (last 10 attempts)
        $recentAttempts = $attempts->sortByDesc('completed_at')->take(10);
        $recentAvgScore = $recentAttempts->avg('score') ?? 0;

        return [
            'total_attempts' => $totalAttempts,
            'avg_score' => round($avgScore, 2),
            'recent_avg_score' => round($recentAvgScore, 2),
            'scores_by_type' => $scoresByType,
        ];
    }

    /**
     * Get progress by content type across all lessons
     */
    protected function getContentTypeProgress($user): array
    {
        $allLessons = Lesson::with(['phrases', 'dialogues', 'drills', 'shadowingExercises'])->get();
        $userProgress = $user->progress()->get()->keyBy('lesson_id');

        $totals = [
            'phrases' => 0,
            'dialogues' => 0,
            'drills' => 0,
            'shadowing' => 0,
        ];

        $completed = [
            'phrases' => 0,
            'dialogues' => 0,
            'drills' => 0,
            'shadowing' => 0,
        ];

        foreach ($allLessons as $lesson) {
            $totals['phrases'] += $lesson->phrases->count();
            $totals['dialogues'] += $lesson->dialogues->count();
            $totals['drills'] += $lesson->drills->count();
            $totals['shadowing'] += $lesson->shadowingExercises->count();

            $progress = $userProgress->get($lesson->id);
            if ($progress) {
                $completed['phrases'] += min($progress->phrases_viewed, $lesson->phrases->count());
                $completed['dialogues'] += min($progress->dialogues_viewed, $lesson->dialogues->count());
                $completed['drills'] += min($progress->drills_completed, $lesson->drills->count());
                $completed['shadowing'] += min($progress->shadowing_completed, $lesson->shadowingExercises->count());
            }
        }

        return [
            'phrases' => [
                'total' => $totals['phrases'],
                'completed' => $completed['phrases'],
                'percentage' => $totals['phrases'] > 0 ? round(($completed['phrases'] / $totals['phrases']) * 100, 2) : 0,
            ],
            'dialogues' => [
                'total' => $totals['dialogues'],
                'completed' => $completed['dialogues'],
                'percentage' => $totals['dialogues'] > 0 ? round(($completed['dialogues'] / $totals['dialogues']) * 100, 2) : 0,
            ],
            'drills' => [
                'total' => $totals['drills'],
                'completed' => $completed['drills'],
                'percentage' => $totals['drills'] > 0 ? round(($completed['drills'] / $totals['drills']) * 100, 2) : 0,
            ],
            'shadowing' => [
                'total' => $totals['shadowing'],
                'completed' => $completed['shadowing'],
                'percentage' => $totals['shadowing'] > 0 ? round(($completed['shadowing'] / $totals['shadowing']) * 100, 2) : 0,
            ],
        ];
    }

    /**
     * Get weak areas (lessons with low exercise scores)
     */
    protected function getWeakAreas($user): array
    {
        // Get lessons with exercise attempts
        $lessonScores = ExerciseAttempt::where('user_id', $user->id)
            ->with('drill.lesson')
            ->get()
            ->groupBy(function ($attempt) {
                return $attempt->drill->lesson_id ?? null;
            })
            ->filter(function ($group, $lessonId) {
                return $lessonId !== null;
            })
            ->map(function ($group) {
                $avgScore = $group->avg('score');
                $lesson = $group->first()->drill->lesson;
                
                return [
                    'lesson_id' => $lesson->id,
                    'lesson_title' => $lesson->title,
                    'lesson_slug' => $lesson->slug,
                    'avg_score' => round($avgScore, 2),
                    'attempts_count' => $group->count(),
                ];
            })
            ->sortBy('avg_score')
            ->take(5)
            ->values()
            ->toArray();

        return $lessonScores;
    }
}

