<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Welcome Message -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 text-gray-900">
                    <h2 class="text-2xl font-bold mb-2">Welcome back, {{ $user->name }}! 👋</h2>
                    <p class="text-gray-600">Ready to continue your Japanese learning journey?</p>
                </div>
            </div>

            <!-- Quick Stats Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
                <!-- Cards Due Today -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center justify-between mb-2">
                            <h3 class="text-sm font-medium text-gray-600">Cards Due Today</h3>
                            <svg class="w-6 h-6 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                            </svg>
                        </div>
                        <p class="text-3xl font-bold text-gray-900">{{ $cardsDueToday }}</p>
                        <p class="text-xs text-gray-500 mt-1">{{ $newCardsAvailable }} new cards</p>
                    </div>
                </div>

                <!-- Level & XP -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center justify-between mb-2">
                            <h3 class="text-sm font-medium text-gray-600">Level & XP</h3>
                            <svg class="w-6 h-6 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                            </svg>
                        </div>
                        <p class="text-3xl font-bold text-gray-900">Level {{ $currentLevel }}</p>
                        <div class="mt-2">
                            <div class="flex justify-between text-xs text-gray-600 mb-1">
                                <span>{{ number_format($xpProgress) }} XP</span>
                                <span>{{ number_format($xpNeeded) }} XP</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2">
                                <div class="bg-purple-500 h-2 rounded-full transition-all duration-300" style="width: {{ min($xpProgressPercentage, 100) }}%"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Current Streak -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center justify-between mb-2">
                            <h3 class="text-sm font-medium text-gray-600">Current Streak</h3>
                            <svg class="w-6 h-6 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 18.657A8 8 0 016.343 7.343S7 9 9 10c0-2 .5-5 2.986-7C14 5 16.09 5.777 17.656 7.343A7.975 7.975 0 0120 13a7.975 7.975 0 01-2.343 5.657z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.879 16.121A3 3 0 1012.015 11L11 14H9c0 .768.293 1.536.879 2.121z"></path>
                            </svg>
                        </div>
                        <p class="text-3xl font-bold text-gray-900">{{ $currentStreak }}</p>
                        <p class="text-xs text-gray-500 mt-1">{{ $currentStreak === 1 ? 'day' : 'days' }} in a row</p>
                    </div>
                </div>

                <!-- Study Time Today -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center justify-between mb-2">
                            <h3 class="text-sm font-medium text-gray-600">Study Time Today</h3>
                            <svg class="w-6 h-6 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <p class="text-3xl font-bold text-gray-900">{{ $studyTimeToday }}</p>
                        <p class="text-xs text-gray-500 mt-1">minutes</p>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Quick Actions</h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <!-- Start Review -->
                        <a href="{{ route('flashcards.review') }}" class="flex items-center justify-center px-6 py-4 bg-blue-500 hover:bg-blue-600 text-white rounded-lg transition-colors duration-200">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                            </svg>
                            <span class="font-semibold">Start Review</span>
                        </a>

                        <!-- Continue Lesson -->
                        @if($recentLessons->isNotEmpty())
                            <a href="{{ route('lessons.show', $recentLessons->first()->slug) }}" class="flex items-center justify-center px-6 py-4 bg-purple-500 hover:bg-purple-600 text-white rounded-lg transition-colors duration-200">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                                </svg>
                                <span class="font-semibold">Continue Lesson</span>
                            </a>
                        @else
                            <a href="{{ route('lessons.index') }}" class="flex items-center justify-center px-6 py-4 bg-purple-500 hover:bg-purple-600 text-white rounded-lg transition-colors duration-200">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                                </svg>
                                <span class="font-semibold">Browse Lessons</span>
                            </a>
                        @endif

                        <!-- View Progress -->
                        <a href="{{ route('progress.index') }}" class="flex items-center justify-center px-6 py-4 bg-green-500 hover:bg-green-600 text-white rounded-lg transition-colors duration-200">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                            </svg>
                            <span class="font-semibold">View Progress</span>
                        </a>
                    </div>
                </div>
            </div>

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
                                <p class="text-gray-600 mb-3">No lessons accessed yet</p>
                                <a href="{{ route('lessons.index') }}" class="text-blue-500 hover:text-blue-600 font-medium">Browse Lessons</a>
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
