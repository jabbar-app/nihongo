<?php

namespace App\Http\Controllers;

use App\Models\Drill;
use App\Models\ExerciseAttempt;
use App\Models\Phrase;
use App\Models\PhraseReviewQueue;
use App\Services\ExerciseFactory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

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
     * Start an exercise attempt (track start time)
     */
    public function attempt(Drill $drill)
    {
        return $this->show($drill);
    }

    /**
     * Complete an exercise and save results
     */
    public function complete(Request $request, Drill $drill)
    {
        return $this->submit($request, $drill);
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

        // Track incorrect answers and add to review queue
        $this->trackIncorrectAnswers($drill, $results);

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

    /**
     * Track incorrect answers and add phrases to review queue
     */
    protected function trackIncorrectAnswers(Drill $drill, array $results)
    {
        $userId = Auth::id();
        $incorrectPhraseIds = [];

        // Extract incorrect answers and find related phrases
        foreach ($results as $result) {
            if (isset($result['correct']) && !$result['correct']) {
                // Try to find related phrase based on the question content
                $questionText = $result['question'] ?? '';
                
                // Search for phrases that match the question content
                $phrase = Phrase::where('lesson_id', $drill->lesson_id)
                    ->where(function ($query) use ($questionText) {
                        $query->where('japanese', 'like', '%' . $questionText . '%')
                              ->orWhere('english', 'like', '%' . $questionText . '%')
                              ->orWhere('romaji', 'like', '%' . $questionText . '%');
                    })
                    ->first();

                if ($phrase) {
                    $incorrectPhraseIds[] = $phrase->id;
                }
            }
        }

        // Add unique phrases to review queue
        foreach (array_unique($incorrectPhraseIds) as $phraseId) {
            PhraseReviewQueue::updateOrCreate(
                [
                    'user_id' => $userId,
                    'phrase_id' => $phraseId,
                    'drill_id' => $drill->id,
                ],
                [
                    'incorrect_count' => DB::raw('incorrect_count + 1'),
                    'last_incorrect_at' => now(),
                ]
            );
        }
    }

    /**
     * Show exercise history for a lesson
     */
    public function history(Request $request)
    {
        $lessonId = $request->input('lesson_id');
        
        $query = ExerciseAttempt::with(['drill.lesson'])
            ->where('user_id', Auth::id());

        if ($lessonId) {
            $query->whereHas('drill', function ($q) use ($lessonId) {
                $q->where('lesson_id', $lessonId);
            });
        }

        $attempts = $query->orderBy('completed_at', 'desc')
            ->paginate(20);

        // Calculate average scores by exercise type
        $averageScores = ExerciseAttempt::select('drills.type', DB::raw('AVG(exercise_attempts.score) as avg_score'))
            ->join('drills', 'exercise_attempts.drill_id', '=', 'drills.id')
            ->where('exercise_attempts.user_id', Auth::id())
            ->when($lessonId, function ($q) use ($lessonId) {
                $q->where('drills.lesson_id', $lessonId);
            })
            ->groupBy('drills.type')
            ->get()
            ->pluck('avg_score', 'type');

        return view('exercises.history', [
            'attempts' => $attempts,
            'averageScores' => $averageScores,
            'lessonId' => $lessonId,
        ]);
    }
}
