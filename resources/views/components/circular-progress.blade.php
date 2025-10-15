@props([
    'percentage' => 0,
    'level' => 'Beginner',
    'size' => 120,
    'strokeWidth' => 8,
])

@php
    $radius = ($size - $strokeWidth) / 2;
    $circumference = 2 * pi() * $radius;
    $offset = $circumference - ($percentage / 100) * $circumference;
    
    // Determine gradient colors based on progress level
    $gradientStart = match(true) {
        $percentage >= 80 => '#10B981', // Green for high progress
        $percentage >= 50 => '#6366F1', // Indigo for medium progress
        $percentage >= 25 => '#3B82F6', // Blue for low-medium progress
        default => '#94A3B8', // Gray for low progress
    };
    
    $gradientEnd = match(true) {
        $percentage >= 80 => '#059669',
        $percentage >= 50 => '#4F46E5',
        $percentage >= 25 => '#2563EB',
        default => '#64748B',
    };
@endphp

<div {{ $attributes->merge(['class' => 'inline-flex flex-col items-center justify-center']) }}>
    <div class="relative" style="width: {{ $size }}px; height: {{ $size }}px;">
        <svg class="transform -rotate-90" width="{{ $size }}" height="{{ $size }}">
            <defs>
                <linearGradient id="progress-gradient-{{ $percentage }}" x1="0%" y1="0%" x2="100%" y2="100%">
                    <stop offset="0%" style="stop-color:{{ $gradientStart }};stop-opacity:1" />
                    <stop offset="100%" style="stop-color:{{ $gradientEnd }};stop-opacity:1" />
                </linearGradient>
            </defs>
            
            <!-- Background circle -->
            <circle
                cx="{{ $size / 2 }}"
                cy="{{ $size / 2 }}"
                r="{{ $radius }}"
                stroke="currentColor"
                stroke-width="{{ $strokeWidth }}"
                fill="none"
                class="text-gray-200"
            />
            
            <!-- Progress circle -->
            <circle
                cx="{{ $size / 2 }}"
                cy="{{ $size / 2 }}"
                r="{{ $radius }}"
                stroke="url(#progress-gradient-{{ $percentage }})"
                stroke-width="{{ $strokeWidth }}"
                fill="none"
                stroke-linecap="round"
                style="
                    stroke-dasharray: {{ $circumference }};
                    stroke-dashoffset: {{ $offset }};
                    transition: stroke-dashoffset 1s ease-in-out;
                "
            />
        </svg>
        
        <!-- Center content -->
        <div class="absolute inset-0 flex flex-col items-center justify-center">
            <span class="text-2xl font-bold text-gray-900">{{ $percentage }}%</span>
            <span class="text-xs text-gray-500 mt-1">Speaking</span>
        </div>
    </div>
    
    <!-- Level label -->
    <div class="mt-3 text-center">
        <span class="text-sm font-semibold text-gray-700">{{ $level }}</span>
    </div>
</div>
