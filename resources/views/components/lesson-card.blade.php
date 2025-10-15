@props(['lesson'])

@php
    $dialogueCount = $lesson->dialogues_count ?? 0;
    $shadowingCount = $lesson->shadowing_exercises_count ?? 0;
    $estimatedMinutes = ($dialogueCount * 2) + ($shadowingCount * 3); // Rough estimate
    $progress = $lesson->user_progress;
    $completionPercentage = $progress?->completion_percentage ?? 0;
    $status = $lesson->status ?? 'available';
    $prerequisiteLesson = $lesson->prerequisite_lesson ?? null;
    
    // Determine card state classes
    $stateClasses = match($status) {
        'locked' => 'bg-gray-50 border-gray-200 opacity-75',
        'in-progress' => 'bg-white border-indigo-300 border-2',
        'completed' => 'bg-white border-green-300 border-2',
        default => 'bg-white border-gray-200',
    };
    
    $hoverClasses = $status !== 'locked' 
        ? 'hover:-translate-y-1 hover:shadow-lg transition-all duration-200' 
        : '';
@endphp

<div class="rounded-lg border {{ $stateClasses }} {{ $hoverClasses }} p-6 relative">
    {{-- Lesson Number Badge --}}
    <div class="absolute -top-3 -left-3 w-12 h-12 bg-gradient-to-br from-indigo-500 to-indigo-600 rounded-full flex items-center justify-center text-white font-bold text-lg shadow-lg">
        {{ $lesson->order }}
    </div>
    
    {{-- Completed Badge --}}
    @if($status === 'completed')
        <div class="absolute -top-3 -right-3 w-12 h-12 bg-gradient-to-br from-green-500 to-green-600 rounded-full flex items-center justify-center text-white shadow-lg">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path>
            </svg>
        </div>
    @endif
    
    {{-- Locked Icon --}}
    @if($status === 'locked')
        <div class="absolute -top-3 -right-3 w-12 h-12 bg-gray-400 rounded-full flex items-center justify-center text-white shadow-lg">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
            </svg>
        </div>
    @endif
    
    <div class="mt-2">
        {{-- Conversation Topic Title --}}
        <div class="flex items-start gap-3 mb-2">
            <div class="text-2xl">🗣️</div>
            <div class="flex-1">
                <h3 class="text-xl font-semibold text-gray-900 mb-1">
                    {{ $lesson->title }}
                </h3>
                
                {{-- Description --}}
                @if($lesson->description)
                    <p class="text-gray-600 text-sm leading-relaxed">
                        {{ $lesson->description }}
                    </p>
                @endif
            </div>
        </div>
        
        {{-- Locked Message --}}
        @if($status === 'locked' && $prerequisiteLesson)
            <div class="mt-4 p-3 bg-gray-100 rounded-lg border border-gray-200">
                <p class="text-sm text-gray-700">
                    🔒 Complete <span class="font-semibold">{{ $prerequisiteLesson->title }}</span> to unlock this conversation
                </p>
            </div>
        @endif
        
        {{-- Metadata: Dialogue count, Shadowing count, Estimated time --}}
        <div class="flex items-center gap-4 mt-4 text-sm text-gray-600">
            <div class="flex items-center gap-1.5">
                <svg class="w-5 h-5 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                </svg>
                <span>{{ $dialogueCount }} Dialogues</span>
            </div>
            
            <div class="flex items-center gap-1.5">
                <svg class="w-5 h-5 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11a7 7 0 01-7 7m0 0a7 7 0 01-7-7m7 7v4m0 0H8m4 0h4m-4-8a3 3 0 01-3-3V5a3 3 0 116 0v6a3 3 0 01-3 3z"></path>
                </svg>
                <span>{{ $shadowingCount }} Shadowing</span>
            </div>
            
            <div class="flex items-center gap-1.5">
                <svg class="w-5 h-5 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <span>{{ $estimatedMinutes }} min</span>
            </div>
        </div>
        
        {{-- Progress Bar (for in-progress and completed) --}}
        @if($status === 'in-progress' || $status === 'completed')
            <div class="mt-4">
                <div class="flex items-center justify-between text-sm mb-1.5">
                    <span class="text-gray-600 font-medium">Progress</span>
                    <span class="text-indigo-600 font-semibold">{{ number_format($completionPercentage, 0) }}%</span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-2 overflow-hidden">
                    <div 
                        class="h-full rounded-full transition-all duration-500 {{ $status === 'completed' ? 'bg-gradient-to-r from-green-500 to-green-600' : 'bg-gradient-to-r from-indigo-500 to-indigo-600' }}"
                        style="width: {{ $completionPercentage }}%"
                    ></div>
                </div>
            </div>
        @endif
        
        {{-- CTA Button --}}
        <div class="mt-5">
            @if($status === 'locked')
                <button 
                    disabled
                    class="w-full py-3 px-6 bg-gray-300 text-gray-500 rounded-lg font-semibold cursor-not-allowed"
                >
                    Locked
                </button>
            @elseif($status === 'completed')
                <a 
                    href="{{ route('lessons.show', $lesson->slug) }}"
                    class="block w-full py-3 px-6 bg-gradient-to-r from-green-500 to-green-600 hover:from-green-600 hover:to-green-700 text-white text-center rounded-lg font-semibold shadow-md hover:shadow-lg transition-all duration-200"
                >
                    Review Conversation →
                </a>
            @elseif($status === 'in-progress')
                <a 
                    href="{{ route('lessons.show', $lesson->slug) }}"
                    class="block w-full py-3 px-6 bg-gradient-to-r from-indigo-500 to-indigo-600 hover:from-indigo-600 hover:to-indigo-700 text-white text-center rounded-lg font-semibold shadow-md hover:shadow-lg transition-all duration-200"
                >
                    Continue Speaking →
                </a>
            @else
                <a 
                    href="{{ route('lessons.show', $lesson->slug) }}"
                    class="block w-full py-3 px-6 bg-gradient-to-r from-indigo-500 to-indigo-600 hover:from-indigo-600 hover:to-indigo-700 text-white text-center rounded-lg font-semibold shadow-md hover:shadow-lg transition-all duration-200"
                >
                    Practice Speaking →
                </a>
            @endif
        </div>
    </div>
</div>
