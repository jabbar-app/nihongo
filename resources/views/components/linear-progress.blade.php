@props([
    'current' => 0,
    'total' => 100,
    'label' => 'Progress',
    'showFraction' => true,
    'height' => 'h-2',
])

@php
    $percentage = $total > 0 ? round(($current / $total) * 100) : 0;
    $ariaLabel = $showFraction 
        ? "{$label}: {$current} of {$total} completed, {$percentage}%"
        : "{$label}: {$percentage}% complete";
@endphp

<div {{ $attributes->merge(['class' => 'w-full']) }}>
    @if($label || $showFraction)
        <div class="flex items-center justify-between mb-2">
            @if($label)
                <span class="text-sm font-medium text-gray-700">{{ $label }}</span>
            @endif
            
            @if($showFraction)
                <span class="text-sm font-semibold text-gray-900">{{ $current }}/{{ $total }}</span>
            @endif
        </div>
    @endif
    
    <div 
        class="w-full bg-gray-200 rounded-full overflow-hidden {{ $height }}"
        role="progressbar"
        aria-label="{{ $ariaLabel }}"
        aria-valuenow="{{ $percentage }}"
        aria-valuemin="0"
        aria-valuemax="100"
    >
        <div 
            class="h-full rounded-full bg-gradient-to-r from-indigo-500 to-indigo-600 transition-all duration-1000 ease-out"
            style="width: {{ $percentage }}%"
        ></div>
    </div>
</div>
