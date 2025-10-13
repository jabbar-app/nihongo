@props(['levelUpData'])

<div x-data="levelUpModal" 
     x-show="show" 
     x-cloak
     @level-up.window="handleLevelUp($event.detail)"
     role="dialog"
     aria-modal="true"
     aria-labelledby="level-up-title"
     aria-describedby="level-up-description"
     class="fixed inset-0 z-50 overflow-y-auto"
     style="display: none;">
    
    <!-- Backdrop -->
    <div x-show="show" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 bg-black bg-opacity-50 backdrop-blur-sm"
         @click="close()"
         aria-hidden="true">
    </div>

    <!-- Modal -->
    <div class="flex items-center justify-center min-h-screen p-4">
        <div x-show="show"
             x-transition:enter="transition ease-out duration-300 transform"
             x-transition:enter-start="opacity-0 scale-90"
             x-transition:enter-end="opacity-100 scale-100"
             x-transition:leave="transition ease-in duration-200 transform"
             x-transition:leave-start="opacity-100 scale-100"
             x-transition:leave-end="opacity-0 scale-90"
             class="relative bg-gradient-to-br from-purple-50 to-purple-100 rounded-2xl shadow-2xl max-w-md w-full p-8 border-4 border-purple-300"
             @click.stop
             @keydown.escape="close()">
            
            <!-- Confetti Canvas -->
            <canvas id="confetti-canvas" class="absolute inset-0 pointer-events-none" width="400" height="600" aria-hidden="true"></canvas>
            
            <!-- Close Button -->
            <button @click="close()" 
                    aria-label="Close level up celebration"
                    class="absolute top-4 right-4 text-purple-400 hover:text-purple-600 transition min-h-[44px] min-w-[44px] flex items-center justify-center">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>

            <!-- Content -->
            <div class="text-center relative z-10">
                <!-- Trophy Icon -->
                <div class="mb-6 bounce-in" aria-hidden="true">
                    <div class="inline-flex items-center justify-center w-24 h-24 bg-gradient-to-br from-yellow-400 to-yellow-600 rounded-full shadow-lg glow">
                        <svg class="w-16 h-16 text-white celebrate" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"></path>
                        </svg>
                    </div>
                </div>

                <!-- Level Up Text -->
                <h2 id="level-up-title" class="text-4xl font-bold text-purple-900 mb-2 slide-in-top">Level Up!</h2>
                <p id="level-up-description" class="text-lg text-purple-700 mb-6 fade-in">Congratulations on reaching</p>

                <!-- New Level -->
                <div class="bg-white rounded-xl p-6 mb-6 shadow-lg border-2 border-purple-200 slide-in-bottom">
                    <div class="text-6xl font-bold text-transparent bg-clip-text bg-gradient-to-r from-purple-600 to-pink-600 mb-2 celebrate" 
                         x-text="'Level ' + data.new_level"
                         role="status"
                         aria-live="polite">
                    </div>
                    <div class="text-sm text-gray-600">
                        <span x-text="data.xp_earned"></span> XP earned
                        <template x-if="data.bonus_xp > 0">
                            <span class="text-yellow-600 font-semibold bounce-in">
                                + <span x-text="data.bonus_xp"></span> Bonus XP! 🎉
                            </span>
                        </template>
                    </div>
                </div>

                <!-- Unlocked Features -->
                <template x-if="data.unlocked_features && data.unlocked_features.length > 0">
                    <div class="bg-purple-50 rounded-xl p-4 mb-6 border border-purple-200" role="region" aria-label="Unlocked features">
                        <h3 class="text-sm font-semibold text-purple-900 mb-3">🎁 Unlocked Features</h3>
                        <ul class="space-y-2" role="list">
                            <template x-for="feature in data.unlocked_features" :key="feature">
                                <li class="text-sm text-purple-700 flex items-center justify-center gap-2" role="listitem">
                                    <svg class="w-4 h-4 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    <span x-text="feature"></span>
                                </li>
                            </template>
                        </ul>
                    </div>
                </template>

                <!-- Continue Button -->
                <button @click="close()" 
                        aria-label="Close and continue learning"
                        class="w-full bg-gradient-to-r from-purple-600 to-pink-600 hover:from-purple-700 hover:to-pink-700 text-white font-bold py-3 px-6 rounded-xl transition transform hover:scale-105 shadow-lg min-h-[44px]">
                    Continue Learning
                </button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('levelUpModal', () => ({
        show: false,
        data: {
            old_level: 0,
            new_level: 0,
            xp_earned: 0,
            bonus_xp: 0,
            total_xp: 0,
            unlocked_features: []
        },
        confettiInterval: null,

        init() {
            // Check if there's level up data passed from server
            @if(isset($levelUpData) && $levelUpData)
                this.handleLevelUp(@json($levelUpData));
            @endif
        },

        handleLevelUp(levelUpData) {
            this.data = levelUpData;
            this.show = true;
            this.startConfetti();
            
            // Play a sound effect (optional)
            this.playLevelUpSound();
        },

        close() {
            this.show = false;
            this.stopConfetti();
        },

        startConfetti() {
            const canvas = document.getElementById('confetti-canvas');
            if (!canvas) return;
            
            const ctx = canvas.getContext('2d');
            const particles = [];
            const particleCount = 100;
            const colors = ['#9333ea', '#ec4899', '#f59e0b', '#10b981', '#3b82f6'];

            // Set canvas size
            canvas.width = canvas.offsetWidth;
            canvas.height = canvas.offsetHeight;

            // Create particles
            for (let i = 0; i < particleCount; i++) {
                particles.push({
                    x: Math.random() * canvas.width,
                    y: Math.random() * canvas.height - canvas.height,
                    r: Math.random() * 4 + 2,
                    d: Math.random() * particleCount,
                    color: colors[Math.floor(Math.random() * colors.length)],
                    tilt: Math.floor(Math.random() * 10) - 10,
                    tiltAngleIncremental: Math.random() * 0.07 + 0.05,
                    tiltAngle: 0
                });
            }

            const draw = () => {
                ctx.clearRect(0, 0, canvas.width, canvas.height);

                particles.forEach((p, i) => {
                    ctx.beginPath();
                    ctx.lineWidth = p.r / 2;
                    ctx.strokeStyle = p.color;
                    ctx.moveTo(p.x + p.tilt + p.r, p.y);
                    ctx.lineTo(p.x + p.tilt, p.y + p.tilt + p.r);
                    ctx.stroke();

                    // Update particle
                    p.tiltAngle += p.tiltAngleIncremental;
                    p.y += (Math.cos(p.d) + 3 + p.r / 2) / 2;
                    p.tilt = Math.sin(p.tiltAngle - i / 3) * 15;

                    if (p.y > canvas.height) {
                        particles[i] = {
                            x: Math.random() * canvas.width,
                            y: -10,
                            r: p.r,
                            d: p.d,
                            color: p.color,
                            tilt: p.tilt,
                            tiltAngleIncremental: p.tiltAngleIncremental,
                            tiltAngle: p.tiltAngle
                        };
                    }
                });
            };

            this.confettiInterval = setInterval(draw, 33);

            // Stop confetti after 5 seconds
            setTimeout(() => this.stopConfetti(), 5000);
        },

        stopConfetti() {
            if (this.confettiInterval) {
                clearInterval(this.confettiInterval);
                this.confettiInterval = null;
            }
        },

        playLevelUpSound() {
            // Create a simple beep sound using Web Audio API
            try {
                const audioContext = new (window.AudioContext || window.webkitAudioContext)();
                const oscillator = audioContext.createOscillator();
                const gainNode = audioContext.createGain();

                oscillator.connect(gainNode);
                gainNode.connect(audioContext.destination);

                oscillator.frequency.value = 800;
                oscillator.type = 'sine';

                gainNode.gain.setValueAtTime(0.3, audioContext.currentTime);
                gainNode.gain.exponentialRampToValueAtTime(0.01, audioContext.currentTime + 0.5);

                oscillator.start(audioContext.currentTime);
                oscillator.stop(audioContext.currentTime + 0.5);
            } catch (e) {
                // Silently fail if audio context is not supported
                console.log('Audio not supported');
            }
        }
    }));
});
</script>
@endpush
