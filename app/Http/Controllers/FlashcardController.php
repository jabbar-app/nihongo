<?php

namespace App\Http\Controllers;

use App\Models\Flashcard;
use App\Models\Lesson;
use App\Models\Phrase;
use App\Services\SpacedRepetitionService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\JsonResponse;

class FlashcardController extends Controller
{
    public function __construct(
        protected SpacedRepetitionService $spacedRepetitionService
    ) {}

    public function index(): View
    {
        $user = auth()->user();
        
        // Get all flashcards grouped by lesson
        $flashcards = Flashcard::where('user_id', $user->id)
            ->with(['phrase.lesson'])
            ->get()
            ->groupBy(function ($flashcard) {
                return $flashcard->phrase?->lesson?->title ?? 'Custom Cards';
            });
        
        // Calculate statistics
        $totalCards = Flashcard::where('user_id', $user->id)->count();
        $dueCards = Flashcard::where('user_id', $user->id)->due()->count();
        $newCards = Flashcard::where('user_id', $user->id)->new()->count();
        $masteredCards = Flashcard::where('user_id', $user->id)->mastered()->count();
        
        return view('flashcards.index', compact('flashcards', 'totalCards', 'dueCards', 'newCards', 'masteredCards'));
    }

    public function create(Request $request): View
    {
        $lessonId = $request->query('lesson_id');
        $lesson = null;
        $phrases = collect();
        
        if ($lessonId) {
            $lesson = Lesson::with('phrases')->findOrFail($lessonId);
            $phrases = $lesson->phrases;
        }
        
        return view('flashcards.create', compact('lesson', 'phrases'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'phrase_ids' => 'nullable|array',
            'phrase_ids.*' => 'exists:phrases,id',
            'front' => 'nullable|required_without:phrase_ids|string|max:255',
            'back' => 'nullable|required_without:phrase_ids|string|max:255',
            'romaji' => 'nullable|string|max:255',
        ]);
        
        $user = auth()->user();
        $createdCount = 0;
        
        // Bulk creation from phrases
        if (!empty($validated['phrase_ids'])) {
            $phrases = Phrase::whereIn('id', $validated['phrase_ids'])->get();
            
            foreach ($phrases as $phrase) {
                // Check if flashcard already exists for this phrase
                $exists = Flashcard::where('user_id', $user->id)
                    ->where('phrase_id', $phrase->id)
                    ->exists();
                
                if (!$exists) {
                    Flashcard::create([
                        'user_id' => $user->id,
                        'phrase_id' => $phrase->id,
                        'front' => $phrase->japanese,
                        'back' => $phrase->english,
                        'romaji' => $phrase->romaji,
                        'ease_factor' => 2.5,
                        'interval' => 0,
                        'repetitions' => 0,
                        'next_review_at' => now(),
                    ]);
                    $createdCount++;
                }
            }
            
            return redirect()->route('flashcards.index')
                ->with('success', "Created {$createdCount} flashcard(s) successfully!");
        }
        
        // Manual creation
        if (!empty($validated['front']) && !empty($validated['back'])) {
            Flashcard::create([
                'user_id' => $user->id,
                'phrase_id' => null,
                'front' => $validated['front'],
                'back' => $validated['back'],
                'romaji' => $validated['romaji'] ?? '',
                'ease_factor' => 2.5,
                'interval' => 0,
                'repetitions' => 0,
                'next_review_at' => now(),
            ]);
            
            return redirect()->route('flashcards.index')
                ->with('success', 'Flashcard created successfully!');
        }
        
        return back()->with('error', 'Please select phrases or provide custom flashcard content.');
    }

    public function destroy(Flashcard $flashcard): RedirectResponse
    {
        // Ensure the flashcard belongs to the authenticated user
        if ($flashcard->user_id !== auth()->id()) {
            abort(403);
        }
        
        $flashcard->delete();
        
        return back()->with('success', 'Flashcard deleted successfully!');
    }

    public function review(Request $request): View
    {
        $user = auth()->user();
        $mode = $request->query('mode');

        if ($mode === 'new') {
            // Learn new cards only
            $sessionCards = $this->spacedRepetitionService->getNewCards($user, 10);
        } elseif ($mode === 'due') {
            // Review due cards only
            $sessionCards = $this->spacedRepetitionService->getDueCards($user, 20);
        } else {
            // Mixed session: prioritize due, then add new
            $dueCards = $this->spacedRepetitionService->getDueCards($user, 20);
            $newCards = $this->spacedRepetitionService->getNewCards($user, 10);
            $sessionCards = $dueCards->merge($newCards)->shuffle();
        }
        
        if ($sessionCards->isEmpty()) {
            return redirect()->route('flashcards.index')
                ->with('info', 'No cards due for review. Great job!');
        }
        
        // Store session data
        $sessionData = [
            'cards' => $sessionCards->pluck('id')->toArray(),
            'total' => $sessionCards->count(),
            'reviewed' => 0,
            'correct' => 0,
            'start_time' => now()->timestamp,
        ];
        
        $cardsPayload = $sessionCards->map(function ($c) {
            return [
                'id' => $c->id,
                'front' => $c->front,
                'back' => $c->back,
                'romaji' => $c->romaji,
            ];
        })->values();

        return view('flashcards.review', [
            'sessionCards' => $cardsPayload,
            'sessionData' => $sessionData,
        ]);
    }

    public function rate(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'flashcard_id' => 'required|exists:flashcards,id',
            'rating' => 'required|integer|min:1|max:4',
            'duration' => 'required|integer|min:0',
        ]);
        
        try {
            $flashcard = Flashcard::findOrFail($validated['flashcard_id']);

            // Ensure the flashcard belongs to the authenticated user
            if ($flashcard->user_id !== auth()->id()) {
                abort(403);
            }

            // Record the review
            $this->spacedRepetitionService->recordReview(
                $flashcard,
                $validated['rating'],
                $validated['duration']
            );

            return response()->json([
                'success' => true,
                'message' => 'Review recorded successfully',
            ]);
        } catch (\Throwable $e) {
            \Log::error('Flashcard rate failed', [
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }
}
