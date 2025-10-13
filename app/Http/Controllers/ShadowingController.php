<?php

namespace App\Http\Controllers;

use App\Models\ShadowingExercise;
use App\Models\ShadowingCompletion;
use App\Models\StudyActivity;
use App\Services\ProgressService;
use App\Services\GamificationService;
use Illuminate\Http\Request;

class ShadowingController extends Controller
{
    protected $progressService;
    protected $gamificationService;

    public function __construct(ProgressService $progressService, GamificationService $gamificationService)
    {
        $this->progressService = $progressService;
        $this->gamificationService = $gamificationService;
    }

    /**
     * Display the specified shadowing exercise.
     */
    public function show(ShadowingExercise $shadowingExercise)
    {
        // Load the lesson relationship
        $shadowingExercise->load('lesson');
        
        // Get user's completions for this exercise
        $completions = $shadowingExercise->completions()
            ->where('user_id', auth()->id())
            ->with('recording')
            ->orderBy('completed_at', 'desc')
            ->get();
        
        $completionCount = $completions->count();
        $lastCompletion = $completions->first();
        
        // Get user's recordings for this exercise
        $recordings = \App\Models\UserRecording::where('user_id', auth()->id())
            ->where('recordable_type', 'shadowing')
            ->where('recordable_id', $shadowingExercise->id)
            ->orderBy('created_at', 'desc')
            ->get();
        
        return view('shadowing.show', compact(
            'shadowingExercise',
            'completions',
            'completionCount',
            'lastCompletion',
            'recordings'
        ));
    }

    /**
     * Mark a shadowing exercise as completed
     */
    public function complete(Request $request, ShadowingExercise $shadowingExercise)
    {
        $validated = $request->validate([
            'duration_seconds' => 'required|integer|min:1',
            'recording_id' => 'nullable|exists:user_recordings,id',
        ]);

        $user = auth()->user();

        // Create completion record
        $completion = ShadowingCompletion::create([
            'user_id' => $user->id,
            'shadowing_exercise_id' => $shadowingExercise->id,
            'duration_seconds' => $validated['duration_seconds'],
            'recording_id' => $validated['recording_id'] ?? null,
            'completed_at' => now(),
        ]);

        // Award XP
        $xpEarned = GamificationService::XP_SHADOWING_COMPLETION;
        $this->gamificationService->awardXP($user, $xpEarned, 'shadowing_completion');

        // Log study activity
        StudyActivity::create([
            'user_id' => $user->id,
            'activity_type' => 'shadowing',
            'activityable_type' => ShadowingCompletion::class,
            'activityable_id' => $completion->id,
            'duration_seconds' => $validated['duration_seconds'],
            'xp_earned' => $xpEarned,
        ]);

        // Update lesson progress
        $this->progressService->updateShadowingProgress($user, $shadowingExercise->lesson_id);

        // Check for level up
        $newLevel = $this->gamificationService->checkLevelUp($user);

        return response()->json([
            'success' => true,
            'message' => 'Shadowing exercise completed!',
            'xp_earned' => $xpEarned,
            'level_up' => $newLevel,
            'completion' => $completion->load('recording'),
        ]);
    }
}
