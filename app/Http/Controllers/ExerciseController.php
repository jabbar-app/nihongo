<?php

namespace App\Http\Controllers;

use App\Models\Drill;
use App\Models\ExerciseAttempt;
use App\Services\ExerciseFactory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ExerciseController extends Controller
{
    /**
     * Show the exercise attempt view
     */
    public function show(Drill $drill)
    {
        $exercise = ExerciseFactory::create($drill);
        $exerciseData = $exercise->generate($drill);

        return view('exercises.attempt', [
            'drill' => $drill,
            'exerciseData' => $exerciseData,
        ]);
    }

    /**
     * Submit and validate exercise answers
     */
    public function submit(Request $request, Drill $drill)
    {
        $request->validate([
            'answers' => 'required|array',
            'duration_seconds' => 'nullable|integer|min:0',
        ]);

        $exercise = ExerciseFactory::create($drill);
        
        // Validate answers
        $results = $exercise->validate($request->input('answers'), $drill);
        
        // Calculate score
        $score = $exercise->getScore($results);

        // Save exercise attempt
        $attempt = ExerciseAttempt::create([
            'user_id' => Auth::id(),
            'drill_id' => $drill->id,
            'answers' => $request->input('answers'),
            'score' => $score,
            'duration_seconds' => $request->input('duration_seconds', 0),
            'completed_at' => now(),
        ]);

        // Return results as JSON for AJAX requests
        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'results' => $results,
                'score' => $score,
                'attempt_id' => $attempt->id,
            ]);
        }

        // Redirect back with results for non-AJAX requests
        return redirect()->route('exercises.show', $drill)
            ->with('results', $results)
            ->with('score', $score);
    }
}
