@props([
    'text' => '',
    'lang' => 'ja-JP',
    'showSpeedControl' => true,
    'autoPlay' => false
])

<div x-data="audioPlayer()" x-init="init(); {{ $autoPlay ? "play('$text', '$lang')" : '' }}" class="inline-flex items-center gap-3">
    <!-- Play/Pause Button -->
    <button 
        @click="togglePlayPause('{{ addslashes($text) }}', '{{ $lang }}')"
        :disabled="loading"
        class="inline-flex items-center justify-center w-10 h-10 rounded-full bg-blue-600 hover:bg-blue-700 text-white transition disabled:opacity-50 disabled:cursor-not-allowed"
        :class="{ 'bg-blue-700': playing }"
        {{ $attributes }}>
        
        <!-- Loading State -->
        <template x-if="loading">
            <svg class="w-5 h-5 animate-spin" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
        </template>
        
        <!-- Play Icon -->
        <template x-if="!playing && !loading">
            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                <path d="M6.3 2.841A1.5 1.5 0 004 4.11V15.89a1.5 1.5 0 002.3 1.269l9.344-5.89a1.5 1.5 0 000-2.538L6.3 2.84z" />
            </svg>
        </template>
        
        <!-- Pause Icon -->
        <template x-if="playing && !loading">
            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                <path d="M5.75 3a.75.75 0 00-.75.75v12.5c0 .414.336.75.75.75h1.5a.75.75 0 00.75-.75V3.75A.75.75 0 007.25 3h-1.5zM12.75 3a.75.75 0 00-.75.75v12.5c0 .414.336.75.75.75h1.5a.75.75 0 00.75-.75V3.75a.75.75 0 00-.75-.75h-1.5z" />
            </svg>
        </template>
    </button>
    
    <!-- Speed Control -->
    @if($showSpeedControl)
    <div class="flex items-center gap-2">
        <label class="text-sm text-gray-600">Speed:</label>
        <select 
            x-model="playbackSpeed"
            @change="setSpeed($event.target.value)"
            class="text-sm border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500">
            <option value="0.5">0.5x</option>
            <option value="0.75">0.75x</option>
            <option value="1" selected>1x</option>
            <option value="1.25">1.25x</option>
            <option value="1.5">1.5x</option>
        </select>
    </div>
    @endif
    
    <!-- Stop Button -->
    <button 
        @click="stop()"
        x-show="playing"
        class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-gray-200 hover:bg-gray-300 text-gray-700 transition">
        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
            <path d="M5.25 3A2.25 2.25 0 003 5.25v9.5A2.25 2.25 0 005.25 17h9.5A2.25 2.25 0 0017 14.75v-9.5A2.25 2.25 0 0014.75 3h-9.5z" />
        </svg>
    </button>
    
    <!-- Error Message -->
    <template x-if="error">
        <div class="text-sm text-red-600" x-text="error"></div>
    </template>
</div>
