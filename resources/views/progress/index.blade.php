<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Progress & Statistics') }}
        </h2>
    </x-slot>

    <div class="py-4 sm:py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-4 sm:space-y-6">
            <!-- Breadcrumb -->
            <x-breadcrumb :items="[
                ['label' => 'Progress']
            ]" />
            
            <!-- Overall Progress Section -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-4 sm:p-6">
                    <h3 class="text-base sm:text-lg font-semibold text-gray-900 mb-4">Overall Progress</h3>
                    
                    <div class="mb-4 sm:mb-6">
                        <div class="flex justify-between items-center mb-2">
                            <span class="text-xs sm:text-sm font-medium text-gray-700">Course Completion</span>
                            <span class="text-xs sm:text-sm font-semibold text-gray-900">{{ number_format($overallProgress['average_completion'], 1) }}%</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-3 sm:h-4">
                            <div class="bg-blue-600 h-3 sm:h-4 rounded-full progress-bar" 
                                 style="width: {{ $overallProgress['average_completion'] }}%"></div>
                        </div>
                    </div>

                    <div class="grid grid-cols-3 gap-2 sm:gap-4">
                        <div class="bg-blue-50 rounded-lg p-3 sm:p-4">
                            <div class="text-xl sm:text-2xl font-bold text-blue-600">{{ $overallProgress['completed_lessons'] }}</div>
                            <div class="text-xs sm:text-sm text-gray-600">Completed</div>
                        </div>
                        <div class="bg-yellow-50 rounded-lg p-3 sm:p-4">
                            <div class="text-xl sm:text-2xl font-bold text-yellow-600">{{ $overallProgress['in_progress_lessons'] }}</div>
                            <div class="text-xs sm:text-sm text-gray-600">In Progress</div>
                        </div>
                        <div class="bg-gray-50 rounded-lg p-3 sm:p-4">
                            <div class="text-xl sm:text-2xl font-bold text-gray-600">{{ $overallProgress['total_lessons'] }}</div>
                            <div class="text-xs sm:text-sm text-gray-600">Total</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Progress by Lesson -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Progress by Lesson</h3>
                    
                    <div class="space-y-4">
                        @forelse($overallProgress['lessons'] as $progress)
                            <div class="border border-gray-200 rounded-lg p-4">
                                <div class="flex justify-between items-center mb-2">
                                    <a href="{{ route('lessons.show', $progress->lesson->slug) }}" 
                                       class="text-base font-medium text-gray-900 hover:text-blue-600">
                                        {{ $progress->lesson->title }}
                                    </a>
                                    <span class="text-sm font-semibold text-gray-900">
                                        {{ number_format($progress->completion_percentage, 1) }}%
                                    </span>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full h-3 mb-3">
                                    <div class="bg-green-500 h-3 rounded-full progress-bar" 
                                         style="width: {{ min($progress->completion_percentage, 100) }}%"></div>
                                </div>
                                <div class="grid grid-cols-4 gap-2 text-xs text-gray-600">
                                    <div>
                                        <span class="font-medium">Phrases:</span> {{ $progress->phrases_viewed }}
                                    </div>
                                    <div>
                                        <span class="font-medium">Dialogues:</span> {{ $progress->dialogues_viewed }}
                                    </div>
                                    <div>
                                        <span class="font-medium">Drills:</span> {{ $progress->drills_completed }}
                                    </div>
                                    <div>
                                        <span class="font-medium">Shadowing:</span> {{ $progress->shadowing_completed }}
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-8 text-gray-500">
                                <p>Ready to start your speaking journey? 🎤 Begin your first conversation to track your progress!</p>
                                <a href="{{ route('lessons.index') }}" 
                                   class="inline-block mt-4 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                                    Start Speaking Practice
                                </a>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- Progress by Content Type -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Progress by Content Type</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        @foreach($contentTypeProgress as $type => $data)
                            <div class="border border-gray-200 rounded-lg p-4">
                                <div class="flex justify-between items-center mb-2">
                                    <span class="text-sm font-medium text-gray-700 capitalize">{{ $type }}</span>
                                    <span class="text-sm font-semibold text-gray-900">
                                        {{ $data['completed'] }} / {{ $data['total'] }}
                                    </span>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full h-3 mb-2">
                                    <div class="bg-purple-500 h-3 rounded-full transition-all duration-500" 
                                         style="width: {{ $data['percentage'] }}%"></div>
                                </div>
                                <div class="text-xs text-gray-600">
                                    {{ number_format($data['percentage'], 1) }}% complete
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Speaking Time Graphs -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Speaking Time (Last 7 Days)</h3>
                    
                    <div class="mb-6">
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                            <div class="bg-blue-50 rounded-lg p-4">
                                <div class="text-2xl font-bold text-blue-600">{{ number_format($weeklyStats['total_duration_minutes'], 0) }}</div>
                                <div class="text-sm text-gray-600">Total Minutes</div>
                            </div>
                            <div class="bg-green-50 rounded-lg p-4">
                                <div class="text-2xl font-bold text-green-600">{{ number_format($weeklyStats['avg_daily_minutes'], 1) }}</div>
                                <div class="text-sm text-gray-600">Avg Minutes/Day</div>
                            </div>
                            <div class="bg-purple-50 rounded-lg p-4">
                                <div class="text-2xl font-bold text-purple-600">{{ $weeklyStats['total_activities'] }}</div>
                                <div class="text-sm text-gray-600">Total Activities</div>
                            </div>
                            <div class="bg-yellow-50 rounded-lg p-4">
                                <div class="text-2xl font-bold text-yellow-600">{{ $weeklyStats['days_with_activity'] }}</div>
                                <div class="text-sm text-gray-600">Active Days</div>
                            </div>
                        </div>

                        <!-- Simple bar chart -->
                        <div class="space-y-2">
                            @foreach($weeklyStats['daily_breakdown'] as $day)
                                <div class="flex items-center gap-3">
                                    <div class="w-24 text-xs text-gray-600">
                                        {{ \Carbon\Carbon::parse($day['date'])->format('M d') }}
                                    </div>
                                    <div class="flex-1">
                                        <div class="w-full bg-gray-200 rounded-full h-6">
                                            <div class="bg-blue-500 h-6 rounded-full flex items-center px-2 text-xs text-white font-medium transition-all duration-500" 
                                                 style="width: {{ $weeklyStats['total_duration_minutes'] > 0 ? min(($day['study_minutes'] / max($weeklyStats['total_duration_minutes'], 1)) * 100 * 7, 100) : 0 }}%">
                                                @if($day['study_minutes'] > 0)
                                                    {{ $day['study_minutes'] }}m
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    <div class="w-16 text-xs text-gray-600 text-right">
                                        {{ $day['activities_count'] }} acts
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Activity Type Breakdown -->
                    @if(!empty($weeklyStats['activity_type_breakdown']))
                        <div class="mt-6 pt-6 border-t border-gray-200">
                            <h4 class="text-sm font-semibold text-gray-900 mb-3">Activity Breakdown</h4>
                            <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                                @foreach($weeklyStats['activity_type_breakdown'] as $type => $data)
                                    <div class="bg-gray-50 rounded-lg p-3">
                                        <div class="text-lg font-bold text-gray-900">{{ $data['count'] }}</div>
                                        <div class="text-xs text-gray-600 capitalize">{{ str_replace('_', ' ', $type) }}</div>
                                        <div class="text-xs text-gray-500">{{ number_format($data['duration'] / 60, 0) }}m</div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Flashcard Statistics -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Flashcard Statistics</h3>
                    
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
                        <div class="bg-blue-50 rounded-lg p-4">
                            <div class="text-2xl font-bold text-blue-600">{{ $flashcardStats['total_cards'] }}</div>
                            <div class="text-sm text-gray-600">Total Cards</div>
                        </div>
                        <div class="bg-green-50 rounded-lg p-4">
                            <div class="text-2xl font-bold text-green-600">{{ $flashcardStats['mastered_cards'] }}</div>
                            <div class="text-sm text-gray-600">Mastered</div>
                        </div>
                        <div class="bg-yellow-50 rounded-lg p-4">
                            <div class="text-2xl font-bold text-yellow-600">{{ $flashcardStats['due_cards'] }}</div>
                            <div class="text-sm text-gray-600">Due Today</div>
                        </div>
                        <div class="bg-purple-50 rounded-lg p-4">
                            <div class="text-2xl font-bold text-purple-600">{{ $flashcardStats['new_cards'] }}</div>
                            <div class="text-sm text-gray-600">New Cards</div>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="border border-gray-200 rounded-lg p-4">
                            <div class="text-sm font-medium text-gray-700 mb-2">Retention Rate (30 days)</div>
                            <div class="flex items-end gap-2">
                                <div class="text-3xl font-bold text-green-600">{{ number_format($flashcardStats['retention_rate'], 1) }}%</div>
                                <div class="text-sm text-gray-600 mb-1">
                                    ({{ $flashcardStats['total_reviews'] }} reviews)
                                </div>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2 mt-2">
                                <div class="bg-green-500 h-2 rounded-full" 
                                     style="width: {{ $flashcardStats['retention_rate'] }}%"></div>
                            </div>
                        </div>
                        <div class="border border-gray-200 rounded-lg p-4">
                            <div class="text-sm font-medium text-gray-700 mb-2">Average Review Time</div>
                            <div class="text-3xl font-bold text-blue-600">{{ number_format($flashcardStats['avg_review_time'], 1) }}s</div>
                            <div class="text-sm text-gray-600 mt-1">per card</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Exercise Statistics -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Exercise Statistics</h3>
                    
                    @if($exerciseStats['total_attempts'] > 0)
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                            <div class="bg-blue-50 rounded-lg p-4">
                                <div class="text-2xl font-bold text-blue-600">{{ $exerciseStats['total_attempts'] }}</div>
                                <div class="text-sm text-gray-600">Total Attempts</div>
                            </div>
                            <div class="bg-green-50 rounded-lg p-4">
                                <div class="text-2xl font-bold text-green-600">{{ number_format($exerciseStats['avg_score'], 1) }}%</div>
                                <div class="text-sm text-gray-600">Average Score</div>
                            </div>
                            <div class="bg-purple-50 rounded-lg p-4">
                                <div class="text-2xl font-bold text-purple-600">{{ number_format($exerciseStats['recent_avg_score'], 1) }}%</div>
                                <div class="text-sm text-gray-600">Recent Avg (Last 10)</div>
                            </div>
                        </div>

                        <!-- Scores by Exercise Type -->
                        @if(!empty($exerciseStats['scores_by_type']))
                            <div>
                                <h4 class="text-sm font-semibold text-gray-900 mb-3">Average Scores by Exercise Type</h4>
                                <div class="space-y-3">
                                    @foreach($exerciseStats['scores_by_type'] as $type => $data)
                                        <div class="border border-gray-200 rounded-lg p-4">
                                            <div class="flex justify-between items-center mb-2">
                                                <span class="text-sm font-medium text-gray-700 capitalize">{{ $type }}</span>
                                                <span class="text-sm font-semibold text-gray-900">
                                                    {{ number_format($data['avg_score'], 1) }}%
                                                </span>
                                            </div>
                                            <div class="w-full bg-gray-200 rounded-full h-3 mb-2">
                                                <div class="h-3 rounded-full transition-all duration-500 
                                                    {{ $data['avg_score'] >= 80 ? 'bg-green-500' : ($data['avg_score'] >= 60 ? 'bg-yellow-500' : 'bg-red-500') }}" 
                                                     style="width: {{ $data['avg_score'] }}%"></div>
                                            </div>
                                            <div class="flex justify-between text-xs text-gray-600">
                                                <span>{{ $data['count'] }} attempts</span>
                                                <span>Best: {{ number_format($data['best_score'], 1) }}%</span>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    @else
                        <div class="text-center py-8 text-gray-500">
                            <p>Your speaking practice stats will appear here! 📊 Start practicing conversations to see your progress.</p>
                            <a href="{{ route('lessons.index') }}" 
                               class="inline-block mt-4 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                                Start Exercises
                            </a>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Weak Areas -->
            @if(!empty($weakAreas))
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Areas for Improvement</h3>
                        <p class="text-sm text-gray-600 mb-4">Lessons where you might need more practice based on exercise scores</p>
                        
                        <div class="space-y-3">
                            @foreach($weakAreas as $area)
                                <div class="border border-red-200 bg-red-50 rounded-lg p-4">
                                    <div class="flex justify-between items-center mb-2">
                                        <a href="{{ route('lessons.show', $area['lesson_slug']) }}" 
                                           class="text-base font-medium text-gray-900 hover:text-blue-600">
                                            {{ $area['lesson_title'] }}
                                        </a>
                                        <span class="text-sm font-semibold {{ $area['avg_score'] < 60 ? 'text-red-600' : 'text-yellow-600' }}">
                                            {{ number_format($area['avg_score'], 1) }}% avg
                                        </span>
                                    </div>
                                    <div class="w-full bg-gray-200 rounded-full h-2 mb-2">
                                        <div class="h-2 rounded-full {{ $area['avg_score'] < 60 ? 'bg-red-500' : 'bg-yellow-500' }}" 
                                             style="width: {{ $area['avg_score'] }}%"></div>
                                    </div>
                                    <div class="text-xs text-gray-600">
                                        {{ $area['attempts_count'] }} attempts
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif

        </div>
    </div>
</x-app-layout>
