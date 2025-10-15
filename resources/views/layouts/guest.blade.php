<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Nihongo') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js', 'resources/js/formValidation.js'])
    </head>
    <body class="font-sans text-gray-900 antialiased">
        <div class="min-h-screen bg-gradient-to-b from-indigo-50 to-white">
            <header class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 flex items-center justify-between">
                <a href="/" class="flex items-center gap-2" aria-label="Nihongo home">
                    <x-application-logo class="w-10 h-10 fill-current text-indigo-600" />
                    <span class="sr-only">Nihongo</span>
                </a>
                <nav class="flex items-center gap-3 text-sm">
                    <a href="{{ route('login') }}" class="text-indigo-700 hover:text-indigo-900">Log in</a>
                    @if (Route::has('register'))
                        <a href="{{ route('register') }}" class="inline-flex items-center px-3 py-1.5 border border-indigo-600 text-indigo-700 rounded-md hover:bg-indigo-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">Register</a>
                    @endif
                </nav>
            </header>

            <div class="w-full max-w-md mx-auto mt-4 sm:mt-8 px-6 py-6 bg-white shadow-md overflow-hidden rounded-2xl ring-1 ring-gray-100">
                {{ $slot }}
            </div>

            <footer class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 text-center text-sm text-gray-500">
                <div class="space-y-1">
                    <div>&copy; {{ date('Y') }} Nihongo</div>
                    <div>Built by <a href="https://www.linkedin.com/in/jabbarpanggabean/" target="_blank" rel="noopener noreferrer" class="text-indigo-600 hover:text-indigo-700 underline-offset-2 hover:underline">Jabbar Ali Panggabean</a></div>
                </div>
            </footer>
        </div>
    </body>
</html>
