<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('Study Preferences') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            {{ __("Update your daily study goals and learning preferences.") }}
        </p>
    </header>

    <form method="post" action="{{ route('profile.study-preferences.update') }}" class="mt-6 space-y-6">
        @csrf
        @method('patch')

        <div>
            <x-input-label for="study_goal_minutes" :value="__('Daily Study Goal (minutes)')" />
            <x-text-input id="study_goal_minutes" name="study_goal_minutes" type="number" class="mt-1 block w-full" 
                :value="old('study_goal_minutes', $user->profile->study_goal_minutes ?? 120)" 
                min="15" max="480" required />
            <p class="mt-1 text-sm text-gray-600">How many minutes per day do you plan to study?</p>
            <x-input-error class="mt-2" :messages="$errors->get('study_goal_minutes')" />
        </div>

        <div>
            <x-input-label for="cards_per_day_goal" :value="__('New Cards Per Day')" />
            <x-text-input id="cards_per_day_goal" name="cards_per_day_goal" type="number" class="mt-1 block w-full" 
                :value="old('cards_per_day_goal', $user->profile->cards_per_day_goal ?? 20)" 
                min="5" max="100" required />
            <p class="mt-1 text-sm text-gray-600">How many new flashcards do you want to learn each day?</p>
            <x-input-error class="mt-2" :messages="$errors->get('cards_per_day_goal')" />
        </div>

        <div class="border-t pt-6">
            <h3 class="text-md font-medium text-gray-900 mb-4">{{ __('Study Reminders') }}</h3>
            
            <div class="space-y-4">
                <div class="flex items-center">
                    <input id="study_reminders_enabled" name="study_reminders_enabled" type="checkbox" 
                        class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500"
                        value="1"
                        {{ old('study_reminders_enabled', $user->profile->study_reminders_enabled ?? false) ? 'checked' : '' }}
                        x-data="{ enabled: {{ old('study_reminders_enabled', $user->profile->study_reminders_enabled ?? false) ? 'true' : 'false' }} }"
                        x-model="enabled"
                        @change="if (enabled && !('Notification' in window)) { alert('Your browser does not support notifications.'); enabled = false; $el.checked = false; }"
                    />
                    <label for="study_reminders_enabled" class="ml-2 text-sm text-gray-900">
                        {{ __('Enable daily study reminders') }}
                    </label>
                </div>

                <div x-show="enabled" x-cloak>
                    <x-input-label for="study_reminder_time" :value="__('Reminder Time')" />
                    <x-text-input id="study_reminder_time" name="study_reminder_time" type="time" class="mt-1 block w-full" 
                        :value="old('study_reminder_time', $user->profile->study_reminder_time ?? '09:00')" />
                    <p class="mt-1 text-sm text-gray-600">What time would you like to receive your daily study reminder?</p>
                    <x-input-error class="mt-2" :messages="$errors->get('study_reminder_time')" />
                </div>

                <div x-show="enabled" x-cloak class="bg-blue-50 border border-blue-200 rounded-md p-3">
                    <p class="text-sm text-blue-800">
                        <strong>Note:</strong> You'll be asked to grant notification permission when you save these settings. 
                        Make sure to allow notifications in your browser to receive reminders.
                    </p>
                </div>
            </div>
        </div>

        <div class="flex items-center gap-4">
            <x-primary-button x-data @click="handleSave($event)">{{ __('Save') }}</x-primary-button>

            @if (session('status') === 'study-preferences-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-gray-600"
                >{{ __('Saved.') }}</p>
            @endif
        </div>
    </form>

    <script>
        function handleSave(event) {
            const remindersEnabled = document.getElementById('study_reminders_enabled').checked;
            
            if (remindersEnabled && window.notificationService) {
                // Request permission before submitting form
                event.preventDefault();
                
                window.notificationService.requestPermission().then(granted => {
                    if (granted) {
                        // Permission granted, submit the form
                        event.target.closest('form').submit();
                    } else {
                        // Permission denied, show alert and uncheck the checkbox
                        alert('Notification permission is required to enable study reminders. Please allow notifications in your browser settings.');
                        document.getElementById('study_reminders_enabled').checked = false;
                    }
                });
            }
        }
    </script>
</section>
