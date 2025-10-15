@props([
    'show' => false,
    'message' => 'よくできました！',
    'xpGained' => 0,
    'duration' => 3000
])

<div x-data="{ 
        show: @js($show),
        init() {
            if (this.show) {
                setTimeout(() => { this.show = false }, {{ $duration }})
            }
        }
     }"
     x-show="show"
     x-cloak
     x-transition:enter="transform ease-out duration-300 transition"
     x-transition:enter-start="translate-y-2 opacity-0 sm:translate-y-0 sm:translate-x-2"
     x-transition:enter-end="translate-y-0 opacity-100 sm:translate-x-0"
     x-transition:leave="transition ease-in duration-100"
     x-transition:leave-start="opacity-100"
     x-transition:leave-end="opacity-0"
     class="fixed bottom-20 sm:bottom-4 right-4 z-50 pointer-events-auto w-full max-w-sm overflow-hidden rounded-lg bg-white shadow-lg ring-1 ring-black ring-opacity-5">
    
    <div class="p-4">
        <div class="flex items-start">
            <div class="flex-shrink-0">
                <svg class="h-6 w-6 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <div class="ml-3 w-0 flex-1 pt-0.5">
                <p class="text-lg font-semibold text-gray-900" lang="ja">{{ $message }}</p>
                @if ($xpGained > 0)
                    <p class="mt-1 text-sm text-gray-500">
                        <span class="inline-flex items-center">
                            <svg class="w-4 h-4 mr-1 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                            </svg>
                            <span class="font-semibold text-indigo-600">+{{ $xpGained }} XP</span>
                        </span>
                    </p>
                @endif
            </div>
            <div class="ml-4 flex flex-shrink-0">
                <button @click="show = false"
                        class="inline-flex rounded-md bg-white text-gray-400 hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                    <span class="sr-only">Close</span>
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>
</div>
