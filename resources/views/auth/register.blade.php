<x-guest-layout>
    <div class="text-center mb-6">
        <h1 class="text-2xl font-semibold text-gray-900">Create your Nihongo account</h1>
        <p class="mt-1 text-sm text-gray-600">Personalize your study plan and start learning today.</p>
    </div>

    <form method="POST" action="{{ route('register') }}" aria-label="Registration form">
        @csrf

        <!-- Name -->
        <div>
            <x-input-label for="name" :value="__('Name')" />
            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <!-- Email Address -->
        <div class="mt-4">
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />

            <x-text-input id="password" class="block mt-1 w-full"
                            type="password"
                            name="password"
                            required autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div class="mt-4">
            <x-input-label for="password_confirmation" :value="__('Confirm Password')" />

            <x-text-input id="password_confirmation" class="block mt-1 w-full"
                            type="password"
                            name="password_confirmation" required autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <!-- Study Goals -->
        <div class="mt-4">
            <x-input-label for="study_goal_minutes" :value="__('Daily Study Goal (minutes)')" />
            <x-text-input id="study_goal_minutes" class="block mt-1 w-full" type="number" name="study_goal_minutes" :value="old('study_goal_minutes', 120)" min="15" max="480" required />
            <p class="mt-1 text-sm text-gray-600">How many minutes per day do you plan to study?</p>
            <x-input-error :messages="$errors->get('study_goal_minutes')" class="mt-2" />
        </div>

        <!-- Cards Per Day Goal -->
        <div class="mt-4">
            <x-input-label for="cards_per_day_goal" :value="__('New Cards Per Day')" />
            <x-text-input id="cards_per_day_goal" class="block mt-1 w-full" type="number" name="cards_per_day_goal" :value="old('cards_per_day_goal', 20)" min="5" max="100" required />
            <p class="mt-1 text-sm text-gray-600">How many new flashcards do you want to learn each day?</p>
            <x-input-error :messages="$errors->get('cards_per_day_goal')" class="mt-2" />
        </div>

        <div class="flex items-center justify-between mt-6">
            <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('login') }}">
                {{ __('Already registered?') }}
            </a>

            <x-primary-button class="ms-4">
                {{ __('Register') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
