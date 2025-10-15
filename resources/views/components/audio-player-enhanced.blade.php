@props([
    'audioUrl' => '',
    'title' => '',
    'showSpeedControl' => true,
    'showWaveform' => false,
    'compact' => false,
    'autoPlay' => false,
])

<div 
    x-data="audioPlayerEnhanced({
        audioUrl: '{{ $audioUrl }}',
        autoPlay: {{ $autoPlay ? 'true' : 'false' }}
    })" 
    x-init="init()"
    class="{{ $compact ? 'audio-player-compact' : 'audio-player-full' }} bg-white rounded-lg shadow-sm border border-gray-200 p-4"
    {{ $attributes }}>
    
    <!-- Title with State Indicator -->
    @if($title && !$compact)
    <div class="flex items-center justify-between mb-3">
        <div class="flex items-center gap-2">
            <svg class="w-5 h-5 text-indigo-600" fill="currentColor" viewBox="0 0 20 20">
                <path d="M18.3 5.71a.996.996 0 00-1.41 0L7 15.59 3.11 11.7A.996.996 0 101.7 13.11l4.59 4.59c.39.39 1.02.39 1.41 0l10.6-10.6c.39-.38.39-1.02 0-1.41z"/>
            </svg>
            <span class="text-sm font-medium text-gray-700">{{ $title }}</span>
        </div>
        
        <!-- State Indicator -->
        <div class="flex items-center gap-2">
            <template x-if="playing">
                <div class="flex items-center gap-1.5 text-xs text-green-600 font-medium">
                    <span class="relative flex h-2 w-2">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-2 w-2 bg-green-500"></span>
                    </span>
                    Playing
                </div>
            </template>
            <template x-if="!playing && !loading && duration > 0">
                <div class="flex items-center gap-1.5 text-xs text-gray-500 font-medium">
                    <span class="h-2 w-2 rounded-full bg-gray-400"></span>
                    Paused
                </div>
            </template>
        </div>
    </div>
    @endif

    <div class="flex items-center gap-4">
        <!-- Play/Pause Button (56px on mobile for better thumb reach) -->
        <button 
            @click="togglePlayPause()"
            :disabled="loading || error"
            class="flex-shrink-0 flex items-center justify-center rounded-full transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 touch-manipulation min-w-[56px] min-h-[56px]"
            :class="{
                'w-14 h-14 sm:w-16 sm:h-16': !{{ $compact ? 'true' : 'false' }},
                'w-14 h-14': {{ $compact ? 'true' : 'false' }},
                'bg-indigo-600 hover:bg-indigo-700 shadow-lg hover:shadow-xl': !loading && !error,
                'bg-gray-300 cursor-not-allowed': loading || error
            }"
            aria-label="Play/Pause">
            
            <!-- Loading State -->
            <template x-if="loading">
                <svg class="w-6 h-6 text-white animate-spin" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
            </template>
            
            <!-- Play Icon -->
            <template x-if="!playing && !loading">
                <svg class="w-6 h-6 text-white ml-0.5" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M6.3 2.841A1.5 1.5 0 004 4.11V15.89a1.5 1.5 0 002.3 1.269l9.344-5.89a1.5 1.5 0 000-2.538L6.3 2.84z" />
                </svg>
            </template>
            
            <!-- Pause Icon -->
            <template x-if="playing && !loading">
                <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M5.75 3a.75.75 0 00-.75.75v12.5c0 .414.336.75.75.75h1.5a.75.75 0 00.75-.75V3.75A.75.75 0 007.25 3h-1.5zM12.75 3a.75.75 0 00-.75.75v12.5c0 .414.336.75.75.75h1.5a.75.75 0 00.75-.75V3.75a.75.75 0 00-.75-.75h-1.5z" />
                </svg>
            </template>
        </button>

        <!-- Progress and Controls -->
        <div class="flex-1 min-w-0">
            <!-- Progress Bar -->
            <div class="relative">
                <!-- Waveform Visualization (if enabled) -->
                @if($showWaveform)
                <div x-show="!loading" class="mb-3">
                    <div class="relative h-16 bg-gray-50 rounded-lg overflow-hidden">
                        <!-- Waveform bars -->
                        <div class="absolute inset-0 flex items-center justify-between px-1" x-ref="waveform">
                            <template x-for="i in 60" :key="i">
                                <div 
                                    class="flex-1 mx-px rounded-full transition-all duration-200"
                                    :class="{
                                        'bg-indigo-600': (currentTime / duration) >= (i / 60),
                                        'bg-indigo-200': (currentTime / duration) < (i / 60)
                                    }"
                                    :style="`height: ${waveformHeights[i] || Math.random() * 80 + 20}%`">
                                </div>
                            </template>
                        </div>
                        
                        <!-- Playing indicator overlay -->
                        <div 
                            x-show="playing"
                            class="absolute inset-0 pointer-events-none"
                            :style="`background: linear-gradient(to right, rgba(79, 70, 229, 0.1) 0%, rgba(79, 70, 229, 0.1) ${(currentTime / duration * 100) || 0}%, transparent ${(currentTime / duration * 100) || 0}%)`">
                        </div>
                    </div>
                </div>
                @endif

                <!-- Seekable Progress Bar (Larger touch target on mobile) -->
                <div class="relative py-2 sm:py-0">
                    <input 
                        type="range" 
                        min="0" 
                        :max="duration || 100" 
                        x-model="currentTime"
                        @input="seek($event.target.value)"
                        :disabled="!duration || loading"
                        class="w-full h-2 sm:h-2 bg-gray-200 rounded-full appearance-none cursor-pointer disabled:cursor-not-allowed focus:outline-none focus:ring-2 focus:ring-indigo-500 touch-manipulation"
                        style="background: linear-gradient(to right, #4F46E5 0%, #4F46E5 var(--progress), #E5E7EB var(--progress), #E5E7EB 100%);"
                        :style="`--progress: ${(currentTime / duration * 100) || 0}%`"
                        aria-label="Audio progress">
                </div>

                <!-- Loading Skeleton -->
                <template x-if="loading">
                    <div class="space-y-3">
                        @if($showWaveform)
                        <div class="h-16 bg-gray-200 rounded-lg animate-pulse"></div>
                        @endif
                        <div class="h-2 bg-gray-200 rounded-full animate-pulse"></div>
                    </div>
                </template>
            </div>

            <!-- Time Display and Speed Control -->
            <div class="flex items-center justify-between mt-2">
                <!-- Timestamp -->
                <div class="text-xs text-gray-600 font-mono tabular-nums">
                    <span x-text="formatTime(currentTime)">0:00</span>
                    <span class="text-gray-400">/</span>
                    <span x-text="formatTime(duration)">0:00</span>
                </div>

                <!-- Speed Control (Touch-friendly on mobile) -->
                @if($showSpeedControl)
                <div class="flex items-center gap-2">
                    <template x-for="speed in [0.75, 1, 1.25]" :key="speed">
                        <button
                            @click="setSpeed(speed)"
                            :class="{
                                'bg-indigo-600 text-white': playbackSpeed === speed,
                                'bg-gray-100 text-gray-700 hover:bg-gray-200': playbackSpeed !== speed
                            }"
                            class="px-3 py-2 min-w-[44px] min-h-[44px] text-xs font-medium rounded transition-colors focus:outline-none focus:ring-2 focus:ring-indigo-500 touch-manipulation"
                            x-text="speed + 'x'">
                        </button>
                    </template>
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Error Message -->
    <template x-if="error">
        <div class="mt-3 p-3 bg-red-50 border border-red-200 rounded-lg">
            <div class="flex items-start gap-2">
                <svg class="w-5 h-5 text-red-600 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.28 7.22a.75.75 0 00-1.06 1.06L8.94 10l-1.72 1.72a.75.75 0 101.06 1.06L10 11.06l1.72 1.72a.75.75 0 101.06-1.06L11.06 10l1.72-1.72a.75.75 0 00-1.06-1.06L10 8.94 8.28 7.22z" clip-rule="evenodd" />
                </svg>
                <div>
                    <p class="text-sm font-medium text-red-800">Audio Error</p>
                    <p class="text-sm text-red-600 mt-1" x-text="error"></p>
                </div>
            </div>
        </div>
    </template>

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
