<x-guest-layout>
    <div class="text-center mb-6">
        <!-- Japanese design element: microphone icon with gradient -->
        <div class="inline-flex items-center justify-center w-16 h-16 mb-4 rounded-full bg-gradient-to-br from-indigo-50 to-sakura-50">
            <svg class="w-8 h-8 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11a7 7 0 01-7 7m0 0a7 7 0 01-7-7m7 7v4m0 0H8m4 0h4m-4-8a3 3 0 01-3-3V5a3 3 0 116 0v6a3 3 0 01-3 3z" />
            </svg>
        </div>
        <h1 class="text-2xl font-semibold text-gray-900">Start Speaking Japanese Today</h1>
        <p class="mt-2 text-sm text-gray-600">Join thousands of learners mastering Japanese through real conversations.</p>
    </div>

    <form method="POST" action="{{ route('register') }}" aria-label="Registration form">
        @csrf

        <!-- Name -->
        <div>
            <x-input-label for="name" :value="__('What should we call you?')" />
            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" placeholder="Your name" aria-describedby="name-error" :hasError="$errors->has('name')" />
            <x-input-error id="name-error" :messages="$errors->get('name')" />
        </div>

        <!-- Email Address -->
        <div class="mt-4">
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autocomplete="username" placeholder="you@example.com" aria-describedby="email-error" :hasError="$errors->has('email')" />
            <x-input-error id="email-error" :messages="$errors->get('email')" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />

            <x-text-input id="password" class="block mt-1 w-full"
                            type="password"
                            name="password"
                            required autocomplete="new-password" aria-describedby="password-error" :hasError="$errors->has('password')" />

            <x-input-error id="password-error" :messages="$errors->get('password')" />
        </div>

        <!-- Confirm Password -->
        <div class="mt-4">
            <x-input-label for="password_confirmation" :value="__('Confirm Password')" />

            <x-text-input id="password_confirmation" class="block mt-1 w-full"
                            type="password"
                            name="password_confirmation" required autocomplete="new-password" aria-describedby="password-confirmation-error" :hasError="$errors->has('password_confirmation')" />

            <x-input-error id="password-confirmation-error" :messages="$errors->get('password_confirmation')" />
        </div>

        <!-- Daily Speaking Practice Goal -->
        <div class="mt-6">
            <x-input-label for="study_goal_minutes" :value="__('Daily speaking practice goal')" />
            <div class="mt-2 grid grid-cols-3 gap-3">
                <label class="relative flex items-center justify-center h-11 cursor-pointer rounded-lg border-2 transition-all {{ old('study_goal_minutes', 30) == 15 ? 'border-indigo-600 bg-indigo-50' : 'border-gray-200 hover:border-gray-300' }}">
                    <input type="radio" name="study_goal_minutes" value="15" class="sr-only" {{ old('study_goal_minutes', 30) == 15 ? 'checked' : '' }} />
                    <span class="text-sm font-medium {{ old('study_goal_minutes', 30) == 15 ? 'text-indigo-700' : 'text-gray-700' }}">15 min</span>
                </label>
                <label class="relative flex items-center justify-center h-11 cursor-pointer rounded-lg border-2 transition-all {{ old('study_goal_minutes', 30) == 30 ? 'border-indigo-600 bg-indigo-50' : 'border-gray-200 hover:border-gray-300' }}">
                    <input type="radio" name="study_goal_minutes" value="30" class="sr-only" {{ old('study_goal_minutes', 30) == 30 ? 'checked' : '' }} />
                    <span class="text-sm font-medium {{ old('study_goal_minutes', 30) == 30 ? 'text-indigo-700' : 'text-gray-700' }}">30 min</span>
                </label>
                <label class="relative flex items-center justify-center h-11 cursor-pointer rounded-lg border-2 transition-all {{ old('study_goal_minutes', 30) == 60 ? 'border-indigo-600 bg-indigo-50' : 'border-gray-200 hover:border-gray-300' }}">
                    <input type="radio" name="study_goal_minutes" value="60" class="sr-only" {{ old('study_goal_minutes', 30) == 60 ? 'checked' : '' }} />
                    <span class="text-sm font-medium {{ old('study_goal_minutes', 30) == 60 ? 'text-indigo-700' : 'text-gray-700' }}">60 min</span>
                </label>
            </div>
            <x-input-error :messages="$errors->get('study_goal_minutes')" class="mt-2" />
        </div>

        <!-- Conversation Topic Interests -->
        <div class="mt-6">
            <x-input-label :value="__('What do you want to talk about in Japanese?')" />
            <p class="mt-1 text-xs text-gray-500">Select all that interest you</p>
            <div class="mt-2 space-y-2">
                @php
                    $topics = [
                        'travel' => 'Travel & Directions',
                        'food' => 'Food & Dining',
                        'daily_life' => 'Daily Life',
                        'business' => 'Business & Work',
                        'culture' => 'Culture & Traditions',
                        'hobbies' => 'Hobbies & Interests'
                    ];
                    $oldTopics = old('conversation_topics', []);
                @endphp
                @foreach($topics as $value => $label)
                    <label class="flex items-center cursor-pointer group">
                        <input type="checkbox" name="conversation_topics[]" value="{{ $value }}" 
                               class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500"
                               {{ in_array($value, $oldTopics) ? 'checked' : '' }}>
                        <span class="ms-2 text-sm text-gray-700 group-hover:text-gray-900">{{ $label }}</span>
                    </label>
                @endforeach
            </div>
            <x-input-error :messages="$errors->get('conversation_topics')" class="mt-2" />
        </div>

        <!-- Cards Per Day Goal (kept for backward compatibility) -->
        <input type="hidden" name="cards_per_day_goal" value="{{ old('cards_per_day_goal', 20) }}" />

        <div class="mt-6">
            <x-primary-button class="w-full justify-center">
                {{ __('Start My Speaking Journey') }}
            </x-primary-button>
        </div>

        <div class="text-center mt-4">
            <span class="text-sm text-gray-600">Already have an account? </span>
            <a href="{{ route('login') }}" class="text-sm text-indigo-600 hover:text-indigo-700 font-medium underline-offset-2 hover:underline rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                {{ __('Log in →') }}
            </a>
        </div>
    </form>
</x-guest-layout>
