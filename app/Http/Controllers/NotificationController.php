<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Services\StudyPlanService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    protected StudyPlanService $studyPlanService;

    public function __construct(StudyPlanService $studyPlanService)
    {
        $this->studyPlanService = $studyPlanService;
    }

    /**
     * Update notification permission status
     */
    public function updatePermission(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'granted' => ['required', 'boolean'],
        ]);

        $request->user()->profile()->updateOrCreate(
            ['user_id' => $request->user()->id],
            ['notification_permission_requested' => true]
        );

        return response()->json([
            'success' => true,
            'message' => 'Notification permission status updated',
        ]);
    }

    /**
     * Get daily plan summary for notification
     */
    public function dailyPlanSummary(Request $request): JsonResponse
    {
        $user = $request->user();
        $today = now()->toDateString();
        
        // Get or generate today's plan
        $plan = $this->studyPlanService->getDailyPlan($user, $today);
        
        if (!$plan) {
            return response()->json([
                'summary' => 'Your daily study plan is ready. Click to start learning!',
                'activities' => [],
            ]);
        }

        $planData = $plan->plan_data;
        $totalActivities = count($planData);
        $completedActivities = $plan->completed_activities;
        $remainingActivities = $totalActivities - $completedActivities;

        // Build summary message
        if ($completedActivities === 0) {
            $summary = "Ready to start? You have {$totalActivities} activities planned today!";
        } elseif ($remainingActivities > 0) {
            $summary = "Great progress! {$remainingActivities} activities remaining today.";
        } else {
            $summary = "Amazing! You've completed all activities today. Keep up the streak!";
        }

        // Get activity breakdown
        $activityTypes = collect($planData)->groupBy('type')->map->count();
        $breakdown = [];
        
        if ($activityTypes->has('flashcard_review')) {
            $breakdown[] = "{$activityTypes['flashcard_review']} flashcard reviews";
        }
        if ($activityTypes->has('new_content')) {
            $breakdown[] = "{$activityTypes['new_content']} new lessons";
        }
        if ($activityTypes->has('exercise')) {
            $breakdown[] = "{$activityTypes['exercise']} exercises";
        }
        if ($activityTypes->has('shadowing')) {
            $breakdown[] = "{$activityTypes['shadowing']} shadowing practices";
        }

        if (!empty($breakdown)) {
            $summary .= "\n\nToday's plan: " . implode(', ', $breakdown) . ".";
        }

        return response()->json([
            'summary' => $summary,
            'activities' => $planData,
            'total' => $totalActivities,
            'completed' => $completedActivities,
            'remaining' => $remainingActivities,
        ]);
    }
}
