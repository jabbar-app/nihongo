<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('Your Progress & Achievements') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            {{ __('Track your learning journey with levels, XP, and streaks.') }}
        </p>
    </header>

    <div class="mt-6 space-y-6">
        <!-- Level and XP -->
        <div class="bg-gradient-to-br from-purple-50 to-purple-100 rounded-lg p-6 border border-purple-200">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h3 class="text-2xl font-bold text-purple-900">Level {{ $xpProgress['current_level'] }}</h3>
                    <p class="text-sm text-purple-700">{{ number_format($xpProgress['current_xp']) }} Total XP</p>
                </div>
                <div class="bg-purple-500 text-white rounded-full p-4">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                    </svg>
                </div>
            </div>
            
            <div class="space-y-2">
                <div class="flex justify-between text-sm text-purple-700">
                    <span>Progress to Level {{ $xpProgress['current_level'] + 1 }}</span>
                    <span>{{ number_format($xpProgress['xp_progress']) }} / {{ number_format($xpProgress['xp_for_next_level'] - $xpProgress['xp_for_current_level']) }} XP</span>
                </div>
                <div class="w-full bg-purple-200 rounded-full h-3">
                    <div class="bg-purple-600 h-3 rounded-full transition-all duration-300" style="width: {{ min($xpProgress['progress_percentage'], 100) }}%"></div>
                </div>
                <p class="text-xs text-purple-600 text-right">{{ number_format($xpProgress['xp_remaining']) }} XP remaining</p>
            </div>
        </div>

        <!-- Streak Stats -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div class="bg-gradient-to-br from-orange-50 to-orange-100 rounded-lg p-6 border border-orange-200">
                <div class="flex items-center gap-3 mb-2">
                    <svg class="w-6 h-6 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 18.657A8 8 0 016.343 7.343S7 9 9 10c0-2 .5-5 2.986-7C14 5 16.09 5.777 17.656 7.343A7.975 7.975 0 0120 13a7.975 7.975 0 01-2.343 5.657z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.879 16.121A3 3 0 1012.015 11L11 14H9c0 .768.293 1.536.879 2.121z"></path>
                    </svg>
                    <h3 class="text-sm font-medium text-orange-700">Current Streak</h3>
                </div>
                <p class="text-3xl font-bold text-orange-900">{{ $profile->current_streak ?? 0 }}</p>
                <p class="text-xs text-orange-600 mt-1">{{ ($profile->current_streak ?? 0) === 1 ? 'day' : 'days' }} in a row</p>
            </div>

            <div class="bg-gradient-to-br from-yellow-50 to-yellow-100 rounded-lg p-6 border border-yellow-200">
                <div class="flex items-center gap-3 mb-2">
                    <svg class="w-6 h-6 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"></path>
                    </svg>
                    <h3 class="text-sm font-medium text-yellow-700">Longest Streak</h3>
                </div>
                <p class="text-3xl font-bold text-yellow-900">{{ $profile->longest_streak ?? 0 }}</p>
                <p class="text-xs text-yellow-600 mt-1">{{ ($profile->longest_streak ?? 0) === 1 ? 'day' : 'days' }} record</p>
            </div>
        </div>

        <!-- Study Goals -->
        <div class="bg-gradient-to-br from-blue-50 to-blue-100 rounded-lg p-6 border border-blue-200">
            <h3 class="text-sm font-medium text-blue-700 mb-4">Study Goals</h3>
            <div class="space-y-3">
                <div class="flex items-center justify-between">
                    <span class="text-sm text-blue-900">Daily Study Time</span>
                    <span class="text-lg font-bold text-blue-900">{{ $profile->study_goal_minutes ?? 120 }} minutes</span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-sm text-blue-900">Cards Per Day</span>
                    <span class="text-lg font-bold text-blue-900">{{ $profile->cards_per_day_goal ?? 20 }} cards</span>
                </div>
            </div>
        </div>
    </div>
</section>
