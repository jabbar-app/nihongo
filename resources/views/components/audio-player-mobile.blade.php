@props([
    'audioUrl' => '',
    'title' => '',
    'nextUrl' => null,
    'prevUrl' => null,
])

<div 
    x-data="mobileAudioPlayer({
        audioUrl: '{{ $audioUrl }}',
        nextUrl: {{ $nextUrl ? "'" . $nextUrl . "'" : 'null' }},
        prevUrl: {{ $prevUrl ? "'" . $prevUrl . "'" : 'null' }},
        autoPlay: false
    })" 
    x-init="init()"
    class="audio-player-floating sm:hidden"
    x-show="isVisible"
    x-transition:enter="transition ease-out duration-300"
    x-transition:enter-start="transform translate-y-full"
    x-transition:enter-end="transform translate-y-0"
    x-transition:leave="transition ease-in duration-200"
    x-transition:leave-start="transform translate-y-0"
    x-transition:leave-end="transform translate-y-full"
    {{ $attributes }}
    @touchstart="handleTouchStart($event)"
    @touchmove="handleTouchMove($event)"
    @touchend="handleTouchEnd($event)">
    
    <div class="max-w-screen-xl mx-auto px-4 py-3" style="padding-bottom: calc(0.75rem + env(safe-area-inset-bottom));">
        <!-- Drag Handle -->
        <div class="flex justify-center mb-2">
            <div class="w-12 h-1 bg-gray-300 rounded-full"></div>
        </div>

        <div class="flex items-center gap-3">
            <!-- Previous Button (Touch-friendly 56px) -->
            <button 
                @click="playPrevious()"
                :disabled="!prevUrl || loading"
                class="flex-shrink-0 flex items-center justify-center w-11 h-11 rounded-full transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-indigo-500 touch-manipulation"
                :class="{
                    'bg-gray-100 hover:bg-gray-200 active:bg-gray-300': prevUrl && !loading,
                    'bg-gray-100 opacity-40 cursor-not-allowed': !prevUrl || loading
                }"
                aria-label="Previous">
                <svg class="w-5 h-5 text-gray-700" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M8.445 14.832A1 1 0 0010 14v-2.798l5.445 3.63A1 1 0 0017 14V6a1 1 0 00-1.555-.832L10 8.798V6a1 1 0 00-1.555-.832l-6 4a1 1 0 000 1.664l6 4z" />
                </svg>
            </button>

            <!-- Large Play/Pause Button (56px for thumb-reach) -->
            <button 
                @click="togglePlayPause()"
                :disabled="loading || error"
                class="flex-shrink-0 flex items-center justify-center w-14 h-14 rounded-full transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 touch-manipulation shadow-lg"
                :class="{
                    'bg-indigo-600 hover:bg-indigo-700 active:bg-indigo-800': !loading && !error,
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

            <!-- Next Button (Touch-friendly 56px) -->
            <button 
                @click="playNext()"
                :disabled="!nextUrl || loading"
                class="flex-shrink-0 flex items-center justify-center w-11 h-11 rounded-full transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-indigo-500 touch-manipulation"
                :class="{
                    'bg-gray-100 hover:bg-gray-200 active:bg-gray-300': nextUrl && !loading,
                    'bg-gray-100 opacity-40 cursor-not-allowed': !nextUrl || loading
                }"
                aria-label="Next">
                <svg class="w-5 h-5 text-gray-700" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M4.555 5.168A1 1 0 003 6v8a1 1 0 001.555.832L10 11.202V14a1 1 0 001.555.832l6-4a1 1 0 000-1.664l-6-4A1 1 0 0010 6v2.798l-5.445-3.63z" />
                </svg>
            </button>

            <!-- Progress and Info -->
            <div class="flex-1 min-w-0">
                <!-- Title (truncated) -->
                @if($title)
                <div class="text-sm font-medium text-gray-900 truncate mb-1">
                    {{ $title }}
                </div>
                @endif

                <!-- Progress Bar with larger touch target -->
                <div class="relative py-2">
                    <input 
                        type="range" 
                        min="0" 
                        :max="duration || 100" 
                        x-model="currentTime"
                        @input="seek($event.target.value)"
                        :disabled="!duration || loading"
                        class="w-full h-2 bg-gray-200 rounded-full appearance-none cursor-pointer disabled:cursor-not-allowed focus:outline-none focus:ring-2 focus:ring-indigo-500 touch-manipulation"
                        style="background: linear-gradient(to right, #4F46E5 0%, #4F46E5 var(--progress), #E5E7EB var(--progress), #E5E7EB 100%);"
                        :style="`--progress: ${(currentTime / duration * 100) || 0}%`"
                        aria-label="Audio progress">
                </div>

                <!-- Timestamp and Speed -->
                <div class="flex items-center justify-between">
                    <div class="text-xs text-gray-600 font-mono tabular-nums">
                        <span x-text="formatTime(currentTime)">0:00</span>
                        <span class="text-gray-400 mx-0.5">/</span>
                        <span x-text="formatTime(duration)">0:00</span>
                    </div>

                    <!-- Speed Control -->
                    <button
                        @click="cycleSpeed()"
                        class="flex items-center justify-center px-3 py-1 rounded-full bg-gray-100 hover:bg-gray-200 active:bg-gray-300 transition-colors touch-manipulation focus:outline-none focus:ring-2 focus:ring-indigo-500"
                        :class="{ 'bg-indigo-100 text-indigo-700': playbackSpeed !== 1 }"
                        aria-label="Playback speed">
                        <span class="text-xs font-semibold" x-text="playbackSpeed + 'x'">1x</span>
                    </button>
                </div>
            </div>
        </div>

        <!-- Error Message -->
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

@push('scripts')
<script>
function mobileAudioPlayer(config) {
    return {
        audioUrl: config.audioUrl,
        nextUrl: config.nextUrl,
        prevUrl: config.prevUrl,
        playing: false,
        loading: true,
        error: null,
        currentTime: 0,
        duration: 0,
        playbackSpeed: 1,
        isVisible: true,
        touchStartX: 0,
        touchStartY: 0,
        touchEndX: 0,
        touchEndY: 0,

        init() {
            this.$refs.audio.src = this.audioUrl;
            this.$refs.audio.load();
        },

        togglePlayPause() {
            if (this.playing) {
                this.$refs.audio.pause();
                this.playing = false;
            } else {
                this.$refs.audio.play();
                this.playing = true;
            }
        },

        seek(time) {
            this.$refs.audio.currentTime = time;
        },

        cycleSpeed() {
            const speeds = [0.75, 1, 1.25, 1.5];
            const currentIndex = speeds.indexOf(this.playbackSpeed);
            const nextIndex = (currentIndex + 1) % speeds.length;
            this.playbackSpeed = speeds[nextIndex];
            this.$refs.audio.playbackRate = this.playbackSpeed;
        },

        playNext() {
            if (this.nextUrl) {
                window.location.href = this.nextUrl;
            }
        },

        playPrevious() {
            if (this.prevUrl) {
                window.location.href = this.prevUrl;
            }
        },

        handleTouchStart(e) {
            this.touchStartX = e.changedTouches[0].screenX;
            this.touchStartY = e.changedTouches[0].screenY;
        },

        handleTouchMove(e) {
            this.touchEndX = e.changedTouches[0].screenX;
            this.touchEndY = e.changedTouches[0].screenY;
        },

        handleTouchEnd(e) {
            const diffX = this.touchEndX - this.touchStartX;
            const diffY = this.touchEndY - this.touchStartY;
            
            // Horizontal swipe (must be more horizontal than vertical)
            if (Math.abs(diffX) > Math.abs(diffY) && Math.abs(diffX) > 50) {
                if (diffX > 0 && this.prevUrl) {
                    // Swipe right - previous
                    this.playPrevious();
                } else if (diffX < 0 && this.nextUrl) {
                    // Swipe left - next
                    this.playNext();
                }
            }
        },

        onLoadedMetadata() {
            this.duration = this.$refs.audio.duration;
            this.loading = false;
        },

        onTimeUpdate() {
            this.currentTime = this.$refs.audio.currentTime;
        },

        onEnded() {
            this.playing = false;
            this.currentTime = 0;
            if (this.nextUrl) {
                // Auto-play next if available
                setTimeout(() => this.playNext(), 1000);
            }
        },

        onError() {
            this.error = 'Failed to load audio. Please try again.';
            this.loading = false;
        },

        onCanPlay() {
            this.loading = false;
        },

        formatTime(seconds) {
            if (!seconds || isNaN(seconds)) return '0:00';
            const mins = Math.floor(seconds / 60);
            const secs = Math.floor(seconds % 60);
            return `${mins}:${secs.toString().padStart(2, '0')}`;
        }
    };
}
</script>
@endpush
