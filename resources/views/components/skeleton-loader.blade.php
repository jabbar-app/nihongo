@props([
    'type' => 'card', // card, list, text, conversation-card
    'count' => 1
])

@php
    $skeletonTypes = [
        'card' => 'skeleton-card',
        'list' => 'skeleton-list',
        'text' => 'skeleton-text',
        'conversation-card' => 'skeleton-conversation-card'
    ];
    
    $skeletonClass = $skeletonTypes[$type] ?? $skeletonTypes['card'];
@endphp

<div {{ $attributes->merge(['class' => 'animate-pulse']) }}>
    @for ($i = 0; $i < $count; $i++)
        @if ($type === 'card')
            <div class="bg-white rounded-lg border border-gray-200 p-6 mb-4">
                <div class="h-4 bg-gray-200 rounded w-3/4 mb-4"></div>
                <div class="h-3 bg-gray-200 rounded w-full mb-2"></div>
                <div class="h-3 bg-gray-200 rounded w-5/6 mb-4"></div>
                <div class="flex gap-4 mb-4">
                    <div class="h-3 bg-gray-200 rounded w-20"></div>
                    <div class="h-3 bg-gray-200 rounded w-20"></div>
                    <div class="h-3 bg-gray-200 rounded w-20"></div>
                </div>
                <div class="h-2 bg-gray-200 rounded-full w-full mb-4"></div>
                <div class="h-10 bg-gray-200 rounded w-full"></div>
            </div>
        @elseif ($type === 'conversation-card')
            <div class="bg-white rounded-lg border border-gray-200 p-6 mb-4">
                <div class="flex items-start gap-3 mb-4">
                    <div class="w-12 h-12 bg-gray-200 rounded-full flex-shrink-0"></div>
                    <div class="flex-1">
                        <div class="h-5 bg-gray-200 rounded w-3/4 mb-2"></div>
                        <div class="h-3 bg-gray-200 rounded w-full"></div>
                    </div>
                </div>
                <div class="flex gap-4 mb-4">
                    <div class="h-3 bg-gray-200 rounded w-24"></div>
                    <div class="h-3 bg-gray-200 rounded w-24"></div>
                    <div class="h-3 bg-gray-200 rounded w-20"></div>
                </div>
                <div class="h-2 bg-gray-200 rounded-full w-full mb-4"></div>
                <div class="h-11 bg-gray-200 rounded-lg w-full"></div>
            </div>
        @elseif ($type === 'list')
            <div class="bg-white rounded-lg border border-gray-200 p-4 mb-3">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-gray-200 rounded-full flex-shrink-0"></div>
                    <div class="flex-1">
                        <div class="h-4 bg-gray-200 rounded w-3/4 mb-2"></div>
                        <div class="h-3 bg-gray-200 rounded w-1/2"></div>
                    </div>
                </div>
            </div>
        @elseif ($type === 'text')
            <div class="mb-2">
                <div class="h-4 bg-gray-200 rounded w-full mb-2"></div>
                <div class="h-4 bg-gray-200 rounded w-5/6 mb-2"></div>
                <div class="h-4 bg-gray-200 rounded w-4/6"></div>
            </div>
        @endif
    @endfor
</div>

@push('styles')
<style>
    @keyframes pulse {
        0%, 100% {
            opacity: 1;
        }
        50% {
            opacity: 0.5;
        }
    }
    
    .animate-pulse {
        animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
    }
</style>
@endpush
