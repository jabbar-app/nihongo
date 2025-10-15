<?php

namespace App\Http\Controllers;

use App\Models\Lesson;
use App\Services\RecentlyViewedService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class LessonController extends Controller
{
    public function __construct(
        private RecentlyViewedService $recentlyViewedService
    ) {
    }
    public function index(): View
    {
        $user = Auth::user();
        
        // Cache lessons list for 1 hour (content rarely changes)
        $lessons = cache()->remember(
            'lessons.all.with_counts',
            3600,
            fn() => Lesson::withCount(['dialogues', 'shadowingExercises'])
                ->orderBy('order')
                ->get()
        );
        
        // Get user progress data
        $userProgress = $user->progress()->with('lesson')->get();
        
        // Calculate conversations mastered (lessons with 100% completion)
        $conversationsMastered = $userProgress->where('completion_percentage', '>=', 100)->count();
        $totalConversations = $lessons->count();
        
        // Calculate speaking streak
        $speakingStreak = $user->current_streak ?? 0;
        
        // Calculate total speaking time (in hours) from study activities
        $totalSpeakingSeconds = $user->studyActivities()
            ->whereIn('activity_type', ['exercise', 'shadowing'])
            ->sum('duration_seconds');
        $totalSpeakingTime = round($totalSpeakingSeconds / 3600, 1);
        
        // Attach progress data and determine status for each lesson
        $lessons = $lessons->map(function ($lesson, $index) use ($userProgress, $lessons) {
            $progress = $userProgress->firstWhere('lesson_id', $lesson->id);
            $lesson->user_progress = $progress;
            
            // Determine lesson status
            if ($progress && $progress->completion_percentage >= 100) {
                $lesson->status = 'completed';
            } elseif ($progress && $progress->completion_percentage > 0) {
                $lesson->status = 'in-progress';
            } elseif ($index === 0) {
                // First lesson is always available
                $lesson->status = 'available';
            } else {
                // Check if previous lesson is completed
                $previousLesson = $lessons[$index - 1];
                $previousProgress = $userProgress->firstWhere('lesson_id', $previousLesson->id);
                $lesson->status = ($previousProgress && $previousProgress->completion_percentage >= 100) 
                    ? 'available' 
                    : 'locked';
                $lesson->prerequisite_lesson = $previousLesson;
            }
            
            return $lesson;
        });
        
        return view('lessons.index', compact(
            'lessons',
            'conversationsMastered',
            'totalConversations',
            'speakingStreak',
            'totalSpeakingTime'
        ));
    }

    public function show(string $slug): View
    {
        // Cache lesson content for 1 hour (content rarely changes)
        $lesson = cache()->remember(
            "lesson.{$slug}.full",
            3600,
            fn() => Lesson::where('slug', $slug)
                ->with(['phrases', 'dialogues', 'drills', 'shadowingExercises'])
                ->firstOrFail()
        );
        
        // Track this view for recently viewed
        if (Auth::check()) {
            $this->recentlyViewedService->trackView(
                Auth::user(),
                'lesson',
                $lesson->id,
                ['title' => $lesson->title, 'slug' => $lesson->slug]
            );
        }
        
        // Get previous and next lessons for navigation
        $previousLesson = Lesson::where('order', '<', $lesson->order)
            ->orderBy('order', 'desc')
            ->first();
        
        $nextLesson = Lesson::where('order', '>', $lesson->order)
            ->orderBy('order', 'asc')
            ->first();
        
        // Get user progress for this lesson
        $userProgress = null;
        $speakingTimePracticed = 0;
        $estimatedTime = 15; // Default estimated time in minutes
        
        if (Auth::check()) {
            $user = Auth::user();
            $userProgress = $user->progress()->where('lesson_id', $lesson->id)->first();
            
            // Calculate speaking time practiced for this lesson (in minutes)
            $exerciseSeconds = $user->exerciseAttempts()
                ->whereHas('drill', function ($q) use ($lesson) {
                    $q->where('lesson_id', $lesson->id);
                })
                ->sum('duration_seconds');

            $shadowingSeconds = $user->shadowingCompletions()
                ->whereHas('exercise', function ($q) use ($lesson) {
                    $q->where('lesson_id', $lesson->id);
                })
                ->sum('duration_seconds');

            $speakingTimePracticed = round(($exerciseSeconds + $shadowingSeconds) / 60, 1);
            
            // Calculate estimated time based on content
            $estimatedTime = ($lesson->dialogues->count() * 3) + ($lesson->shadowingExercises->count() * 5);
        }
        
        return view('lessons.show', compact(
            'lesson', 
            'previousLesson', 
            'nextLesson',
            'userProgress',
            'speakingTimePracticed',
            'estimatedTime'
        ));
    }
}
