<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Flashcard Review') }}
        </h2>
    </x-slot>

    <div class="py-4 sm:py-12">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div x-data="flashcardReview({{ json_encode($sessionCards) }}, {{ json_encode($sessionData) }})" 
                 x-init="init()"
                 @keydown.window="handleKeyPress($event)"
                 class="space-y-4 sm:space-y-6">
                
                <!-- Progress Bar -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <div class="flex justify-between items-center mb-2">
                        <span class="text-sm font-medium text-gray-700">Progress</span>
                        <span class="text-sm font-medium text-gray-700" x-text="`${reviewed} / ${total}`"></span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2.5">
                        <div class="bg-blue-600 h-2.5 rounded-full progress-bar-fill" 
                             :style="`width: ${(reviewed / total) * 100}%`"></div>
                    </div>
                    
                    <!-- Playback Speed Control -->
                    <div class="mt-4 flex items-center justify-end gap-2">
                        <span class="text-sm text-gray-600">Playback Speed:</span>
                        <select x-model="playbackSpeed" 
                                class="text-sm border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            <option value="0.5">0.5x</option>
                            <option value="0.75">0.75x</option>
                            <option value="1">1x</option>
                            <option value="1.25">1.25x</option>
                            <option value="1.5">1.5x</option>
                        </select>
                    </div>
                </div>

                <!-- Flashcard Display -->
                <div x-show="!sessionComplete" class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-4 sm:p-8">
                        <div class="relative">
					<!-- Card Container with Flip Animation -->
                            <div class="perspective-1000">
                                <div class="relative min-h-[260px] sm:min-h-[360px]">
                                    <div class="absolute inset-0 flashcard-container"
								 :class="{ 'rotate-y-180': showAnswer }">
                                    
							<!-- Front Side (Japanese) -->
							<div class="absolute inset-0 backface-hidden" :aria-hidden="showAnswer">
                                        <div class="text-center py-8 sm:py-16">
                                            <div class="text-3xl sm:text-5xl font-bold text-gray-900 mb-4 px-4" x-text="currentCard?.front"></div>
                                            <div class="text-xl sm:text-2xl text-gray-600 px-4" x-text="currentCard?.romaji"></div>
                                            
                                            <!-- Audio Play Button for Japanese -->
                                            <div class="mt-6">
                                                <button @click="playAudio(currentCard?.front, 'ja-JP')" 
                                                        :disabled="audioPlaying"
                                                        aria-label="Play Japanese audio"
                                                        class="inline-flex items-center px-6 py-3 bg-gray-100 hover:bg-gray-200 active:bg-gray-300 text-gray-700 rounded-lg transition disabled:opacity-50 disabled:cursor-not-allowed min-h-[44px] touch-manipulation">
                                                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                                        <path d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zM7 8a1 1 0 012 0v4a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v4a1 1 0 102 0V8a1 1 0 00-1-1z" />
                                                    </svg>
                                                    <span x-text="audioPlaying ? 'Playing...' : 'Play Audio'"></span>
                                                </button>
                                            </div>
                                        </div>
                                        <div class="text-center">
                                            <button @click="flipCard()" 
                                                    aria-label="Show answer (press Space)"
                                                    class="w-full sm:w-auto px-8 py-4 bg-blue-600 text-white rounded-lg hover:bg-blue-700 active:bg-blue-800 transition text-lg font-semibold min-h-[44px] touch-manipulation">
                                                <span class="sm:hidden">Show Answer</span>
                                                <span class="hidden sm:inline">Show Answer <kbd class="ml-2 px-2 py-1 text-xs bg-blue-700 rounded">Space</kbd></span>
                                            </button>
                                        </div>
                                    </div>

                                    <!-- Back Side (English) -->
                                    <div class="absolute inset-0 backface-hidden rotate-y-180" :aria-hidden="!showAnswer">
                                        <div class="text-center py-8 sm:py-16">
                                            <div class="text-2xl sm:text-3xl text-gray-600 mb-4 px-4" x-text="currentCard?.front"></div>
                                            <div class="text-lg sm:text-xl text-gray-500 mb-6 px-4" x-text="currentCard?.romaji"></div>
                                            <div class="text-3xl sm:text-4xl font-bold text-gray-900 px-4" x-text="currentCard?.back"></div>
                                            
                                            <!-- Audio Play Buttons -->
                                            <div class="mt-6 flex flex-col sm:flex-row justify-center gap-3 sm:gap-4 px-4">
                                                <button @click="playAudio(currentCard?.front, 'ja-JP')" 
                                                        :disabled="audioPlaying"
                                                        aria-label="Play Japanese audio"
                                                        class="inline-flex items-center justify-center px-4 py-3 bg-gray-100 hover:bg-gray-200 active:bg-gray-300 text-gray-700 rounded-lg transition disabled:opacity-50 disabled:cursor-not-allowed min-h-[44px] touch-manipulation">
                                                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                                        <path d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zM7 8a1 1 0 012 0v4a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v4a1 1 0 102 0V8a1 1 0 00-1-1z" />
                                                    </svg>
                                                    <span>Play Japanese</span>
                                                </button>
                                                <button @click="playAudio(currentCard?.back, 'en-US')" 
                                                        :disabled="audioPlaying"
                                                        aria-label="Play English audio"
                                                        class="inline-flex items-center justify-center px-4 py-3 bg-gray-100 hover:bg-gray-200 active:bg-gray-300 text-gray-700 rounded-lg transition disabled:opacity-50 disabled:cursor-not-allowed min-h-[44px] touch-manipulation">
                                                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                                        <path d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zM7 8a1 1 0 012 0v4a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v4a1 1 0 102 0V8a1 1 0 00-1-1z" />
                                                    </svg>
                                                    <span>Play English</span>
                                                </button>
                                            </div>
                                        </div>
                                        
                                        
							</div>
						</div>
                                </div>
                            </div>
                        
                        <!-- Rating / Actions Bar -->
                        <div x-show="showAnswer">
                            <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 sm:gap-4">
                                <button @click="rateCard(1)" 
                                        aria-label="Rate as Again (press 1)"
                                        class="rating-button px-4 py-4 bg-red-600 text-white rounded-lg hover:bg-red-700 active:bg-red-800 flex flex-col items-center justify-center min-h-[60px] touch-manipulation">
                                    <span class="font-bold text-base sm:text-lg">Again</span>
                                    <span class="text-xs sm:text-sm opacity-75 hidden sm:inline"><kbd class="bg-red-700 px-1.5 py-0.5 rounded">1</kbd></span>
                                </button>
                                <button @click="rateCard(2)" 
                                        aria-label="Rate as Hard (press 2)"
                                        class="rating-button px-4 py-4 bg-orange-600 text-white rounded-lg hover:bg-orange-700 active:bg-orange-800 flex flex-col items-center justify-center min-h-[60px] touch-manipulation">
                                    <span class="font-bold text-base sm:text-lg">Hard</span>
                                    <span class="text-xs sm:text-sm opacity-75 hidden sm:inline"><kbd class="bg-orange-700 px-1.5 py-0.5 rounded">2</kbd></span>
                                </button>
                                <button @click="rateCard(3)" 
                                        aria-label="Rate as Good (press 3)"
                                        class="rating-button px-4 py-4 bg-green-600 text-white rounded-lg hover:bg-green-700 active:bg-green-800 flex flex-col items-center justify-center min-h-[60px] touch-manipulation">
                                    <span class="font-bold text-base sm:text-lg">Good</span>
                                    <span class="text-xs sm:text-sm opacity-75 hidden sm:inline"><kbd class="bg-green-700 px-1.5 py-0.5 rounded">3</kbd></span>
                                </button>
                                <button @click="rateCard(4)" 
                                        aria-label="Rate as Easy (press 4)"
                                        class="rating-button px-4 py-4 bg-blue-600 text-white rounded-lg hover:bg-blue-700 active:bg-blue-800 flex flex-col items-center justify-center min-h-[60px] touch-manipulation">
                                    <span class="font-bold text-base sm:text-lg">Easy</span>
                                    <span class="text-xs sm:text-sm opacity-75 hidden sm:inline"><kbd class="bg-blue-700 px-1.5 py-0.5 rounded">4</kbd></span>
                                </button>
                            </div>
                            
                            <div class="mt-4 flex items-center justify-between">
                                <div class="text-xs sm:text-sm text-gray-500">Keys: Space = Flip, 1–4 = Rate, Enter = Good, N = Next (Good)</div>
                                <div class="flex items-center gap-3">
                                    <label class="inline-flex items-center text-sm text-gray-600 select-none">
                                        <input type="checkbox" x-model="autoAdvance" class="mr-2 rounded border-gray-300 text-blue-600 focus:ring-blue-500"> Auto-advance
                                    </label>
                                    <button @click="nextGood()" class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 active:bg-indigo-800 min-h-[44px] touch-manipulation">
                                        Next (Good)
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    </div>
                </div>

                <!-- Session Complete -->
                <div x-show="sessionComplete" class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-8 text-center">
                        <div class="text-6xl mb-4">🎉</div>
                        <h3 class="text-3xl font-bold text-gray-900 mb-4">Session Complete!</h3>
                        
                        <div class="grid grid-cols-3 gap-6 my-8 max-w-2xl mx-auto">
                            <div class="bg-gray-50 rounded-lg p-4">
                                <div class="text-3xl font-bold text-blue-600" x-text="reviewed"></div>
                                <div class="text-sm text-gray-600">Cards Reviewed</div>
                            </div>
                            <div class="bg-gray-50 rounded-lg p-4">
                                <div class="text-3xl font-bold text-green-600" x-text="`${accuracy}%`"></div>
                                <div class="text-sm text-gray-600">Accuracy</div>
                            </div>
                            <div class="bg-gray-50 rounded-lg p-4">
                                <div class="text-3xl font-bold text-purple-600" x-text="formatTime(timeSpent)"></div>
                                <div class="text-sm text-gray-600">Time Spent</div>
                            </div>
                        </div>

                        <div class="space-x-4">
                            <a href="{{ route('flashcards.review') }}" 
                               class="inline-block px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                                Review Again
                            </a>
                            <a href="{{ route('flashcards.index') }}" 
                               class="inline-block px-6 py-3 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition">
                                Back to Flashcards
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .perspective-1000 {
            perspective: 1000px;
        }
        .transform-style-3d {
            transform-style: preserve-3d;
        }
        .backface-hidden { backface-visibility: hidden; }
        .rotate-y-180 { transform: rotateY(180deg); }
        .flashcard-container { transition: transform 0.6s cubic-bezier(0.4, 0.0, 0.2, 1); transform-style: preserve-3d; }
        .rotate-y-180 {
            transform: rotateY(180deg);
        }
        
        /* Enhanced card flip animation */
        .flashcard-container {
            transition: transform 0.6s cubic-bezier(0.4, 0.0, 0.2, 1);
        }
        
        /* Smooth progress bar animation */
        .progress-bar-fill {
            transition: width 0.5s cubic-bezier(0.4, 0.0, 0.2, 1);
        }
        
        /* Button hover effects */
        .rating-button {
            transition: all 0.2s ease-in-out;
        }
        
        .rating-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }
        
        .rating-button:active {
            transform: translateY(0);
        }
    </style>

    <script>
        function flashcardReview(cards, sessionData) {
            return {
                cards: cards,
                currentIndex: 0,
                currentCard: null,
                showAnswer: false,
                reviewed: sessionData.reviewed,
                total: sessionData.total,
                correct: sessionData.correct,
                startTime: sessionData.start_time,
                cardStartTime: null,
                sessionComplete: false,
                timeSpent: 0,
                accuracy: 0,
                playbackSpeed: 1,
                audioPlaying: false,
                speechSynthesis: null,
                currentUtterance: null,
                autoAdvance: true,

                init() {
                    console.log('[Review] init');
                    console.log('[Review] received cards:', this.cards);
                    console.log('[Review] sessionData:', {
                        reviewed: this.reviewed,
                        total: this.total,
                        correct: this.correct,
                        startTime: this.startTime
                    });
                    window.debugFlashcards = { cards: this.cards, sessionData };
                    this.loadCard();
                    this.initSpeechSynthesis();
                },

                initSpeechSynthesis() {
                    if ('speechSynthesis' in window) {
                        this.speechSynthesis = window.speechSynthesis;
                    } else {
                        console.warn('Speech Synthesis API not supported in this browser');
                    }
                },

                playAudio(text, lang) {
                    if (!this.speechSynthesis) {
                        alert('Audio playback is not supported in your browser');
                        return;
                    }

                    // Cancel any ongoing speech
                    this.speechSynthesis.cancel();

                    // Create utterance
                    this.currentUtterance = new SpeechSynthesisUtterance(text);
                    this.currentUtterance.lang = lang;
                    this.currentUtterance.rate = parseFloat(this.playbackSpeed);

                    // Set up event handlers
                    this.currentUtterance.onstart = () => {
                        this.audioPlaying = true;
                    };

                    this.currentUtterance.onend = () => {
                        this.audioPlaying = false;
                    };

                    this.currentUtterance.onerror = (event) => {
                        console.error('Speech synthesis error:', event);
                        this.audioPlaying = false;
                        
                        // Show user-friendly error message
                        if (event.error === 'not-allowed') {
                            alert('Audio playback was blocked. Please check your browser permissions.');
                        } else if (event.error === 'network') {
                            alert('Network error occurred while loading audio.');
                        } else {
                            alert('An error occurred while playing audio. Please try again.');
                        }
                    };

                    // Speak the text
                    try {
                        this.speechSynthesis.speak(this.currentUtterance);
                    } catch (error) {
                        console.error('Error playing audio:', error);
                        this.audioPlaying = false;
                        alert('Failed to play audio. Please try again.');
                    }
                },

                stopAudio() {
                    if (this.speechSynthesis) {
                        this.speechSynthesis.cancel();
                        this.audioPlaying = false;
                    }
                },

                loadCard() {
                    // Stop any playing audio when loading a new card
                    this.stopAudio();
                    
                    if (this.currentIndex < this.cards.length) {
                        this.currentCard = this.cards[this.currentIndex];
                        console.log('[Review] loadCard index:', this.currentIndex, 'card:', this.currentCard);
                        this.showAnswer = false;
                        this.cardStartTime = Date.now();
                    } else {
                        console.log('[Review] completeSession reached');
                        this.completeSession();
                    }
                },

                flipCard() {
                    console.log('[Review] flipCard from showAnswer=', this.showAnswer);
                    this.showAnswer = true;
                },

                nextGood() {
                    console.log('[Review] nextGood clicked. showAnswer=', this.showAnswer);
                    if (!this.showAnswer) {
                        this.flipCard();
                        return;
                    }
                    console.log('[Review] nextGood -> rateCard(3) with currentCard:', this.currentCard);
                    this.rateCard(3);
                },

                async rateCard(rating) {
                    if (!this.showAnswer) {
                        console.warn('[Review] rateCard ignored because answer not shown yet');
                        return;
                    }
                    console.log('[Review] rateCard called with rating:', rating, 'card:', this.currentCard);

                    const duration = Math.floor((Date.now() - this.cardStartTime) / 1000);
                    
                    // Track accuracy (ratings 3 and 4 are considered correct)
                    if (rating >= 3) {
                        this.correct++;
                    }

                    try {
                        const response = await fetch('{{ route('flashcards.rate') }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                            },
                            body: JSON.stringify({
                                flashcard_id: this.currentCard.id,
                                rating: rating,
                                duration: duration
                            })
                        });

                        if (response.ok) {
                            console.log('[Review] rate success');
                            this.reviewed++;
                            this.currentIndex++;
                            
                            // Auto-advance to next card after a short delay
                            const delay = this.autoAdvance ? 250 : 0;
                            setTimeout(() => {
                                this.loadCard();
                            }, delay);
                        } else {
                            const text = await response.text().catch(() => '');
                            console.error('[Review] rate failed', response.status, text);
                        }
                    } catch (error) {
                        console.error('Error recording review:', error);
                        alert('Failed to record review. Please try again.');
                    }
                },

                completeSession() {
                    this.sessionComplete = true;
                    this.timeSpent = Math.floor((Date.now() / 1000) - this.startTime);
                    this.accuracy = this.reviewed > 0 ? Math.round((this.correct / this.reviewed) * 100) : 0;
                },

                formatTime(seconds) {
                    const mins = Math.floor(seconds / 60);
                    const secs = seconds % 60;
                    return `${mins}:${secs.toString().padStart(2, '0')}`;
                },

                handleKeyPress(event) {
                    // Ignore if typing in an input
                    if (event.target.tagName === 'INPUT' || event.target.tagName === 'TEXTAREA') {
                        return;
                    }

                    if (this.sessionComplete) return;

                    // Space to flip card
                    if (event.code === 'Space' && !this.showAnswer) {
                        event.preventDefault();
                        this.flipCard();
                    }

                    // Number keys 1-4 to rate
                    if (this.showAnswer) {
                        const key = event.key;
                        if (key >= '1' && key <= '4') {
                            event.preventDefault();
                            this.rateCard(parseInt(key));
                        }
                        if (event.key === 'Enter') {
                            event.preventDefault();
                            this.rateCard(3);
                        }
                    }

                    // N for Next (Good)
                    if (event.key.toLowerCase() === 'n') {
                        event.preventDefault();
                        this.nextGood();
                    }
                }
            }
        }
    </script>
</x-app-layout>
