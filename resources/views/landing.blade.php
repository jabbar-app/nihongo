<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="Nihongo - Learn Japanese with interactive lessons, flashcards, and shadowing exercises.">

        <title>{{ config('app.name', 'Nihongo') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Styles / Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="bg-gray-50 text-gray-900 antialiased">
        <header class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4 flex items-center justify-between">
            <a href="/" class="flex items-center gap-2" aria-label="Nihongo home">
                <x-application-logo class="h-9 w-auto text-indigo-600" />
                <span class="sr-only">Nihongo</span>
            </a>
            <nav class="flex items-center gap-3">
                @auth
                    <a href="{{ route('dashboard') }}" class="inline-flex items-center px-4 py-2 text-sm font-medium border border-transparent rounded-md bg-indigo-600 text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">Dashboard</a>
                @else
                    <a href="{{ route('login') }}" class="inline-flex items-center px-4 py-2 text-sm font-medium text-indigo-700 hover:text-indigo-900">Log in</a>
                    @if (Route::has('register'))
                        <a href="{{ route('register') }}" class="inline-flex items-center px-4 py-2 text-sm font-medium border border-indigo-600 text-indigo-700 rounded-md hover:bg-indigo-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">Register</a>
                    @endif
                @endauth
            </nav>
        </header>

        <main class="relative overflow-hidden">
            <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 sm:py-24 grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
                <div>
                    <h1 class="text-4xl sm:text-5xl font-extrabold tracking-tight text-gray-900">
                        Speak Japanese with confidence
                    </h1>
                    <p class="mt-2 text-base text-gray-700" lang="ja">話せる日本語を、毎日少しずつ。</p>
                    <p class="mt-4 text-lg text-gray-600">
                        Learn by speaking. Practice real dialogues, shadow pronunciation, and reinforce with SRS flashcards. See your progress and keep your streak alive.
                    </p>
                    <div class="mt-8 flex flex-wrap gap-3">
                        @guest
                            <a href="{{ route('register') }}" class="inline-flex items-center px-6 py-3 text-base font-medium rounded-md bg-indigo-600 text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                Get started — it's free
                            </a>
                            <a href="{{ route('login') }}" class="inline-flex items-center px-6 py-3 text-base font-medium rounded-md border border-indigo-600 text-indigo-700 hover:bg-indigo-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                I already have an account
                            </a>
                        @else
                            <a href="{{ route('dashboard') }}" class="inline-flex items-center px-6 py-3 text-base font-medium rounded-md bg-indigo-600 text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                Go to dashboard
                            </a>
                        @endguest
                    </div>

                    <dl class="mt-10 grid grid-cols-1 sm:grid-cols-3 gap-6">
                        <div class="p-4 bg-white rounded-lg shadow">
                            <dt class="text-sm font-medium text-gray-500">Lessons</dt>
                            <dd class="mt-1 text-2xl font-semibold text-gray-900">Curated dialogues</dd>
                        </div>
                        <div class="p-4 bg-white rounded-lg shadow">
                            <dt class="text-sm font-medium text-gray-500">Flashcards</dt>
                            <dd class="mt-1 text-2xl font-semibold text-gray-900">SRS-powered reviews</dd>
                        </div>
                        <div class="p-4 bg-white rounded-lg shadow">
                            <dt class="text-sm font-medium text-gray-500">Shadowing</dt>
                            <dd class="mt-1 text-2xl font-semibold text-gray-900">Speak with confidence</dd>
                        </div>
                    </dl>
                </div>

                <div class="relative">
                    <div class="relative mx-auto w-full max-w-md bg-white rounded-2xl shadow-xl ring-1 ring-gray-200" x-data="{ added: false }">
                        <div class="px-6 py-5 border-b border-gray-100">
                            <div class="flex items-center gap-2">
                                <span class="h-2.5 w-2.5 rounded-full bg-red-400"></span>
                                <span class="h-2.5 w-2.5 rounded-full bg-yellow-400"></span>
                                <span class="h-2.5 w-2.5 rounded-full bg-green-400"></span>
                                <span class="ml-2 text-sm font-medium text-gray-600">Preview</span>
                            </div>
                        </div>
                        <div class="px-6 py-6 space-y-4">
                            <div class="flex items-center justify-between">
                                <span class="text-sm font-medium text-gray-700">今日のフレーズ</span>
                                <span class="text-xs text-gray-500">Lesson 1</span>
                            </div>
                            <div class="p-4 bg-indigo-50 rounded-lg">
                                <p class="text-gray-900 font-medium">駅はどこですか？</p>
                                <p class="text-gray-600 text-sm mt-1">Where is the station?</p>
                            </div>
                            <div class="flex items-center gap-3">
                                <x-audio-button text="駅はどこですか？" lang="ja-JP" size="md" variant="primary">Play audio</x-audio-button>
                                <x-secondary-button type="button" @click="added = true; setTimeout(() => added = false, 1200)" aria-live="polite">
                                    <span x-show="!added">Add to cards</span>
                                    <span x-show="added" class="inline-flex items-center text-green-700">
                                        <svg class="w-4 h-4 mr-1" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-7.364 7.364a1 1 0 01-1.414 0L3.293 10.435a1 1 0 111.414-1.414l3.05 3.05 6.657-6.657a1 1 0 011.293-.121z" clip-rule="evenodd"/></svg>
                                        <span>Added!</span>
                                    </span>
                                </x-secondary-button>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            
            <!-- Features Section -->
            <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-16 sm:pb-24">
                <div class="text-center mb-10 sm:mb-12">
                    <h2 class="text-2xl sm:text-3xl font-bold text-gray-900">Designed for speaking-first learning</h2>
                    <p class="mt-2 text-gray-600">Shadow real dialogues, practice out loud, and let spaced repetition lock it in.</p>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6">
                    <div class="p-5 bg-white rounded-xl shadow border border-gray-200">
                        <div class="flex items-center gap-2 mb-2">
                            <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19V6l12-2v13"/></svg>
                            <h3 class="font-semibold text-gray-900">Real Dialogues</h3>
                        </div>
                        <p class="text-sm text-gray-600">短い会話で実践練習。自然な表現を身につける。</p>
                    </div>
                    <div class="p-5 bg-white rounded-xl shadow border border-gray-200">
                        <div class="flex items-center gap-2 mb-2">
                            <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5"/></svg>
                            <h3 class="font-semibold text-gray-900">Shadowing & Speaking</h3>
                        </div>
                        <p class="text-sm text-gray-600">音声をまねて発音練習。話す力を最短で伸ばす。</p>
                    </div>
                    <div class="p-5 bg-white rounded-xl shadow border border-gray-200">
                        <div class="flex items-center gap-2 mb-2">
                            <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            <h3 class="font-semibold text-gray-900">Spaced Repetition</h3>
                        </div>
                        <p class="text-sm text-gray-600">忘れる前に復習。効率よく定着させる。</p>
                    </div>
                    <div class="p-5 bg-white rounded-xl shadow border border-gray-200">
                        <div class="flex items-center gap-2 mb-2">
                            <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h4l3 10 4-18 3 8h4"/></svg>
                            <h3 class="font-semibold text-gray-900">Progress & Streaks</h3>
                        </div>
                        <p class="text-sm text-gray-600">毎日の積み重ねを可視化。やる気も続く。</p>
                    </div>
                </div>
            </section>
        </main>

        <footer class="border-t border-gray-200">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 text-sm text-gray-500 flex items-center justify-between">
                <p>&copy; {{ date('Y') }} Nihongo. All rights reserved.</p>
                <div class="flex items-center gap-4">
                    <a href="{{ route('login') }}" class="hover:text-gray-700">Log in</a>
                    @if (Route::has('register'))
                        <a href="{{ route('register') }}" class="hover:text-gray-700">Register</a>
                    @endif
                </div>
            </div>
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-6 text-xs sm:text-sm text-gray-500">
                Built by <a href="https://www.linkedin.com/in/jabbarpanggabean/" target="_blank" rel="noopener noreferrer" class="text-indigo-600 hover:text-indigo-700 underline-offset-2 hover:underline">Jabbar Ali Panggabean</a>
            </div>
        </footer>
    </body>
</html>


