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
        // Cache lessons list for 1 hour (content rarely changes)
        $lessons = cache()->remember(
            'lessons.all',
            3600,
            fn() => Lesson::orderBy('order')->get()
        );
        
        return view('lessons.index', compact('lessons'));
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
        
        return view('lessons.show', compact('lesson', 'previousLesson', 'nextLesson'));
    }
}
