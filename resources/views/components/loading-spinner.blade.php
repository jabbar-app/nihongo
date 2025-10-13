@props(['size' => 'md', 'text' => null])

@php
    $sizeClasses = [
        'sm' => 'w-4 h-4 border-2',
        'md' => 'w-8 h-8 border-4',
        'lg' => 'w-12 h-12 border-4',
        'xl' => 'w-16 h-16 border-4',
    ];
    
    $spinnerClass = $sizeClasses[$size] ?? $sizeClasses['md'];
@endphp

<div {{ $attributes->merge(['class' => 'flex flex-col items-center justify-center']) }} role="status" aria-live="polite">
    <div class="{{ $spinnerClass }} border-gray-200 border-t-blue-600 rounded-full spinner" aria-hidden="true"></div>
    @if($text)
        <span class="mt-2 text-sm text-gray-600">{{ $text }}</span>
    @endif
    <span class="sr-only">Loading...</span>
</div>
