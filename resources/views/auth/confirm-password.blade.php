<x-guest-layout>
    <div class="text-center mb-6">
        <h1 class="text-2xl font-semibold text-gray-900">パスワードを確認</h1>
        <p class="mt-1 text-sm text-gray-600">For security, please confirm your password to continue.</p>
    </div>

    <form method="POST" action="{{ route('password.confirm') }}">
        @csrf

        <!-- Password -->
        <div>
            <x-input-label for="password" :value="__('Password')" />

            <x-text-input id="password" class="block mt-1 w-full"
                            type="password"
                            name="password"
                            required autocomplete="current-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <div class="flex justify-between items-center mt-6">
            <a href="{{ route('login') }}" class="underline text-sm text-gray-600 hover:text-gray-900">Back to login</a>
            <x-primary-button>
                {{ __('Confirm') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
