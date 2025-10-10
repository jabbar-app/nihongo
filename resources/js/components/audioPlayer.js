/**
 * Alpine.js Audio Player Component
 * 
 * Provides text-to-speech functionality using the Web Speech API
 * with playback speed control and error handling.
 */

export default function audioPlayer() {
    return {
        playing: false,
        loading: false,
        error: null,
        playbackSpeed: 1,
        speechSynthesis: null,
        currentUtterance: null,

        init() {
            this.initSpeechSynthesis();
        },

        initSpeechSynthesis() {
            if ('speechSynthesis' in window) {
                this.speechSynthesis = window.speechSynthesis;
            } else {
                this.error = 'Speech synthesis not supported';
                console.warn('Speech Synthesis API not supported in this browser');
            }
        },

        /**
         * Play text using text-to-speech
         * @param {string} text - The text to speak
         * @param {string} lang - Language code (e.g., 'ja-JP', 'en-US')
         */
        play(text, lang = 'ja-JP') {
            if (!this.speechSynthesis) {
                this.error = 'Audio playback not supported';
                return;
            }

            // Cancel any ongoing speech
            this.stop();

            // Reset error state
            this.error = null;
            this.loading = true;

            // Create utterance
            this.currentUtterance = new SpeechSynthesisUtterance(text);
            this.currentUtterance.lang = lang;
            this.currentUtterance.rate = parseFloat(this.playbackSpeed);

            // Set up event handlers
            this.currentUtterance.onstart = () => {
                this.loading = false;
                this.playing = true;
            };

            this.currentUtterance.onend = () => {
                this.playing = false;
                this.loading = false;
            };

            this.currentUtterance.onerror = (event) => {
                console.error('Speech synthesis error:', event);
                this.playing = false;
                this.loading = false;
                
                // Set user-friendly error message
                switch (event.error) {
                    case 'not-allowed':
                        this.error = 'Audio playback blocked. Check browser permissions.';
                        break;
                    case 'network':
                        this.error = 'Network error occurred.';
                        break;
                    case 'synthesis-unavailable':
                        this.error = 'Speech synthesis unavailable.';
                        break;
                    case 'synthesis-failed':
                        this.error = 'Speech synthesis failed.';
                        break;
                    default:
                        this.error = 'Audio playback error.';
                }
            };

            // Speak the text
            try {
                this.speechSynthesis.speak(this.currentUtterance);
            } catch (error) {
                console.error('Error playing audio:', error);
                this.playing = false;
                this.loading = false;
                this.error = 'Failed to play audio.';
            }
        },

        /**
         * Stop current audio playback
         */
        stop() {
            if (this.speechSynthesis) {
                this.speechSynthesis.cancel();
                this.playing = false;
                this.loading = false;
            }
        },

        /**
         * Pause current audio playback
         */
        pause() {
            if (this.speechSynthesis && this.playing) {
                this.speechSynthesis.pause();
                this.playing = false;
            }
        },

        /**
         * Resume paused audio playback
         */
        resume() {
            if (this.speechSynthesis && !this.playing) {
                this.speechSynthesis.resume();
                this.playing = true;
            }
        },

        /**
         * Toggle play/pause
         */
        togglePlayPause(text, lang = 'ja-JP') {
            if (this.playing) {
                this.pause();
            } else if (this.currentUtterance) {
                this.resume();
            } else {
                this.play(text, lang);
            }
        },

        /**
         * Set playback speed
         * @param {number} speed - Speed multiplier (0.5 to 2.0)
         */
        setSpeed(speed) {
            this.playbackSpeed = speed;
            
            // If currently playing, restart with new speed
            if (this.playing && this.currentUtterance) {
                const text = this.currentUtterance.text;
                const lang = this.currentUtterance.lang;
                this.play(text, lang);
            }
        },

        /**
         * Check if speech synthesis is supported
         * @returns {boolean}
         */
        isSupported() {
            return this.speechSynthesis !== null;
        },

        /**
         * Get available voices for a language
         * @param {string} lang - Language code
         * @returns {Array}
         */
        getVoices(lang = null) {
            if (!this.speechSynthesis) return [];
            
            const voices = this.speechSynthesis.getVoices();
            
            if (lang) {
                return voices.filter(voice => voice.lang.startsWith(lang));
            }
            
            return voices;
        }
    };
}

// Make it available globally for Alpine.js
if (typeof window !== 'undefined') {
    window.audioPlayer = audioPlayer;
}
