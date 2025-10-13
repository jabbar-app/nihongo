<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Services\GamificationService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ProfileController extends Controller
{
    protected GamificationService $gamificationService;

    public function __construct(GamificationService $gamificationService)
    {
        $this->gamificationService = $gamificationService;
    }

    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        $user = $request->user();
        $profile = $user->profile;
        $xpProgress = $this->gamificationService->getXpProgress($user);

        return view('profile.edit', [
            'user' => $user,
            'profile' => $profile,
            'xpProgress' => $xpProgress,
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Update the user's study preferences.
     */
    public function updateStudyPreferences(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'study_goal_minutes' => ['required', 'integer', 'min:15', 'max:480'],
            'cards_per_day_goal' => ['required', 'integer', 'min:5', 'max:100'],
            'study_reminders_enabled' => ['nullable', 'boolean'],
            'study_reminder_time' => ['nullable', 'date_format:H:i'],
        ]);

        // Convert checkbox to boolean
        $validated['study_reminders_enabled'] = $request->has('study_reminders_enabled');

        // If reminders are disabled, clear the time
        if (!$validated['study_reminders_enabled']) {
            $validated['study_reminder_time'] = null;
        }

        $request->user()->profile()->updateOrCreate(
            ['user_id' => $request->user()->id],
            $validated
        );

        return Redirect::route('profile.edit')->with('status', 'study-preferences-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
