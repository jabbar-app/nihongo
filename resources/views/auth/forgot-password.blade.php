<x-guest-layout>
    <div class="text-center mb-6">
        <h1 class="text-2xl font-semibold text-gray-900">パスワード再設定</h1>
        <p class="mt-1 text-sm text-gray-600">Forgot your password? Enter your email to receive a reset link.</p>
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('password.email') }}" aria-label="Forgot password form">
        @csrf

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div class="flex items-center justify-between mt-6">
            <a href="{{ route('login') }}" class="underline text-sm text-gray-600 hover:text-gray-900">Back to login</a>
            <x-primary-button>
                {{ __('Send Reset Link') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
