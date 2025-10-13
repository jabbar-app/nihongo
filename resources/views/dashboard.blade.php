<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            <!-- Welcome Section -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-2xl font-bold text-gray-900 mb-2">
                        Welcome back, {{ Auth::user()->name }}!
                    </h3>
                    <p class="text-gray-600">Here's your learning progress at a glance.</p>
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
                                    :class="range === 'week' ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-700'"
                                    class="px-4 py-2 rounded-lg text-sm font-medium hover:bg-blue-500 hover:text-white transition">
                                Week
                            </button>
                            <button @click="setRange('month')" 
                                    :class="range === 'month' ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-700'"
                                    class="px-4 py-2 rounded-lg text-sm font-medium hover:bg-blue-500 hover:text-white transition">
                                Month
                            </button>
                            <button @click="setRange('all')" 
                                    :class="range === 'all' ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-700'"
                                    class="px-4 py-2 rounded-lg text-sm font-medium hover:bg-blue-500 hover:text-white transition">
                                All Time
                            </button>
                        </div>
                    </div>

                    <!-- Key Metrics -->
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                        <div class="bg-gradient-to-br from-blue-50 to-blue-100 rounded-lg p-4 border border-blue-200">
                            <div class="text-sm font-medium text-blue-700 mb-1">Total Study Hours</div>
                            <div class="text-3xl font-bold text-blue-900" x-text="metrics.totalHours"></div>
                            <div class="text-xs text-blue-600 mt-1" x-text="metrics.totalMinutes + ' minutes'"></div>
                        </div>
                        <div class="bg-gradient-to-br from-green-50 to-green-100 rounded-lg p-4 border border-green-200">
                            <div class="text-sm font-medium text-green-700 mb-1">Avg Daily Time</div>
                            <div class="text-3xl font-bold text-green-900" x-text="metrics.avgDailyMinutes"></div>
                            <div class="text-xs text-green-600 mt-1">minutes per day</div>
                        </div>
                        <div class="bg-gradient-to-br from-purple-50 to-purple-100 rounded-lg p-4 border border-purple-200">
                            <div class="text-sm font-medium text-purple-700 mb-1">Completion Rate</div>
                            <div class="text-3xl font-bold text-purple-900" x-text="metrics.completionRate + '%'"></div>
                            <div class="text-xs text-purple-600 mt-1">overall progress</div>
                        </div>
                        <div class="bg-gradient-to-br from-yellow-50 to-yellow-100 rounded-lg p-4 border border-yellow-200">
                            <div class="text-sm font-medium text-yellow-700 mb-1">Current Streak</div>
                            <div class="text-3xl font-bold text-yellow-900" x-text="metrics.currentStreak"></div>
                            <div class="text-xs text-yellow-600 mt-1" x-text="'Longest: ' + metrics.longestStreak + ' days'"></div>
                        </div>
                    </div>

                    <!-- Study Time Chart -->
                    <div class="mb-6">
                        <h4 class="text-sm font-semibold text-gray-900 mb-3">Study Time</h4>
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
                                            <div class="text-2xl font-bold text-gray-900" x-text="activityBreakdown.total"></div>
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
                        <h4 class="text-sm font-semibold text-gray-900 mb-3">Streak Calendar</h4>
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
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <a href="{{ route('flashcards.review') }}" 
                   class="bg-blue-600 hover:bg-blue-700 text-white rounded-lg p-6 text-center transition">
                    <div class="text-2xl font-bold mb-2">Start Review</div>
                    <div class="text-sm opacity-90">Practice your flashcards</div>
                </a>
                <a href="{{ route('lessons.index') }}" 
                   class="bg-green-600 hover:bg-green-700 text-white rounded-lg p-6 text-center transition">
                    <div class="text-2xl font-bold mb-2">Continue Lesson</div>
                    <div class="text-sm opacity-90">Keep learning</div>
                </a>
                <a href="{{ route('progress.index') }}" 
                   class="bg-purple-600 hover:bg-purple-700 text-white rounded-lg p-6 text-center transition">
                    <div class="text-2xl font-bold mb-2">View Progress</div>
                    <div class="text-sm opacity-90">See detailed stats</div>
                </a>
            </div>

        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('analyticsFilter', () => ({
                range: 'week',
                metrics: {},
                studyTimeData: [],
                activityBreakdown: {},
                pieChartSegments: [],
                xpData: [],
                streakCalendar: [],
                
                init() {
                    this.loadData();
                },
                
                setRange(newRange) {
                    this.range = newRange;
                    this.loadData();
                },
                
                async loadData() {
                    try {
                        const response = await fetch(`/api/analytics?range=${this.range}`);
                        const data = await response.json();
                        
                        this.metrics = data.metrics;
                        this.studyTimeData = data.studyTimeData;
                        this.activityBreakdown = data.activityBreakdown;
                        this.xpData = data.xpData;
                        this.streakCalendar = data.streakCalendar;
                        
                        this.calculatePieChart();
                    } catch (error) {
                        console.error('Failed to load analytics data:', error);
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
</x-app-layout>
