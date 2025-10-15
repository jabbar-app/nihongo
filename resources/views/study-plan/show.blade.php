<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Daily Study Plan') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Breadcrumb -->
            <x-breadcrumb :items="[
                ['label' => 'Study Plan']
            ]" />
            <!-- Plan Header -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <div class="flex items-center justify-between mb-4">
                        <div>
                            <h3 class="text-2xl font-bold text-gray-900">
                                {{ \Carbon\Carbon::parse($plan->date)->format('l, F j, Y') }}
                            </h3>
                            <p class="text-gray-600 mt-1">
                                Your personalized study plan for today
                            </p>
                        </div>
                        
                        @if($plan->completed_at)
                            <div class="flex items-center text-green-600">
                                <svg class="w-8 h-8 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                                <span class="text-lg font-semibold">Plan Completed!</span>
                            </div>
                        @endif
                    </div>

                    <!-- Progress Bar -->
                    <div class="mb-4">
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-sm font-medium text-gray-700">
                                Progress: {{ $plan->completed_activities }} / {{ $plan->total_activities }} activities
                            </span>
                            <span class="text-sm font-medium text-gray-700">
                                {{ $completionPercentage }}%
                            </span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-3">
                            <div class="bg-blue-600 h-3 rounded-full transition-all duration-500" 
                                 style="width: {{ $completionPercentage }}%">
                            </div>
                        </div>
                    </div>

                    <!-- Time Estimate -->
                    <div class="flex items-center justify-between text-sm">
                        <div class="flex items-center text-gray-600">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <span>
                                Estimated time remaining: 
                                <strong>{{ $estimatedTimeRemaining }} minutes</strong>
                            </span>
                        </div>
                        
                        <div class="text-gray-600">
                            Goal: <strong>{{ $plan->plan_data['goal_minutes'] ?? 120 }} minutes</strong>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Activities List -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-xl font-bold text-gray-900 mb-4">Today's Activities</h3>
                    
                    @if(empty($plan->plan_data['activities']))
                        <div class="text-center py-8">
                            <svg class="w-16 h-16 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                            </svg>
                            <p class="text-gray-600">No activities planned for today.</p>
                            <p class="text-gray-500 text-sm mt-2">Check back tomorrow for your next study plan!</p>
                        </div>
                    @else
                        <div class="space-y-4" x-data="studyPlan()">
                            @foreach($plan->plan_data['activities'] as $activity)
                                <div class="border rounded-lg p-4 hover:shadow-md transition-shadow {{ ($activity['completed'] ?? false) ? 'bg-green-50 border-green-200' : 'bg-white border-gray-200' }}"
                                     x-data="{ completed: {{ ($activity['completed'] ?? false) ? 'true' : 'false' }} }">
                                    <div class="flex items-start justify-between">
                                        <div class="flex items-start flex-1">
                                            <!-- Checkbox -->
                                            <div class="flex items-center h-6 mr-4">
                                                <input type="checkbox" 
                                                       x-model="completed"
                                                       @change="toggleActivity('{{ $activity['id'] }}')"
                                                       class="w-5 h-5 text-blue-600 border-gray-300 rounded focus:ring-blue-500"
                                                       {{ ($activity['completed'] ?? false) ? 'checked' : '' }}>
                                            </div>
                                            
                                            <!-- Activity Details -->
                                            <div class="flex-1">
                                                <h4 class="text-lg font-semibold text-gray-900 {{ ($activity['completed'] ?? false) ? 'line-through text-gray-500' : '' }}">
                                                    {{ $activity['title'] }}
                                                </h4>
                                                <p class="text-gray-600 text-sm mt-1">
                                                    {{ $activity['description'] }}
                                                </p>
                                                
                                                <!-- Activity Meta -->
                                                <div class="flex items-center mt-2 space-x-4 text-sm text-gray-500">
                                                    <div class="flex items-center">
                                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                        </svg>
                                                        {{ $activity['estimated_minutes'] }} min
                                                    </div>
                                                    
                                                    @if(isset($activity['target_count']))
                                                        <div class="flex items-center">
                                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                                            </svg>
                                                            {{ $activity['target_count'] }} items
                                                        </div>
                                                    @endif
                                                    
                                                    <!-- Priority Badge -->
                                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium
                                                        {{ $activity['priority'] == 1 ? 'bg-red-100 text-red-800' : '' }}
                                                        {{ $activity['priority'] == 2 ? 'bg-orange-100 text-orange-800' : '' }}
                                                        {{ $activity['priority'] >= 3 ? 'bg-blue-100 text-blue-800' : '' }}">
                                                        Priority {{ $activity['priority'] }}
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <!-- Quick Start Button -->
                                        @if(!($activity['completed'] ?? false))
                                            <div class="ml-4">
                                                @php
                                                    $url = match($activity['type']) {
                                                        'flashcard_review' => route('flashcards.review'),
                                                        'content_view' => isset($activity['lesson_id']) ? route('lessons.show', $activity['lesson_id']) : '#',
                                                        'exercise' => isset($activity['lesson_id']) ? route('lessons.show', $activity['lesson_id']) . '#drills' : '#',
                                                        'shadowing' => isset($activity['lesson_id']) ? route('lessons.show', $activity['lesson_id']) . '#shadowing' : '#',
                                                        default => '#',
                                                    };
                                                @endphp
                                                
                                                <a href="{{ $url }}" 
                                                   class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"/>
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                    </svg>
                                                    Start
                                                </a>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>

            <!-- Completion Celebration -->
            @if($plan->completed_at)
                <div class="mt-6 bg-gradient-to-r from-green-400 to-blue-500 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-white text-center">
                        <h3 class="text-2xl font-bold mb-2">🎉 Congratulations! 🎉</h3>
                        <p class="text-lg">You've completed your daily study plan!</p>
                        <p class="text-sm mt-2 opacity-90">Keep up the great work and come back tomorrow for more!</p>
                    </div>
                </div>
            @endif
        </div>
    </div>

    @push('scripts')
    <script>
        function studyPlan() {
            return {
                toggleActivity(activityId) {
                    fetch(`/study-plan/complete/${activityId}`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            // Reload page to update progress
                            window.location.reload();
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('😅 We couldn\'t update that activity right now. Please try again!');
                    });
                }
            }
        }
    </script>
    @endpush
</x-app-layout>
