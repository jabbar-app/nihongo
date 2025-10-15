/**
 * Alpine.js Enhanced Audio Player Component
 * 
 * Provides audio playback functionality for actual audio files
 * with progress bar, seek functionality, speed controls, and waveform visualization.
 */

export default function audioPlayerEnhanced(config = {}) {
    return {
        // Configuration
        audioUrl: config.audioUrl || '',
        autoPlay: config.autoPlay || false,

        // State
        playing: false,
        loading: true,
        error: null,
        currentTime: 0,
        duration: 0,
        playbackSpeed: 1,
        waveformHeights: [],
        
        // Audio element reference
        audio: null,

        /**
         * Initialize the audio player
         */
        init() {
            this.audio = this.$refs.audio;
            
            if (!this.audioUrl) {
                this.error = 'No audio URL provided';
                this.loading = false;
                return;
            }

            // Set the audio source
            this.audio.src = this.audioUrl;
            
            // Generate waveform visualization data
            this.generateWaveform();
            
            // Setup keyboard shortcuts
            this.setupKeyboardShortcuts();

            // Auto play if configured
            if (this.autoPlay) {
                this.play();
            }
        },

        /**
         * Generate waveform visualization data
         * Creates an array of random heights for visual representation
         */
        generateWaveform() {
            // Generate 60 bars with varying heights for visual interest
            this.waveformHeights = Array.from({ length: 60 }, () => {
                // Create a more natural waveform pattern
                return Math.random() * 60 + 40; // Heights between 40% and 100%
            });
            
            // Smooth the waveform by averaging adjacent values
            for (let i = 1; i < this.waveformHeights.length - 1; i++) {
                this.waveformHeights[i] = (
                    this.waveformHeights[i - 1] + 
                    this.waveformHeights[i] + 
                    this.waveformHeights[i + 1]
                ) / 3;
            }
        },

        /**
         * Setup keyboard shortcuts for audio control
         */
        setupKeyboardShortcuts() {
            document.addEventListener('keydown', (event) => {
                // Only handle shortcuts when not typing in input fields
                const activeElement = document.activeElement;
                const isTyping = activeElement && (
                    activeElement.tagName === 'INPUT' ||
                    activeElement.tagName === 'TEXTAREA' ||
                    activeElement.isContentEditable
                );
                
                if (isTyping) return;

                // Space bar to play/pause
                if (event.code === 'Space' && this.$el.contains(document.activeElement)) {
                    event.preventDefault();
                    this.togglePlayPause();
                }

                // Arrow keys to seek
                if (event.key === 'ArrowLeft') {
                    event.preventDefault();
                    this.seek(Math.max(0, this.currentTime - 5));
                } else if (event.key === 'ArrowRight') {
                    event.preventDefault();
                    this.seek(Math.min(this.duration, this.currentTime + 5));
                }
            });
        },

        /**
         * Toggle play/pause
         */
        togglePlayPause() {
            if (this.playing) {
                this.pause();
            } else {
                this.play();
            }
        },

        /**
         * Play audio
         */
        async play() {
            if (!this.audio || this.error) return;

            try {
                await this.audio.play();
                this.playing = true;
                this.error = null;
            } catch (err) {
                console.error('Error playing audio:', err);
                this.playing = false;
                
                // Set user-friendly error message
                if (err.name === 'NotAllowedError') {
                    this.error = 'Audio playback blocked. Please interact with the page first.';
                } else if (err.name === 'NotSupportedError') {
                    this.error = 'Audio format not supported by your browser.';
                } else {
                    this.error = 'Failed to play audio. Please try again.';
                }
            }
        },

        /**
         * Pause audio
         */
        pause() {
            if (!this.audio) return;
            
            this.audio.pause();
            this.playing = false;
        },

        /**
         * Seek to specific time
         * @param {number} time - Time in seconds
         */
        seek(time) {
            if (!this.audio || !this.duration) return;
            
            const seekTime = Math.max(0, Math.min(time, this.duration));
            this.audio.currentTime = seekTime;
            this.currentTime = seekTime;
        },

        /**
         * Set playback speed
         * @param {number} speed - Speed multiplier (0.75, 1, 1.25, etc.)
         */
        setSpeed(speed) {
            if (!this.audio) return;
            
            this.playbackSpeed = speed;
            this.audio.playbackRate = speed;
        },

        /**
         * Cycle through playback speeds (for mobile)
         * Cycles: 1x -> 0.75x -> 1.25x -> 1x
         */
        cycleSpeed() {
            const speeds = [1, 0.75, 1.25];
            const currentIndex = speeds.indexOf(this.playbackSpeed);
            const nextIndex = (currentIndex + 1) % speeds.length;
            this.setSpeed(speeds[nextIndex]);
        },

        /**
         * Format time in MM:SS format
         * @param {number} seconds - Time in seconds
         * @returns {string} Formatted time string
         */
        formatTime(seconds) {
            if (!seconds || isNaN(seconds)) return '0:00';
            
            const mins = Math.floor(seconds / 60);
            const secs = Math.floor(seconds % 60);
            return `${mins}:${secs.toString().padStart(2, '0')}`;
        },

        /**
         * Event handler: Audio metadata loaded
         */
        onLoadedMetadata() {
            this.duration = this.audio.duration;
            this.loading = false;
        },

        /**
         * Event handler: Audio time update
         */
        onTimeUpdate() {
            this.currentTime = this.audio.currentTime;
        },

        /**
         * Event handler: Audio ended
         */
        onEnded() {
            this.playing = false;
            this.currentTime = 0;
            this.audio.currentTime = 0;
        },

        /**
         * Event handler: Audio can play
         */
        onCanPlay() {
            this.loading = false;
        },

        /**
         * Event handler: Audio error
         */
        onError() {
            this.loading = false;
            this.playing = false;
            
            const error = this.audio.error;
            
            if (error) {
                switch (error.code) {
                    case error.MEDIA_ERR_ABORTED:
                        this.error = 'Audio playback was aborted.';
                        break;
                    case error.MEDIA_ERR_NETWORK:
                        this.error = 'Network error occurred while loading audio.';
                        break;
                    case error.MEDIA_ERR_DECODE:
                        this.error = 'Audio file is corrupted or in an unsupported format.';
                        break;
                    case error.MEDIA_ERR_SRC_NOT_SUPPORTED:
                        this.error = 'Audio format not supported by your browser.';
                        break;
                    default:
                        this.error = 'An error occurred while loading the audio.';
                }
            } else {
                this.error = 'Failed to load audio file.';
            }
            
            console.error('Audio error:', error);
        },

        /**
         * Cleanup when component is destroyed
         */
        destroy() {
            if (this.audio) {
                this.pause();
                this.audio.src = '';
            }
        }
    };
}

// Make it available globally for Alpine.js
if (typeof window !== 'undefined') {
    window.audioPlayerEnhanced = audioPlayerEnhanced;
}
