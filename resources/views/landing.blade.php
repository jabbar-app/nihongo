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
            <a href="/" class="flex items-center gap-2 min-h-[44px]" aria-label="Nihongo home">
                <x-application-logo class="h-9 w-auto text-indigo-600" />
                <span class="sr-only">Nihongo</span>
            </a>
            <nav class="flex items-center gap-2 sm:gap-3">
                @auth
                    <a href="{{ route('dashboard') }}" class="inline-flex items-center justify-center px-4 py-3 text-sm font-medium border border-transparent rounded-md bg-indigo-600 text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 min-h-[44px]">Dashboard</a>
                @else
                    <a href="{{ route('login') }}" class="inline-flex items-center justify-center px-3 sm:px-4 py-3 text-sm font-medium text-indigo-700 hover:text-indigo-900 min-h-[44px]">Log in</a>
                    @if (Route::has('register'))
                        <a href="{{ route('register') }}" class="inline-flex items-center justify-center px-3 sm:px-4 py-3 text-sm font-medium border border-indigo-600 text-indigo-700 rounded-md hover:bg-indigo-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 min-h-[44px]">Register</a>
                    @endif
                @endauth
            </nav>
        </header>

        <main class="relative overflow-hidden">
            <!-- Hero Section -->
            <section class="relative bg-gradient-to-b from-indigo-50 to-white py-16 sm:py-24">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
                        <div class="text-center lg:text-left">
                            <h1 class="text-5xl sm:text-6xl font-extrabold tracking-tight text-indigo-900">
                                Speak Japanese with Confidence
                            </h1>
                            <p class="mt-4 text-xl text-gray-700">
                                Master real conversations through speaking practice
                            </p>
                            <p class="mt-2 text-lg text-gray-600">
                                Practice dialogues, shadow native speakers, and build your speaking skills one conversation at a time.
                            </p>
                            
                            <div class="mt-8 flex flex-col sm:flex-row gap-4 justify-center lg:justify-start">
                                @guest
                                    <a href="{{ route('register') }}" class="inline-flex items-center justify-center px-8 py-4 text-lg font-semibold rounded-lg bg-gradient-to-r from-indigo-600 to-indigo-700 text-white hover:from-indigo-700 hover:to-indigo-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 shadow-lg hover:shadow-xl transition-all duration-200 hover:-translate-y-0.5" style="min-height: 56px;">
                                        Start Speaking Japanese
                                        <svg class="ml-2 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                                        </svg>
                                    </a>
                                    <a href="{{ route('login') }}" class="inline-flex items-center justify-center px-8 py-4 text-lg font-semibold rounded-lg border-2 border-indigo-600 text-indigo-700 hover:bg-indigo-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-all duration-200" style="min-height: 56px;">
                                        Log In
                                    </a>
                                @else
                                    <a href="{{ route('dashboard') }}" class="inline-flex items-center justify-center px-8 py-4 text-lg font-semibold rounded-lg bg-gradient-to-r from-indigo-600 to-indigo-700 text-white hover:from-indigo-700 hover:to-indigo-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 shadow-lg hover:shadow-xl transition-all duration-200 hover:-translate-y-0.5" style="min-height: 56px;">
                                        Continue Speaking
                                        <svg class="ml-2 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                                        </svg>
                                    </a>
                                @endguest
                            </div>

                            <div class="mt-10 flex flex-wrap items-center justify-center lg:justify-start gap-6 text-gray-700">
                                <div class="flex items-center gap-2">
                                    <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/>
                                    </svg>
                                    <span class="font-medium">500+ Conversations</span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.536 8.464a5 5 0 010 7.072m2.828-9.9a9 9 0 010 12.728M5.586 15H4a1 1 0 01-1-1v-4a1 1 0 011-1h1.586l4.707-4.707C10.923 3.663 12 4.109 12 5v14c0 .891-1.077 1.337-1.707.707L5.586 15z"/>
                                    </svg>
                                    <span class="font-medium">Native Audio</span>
                                </div>
                            </div>
                        </div>

                        <!-- Visual Elements -->
                        <div class="relative mt-12 lg:mt-0">
                            <!-- Audio Waveform Visualization -->
                            <div class="relative mx-auto w-full max-w-md">
                                <!-- Decorative Japanese Characters -->
                                <div class="absolute -top-4 sm:-top-8 -right-4 sm:-right-8 text-4xl sm:text-6xl text-indigo-200 font-japanese opacity-50 select-none" aria-hidden="true">
                                    話す
                                </div>
                                <div class="absolute -bottom-4 sm:-bottom-8 -left-4 sm:-left-8 text-4xl sm:text-6xl text-indigo-200 font-japanese opacity-50 select-none" aria-hidden="true">
                                    練習
                                </div>
                                
                                <!-- Main Visual Card -->
                                <div class="relative bg-white rounded-2xl shadow-2xl ring-1 ring-gray-200 overflow-hidden">
                                    <div class="px-6 py-5 border-b border-gray-100 bg-gradient-to-r from-indigo-50 to-purple-50">
                                        <div class="flex items-center justify-between">
                                            <span class="text-sm font-semibold text-indigo-900">Speaking Practice</span>
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800">
                                                Lesson 1
                                            </span>
                                        </div>
                                    </div>
                                    <div class="px-6 py-6 space-y-4">
                                        <div class="p-5 bg-gradient-to-br from-indigo-50 to-purple-50 rounded-xl border border-indigo-100">
                                            <p class="text-2xl font-semibold text-gray-900 font-japanese">駅はどこですか？</p>
                                            <p class="text-sm text-gray-600 mt-1">Eki wa doko desu ka?</p>
                                            <p class="text-base text-gray-700 mt-2">Where is the station?</p>
                                        </div>
                                        
                                        <!-- Audio Waveform -->
                                        <div class="flex items-center gap-2 py-3">
                                            <button class="flex-shrink-0 w-14 h-14 sm:w-12 sm:h-12 rounded-full bg-gradient-to-r from-indigo-600 to-indigo-700 text-white flex items-center justify-center hover:from-indigo-700 hover:to-indigo-800 transition-all shadow-md hover:shadow-lg" aria-label="Play audio">
                                                <svg class="w-6 h-6 sm:w-5 sm:h-5" fill="currentColor" viewBox="0 0 24 24">
                                                    <path d="M8 5v14l11-7z"/>
                                                </svg>
                                            </button>
                                            <div class="flex-1 flex items-center gap-1 h-12 sm:h-12">
                                                <div class="w-1 bg-audio-wave rounded-full" style="height: 20%"></div>
                                                <div class="w-1 bg-audio-wave rounded-full" style="height: 40%"></div>
                                                <div class="w-1 bg-audio-wave rounded-full" style="height: 60%"></div>
                                                <div class="w-1 bg-audio-wave rounded-full" style="height: 80%"></div>
                                                <div class="w-1 bg-audio-wave rounded-full" style="height: 100%"></div>
                                                <div class="w-1 bg-audio-wave rounded-full" style="height: 70%"></div>
                                                <div class="w-1 bg-audio-wave rounded-full" style="height: 50%"></div>
                                                <div class="w-1 bg-audio-wave rounded-full" style="height: 30%"></div>
                                                <div class="w-1 bg-audio-wave rounded-full" style="height: 60%"></div>
                                                <div class="w-1 bg-audio-wave rounded-full" style="height: 90%"></div>
                                                <div class="w-1 bg-audio-wave rounded-full" style="height: 70%"></div>
                                                <div class="w-1 bg-audio-wave rounded-full" style="height: 40%"></div>
                                                <div class="w-1 bg-gray-300 rounded-full" style="height: 20%"></div>
                                                <div class="w-1 bg-gray-300 rounded-full" style="height: 50%"></div>
                                                <div class="w-1 bg-gray-300 rounded-full" style="height: 70%"></div>
                                                <div class="w-1 bg-gray-300 rounded-full" style="height: 40%"></div>
                                            </div>
                                        </div>
                                        
                                        <div class="pt-2">
                                            <button class="w-full px-4 py-3 text-sm font-semibold rounded-lg bg-indigo-600 text-white hover:bg-indigo-700 transition-colors min-h-[44px]">
                                                Practice Speaking This Dialogue
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            
            <!-- Features Section -->
            <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-16 sm:pb-24">
                <div class="text-center mb-12 sm:mb-16">
                    <h2 class="text-3xl sm:text-4xl font-bold text-gray-900">How You'll Learn to Speak</h2>
                    <p class="mt-4 text-lg text-gray-600 max-w-2xl mx-auto">Master Japanese through conversation practice, native audio, and speaking-focused exercises</p>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8 lg:gap-12">
                    <!-- Feature Card 1: Real Conversations -->
                    <div class="group bg-white rounded-2xl shadow-md border border-gray-200 p-8 transition-all duration-300 hover:shadow-xl hover:-translate-y-2">
                        <div class="flex justify-center mb-6">
                            <div class="w-16 h-16 rounded-full bg-gradient-to-br from-indigo-100 to-indigo-200 flex items-center justify-center">
                                <svg class="w-8 h-8 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                                </svg>
                            </div>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 text-center mb-3">Real Conversations</h3>
                        <p class="text-base text-gray-600 text-center leading-relaxed">
                            Practice everyday scenarios with authentic dialogues. Learn to ask for directions, order food, and handle real-world situations confidently.
                        </p>
                    </div>

                    <!-- Feature Card 2: Shadow Native Speakers -->
                    <div class="group bg-white rounded-2xl shadow-md border border-gray-200 p-8 transition-all duration-300 hover:shadow-xl hover:-translate-y-2">
                        <div class="flex justify-center mb-6">
                            <div class="w-16 h-16 rounded-full bg-gradient-to-br from-purple-100 to-purple-200 flex items-center justify-center">
                                <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11a7 7 0 01-7 7m0 0a7 7 0 01-7-7m7 7v4m0 0H8m4 0h4m-4-8a3 3 0 01-3-3V5a3 3 0 116 0v6a3 3 0 01-3 3z"/>
                                </svg>
                            </div>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 text-center mb-3">Shadow Native Speakers</h3>
                        <p class="text-base text-gray-600 text-center leading-relaxed">
                            Perfect your pronunciation by shadowing native audio. Listen, repeat, and develop natural rhythm and intonation through guided practice.
                        </p>
                    </div>

                    <!-- Feature Card 3: Track Speaking Progress -->
                    <div class="group bg-white rounded-2xl shadow-md border border-gray-200 p-8 transition-all duration-300 hover:shadow-xl hover:-translate-y-2">
                        <div class="flex justify-center mb-6">
                            <div class="w-16 h-16 rounded-full bg-gradient-to-br from-green-100 to-green-200 flex items-center justify-center">
                                <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                                </svg>
                            </div>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 text-center mb-3">Track Speaking Progress</h3>
                        <p class="text-base text-gray-600 text-center leading-relaxed">
                            See your improvement over time with speaking streaks, conversations mastered, and speaking time. Stay motivated with visual progress tracking.
                        </p>
                    </div>
                </div>
            </section>

            <!-- Social Proof Section -->
            <section class="bg-gradient-to-b from-white to-indigo-50 py-16 sm:py-24">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="text-center mb-12">
                        <h2 class="text-3xl sm:text-4xl font-bold text-gray-900">Join 10,000+ Learners Speaking Japanese</h2>
                        <p class="mt-4 text-lg text-gray-600">Real results from real learners building conversation confidence</p>
                    </div>

                    <!-- Statistics -->
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-8 mb-16">
                        <div class="text-center">
                            <div class="text-4xl sm:text-5xl font-bold text-indigo-600 mb-2">10,000+</div>
                            <div class="text-lg text-gray-700 font-medium">Active Learners</div>
                            <div class="text-sm text-gray-600 mt-1">Speaking Japanese daily</div>
                        </div>
                        <div class="text-center">
                            <div class="text-4xl sm:text-5xl font-bold text-indigo-600 mb-2">500+</div>
                            <div class="text-lg text-gray-700 font-medium">Real Conversations</div>
                            <div class="text-sm text-gray-600 mt-1">From beginner to advanced</div>
                        </div>
                        <div class="text-center">
                            <div class="text-4xl sm:text-5xl font-bold text-indigo-600 mb-2">4.9/5</div>
                            <div class="text-lg text-gray-700 font-medium">Average Rating</div>
                            <div class="text-sm text-gray-600 mt-1">From 2,000+ reviews</div>
                        </div>
                    </div>

                    <!-- Testimonials -->
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                        <!-- Testimonial 1 -->
                        <div class="bg-white rounded-2xl shadow-md border border-gray-200 p-6 sm:p-8">
                            <div class="flex items-center gap-1 mb-4">
                                <svg class="w-5 h-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                </svg>
                                <svg class="w-5 h-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                </svg>
                                <svg class="w-5 h-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                </svg>
                                <svg class="w-5 h-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                </svg>
                                <svg class="w-5 h-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                </svg>
                            </div>
                            <p class="text-gray-700 leading-relaxed mb-4">
                                "I went from zero to having real conversations in just 3 months. The shadowing exercises are amazing for pronunciation!"
                            </p>
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-full bg-gradient-to-br from-indigo-400 to-purple-400 flex items-center justify-center text-white font-semibold">
                                    SK
                                </div>
                                <div>
                                    <div class="font-semibold text-gray-900">Sarah K.</div>
                                    <div class="text-sm text-gray-600">Speaking Level 5</div>
                                </div>
                            </div>
                        </div>

                        <!-- Testimonial 2 -->
                        <div class="bg-white rounded-2xl shadow-md border border-gray-200 p-6 sm:p-8">
                            <div class="flex items-center gap-1 mb-4">
                                <svg class="w-5 h-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                </svg>
                                <svg class="w-5 h-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                </svg>
                                <svg class="w-5 h-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                </svg>
                                <svg class="w-5 h-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                </svg>
                                <svg class="w-5 h-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                </svg>
                            </div>
                            <p class="text-gray-700 leading-relaxed mb-4">
                                "The conversation-focused approach is exactly what I needed. I can now confidently order food and ask for directions in Tokyo!"
                            </p>
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-full bg-gradient-to-br from-green-400 to-teal-400 flex items-center justify-center text-white font-semibold">
                                    MT
                                </div>
                                <div>
                                    <div class="font-semibold text-gray-900">Michael T.</div>
                                    <div class="text-sm text-gray-600">Speaking Level 3</div>
                                </div>
                            </div>
                        </div>

                        <!-- Testimonial 3 -->
                        <div class="bg-white rounded-2xl shadow-md border border-gray-200 p-6 sm:p-8">
                            <div class="flex items-center gap-1 mb-4">
                                <svg class="w-5 h-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                </svg>
                                <svg class="w-5 h-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                </svg>
                                <svg class="w-5 h-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                </svg>
                                <svg class="w-5 h-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                </svg>
                                <svg class="w-5 h-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                </svg>
                            </div>
                            <p class="text-gray-700 leading-relaxed mb-4">
                                "Maintaining a 30-day speaking streak has transformed my Japanese. The daily practice keeps me motivated and I'm seeing real progress!"
                            </p>
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-full bg-gradient-to-br from-pink-400 to-rose-400 flex items-center justify-center text-white font-semibold">
                                    EL
                                </div>
                                <div>
                                    <div class="font-semibold text-gray-900">Emma L.</div>
                                    <div class="text-sm text-gray-600">Speaking Level 7</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Trust Indicators -->
                    <div class="mt-16 text-center">
                        <div class="inline-flex items-center gap-2 px-6 py-3 bg-white rounded-full shadow-md border border-gray-200">
                            <svg class="w-5 h-5 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                            <span class="text-sm font-medium text-gray-700">Trusted by learners worldwide</span>
                        </div>
                    </div>
                </div>
            </section>
        </main>

        <footer class="border-t border-gray-200">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 text-sm text-gray-500 flex flex-col sm:flex-row items-center justify-between gap-4">
                <p class="text-center sm:text-left">&copy; {{ date('Y') }} Nihongo. All rights reserved.</p>
                <div class="flex items-center gap-4">
                    <a href="{{ route('login') }}" class="hover:text-gray-700 py-2 min-h-[44px] flex items-center">Log in</a>
                    @if (Route::has('register'))
                        <a href="{{ route('register') }}" class="hover:text-gray-700 py-2 min-h-[44px] flex items-center">Register</a>
                    @endif
                </div>
            </div>
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-6 text-xs sm:text-sm text-gray-500 text-center sm:text-left">
                Built by <a href="https://www.linkedin.com/in/jabbarpanggabean/" target="_blank" rel="noopener noreferrer" class="text-indigo-600 hover:text-indigo-700 underline-offset-2 hover:underline py-2 inline-flex items-center min-h-[44px]">Jabbar Ali Panggabean</a>
            </div>
        </footer>
    </body>
</html>


