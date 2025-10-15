<x-guest-layout>
    <div class="text-center mb-6">
        <!-- Japanese design element: subtle wave pattern -->
        <div class="inline-flex items-center justify-center w-16 h-16 mb-4 rounded-full bg-gradient-to-br from-indigo-50 to-sakura-50">
            <svg class="w-8 h-8 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11a7 7 0 01-7 7m0 0a7 7 0 01-7-7m7 7v4m0 0H8m4 0h4m-4-8a3 3 0 01-3-3V5a3 3 0 116 0v6a3 3 0 01-3 3z" />
            </svg>
        </div>
        <h1 class="text-2xl font-semibold text-gray-900">Welcome back! Ready to practice speaking?</h1>
        <p class="mt-2 text-sm text-gray-600">Continue your conversation journey and keep your speaking streak alive.</p>
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}" aria-label="Login form">
        @csrf

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" aria-describedby="email-error" :hasError="$errors->has('email')" />
            <x-input-error id="email-error" :messages="$errors->get('email')" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />

            <x-text-input id="password" class="block mt-1 w-full"
                            type="password"
                            name="password"
                            required autocomplete="current-password" aria-describedby="password-error" :hasError="$errors->has('password')" />

            <x-input-error id="password-error" :messages="$errors->get('password')" />
        </div>

        <!-- Remember Me -->
        <div class="block mt-4">
            <label for="remember_me" class="inline-flex items-center cursor-pointer">
                <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" name="remember">
                <span class="ms-2 text-sm text-gray-600">{{ __('Keep me signed in for daily speaking practice') }}</span>
            </label>
        </div>

        <div class="mt-6">
            <x-primary-button class="w-full justify-center">
                {{ __('Continue Speaking') }}
            </x-primary-button>
        </div>

        @if (Route::has('password.request'))
            <div class="text-center mt-4">
                <a class="text-sm text-indigo-600 hover:text-indigo-700 underline-offset-2 hover:underline rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('password.request') }}">
                    {{ __('Forgot password?') }}
                </a>
            </div>
        @endif

        <div class="relative my-6">
            <div class="absolute inset-0 flex items-center">
                <div class="w-full border-t border-gray-200"></div>
            </div>
            <div class="relative flex justify-center text-sm">
                <span class="px-2 bg-white text-gray-500">or</span>
            </div>
        </div>

        <div class="text-center">
            <span class="text-sm text-gray-600">New to Nihongo? </span>
            <a href="{{ route('register') }}" class="text-sm text-indigo-600 hover:text-indigo-700 font-medium underline-offset-2 hover:underline rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                {{ __('Start speaking today →') }}
            </a>
        </div>
    </form>
</x-guest-layout>
