<x-app-layout>
    <div class="py-4 sm:py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-4 sm:space-y-6">
            <!-- Personalized Greeting Section -->
            <div class="bg-gradient-to-br from-indigo-50 via-purple-50 to-pink-50 overflow-hidden shadow-sm sm:rounded-lg border border-indigo-100">
                <div class="p-6 sm:p-8">
                    @php
                        $hour = now()->hour;
                        $greeting = $hour < 12 ? 'Good morning' : ($hour < 18 ? 'Good afternoon' : 'Good evening');
                        $japaneseGreeting = $hour < 12 ? 'おはようございます' : ($hour < 18 ? 'こんにちは' : 'こんばんは');
                        
                        // Motivational messages based on progress
                        $motivationalMessages = [
                            'Ready to practice speaking today?',
                            'Let\'s keep your speaking streak going!',
                            'Time to master some conversations!',
                            'Your speaking practice awaits!',
                            'Let\'s improve your Japanese speaking today!'
                        ];
                        $motivationalMessage = $motivationalMessages[array_rand($motivationalMessages)];
                    @endphp
                    
                    <div class="flex items-start justify-between mb-6">
                        <div>
                            <h2 class="text-2xl sm:text-3xl font-bold text-gray-900 mb-2">
                                {{ $greeting }}, {{ $user->name }}! 🗣️
                            </h2>
                            <p class="text-lg text-indigo-700 font-medium mb-1">{{ $japaneseGreeting }}</p>
                            <p class="text-base text-gray-700">{{ $motivationalMessage }}</p>
                        </div>
                        @if($currentStreak > 0)
                            <div class="hidden sm:block">
                                <div class="bg-white rounded-lg px-4 py-3 shadow-sm border border-orange-200">
                                    <div class="flex items-center gap-2">
                                        <span class="text-2xl">🔥</span>
                                        <div>
                                            <div class="text-2xl font-bold text-orange-600">{{ $currentStreak }}</div>
                                            <div class="text-xs text-gray-600">day streak</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                    
                    <!-- Today's Speaking Goal Widget -->
                    <div class="bg-white rounded-lg p-6 shadow-sm border border-indigo-100">
                        <div class="flex items-center justify-between mb-3">
                            <h3 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                                <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                Today's Speaking Goal
                            </h3>
                            <span class="text-sm font-medium text-indigo-600">{{ $studyTimeToday }}/{{ $studyGoalMinutes }} min</span>
                        </div>
                        
                        <!-- Progress Bar -->
                        <div class="relative">
                            <div class="w-full bg-gray-200 rounded-full h-4 overflow-hidden">
                                <div class="bg-gradient-to-r from-indigo-500 to-purple-600 h-4 rounded-full transition-all duration-500 ease-out flex items-center justify-end pr-2"
                                     style="width: {{ $todaySpeakingProgress }}%">
                                    @if($todaySpeakingProgress > 15)
                                        <span class="text-xs font-bold text-white">{{ round($todaySpeakingProgress) }}%</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        
                        <!-- Encouraging Message -->
                        <p class="mt-3 text-sm text-gray-600">
                            @if($todaySpeakingProgress >= 100)
                                <span class="text-green-600 font-semibold">🎉 すごい！ You've reached your goal today!</span>
                            @elseif($todaySpeakingProgress >= 75)
                                <span class="text-indigo-600 font-semibold">Almost there! Keep going! 💪</span>
                            @elseif($todaySpeakingProgress >= 50)
                                <span class="text-indigo-600 font-semibold">Great progress! You're halfway there! 🌟</span>
                            @elseif($todaySpeakingProgress > 0)
                                <span class="text-gray-700">Good start! Let's keep the momentum going!</span>
                            @else
                                <span class="text-gray-700">Ready to start speaking? Let's begin your practice!</span>
                            @endif
                        </p>
                    </div>
                </div>
            </div>

            <!-- Empty State for New Users -->
            @if($isNewUser)
                <div class="bg-gradient-to-br from-indigo-500 to-purple-600 overflow-hidden shadow-xl sm:rounded-xl border-2 border-indigo-300">
                    <div class="p-8 sm:p-12 text-center text-white">
                        <div class="mb-6">
                            <div class="inline-flex items-center justify-center w-20 h-20 bg-white/20 backdrop-blur-sm rounded-full mb-4">
                                <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11a7 7 0 01-7 7m0 0a7 7 0 01-7-7m7 7v4m0 0H8m4 0h4m-4-8a3 3 0 01-3-3V5a3 3 0 116 0v6a3 3 0 01-3 3z"></path>
                                </svg>
                            </div>
                            <h2 class="text-3xl sm:text-4xl font-bold mb-3">Welcome to Your Speaking Journey! 🎉</h2>
                            <p class="text-xl text-indigo-100 mb-2">ようこそ！</p>
                            <p class="text-lg text-white/90 max-w-2xl mx-auto">
                                You're about to start an exciting adventure learning Japanese through real conversations. 
                                Let's get you speaking with confidence!
                            </p>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8 max-w-4xl mx-auto">
                            <div class="bg-white/10 backdrop-blur-sm rounded-lg p-6 border border-white/20">
                                <div class="text-4xl mb-3">🗣️</div>
                                <h3 class="font-semibold text-lg mb-2">Practice Real Conversations</h3>
                                <p class="text-sm text-white/80">Learn Japanese through everyday scenarios and dialogues</p>
                            </div>
                            <div class="bg-white/10 backdrop-blur-sm rounded-lg p-6 border border-white/20">
                                <div class="text-4xl mb-3">🎤</div>
                                <h3 class="font-semibold text-lg mb-2">Shadow Native Speakers</h3>
                                <p class="text-sm text-white/80">Perfect your pronunciation by mimicking native audio</p>
                            </div>
                            <div class="bg-white/10 backdrop-blur-sm rounded-lg p-6 border border-white/20">
                                <div class="text-4xl mb-3">📈</div>
                                <h3 class="font-semibold text-lg mb-2">Track Your Progress</h3>
                                <p class="text-sm text-white/80">Watch your speaking skills improve day by day</p>
                            </div>
                        </div>
                        
                        <div class="space-y-4 max-w-md mx-auto">
                            <a href="{{ route('lessons.index') }}" 
                               class="flex items-center justify-center w-full px-8 py-4 bg-white text-indigo-600 hover:bg-indigo-50 rounded-lg font-bold text-lg transition-all duration-200 shadow-lg hover:shadow-xl hover:scale-105">
                                <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                </svg>
                                Start Your First Conversation
                            </a>
                            <p class="text-sm text-white/80">
                                💡 Tip: Start with Lesson 1 to learn basic greetings and introductions
                            </p>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Speaking Metrics Cards -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 sm:gap-6" x-data="{ 
                speakingStreak: {{ $currentStreak }},
                conversationsMastered: {{ $conversationsMastered }},
                speakingHours: {{ $totalSpeakingHours }},
                animateCounters() {
                    // Counters will animate on page load
                }
            }" x-init="animateCounters()">
                <!-- Speaking Streak Card -->
                <a href="{{ route('streak.index') }}" class="bg-gradient-to-br from-orange-50 to-red-50 overflow-hidden shadow-lg sm:rounded-xl hover:shadow-xl transition-all duration-300 hover:-translate-y-1 block border border-orange-200">
                    <div class="p-6">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-sm font-semibold text-orange-700 uppercase tracking-wide">Speaking Streak</h3>
                            <div class="bg-orange-100 rounded-full p-2">
                                <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 18.657A8 8 0 016.343 7.343S7 9 9 10c0-2 .5-5 2.986-7C14 5 16.09 5.777 17.656 7.343A7.975 7.975 0 0120 13a7.975 7.975 0 01-2.343 5.657z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.879 16.121A3 3 0 1012.015 11L11 14H9c0 .768.293 1.536.879 2.121z"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="flex items-baseline gap-2 mb-2">
                            <p class="text-5xl font-bold text-orange-600" x-text="speakingStreak">{{ $currentStreak }}</p>
                            <span class="text-2xl text-orange-500">🔥</span>
                        </div>
                        <p class="text-sm text-orange-700 font-medium">
                            {{ $currentStreak === 1 ? 'day of practice' : 'days of practice' }}
                        </p>
                        <p class="text-xs text-orange-600 mt-2">Keep it going!</p>
                    </div>
                </a>

                <!-- Conversations Mastered Card -->
                <div class="bg-gradient-to-br from-green-50 to-emerald-50 overflow-hidden shadow-lg sm:rounded-xl hover:shadow-xl transition-all duration-300 hover:-translate-y-1 border border-green-200">
                    <div class="p-6">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-sm font-semibold text-green-700 uppercase tracking-wide">Conversations Mastered</h3>
                            <div class="bg-green-100 rounded-full p-2">
                                <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="flex items-baseline gap-2 mb-2">
                            <p class="text-5xl font-bold text-green-600" x-text="conversationsMastered">{{ $conversationsMastered }}</p>
                            <span class="text-lg text-green-500">/{{ $totalLessons }}</span>
                        </div>
                        <p class="text-sm text-green-700 font-medium">lessons completed</p>
                        <div class="mt-3">
                            <div class="w-full bg-green-200 rounded-full h-2">
                                <div class="bg-gradient-to-r from-green-500 to-emerald-600 h-2 rounded-full transition-all duration-500" 
                                     style="width: {{ $totalLessons > 0 ? min(($conversationsMastered / $totalLessons) * 100, 100) : 0 }}%"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Speaking Time Card -->
                <div class="bg-gradient-to-br from-indigo-50 to-purple-50 overflow-hidden shadow-lg sm:rounded-xl hover:shadow-xl transition-all duration-300 hover:-translate-y-1 border border-indigo-200">
                    <div class="p-6">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-sm font-semibold text-indigo-700 uppercase tracking-wide">Speaking Time</h3>
                            <div class="bg-indigo-100 rounded-full p-2">
                                <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="flex items-baseline gap-2 mb-2">
                            <p class="text-5xl font-bold text-indigo-600" x-text="speakingHours">{{ $totalSpeakingHours }}</p>
                            <span class="text-lg text-indigo-500">hrs</span>
                        </div>
                        <p class="text-sm text-indigo-700 font-medium">total speaking time</p>
                        <p class="text-xs text-indigo-600 mt-2">{{ $totalSpeakingMinutes }} minutes</p>
                    </div>
                </div>
            </div>

            <!-- Weekly Speaking Progress Calendar -->
            <div class="bg-white overflow-hidden shadow-lg sm:rounded-xl border border-gray-200">
                <div class="p-6">
                    <h3 class="text-xl font-bold text-gray-900 mb-4 flex items-center gap-2">
                        <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                        Your Speaking Progress This Week
                    </h3>
                    
                    @php
                        // Get the last 7 days of activity
                        $weekDays = [];
                        $today = \Carbon\Carbon::today();
                        
                        for ($i = 6; $i >= 0; $i--) {
                            $date = $today->copy()->subDays($i);
                            $dayStreak = \App\Models\DailyStreak::where('user_id', $user->id)
                                ->where('date', $date->format('Y-m-d'))
                                ->first();
                            
                            $weekDays[] = [
                                'day' => $date->format('D'),
                                'date' => $date->format('M j'),
                                'minutes' => $dayStreak ? $dayStreak->study_minutes : 0,
                                'hasActivity' => $dayStreak && $dayStreak->study_minutes > 0,
                                'isToday' => $date->isToday(),
                            ];
                        }
                    @endphp
                    
                    <div class="grid grid-cols-7 gap-2 sm:gap-3">
                        @foreach($weekDays as $day)
                            <div class="flex flex-col items-center">
                                <div class="text-xs font-medium text-gray-600 mb-2">{{ $day['day'] }}</div>
                                <div class="relative group">
                                    <div class="w-12 h-12 sm:w-16 sm:h-16 rounded-lg flex flex-col items-center justify-center transition-all duration-200 
                                        @if($day['hasActivity'])
                                            @if($day['minutes'] >= 30)
                                                bg-gradient-to-br from-green-500 to-emerald-600 text-white shadow-md
                                            @elseif($day['minutes'] >= 15)
                                                bg-gradient-to-br from-green-400 to-green-500 text-white shadow-sm
                                            @else
                                                bg-gradient-to-br from-green-300 to-green-400 text-white
                                            @endif
                                        @else
                                            bg-gray-100 text-gray-400
                                        @endif
                                        @if($day['isToday'])
                                            ring-2 ring-indigo-500 ring-offset-2
                                        @endif
                                        hover:scale-110 cursor-pointer">
                                        
                                        @if($day['hasActivity'])
                                            <svg class="w-6 h-6 sm:w-8 sm:h-8" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                            </svg>
                                        @else
                                            <div class="w-2 h-2 rounded-full bg-gray-300"></div>
                                        @endif
                                    </div>
                                    
                                    <!-- Tooltip -->
                                    @if($day['hasActivity'])
                                        <div class="absolute bottom-full left-1/2 transform -translate-x-1/2 mb-2 px-3 py-2 bg-gray-900 text-white text-xs rounded-lg opacity-0 group-hover:opacity-100 transition-opacity whitespace-nowrap pointer-events-none z-10">
                                            {{ $day['date'] }}: {{ $day['minutes'] }} min
                                            <div class="absolute top-full left-1/2 transform -translate-x-1/2 -mt-1">
                                                <div class="border-4 border-transparent border-t-gray-900"></div>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                                <div class="text-xs text-gray-500 mt-2 font-medium">
                                    @if($day['hasActivity'])
                                        {{ $day['minutes'] }}m
                                    @else
                                        -
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                    
                    <!-- Legend -->
                    <div class="mt-6 pt-4 border-t border-gray-200">
                        <div class="flex flex-wrap items-center justify-center gap-4 text-xs text-gray-600">
                            <div class="flex items-center gap-2">
                                <div class="w-4 h-4 rounded bg-gray-100"></div>
                                <span>No activity</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <div class="w-4 h-4 rounded bg-gradient-to-br from-green-300 to-green-400"></div>
                                <span>< 15 min</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <div class="w-4 h-4 rounded bg-gradient-to-br from-green-400 to-green-500"></div>
                                <span>15-30 min</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <div class="w-4 h-4 rounded bg-gradient-to-br from-green-500 to-emerald-600"></div>
                                <span>30+ min</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Analytics Section -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg" x-data="analyticsFilter">
                <div class="p-6">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-lg font-semibold text-gray-900">Analytics</h3>
                        
                        <!-- Date Range Filter -->
                        <div class="flex gap-2">
                            <button @click="setRange('week')" 
                                    :disabled="loading"
                                    :class="range === 'week' ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-700'"
                                    class="px-4 py-2 rounded-lg text-sm font-medium hover:bg-blue-500 hover:text-white transition disabled:opacity-50 disabled:cursor-not-allowed">
                                Week
                            </button>
                            <button @click="setRange('month')" 
                                    :disabled="loading"
                                    :class="range === 'month' ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-700'"
                                    class="px-4 py-2 rounded-lg text-sm font-medium hover:bg-blue-500 hover:text-white transition disabled:opacity-50 disabled:cursor-not-allowed">
                                Month
                            </button>
                            <button @click="setRange('all')" 
                                    :disabled="loading"
                                    :class="range === 'all' ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-700'"
                                    class="px-4 py-2 rounded-lg text-sm font-medium hover:bg-blue-500 hover:text-white transition disabled:opacity-50 disabled:cursor-not-allowed">
                                All Time
                            </button>
                        </div>
                    </div>

                    <!-- Loading State -->
                    <div x-show="loading" class="flex items-center justify-center py-12">
                        <x-loading-state message="Loading your speaking progress..." />
                    </div>

                    <!-- Error State -->
                    <div x-show="error && !loading" class="bg-red-50 border border-red-200 rounded-lg p-4 mb-6">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 text-red-600 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                            </svg>
                            <span class="text-red-800" x-text="error"></span>
                        </div>
                    </div>

                    <!-- Content (hidden while loading) -->
                    <div x-show="!loading && !error">

                    <!-- Key Metrics -->
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                        <div class="bg-gradient-to-br from-blue-50 to-blue-100 rounded-lg p-4 border border-blue-200">
                            <div class="text-sm font-medium text-blue-700 mb-1">Total Speaking Hours</div>
                            <div class="text-3xl font-bold text-blue-900" x-text="metrics.totalHours || '0'"></div>
                            <div class="text-xs text-blue-600 mt-1" x-text="(metrics.totalMinutes || 0) + ' minutes'"></div>
                        </div>
                        <div class="bg-gradient-to-br from-green-50 to-green-100 rounded-lg p-4 border border-green-200">
                            <div class="text-sm font-medium text-green-700 mb-1">Avg Daily Time</div>
                            <div class="text-3xl font-bold text-green-900" x-text="metrics.avgDailyMinutes || '0'"></div>
                            <div class="text-xs text-green-600 mt-1">minutes per day</div>
                        </div>
                        <div class="bg-gradient-to-br from-purple-50 to-purple-100 rounded-lg p-4 border border-purple-200">
                            <div class="text-sm font-medium text-purple-700 mb-1">Completion Rate</div>
                            <div class="text-3xl font-bold text-purple-900" x-text="(metrics.completionRate || 0) + '%'"></div>
                            <div class="text-xs text-purple-600 mt-1">overall progress</div>
                        </div>
                        <div class="bg-gradient-to-br from-yellow-50 to-yellow-100 rounded-lg p-4 border border-yellow-200">
                            <div class="text-sm font-medium text-yellow-700 mb-1">Current Streak</div>
                            <div class="text-3xl font-bold text-yellow-900" x-text="metrics.currentStreak || '0'"></div>
                            <div class="text-xs text-yellow-600 mt-1" x-text="'Longest: ' + (metrics.longestStreak || 0) + ' days'"></div>
                        </div>
                    </div>

                    <!-- Speaking Time Chart -->
                    <div class="mb-6">
                        <h4 class="text-sm font-semibold text-gray-900 mb-3">Speaking Time</h4>
                        <div class="bg-gray-50 rounded-lg p-4">
                            <div class="space-y-2">
                                <template x-for="(day, index) in studyTimeData" :key="index">
                                    <div class="flex items-center gap-3">
                                        <div class="w-20 text-xs text-gray-600" x-text="day.label"></div>
                                        <div class="flex-1">
                                            <div class="w-full bg-gray-200 rounded-full h-8 relative">
                                                <div class="bg-gradient-to-r from-blue-500 to-blue-600 h-8 rounded-full flex items-center px-3 text-xs text-white font-medium transition-all duration-500" 
                                                     :style="'width: ' + day.percentage + '%'">
                                                    <span x-show="day.minutes > 0" x-text="day.minutes + 'm'"></span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="w-16 text-xs text-gray-600 text-right" x-text="day.activities + ' acts'"></div>
                                    </div>
                                </template>
                            </div>
                        </div>
                    </div>

                    <!-- Activity Breakdown Pie Chart -->
                    <div class="mb-6">
                        <h4 class="text-sm font-semibold text-gray-900 mb-3">Activity Breakdown</h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Pie Chart Visualization -->
                            <div class="bg-gray-50 rounded-lg p-4 flex items-center justify-center">
                                <div class="relative w-48 h-48">
                                    <svg viewBox="0 0 100 100" class="transform -rotate-90">
                                        <template x-for="(segment, index) in pieChartSegments" :key="index">
                                            <circle cx="50" cy="50" r="40"
                                                    :stroke="segment.color"
                                                    stroke-width="20"
                                                    fill="none"
                                                    :stroke-dasharray="segment.dasharray"
                                                    :stroke-dashoffset="segment.dashoffset"
                                                    class="transition-all duration-500">
                                            </circle>
                                        </template>
                                    </svg>
                                    <div class="absolute inset-0 flex items-center justify-center">
                                        <div class="text-center">
                                            <div class="text-2xl font-bold text-gray-900" x-text="activityBreakdown.total || 0"></div>
                                            <div class="text-xs text-gray-600">activities</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Legend -->
                            <div class="space-y-3">
                                <template x-for="(activity, type) in activityBreakdown.types" :key="type">
                                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                        <div class="flex items-center gap-3">
                                            <div class="w-4 h-4 rounded" :style="'background-color: ' + activity.color"></div>
                                            <div>
                                                <div class="text-sm font-medium text-gray-900 capitalize" x-text="activity.label"></div>
                                                <div class="text-xs text-gray-600" x-text="activity.duration + ' min'"></div>
                                            </div>
                                        </div>
                                        <div class="text-right">
                                            <div class="text-sm font-semibold text-gray-900" x-text="activity.count"></div>
                                            <div class="text-xs text-gray-600" x-text="activity.percentage + '%'"></div>
                                        </div>
                                    </div>
                                </template>
                            </div>
                        </div>
                    </div>

                    <!-- XP Earned Over Time -->
                    <div class="mb-6">
                        <h4 class="text-sm font-semibold text-gray-900 mb-3">XP Earned Over Time</h4>
                        <div class="bg-gray-50 rounded-lg p-4">
                            <div class="flex items-end justify-between gap-2 h-48">
                                <template x-for="(day, index) in xpData" :key="index">
                                    <div class="flex-1 flex flex-col items-center gap-2">
                                        <div class="flex-1 w-full flex items-end">
                                            <div class="w-full bg-gradient-to-t from-purple-500 to-purple-400 rounded-t transition-all duration-500 hover:from-purple-600 hover:to-purple-500 relative group"
                                                 :style="'height: ' + day.percentage + '%'">
                                                <div class="absolute -top-8 left-1/2 transform -translate-x-1/2 bg-gray-900 text-white text-xs px-2 py-1 rounded opacity-0 group-hover:opacity-100 transition-opacity whitespace-nowrap"
                                                     x-text="day.xp + ' XP'">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="text-xs text-gray-600" x-text="day.label"></div>
                                    </div>
                                </template>
                            </div>
                        </div>
                    </div>

                    <!-- Streak Calendar -->
                    <div>
                        <h4 class="text-sm font-semibold text-gray-900 mb-3">Streak Calendar (Last 28 Days)</h4>
                        <div class="bg-gray-50 rounded-lg p-4">
                            <div class="grid grid-cols-7 gap-2">
                                <template x-for="(day, index) in streakCalendar" :key="index">
                                    <div class="aspect-square rounded-lg flex flex-col items-center justify-center text-xs relative group"
                                         :class="day.hasActivity ? 'bg-green-500 text-white font-semibold' : 'bg-gray-200 text-gray-500'">
                                        <div x-text="day.day"></div>
                                        <div class="text-[10px]" x-text="day.date"></div>
                                        <div x-show="day.hasActivity" 
                                             class="absolute -top-10 left-1/2 transform -translate-x-1/2 bg-gray-900 text-white text-xs px-2 py-1 rounded opacity-0 group-hover:opacity-100 transition-opacity whitespace-nowrap z-10"
                                             x-text="day.minutes + ' min'">
                                        </div>
                                    </div>
                                </template>
                            </div>
                            <div class="mt-4 flex items-center justify-center gap-4 text-xs text-gray-600">
                                <div class="flex items-center gap-2">
                                    <div class="w-4 h-4 bg-gray-200 rounded"></div>
                                    <span>No activity</span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <div class="w-4 h-4 bg-green-500 rounded"></div>
                                    <span>Active day</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    </div><!-- End content wrapper for analytics -->
                </div>
            </div>

            <!-- Quick Actions for Speaking Practice -->
            <div class="space-y-4">
                <h3 class="text-xl font-bold text-gray-900">Quick Actions</h3>
                
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
                    <!-- Continue Conversation Card -->
                    @if($currentLesson)
                        <div class="bg-white overflow-hidden shadow-lg sm:rounded-xl border-2 border-indigo-200 hover:border-indigo-400 transition-all duration-300">
                            <div class="p-6">
                                <div class="flex items-start justify-between mb-4">
                                    <div class="flex items-center gap-3">
                                        <div class="bg-indigo-100 rounded-full p-3">
                                            <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                                            </svg>
                                        </div>
                                        <div>
                                            <h4 class="text-sm font-semibold text-gray-600 uppercase tracking-wide">Continue Conversation</h4>
                                            <p class="text-lg font-bold text-gray-900 mt-1">{{ $currentLesson->lesson->title }}</p>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="mb-4">
                                    <div class="flex items-center justify-between text-sm text-gray-600 mb-2">
                                        <span>Progress</span>
                                        <span class="font-semibold">{{ round($currentLesson->completion_percentage) }}%</span>
                                    </div>
                                    <div class="w-full bg-gray-200 rounded-full h-3">
                                        <div class="bg-gradient-to-r from-indigo-500 to-purple-600 h-3 rounded-full transition-all duration-500" 
                                             style="width: {{ $currentLesson->completion_percentage }}%"></div>
                                    </div>
                                </div>
                                
                                <a href="{{ route('lessons.show', $currentLesson->lesson->slug) }}" 
                                   class="flex items-center justify-center w-full px-6 py-3 bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 text-white rounded-lg font-semibold transition-all duration-200 min-h-[48px] touch-manipulation shadow-md hover:shadow-lg">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    Practice Speaking
                                </a>
                            </div>
                        </div>
                    @else
                        <div class="bg-white overflow-hidden shadow-lg sm:rounded-xl border-2 border-gray-200 hover:border-indigo-300 transition-all duration-300">
                            <div class="p-6">
                                <div class="flex items-center gap-3 mb-4">
                                    <div class="bg-gray-100 rounded-full p-3">
                                        <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <h4 class="text-sm font-semibold text-gray-600 uppercase tracking-wide">Start Speaking</h4>
                                        <p class="text-base text-gray-700 mt-1">Begin your first conversation</p>
                                    </div>
                                </div>
                                
                                <p class="text-sm text-gray-600 mb-4">Choose your first conversation topic and start practicing speaking Japanese today!</p>
                                
                                <a href="{{ route('lessons.index') }}" 
                                   class="flex items-center justify-center w-full px-6 py-3 bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 text-white rounded-lg font-semibold transition-all duration-200 min-h-[48px] touch-manipulation shadow-md hover:shadow-lg">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                    </svg>
                                    Browse Conversations
                                </a>
                            </div>
                        </div>
                    @endif

                    <!-- Daily Shadowing Challenge Card -->
                    <div class="bg-gradient-to-br from-purple-500 to-pink-500 overflow-hidden shadow-lg sm:rounded-xl hover:shadow-xl transition-all duration-300">
                        <div class="p-6 text-white">
                            <div class="flex items-center gap-3 mb-4">
                                <div class="bg-white/20 backdrop-blur-sm rounded-full p-3">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11a7 7 0 01-7 7m0 0a7 7 0 01-7-7m7 7v4m0 0H8m4 0h4m-4-8a3 3 0 01-3-3V5a3 3 0 116 0v6a3 3 0 01-3 3z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <h4 class="text-sm font-semibold uppercase tracking-wide opacity-90">Daily Challenge</h4>
                                    <p class="text-lg font-bold mt-1">Shadowing Practice</p>
                                </div>
                            </div>
                            
                            <p class="text-sm opacity-90 mb-4">Perfect your pronunciation by shadowing native speakers. Practice 3 dialogues today!</p>
                            
                            <div class="bg-white/10 backdrop-blur-sm rounded-lg p-3 mb-4">
                                <div class="flex items-center justify-between text-sm">
                                    <span>Today's Progress</span>
                                    <span class="font-semibold">0/3 completed</span>
                                </div>
                                <div class="w-full bg-white/20 rounded-full h-2 mt-2">
                                    <div class="bg-white h-2 rounded-full transition-all duration-500" style="width: 0%"></div>
                                </div>
                            </div>
                            
                            <a href="{{ route('lessons.index') }}" 
                               class="flex items-center justify-center w-full px-6 py-3 bg-white text-purple-600 hover:bg-purple-50 rounded-lg font-semibold transition-all duration-200 min-h-[48px] touch-manipulation shadow-md hover:shadow-lg">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                </svg>
                                Start Challenge
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Continue Learning & Recently Viewed -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Continue Learning -->
                @if($lastViewedLesson)
                <div class="bg-gradient-to-br from-indigo-500 to-purple-600 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-white">
                        <h3 class="text-lg font-semibold mb-2">Continue Speaking Practice</h3>
                        <p class="text-indigo-100 mb-4">Pick up your conversation where you left off</p>
                        <div class="bg-white/10 backdrop-blur-sm rounded-lg p-4 mb-4">
                            <p class="font-medium">{{ $lastViewedLesson['model']->title }}</p>
                            <p class="text-sm text-indigo-100 mt-1">Last viewed {{ \Carbon\Carbon::parse($lastViewedLesson['viewed_at'])->diffForHumans() }}</p>
                        </div>
                        <a href="{{ route('lessons.show', $lastViewedLesson['model']->slug) }}" class="inline-flex items-center px-4 py-2 bg-white text-indigo-600 rounded-lg font-semibold hover:bg-indigo-50 transition">
                            Continue
                            <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                            </svg>
                        </a>
                    </div>
                </div>
                @else
                <!-- Empty State for Continue Learning -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border-2 border-dashed border-gray-300">
                    <div class="p-6 text-center">
                        <svg class="w-16 h-16 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                        </svg>
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">Start Your Speaking Journey</h3>
                        <p class="text-gray-600 mb-4">Begin your first conversation to see your speaking progress here</p>
                        <a href="{{ route('lessons.index') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white rounded-lg font-semibold hover:bg-indigo-700 transition">
                            Browse Conversations
                            <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                            </svg>
                        </a>
                    </div>
                </div>
                @endif

                <!-- Recently Viewed -->
                @if($recentlyViewed->count() > 0)
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                                <svg class="w-6 h-6 text-gray-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                Recently Viewed
                            </h3>
                        </div>
                        <div class="space-y-3">
                            @foreach($recentlyViewed as $item)
                                @php
                                    $model = $item['model'];
                                    $type = $item['type'];
                                @endphp
                                <div class="flex items-center justify-between p-3 border border-gray-200 rounded-lg hover:bg-gray-50 transition">
                                    <div class="flex-1">
                                        @if($type === 'lesson')
                                            <a href="{{ route('lessons.show', $model->slug) }}" class="text-indigo-600 hover:text-indigo-900 font-medium">
                                                {{ $model->title }}
                                            </a>
                                            <p class="text-xs text-gray-500 mt-1">Lesson</p>
                                        @elseif($type === 'phrase')
                                            <a href="{{ route('lessons.show', $model->lesson->slug) }}#phrases" class="text-indigo-600 hover:text-indigo-900 font-medium">
                                                {{ $model->japanese }}
                                            </a>
                                            <p class="text-xs text-gray-500 mt-1">Phrase from {{ $model->lesson->title }}</p>
                                        @elseif($type === 'dialogue')
                                            <a href="{{ route('lessons.show', $model->lesson->slug) }}#dialogues" class="text-indigo-600 hover:text-indigo-900 font-medium">
                                                {{ $model->title }}
                                            </a>
                                            <p class="text-xs text-gray-500 mt-1">Dialogue from {{ $model->lesson->title }}</p>
                                        @elseif($type === 'drill')
                                            <a href="{{ route('exercises.show', $model->id) }}" class="text-indigo-600 hover:text-indigo-900 font-medium">
                                                {{ $model->title }}
                                            </a>
                                            <p class="text-xs text-gray-500 mt-1">Exercise from {{ $model->lesson->title }}</p>
                                        @elseif($type === 'shadowing')
                                            <a href="{{ route('shadowing.show', $model->id) }}" class="text-indigo-600 hover:text-indigo-900 font-medium">
                                                {{ $model->title }}
                                            </a>
                                            <p class="text-xs text-gray-500 mt-1">Shadowing from {{ $model->lesson->title }}</p>
                                        @endif
                                    </div>
                                    <span class="text-xs text-gray-400 ml-4">
                                        {{ \Carbon\Carbon::parse($item['viewed_at'])->diffForHumans() }}
                                    </span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                @else
                <!-- Empty State for Recently Viewed -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border-2 border-dashed border-gray-300">
                    <div class="p-6 text-center">
                        <svg class="w-16 h-16 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">No Recent Activity</h3>
                        <p class="text-gray-600 mb-4">Your recently viewed content will appear here</p>
                        <a href="{{ route('lessons.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-600 text-white rounded-lg font-semibold hover:bg-gray-700 transition">
                            Explore Content
                        </a>
                    </div>
                </div>
                @endif
            </div>

            <!-- Recent Achievements -->
            @if($recentAchievements->count() > 0)
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                            <svg class="w-6 h-6 text-yellow-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                            </svg>
                            Recent Achievements
                        </h3>
                        <a href="{{ route('achievements.index') }}" class="text-sm text-indigo-600 hover:text-indigo-800 font-medium">
                            View All
                        </a>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        @foreach($recentAchievements as $achievement)
                        <div class="border-2 border-yellow-400 rounded-lg p-4 bg-gradient-to-br from-yellow-50 to-white hover:shadow-md transition-shadow">
                            <div class="flex items-start">
                                <div class="text-3xl mr-3">{{ $achievement->icon }}</div>
                                <div class="flex-1">
                                    <h4 class="font-bold text-gray-900 text-sm">{{ $achievement->name }}</h4>
                                    <p class="text-xs text-gray-600 mt-1 line-clamp-2">{{ $achievement->description }}</p>
                                    <div class="mt-2 flex items-center justify-between">
                                        <span class="text-xs text-indigo-600 font-semibold">
                                            +{{ $achievement->xp_reward }} XP
                                        </span>
                                        <span class="text-xs text-gray-500">
                                            {{ $achievement->pivot->earned_at->diffForHumans() }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @else
            <!-- Empty State for Achievements -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border-2 border-dashed border-gray-300">
                <div class="p-6 text-center">
                    <svg class="w-16 h-16 mx-auto text-gray-400 mb-4" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                    </svg>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Your Speaking Achievements Await! 🏆</h3>
                    <p class="text-gray-600 mb-4">Start conversations to unlock badges and celebrate your speaking milestones</p>
                    <a href="{{ route('achievements.index') }}" class="inline-flex items-center px-4 py-2 bg-yellow-500 text-white rounded-lg font-semibold hover:bg-yellow-600 transition">
                        View All Achievements
                    </a>
                </div>
            </div>
            @endif

            <!-- Recent Lessons & Overall Progress -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Recent Lessons -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Recent Lessons</h3>
                        @if($recentLessons->isNotEmpty())
                            <div class="space-y-3">
                                @foreach($recentLessons as $lesson)
                                    <a href="{{ route('lessons.show', $lesson->slug) }}" class="block p-3 bg-gray-50 hover:bg-gray-100 rounded-lg transition-colors duration-200">
                                        <div class="flex items-center justify-between">
                                            <div>
                                                <h4 class="font-medium text-gray-900">{{ $lesson->title }}</h4>
                                                <p class="text-sm text-gray-600">Lesson {{ $lesson->order }}</p>
                                            </div>
                                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                            </svg>
                                        </div>
                                    </a>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-8">
                                <svg class="w-12 h-12 text-gray-400 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                                </svg>
                                <p class="text-gray-600 mb-3">Ready to speak Japanese? 🗣️ Your conversation journey starts here!</p>
                                <a href="{{ route('lessons.index') }}" class="text-blue-500 hover:text-blue-600 font-medium">Start Speaking</a>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Overall Progress -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Overall Progress</h3>
                        <div class="mb-6">
                            <div class="flex justify-between text-sm text-gray-600 mb-2">
                                <span>Course Completion</span>
                                <span>{{ number_format($overallProgress, 1) }}%</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-4">
                                <div class="bg-gradient-to-r from-blue-500 to-purple-500 h-4 rounded-full transition-all duration-300" style="width: {{ min($overallProgress, 100) }}%"></div>
                            </div>
                        </div>

                        <div class="space-y-3">
                            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                <span class="text-sm text-gray-600">Upcoming Reviews</span>
                                <span class="font-semibold text-gray-900">{{ $upcomingReviews }}</span>
                            </div>
                            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                <span class="text-sm text-gray-600">Total XP</span>
                                <span class="font-semibold text-gray-900">{{ number_format($totalXp) }}</span>
                            </div>
                            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                <span class="text-sm text-gray-600">Study Goal</span>
                                <span class="font-semibold text-gray-900">{{ $profile->study_goal_minutes ?? 120 }} min/day</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>


    @push('scripts')
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('analyticsFilter', () => ({
                range: 'week',
                metrics: {},
                studyTimeData: [],
                activityBreakdown: { total: 0, types: {} },
                pieChartSegments: [],
                xpData: [],
                streakCalendar: [],
                loading: false,
                error: null,
                
                init() {
                    this.loadData();
                },
                
                setRange(newRange) {
                    this.range = newRange;
                    this.loadData();
                },
                
                async loadData() {
                    this.loading = true;
                    this.error = null;
                    
                    try {
                        const response = await fetch(`/api/analytics?range=${this.range}`);
                        
                        if (!response.ok) {
                            throw new Error('Failed to load analytics data');
                        }
                        
                        const data = await response.json();
                        
                        this.metrics = data.metrics;
                        this.studyTimeData = data.studyTimeData;
                        this.activityBreakdown = data.activityBreakdown;
                        this.xpData = data.xpData;
                        this.streakCalendar = data.streakCalendar;
                        
                        this.calculatePieChart();
                    } catch (error) {
                        console.error('Failed to load analytics data:', error);
                        this.error = '😅 We couldn\'t load your speaking progress right now. Please refresh and try again!';
                    } finally {
                        this.loading = false;
                    }
                },
                
                calculatePieChart() {
                    const types = this.activityBreakdown.types || {};
                    const total = this.activityBreakdown.total || 1;
                    
                    let offset = 0;
                    const circumference = 2 * Math.PI * 40; // radius = 40
                    
                    this.pieChartSegments = Object.values(types).map(activity => {
                        const percentage = activity.count / total;
                        const dasharray = `${percentage * circumference} ${circumference}`;
                        const dashoffset = -offset * circumference;
                        
                        offset += percentage;
                        
                        return {
                            color: activity.color,
                            dasharray: dasharray,
                            dashoffset: dashoffset
                        };
                    });
                }
            }));
        });
    </script>
    @endpush
