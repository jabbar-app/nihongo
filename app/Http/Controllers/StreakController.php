<?php

namespace App\Http\Controllers;

use App\Services\ProgressService;
use App\Services\GamificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StreakController extends Controller
{
    public function __construct(
        private ProgressService $progressService,
        private GamificationService $gamificationService
    ) {}

    /**
     * Display streak information and calendar
     */
    public function index()
    {
        $user = Auth::user();
        $profile = $user->profile;

        // Get streak calendar for last 28 days
        $streakCalendar = $this->progressService->getStreakCalendar($user, 28);

        // Check if user can use streak recovery
        $canUseRecovery = $this->progressService->canUseStreakRecovery($user);

        // Get streak milestones
        $streakMilestones = [
            ['days' => 7, 'name' => '7-Day Streak', 'achieved' => $profile->longest_streak >= 7],
            ['days' => 14, 'name' => '2-Week Streak', 'achieved' => $profile->longest_streak >= 14],
            ['days' => 30, 'name' => '30-Day Streak', 'achieved' => $profile->longest_streak >= 30],
            ['days' => 60, 'name' => '60-Day Streak', 'achieved' => $profile->longest_streak >= 60],
            ['days' => 100, 'name' => '100-Day Streak', 'achieved' => $profile->longest_streak >= 100],
            ['days' => 365, 'name' => '1-Year Streak', 'achieved' => $profile->longest_streak >= 365],
        ];

        return view('streak.index', compact(
            'profile',
            'streakCalendar',
            'canUseRecovery',
            'streakMilestones'
        ));
    }

    /**
     * Use streak recovery
     */
    public function recover(Request $request)
    {
        $user = Auth::user();

        if (!$this->progressService->canUseStreakRecovery($user)) {
            return back()->with('error', 'You cannot use streak recovery at this time. It can only be used once every 30 days.');
        }

        $success = $this->progressService->useStreakRecovery($user);

        if ($success) {
            // Check for streak achievements
            $this->gamificationService->checkAchievements($user);

            return back()->with('success', 'Streak recovery used successfully! Your streak has been restored.');
        }

        return back()->with('error', 'Failed to use streak recovery. Please try again.');
    }
}
