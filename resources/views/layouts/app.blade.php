<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta name="description" content="Japanese Learning Application - Learn Japanese through interactive lessons, flashcards, and exercises">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased">
        <!-- Skip to main content link for keyboard navigation -->
        <a href="#main-content" class="skip-to-main sr-only-focusable">
            Skip to main content
        </a>

        <div class="min-h-screen bg-gray-100">
            @include('layouts.navigation')

            <!-- Page Heading -->
            @isset($header)
                <header class="bg-white shadow" role="banner">
                    <div class="max-w-7xl mx-auto py-4 px-4 sm:py-6 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            <!-- Page Content -->
            <main id="main-content" class="pb-16 sm:pb-0 page-transition" tabindex="-1" role="main">
                {{ $slot }}
            </main>
            <footer class="bg-white/80 backdrop-blur supports-[backdrop-filter]:bg-white/60 border-t border-gray-200">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4 text-xs sm:text-sm text-gray-500 flex flex-col sm:flex-row items-center justify-between gap-2">
                    <p>&copy; {{ date('Y') }} Nihongo</p>
                    <p>
                        Built by
                        <a href="https://www.linkedin.com/in/jabbarpanggabean/" target="_blank" rel="noopener noreferrer" class="text-indigo-600 hover:text-indigo-700 underline-offset-2 hover:underline">Jabbar Ali Panggabean</a>
                    </p>
                </div>
            </footer>
        </div>

        <!-- Keyboard Shortcuts Help -->
        <x-keyboard-shortcuts-modal />

        <!-- Level Up Modal -->
        @auth
            <x-level-up-modal :levelUpData="session('level_up_data')" />
            
            <!-- Notification Configuration -->
            <script>
                window.notificationConfig = {
                    userId: {{ auth()->id() }},
                    reminderTime: '{{ auth()->user()->profile->study_reminder_time ?? '' }}',
                    remindersEnabled: {{ auth()->user()->profile->study_reminders_enabled ? 'true' : 'false' }},
                };
            </script>
        @endauth
        
        @stack('scripts')
    </body>
</html>
