<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <!-- Page Header -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <h2 class="text-3xl font-bold text-gray-900 flex items-center">
                                <svg class="w-8 h-8 text-orange-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 18.657A8 8 0 016.343 7.343S7 9 9 10c0-2 .5-5 2.986-7C14 5 16.09 5.777 17.656 7.343A7.975 7.975 0 0120 13a7.975 7.975 0 01-2.343 5.657z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.879 16.121A3 3 0 1012.015 11L11 14H9c0 .768.293 1.536.879 2.121z"></path>
                                </svg>
                                Study Streak
                            </h2>
                            <p class="text-gray-600 mt-1">Track your daily study consistency and maintain your streak</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Streak Stats -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Current Streak -->
                <div class="bg-gradient-to-br from-orange-50 to-orange-100 overflow-hidden shadow-sm sm:rounded-lg border-2 border-orange-300">
                    <div class="p-6">
                        <div class="flex items-center justify-between mb-2">
                            <h3 class="text-sm font-medium text-orange-700">Current Streak</h3>
                            <svg class="w-6 h-6 text-orange-500" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M12.395 2.553a1 1 0 00-1.45-.385c-.345.23-.614.558-.822.88-.214.33-.403.713-.57 1.116-.334.804-.614 1.768-.84 2.734a31.365 31.365 0 00-.613 3.58 2.64 2.64 0 01-.945-1.067c-.328-.68-.398-1.534-.398-2.654A1 1 0 005.05 6.05 6.981 6.981 0 003 11a7 7 0 1011.95-4.95c-.592-.591-.98-.985-1.348-1.467-.363-.476-.724-1.063-1.207-2.03zM12.12 15.12A3 3 0 017 13s.879.5 2.5.5c0-1 .5-4 1.25-4.5.5 1 .786 1.293 1.371 1.879A2.99 2.99 0 0113 13a2.99 2.99 0 01-.879 2.121z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <p class="text-5xl font-bold text-orange-900">{{ $profile->current_streak }}</p>
                        <p class="text-sm text-orange-700 mt-2">{{ $profile->current_streak === 1 ? 'day' : 'days' }} in a row</p>
                    </div>
                </div>

                <!-- Longest Streak -->
                <div class="bg-gradient-to-br from-yellow-50 to-yellow-100 overflow-hidden shadow-sm sm:rounded-lg border-2 border-yellow-300">
                    <div class="p-6">
                        <div class="flex items-center justify-between mb-2">
                            <h3 class="text-sm font-medium text-yellow-700">Longest Streak</h3>
                            <svg class="w-6 h-6 text-yellow-500" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                            </svg>
                        </div>
                        <p class="text-5xl font-bold text-yellow-900">{{ $profile->longest_streak }}</p>
                        <p class="text-sm text-yellow-700 mt-2">personal best</p>
                    </div>
                </div>

                <!-- Streak Recovery -->
                <div class="bg-gradient-to-br from-blue-50 to-blue-100 overflow-hidden shadow-sm sm:rounded-lg border-2 border-blue-300">
                    <div class="p-6">
                        <div class="flex items-center justify-between mb-2">
                            <h3 class="text-sm font-medium text-blue-700">Streak Recovery</h3>
                            <svg class="w-6 h-6 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                            </svg>
                        </div>
                        @if($canUseRecovery)
                            <p class="text-3xl font-bold text-blue-900 mb-2">Available</p>
                            <p class="text-xs text-blue-700 mb-3">Use once every 30 days</p>
                            <form method="POST" action="{{ route('streak.recover') }}" onsubmit="return confirm('Are you sure you want to use your streak recovery? This can only be used once every 30 days.');">
                                @csrf
                                <button type="submit" class="w-full px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition">
                                    Use Recovery
                                </button>
                            </form>
                        @else
                            <p class="text-3xl font-bold text-gray-400 mb-2">Used</p>
                            <p class="text-xs text-blue-700">
                                @if($profile->last_streak_recovery_at)
                                    Available again {{ $profile->last_streak_recovery_at->addDays(30)->diffForHumans() }}
                                @else
                                    Not available
                                @endif
                            </p>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Success/Error Messages -->
            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            @if(session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                    <span class="block sm:inline">{{ session('error') }}</span>
                </div>
            @endif

            <!-- Streak Calendar -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Last 28 Days</h3>
                    <div class="grid grid-cols-7 gap-2">
                        @foreach($streakCalendar as $day)
                            <div class="aspect-square rounded-lg flex flex-col items-center justify-center text-xs relative group transition-all duration-200 {{ $day['hasActivity'] ? 'bg-gradient-to-br from-green-400 to-green-500 text-white font-semibold shadow-md hover:shadow-lg' : 'bg-gray-100 text-gray-500 hover:bg-gray-200' }}">
                                <div class="text-[10px] opacity-75">{{ $day['day'] }}</div>
                                <div class="text-lg font-bold">{{ $day['dayNumber'] }}</div>
                                @if($day['hasActivity'])
                                    <div class="absolute -top-16 left-1/2 transform -translate-x-1/2 bg-gray-900 text-white text-xs px-3 py-2 rounded-lg opacity-0 group-hover:opacity-100 transition-opacity whitespace-nowrap z-10 shadow-lg">
                                        <div class="font-semibold">{{ $day['date'] }}</div>
                                        <div>{{ $day['study_minutes'] }} minutes</div>
                                        <div>{{ $day['activities_count'] }} activities</div>
                                        <div>{{ $day['xp_earned'] }} XP</div>
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                    <div class="mt-6 flex items-center justify-center gap-6 text-sm text-gray-600">
                        <div class="flex items-center gap-2">
                            <div class="w-6 h-6 bg-gray-100 rounded-lg"></div>
                            <span>No activity</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <div class="w-6 h-6 bg-gradient-to-br from-green-400 to-green-500 rounded-lg"></div>
                            <span>Active day</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Streak Milestones -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Streak Milestones</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        @foreach($streakMilestones as $milestone)
                            <div class="border-2 rounded-lg p-4 transition-all duration-200 {{ $milestone['achieved'] ? 'border-green-400 bg-gradient-to-br from-green-50 to-white' : 'border-gray-200 bg-gray-50' }}">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <h4 class="font-bold text-gray-900">{{ $milestone['name'] }}</h4>
                                        <p class="text-sm text-gray-600">{{ $milestone['days'] }} days</p>
                                    </div>
                                    @if($milestone['achieved'])
                                        <svg class="w-8 h-8 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                        </svg>
                                    @else
                                        <svg class="w-8 h-8 text-gray-300" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                                        </svg>
                                    @endif
                                </div>
                                @if(!$milestone['achieved'] && $profile->current_streak > 0)
                                    <div class="mt-3">
                                        <div class="w-full bg-gray-200 rounded-full h-2">
                                            <div class="bg-gradient-to-r from-orange-400 to-orange-500 h-2 rounded-full transition-all duration-300" style="width: {{ min(($profile->current_streak / $milestone['days']) * 100, 100) }}%"></div>
                                        </div>
                                        <p class="text-xs text-gray-600 mt-1">{{ $milestone['days'] - $profile->current_streak }} days to go</p>
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Tips for Maintaining Streak -->
            <div class="bg-gradient-to-br from-indigo-50 to-purple-50 overflow-hidden shadow-sm sm:rounded-lg border border-indigo-200">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-3 flex items-center">
                        <svg class="w-6 h-6 text-indigo-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Tips for Maintaining Your Streak
                    </h3>
                    <ul class="space-y-2 text-sm text-gray-700">
                        <li class="flex items-start">
                            <svg class="w-5 h-5 text-green-500 mr-2 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                            <span>Study at the same time each day to build a consistent habit</span>
                        </li>
                        <li class="flex items-start">
                            <svg class="w-5 h-5 text-green-500 mr-2 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                            <span>Even a quick 5-minute review session counts toward your streak</span>
                        </li>
                        <li class="flex items-start">
                            <svg class="w-5 h-5 text-green-500 mr-2 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                            <span>Enable study reminders to get notified at your preferred study time</span>
                        </li>
                        <li class="flex items-start">
                            <svg class="w-5 h-5 text-green-500 mr-2 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                            <span>Use your streak recovery wisely - it's available once every 30 days</span>
                        </li>
                        <li class="flex items-start">
                            <svg class="w-5 h-5 text-green-500 mr-2 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                            <span>Reach streak milestones to unlock special achievements and bonus XP</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
