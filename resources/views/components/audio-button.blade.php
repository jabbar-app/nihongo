@props([
    'text' => '',
    'lang' => 'ja-JP',
    'size' => 'md',
    'variant' => 'default'
])

@php
    $sizeClasses = [
        'sm' => 'px-2 py-1 text-sm',
        'md' => 'px-4 py-2',
        'lg' => 'px-6 py-3 text-lg'
    ];
    
    $variantClasses = [
        'default' => 'bg-gray-100 hover:bg-gray-200 text-gray-700',
        'primary' => 'bg-blue-100 hover:bg-blue-200 text-blue-700',
        'secondary' => 'bg-purple-100 hover:bg-purple-200 text-purple-700'
    ];
    
    $iconSizes = [
        'sm' => 'w-4 h-4',
        'md' => 'w-5 h-5',
        'lg' => 'w-6 h-6'
    ];
    
    $classes = implode(' ', [
        'inline-flex items-center rounded-lg transition',
        'disabled:opacity-50 disabled:cursor-not-allowed',
        $sizeClasses[$size] ?? $sizeClasses['md'],
        $variantClasses[$variant] ?? $variantClasses['default']
    ]);
@endphp

<div x-data="audioPlayer()" x-init="init()">
    <button 
        @click="play('{{ addslashes($text) }}', '{{ $lang }}')"
        :disabled="playing || loading"
        class="{{ $classes }}"
        {{ $attributes }}>
        
        <!-- Loading State -->
        <template x-if="loading">
            <svg class="{{ $iconSizes[$size] ?? $iconSizes['md'] }} mr-2 animate-spin" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
        </template>
        
        <!-- Playing State -->
        <template x-if="playing && !loading">
            <svg class="{{ $iconSizes[$size] ?? $iconSizes['md'] }} mr-2" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zM7 8a1 1 0 012 0v4a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v4a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
            </svg>
        </template>
        
        <!-- Default State -->
        <template x-if="!playing && !loading">
            <svg class="{{ $iconSizes[$size] ?? $iconSizes['md'] }} mr-2" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM9.555 7.168A1 1 0 008 8v4a1 1 0 001.555.832l3-2a1 1 0 000-1.664l-3-2z" clip-rule="evenodd" />
            </svg>
        </template>
        
        <span x-text="playing ? 'Playing...' : (loading ? 'Loading...' : '{{ $slot->isEmpty() ? 'Play Audio' : $slot }}')"></span>
    </button>
    
    <!-- Error Message -->
    <template x-if="error">
        <div class="mt-2 text-sm text-red-600" x-text="error"></div>
    </template>
</div>
