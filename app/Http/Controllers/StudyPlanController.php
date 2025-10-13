<?php

namespace App\Http\Controllers;

use App\Services\StudyPlanService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StudyPlanController extends Controller
{
    public function __construct(
        private StudyPlanService $studyPlanService
    ) {}

    /**
     * Display today's daily study plan
     */
    public function show(Request $request)
    {
        $user = Auth::user();
        $date = Carbon::today();

        // Generate or retrieve today's plan
        $plan = $this->studyPlanService->generateDailyPlan($user, $date);

        // Calculate progress
        $completionPercentage = $plan->total_activities > 0 
            ? round(($plan->completed_activities / $plan->total_activities) * 100) 
            : 0;

        // Calculate estimated time remaining
        $activities = $plan->plan_data['activities'] ?? [];
        $estimatedTimeRemaining = 0;
        
        foreach ($activities as $activity) {
            if (!($activity['completed'] ?? false)) {
                $estimatedTimeRemaining += $activity['estimated_minutes'] ?? 0;
            }
        }

        return view('study-plan.show', compact('plan', 'completionPercentage', 'estimatedTimeRemaining'));
    }

    /**
     * Mark an activity as complete
     */
    public function complete(Request $request, string $activityId)
    {
        $user = Auth::user();
        $date = Carbon::today();

        // Get today's plan
        $plan = $this->studyPlanService->generateDailyPlan($user, $date);

        // Update the activity as completed
        $planData = $plan->plan_data;
        $activities = $planData['activities'] ?? [];
        
        $activityFound = false;
        foreach ($activities as &$activity) {
            if ($activity['id'] === $activityId) {
                $activity['completed'] = true;
                $activityFound = true;
                break;
            }
        }

        if ($activityFound) {
            // Count completed activities
            $completedCount = collect($activities)->where('completed', true)->count();
            
            // Update plan
            $planData['activities'] = $activities;
            $plan->plan_data = $planData;
            $plan->completed_activities = $completedCount;
            
            // Mark plan as completed if all activities done
            if ($completedCount >= $plan->total_activities) {
                $plan->completed_at = now();
            }
            
            $plan->save();

            return response()->json([
                'success' => true,
                'completed_activities' => $completedCount,
                'total_activities' => $plan->total_activities,
                'plan_completed' => $plan->completed_at !== null,
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Activity not found',
        ], 404);
    }
}
