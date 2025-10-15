@props([
    'audioUrl' => '',
    'title' => '',
    'sticky' => true,
])

<div 
    x-data="audioPlayerEnhanced({
        audioUrl: '{{ $audioUrl }}',
        autoPlay: false
    })" 
    x-init="init()"
    class="audio-player-mini bg-white border-t border-gray-200 shadow-lg {{ $sticky ? 'sticky-audio' : '' }}"
    {{ $attributes }}>
    
    <div class="max-w-screen-xl mx-auto px-4 py-3">
        <div class="flex items-center gap-3">
            <!-- Compact Play/Pause Button (Touch-friendly 44px mobile, 56px on small screens) -->
            <button 
                @click="togglePlayPause()"
                :disabled="loading || error"
                class="flex-shrink-0 flex items-center justify-center w-11 h-11 sm:w-14 sm:h-14 rounded-full transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 touch-manipulation"
                :class="{
                    'bg-indigo-600 hover:bg-indigo-700 active:bg-indigo-800 shadow-md': !loading && !error,
                    'bg-gray-300 cursor-not-allowed': loading || error
                }"
                aria-label="Play/Pause">
                
                <!-- Loading State -->
                <template x-if="loading">
                    <svg class="w-5 h-5 text-white animate-spin" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                </template>
                
                <!-- Play Icon -->
                <template x-if="!playing && !loading">
                    <svg class="w-5 h-5 text-white ml-0.5" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M6.3 2.841A1.5 1.5 0 004 4.11V15.89a1.5 1.5 0 002.3 1.269l9.344-5.89a1.5 1.5 0 000-2.538L6.3 2.84z" />
                    </svg>
                </template>
                
                <!-- Pause Icon -->
                <template x-if="playing && !loading">
                    <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M5.75 3a.75.75 0 00-.75.75v12.5c0 .414.336.75.75.75h1.5a.75.75 0 00.75-.75V3.75A.75.75 0 007.25 3h-1.5zM12.75 3a.75.75 0 00-.75.75v12.5c0 .414.336.75.75.75h1.5a.75.75 0 00.75-.75V3.75a.75.75 0 00-.75-.75h-1.5z" />
                    </svg>
                </template>
            </button>

            <!-- Progress and Info -->
            <div class="flex-1 min-w-0">
                <!-- Title (truncated) -->
                @if($title)
                <div class="text-sm font-medium text-gray-900 truncate mb-1">
                    {{ $title }}
                </div>
                @endif

                <!-- Compact Progress Bar -->
                <div class="relative">
                    <input 
                        type="range" 
                        min="0" 
                        :max="duration || 100" 
                        x-model="currentTime"
                        @input="seek($event.target.value)"
                        :disabled="!duration || loading"
                        class="w-full h-1.5 bg-gray-200 rounded-full appearance-none cursor-pointer disabled:cursor-not-allowed focus:outline-none focus:ring-2 focus:ring-indigo-500 touch-manipulation"
                        style="background: linear-gradient(to right, #4F46E5 0%, #4F46E5 var(--progress), #E5E7EB var(--progress), #E5E7EB 100%);"
                        :style="`--progress: ${(currentTime / duration * 100) || 0}%`"
                        aria-label="Audio progress">
                </div>

                <!-- Timestamp -->
                <div class="flex items-center justify-between mt-1">
                    <div class="text-xs text-gray-600 font-mono tabular-nums">
                        <span x-text="formatTime(currentTime)">0:00</span>
                        <span class="text-gray-400 mx-0.5">/</span>
                        <span x-text="formatTime(duration)">0:00</span>
                    </div>

                    <!-- Playing indicator -->
                    <template x-if="playing">
                        <div class="flex items-center gap-1 text-xs text-green-600">
                            <span class="relative flex h-1.5 w-1.5">
                                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75"></span>
                                <span class="relative inline-flex rounded-full h-1.5 w-1.5 bg-green-500"></span>
                            </span>
                        </div>
                    </template>
                </div>
            </div>

            <!-- Speed Control (Touch-friendly 44px) -->
            <div class="flex-shrink-0">
                <button
                    @click="cycleSpeed()"
                    class="flex items-center justify-center w-11 h-11 min-w-[44px] min-h-[44px] rounded-full bg-gray-100 hover:bg-gray-200 active:bg-gray-300 transition-colors touch-manipulation focus:outline-none focus:ring-2 focus:ring-indigo-500"
                    :class="{ 'bg-indigo-100 text-indigo-700': playbackSpeed !== 1 }"
                    aria-label="Playback speed">
                    <span class="text-xs font-semibold" x-text="playbackSpeed + 'x'">1x</span>
                </button>
            </div>
        </div>

        <!-- Error Message (Compact) -->
        <template x-if="error">
            <div class="mt-2 p-2 bg-red-50 border border-red-200 rounded text-xs text-red-600 flex items-center gap-2">
                <svg class="w-4 h-4 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.28 7.22a.75.75 0 00-1.06 1.06L8.94 10l-1.72 1.72a.75.75 0 101.06 1.06L10 11.06l1.72 1.72a.75.75 0 101.06-1.06L11.06 10l1.72-1.72a.75.75 0 00-1.06-1.06L10 8.94 8.28 7.22z" clip-rule="evenodd" />
                </svg>
                <span x-text="error" class="flex-1"></span>
            </div>
        </template>
    </div>

    <!-- Hidden Audio Element -->
    <audio 
        x-ref="audio"
        @loadedmetadata="onLoadedMetadata()"
        @timeupdate="onTimeUpdate()"
        @ended="onEnded()"
        @error="onError()"
        @canplay="onCanPlay()"
        class="hidden"
        preload="metadata">
        <source :src="audioUrl" type="audio/mpeg">
        Your browser does not support the audio element.
    </audio>
</div>
