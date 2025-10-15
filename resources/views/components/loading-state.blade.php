@props([
    'message' => 'Loading your speaking practice...',
    'showTip' => true,
    'size' => 'default' // default, small, large
])

@php
    $tips = [
        '💡 Tip: Mimic the rhythm and intonation, not just the words!',
        '💡 Tip: Practice speaking out loud, even when alone - it builds confidence!',
        '💡 Tip: Shadow native speakers to perfect your pronunciation!',
        '💡 Tip: Daily practice, even 15 minutes, is more effective than long sessions!',
        '💡 Tip: Making mistakes is part of learning - embrace them!',
        '💡 Tip: Listen to the audio multiple times before speaking!',
        '💡 Tip: Record yourself and compare with native speakers!',
        '💡 Tip: Focus on conversations you\'ll actually use!',
    ];
    
    $randomTip = $tips[array_rand($tips)];
    
    $sizeClasses = [
        'small' => 'w-6 h-6',
        'default' => 'w-12 h-12',
        'large' => 'w-16 h-16'
    ];
    
    $spinnerSize = $sizeClasses[$size] ?? $sizeClasses['default'];
@endphp

<div {{ $attributes->merge(['class' => 'flex flex-col items-center justify-center py-8']) }}>
    <!-- Spinner -->
    <div class="relative {{ $spinnerSize }}">
        <div class="absolute inset-0 border-4 border-indigo-200 rounded-full"></div>
        <div class="absolute inset-0 border-4 border-indigo-600 rounded-full border-t-transparent animate-spin"></div>
    </div>
    
    <!-- Loading Message -->
    <p class="mt-4 text-base font-medium text-gray-700">{{ $message }}</p>
    
    <!-- Speaking Tip -->
    @if ($showTip)
        <p class="mt-2 text-sm text-gray-500 max-w-md text-center">{{ $randomTip }}</p>
    @endif
    
    <!-- Optional Slot for Additional Content -->
    @if ($slot->isNotEmpty())
        <div class="mt-4">
            {{ $slot }}
        </div>
    @endif
</div>
