@props(['achievement'])

<div x-data="{ show: true }" 
     x-show="show"
     x-init="setTimeout(() => show = false, 5000)"
     x-transition:enter="transition ease-out duration-300 transform"
     x-transition:enter-start="translate-x-full opacity-0"
     x-transition:enter-end="translate-x-0 opacity-100"
     x-transition:leave="transition ease-in duration-200 transform"
     x-transition:leave-start="translate-x-0 opacity-100"
     x-transition:leave-end="translate-x-full opacity-0"
     class="fixed bottom-4 right-4 z-50 max-w-sm w-full bg-gradient-to-r from-yellow-50 to-yellow-100 border-2 border-yellow-400 rounded-lg shadow-xl p-4 bounce-in"
     role="alert"
     aria-live="assertive"
     aria-atomic="true">
    
    <div class="flex items-start">
        <div class="flex-shrink-0">
            <div class="text-4xl celebrate">{{ $achievement->icon ?? '🏆' }}</div>
        </div>
        
        <div class="ml-3 flex-1">
            <h3 class="text-sm font-bold text-gray-900">Achievement Unlocked!</h3>
            <p class="mt-1 text-sm font-semibold text-gray-800">{{ $achievement->name ?? 'New Achievement' }}</p>
            <p class="mt-1 text-xs text-gray-600">{{ $achievement->description ?? '' }}</p>
            @if(isset($achievement->xp_reward) && $achievement->xp_reward > 0)
                <p class="mt-1 text-xs font-semibold text-yellow-700">+{{ $achievement->xp_reward }} XP</p>
            @endif
        </div>
        
        <button @click="show = false" 
                class="ml-3 flex-shrink-0 text-gray-400 hover:text-gray-600 transition"
                aria-label="Close notification">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </button>
    </div>
</div>
