@props([
    'streak' => 0,
    'weeklyProgress' => [], // Array of 7 booleans for each day of the week
    'showWeekly' => true,
])

@php
    // Generate encouraging text based on streak length
    $encouragingText = match(true) {
        $streak >= 30 => 'Amazing dedication! 🌟',
        $streak >= 14 => 'You\'re on fire! Keep it up! 🚀',
        $streak >= 7 => 'One week strong! 💪',
        $streak >= 3 => 'Great momentum! 🎯',
        $streak >= 1 => 'Keep it going! 👏',
        default => 'Start your streak today! 💫',
    };
    
    // Ensure weeklyProgress has 7 days
    $weeklyProgress = array_pad($weeklyProgress, 7, false);
    $daysOfWeek = ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'];
@endphp

<div {{ $attributes->merge(['class' => 'bg-gradient-to-br from-orange-50 to-red-50 rounded-lg border-2 border-orange-400 p-4']) }}>
    <div class="flex items-center justify-between">
        <div class="flex items-center space-x-3">
            <!-- Flame icon with glow animation -->
            <div class="relative">
                <svg 
                    class="w-8 h-8 text-orange-500 animate-pulse" 
                    fill="currentColor" 
                    viewBox="0 0 24 24"
                    style="filter: drop-shadow(0 0 8px rgba(249, 115, 22, 0.6));"
                >
                    <path d="M12 2c1.5 3 4 4 6 7 1 1.5 1 3.5 0 5-1.5 2-4 3-6 3s-4.5-1-6-3c-1-1.5-1-3.5 0-5 2-3 4.5-4 6-7z"/>
                    <path d="M12 17c-1.5 0-2.5-.5-3-1.5-.5-1 0-2 .5-3 .5-.5 1-1 1.5-2 .5 1 1 1.5 1.5 2 .5 1 1 2 .5 3-.5 1-1.5 1.5-3 1.5z" fill="#FFF"/>
                </svg>
            </div>
            
            <!-- Streak count -->
            <div>
                <div class="flex items-baseline space-x-2">
                    <span class="text-3xl font-bold bg-gradient-to-r from-orange-600 to-red-600 bg-clip-text text-transparent">
                        {{ $streak }}
                    </span>
                    <span class="text-sm font-semibold text-gray-700">
                        {{ $streak === 1 ? 'Day' : 'Days' }}
                    </span>
                </div>
                <p class="text-xs text-gray-600 mt-0.5">Speaking Streak</p>
            </div>
        </div>
        
        <!-- Encouraging text -->
        <div class="text-right">
            <p class="text-sm font-medium text-orange-700">{{ $encouragingText }}</p>
        </div>
    </div>
    
    @if($showWeekly && count(array_filter($weeklyProgress)) > 0)
        <!-- Weekly progress indicators -->
        <div class="mt-4 pt-4 border-t border-orange-200">
            <p class="text-xs font-medium text-gray-600 mb-2">This Week</p>
            <div class="flex items-center justify-between space-x-1">
                @foreach($daysOfWeek as $index => $day)
                    <div class="flex flex-col items-center flex-1">
                        <div class="w-8 h-8 rounded-full flex items-center justify-center mb-1 transition-all duration-300
                            {{ $weeklyProgress[$index] 
                                ? 'bg-gradient-to-br from-orange-400 to-red-400 text-white shadow-md' 
                                : 'bg-gray-200 text-gray-400' }}">
                            @if($weeklyProgress[$index])
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                </svg>
                            @else
                                <span class="text-xs">○</span>
                            @endif
                        </div>
                        <span class="text-xs text-gray-500">{{ $day }}</span>
                    </div>
                @endforeach
            </div>
        </div>
    @endif
</div>
