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

        <div class="flex items-center gap-4">
            <x-primary-button>{{ __('Save') }}</x-primary-button>

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
</section>
