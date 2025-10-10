<?php

namespace App\Http\Controllers;

use App\Models\Lesson;
use Illuminate\Http\Request;
use Illuminate\View\View;

class LessonController extends Controller
{
    public function index(): View
    {
        $lessons = Lesson::orderBy('order')->get();
        
        return view('lessons.index', compact('lessons'));
    }

    public function show(string $slug): View
    {
        $lesson = Lesson::where('slug', $slug)
            ->with(['phrases', 'dialogues', 'drills', 'shadowingExercises'])
            ->firstOrFail();
        
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
