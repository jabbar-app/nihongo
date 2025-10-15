<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Achievements') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Breadcrumb -->
            <x-breadcrumb :items="[
                ['label' => 'Achievements']
            ]" />
            <!-- Achievement Summary -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-2xl font-bold text-gray-900">Your Achievements</h3>
                            <p class="text-gray-600 mt-1">
                                {{ $earnedCount }} of {{ $totalAchievements }} achievements unlocked
                            </p>
                        </div>
                        <div class="text-right">
                            <div class="text-4xl font-bold text-indigo-600">
                                {{ round(($earnedCount / max($totalAchievements, 1)) * 100) }}%
                            </div>
                            <div class="text-sm text-gray-600">Complete</div>
                        </div>
                    </div>
                    
                    <!-- Progress Bar -->
                    <div class="mt-4">
                        <div class="w-full bg-gray-200 rounded-full h-3">
                            <div class="bg-indigo-600 h-3 rounded-full transition-all duration-500" 
                                 style="width: {{ round(($earnedCount / max($totalAchievements, 1)) * 100) }}%">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Earned Achievements -->
            @if($earnedAchievements->count() > 0)
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <h3 class="text-xl font-bold text-gray-900 mb-4 flex items-center">
                        <svg class="w-6 h-6 text-yellow-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                        </svg>
                        Earned Achievements
                    </h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        @foreach($earnedAchievements as $achievement)
                        <div class="border-2 border-yellow-400 rounded-lg p-4 bg-gradient-to-br from-yellow-50 to-white hover:shadow-lg transition-shadow">
                            <div class="flex items-start">
                                <div class="text-4xl mr-3">{{ $achievement['icon'] }}</div>
                                <div class="flex-1">
                                    <h4 class="font-bold text-gray-900">{{ $achievement['name'] }}</h4>
                                    <p class="text-sm text-gray-600 mt-1">{{ $achievement['description'] }}</p>
                                    <div class="mt-2 flex items-center justify-between">
                                        <span class="text-xs text-indigo-600 font-semibold">
                                            +{{ $achievement['xp_reward'] }} XP
                                        </span>
                                        <span class="text-xs text-gray-500">
                                            Earned {{ $achievement['earned_at']->format('M d, Y') }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif

            <!-- Locked Achievements -->
            @if($lockedAchievements->count() > 0)
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-xl font-bold text-gray-900 mb-4 flex items-center">
                        <svg class="w-6 h-6 text-gray-400 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"/>
                        </svg>
                        Locked Achievements
                    </h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        @foreach($lockedAchievements as $achievement)
                        <div class="border-2 border-gray-200 rounded-lg p-4 bg-gray-50 hover:border-gray-300 transition-colors">
                            <div class="flex items-start">
                                <div class="text-4xl mr-3 opacity-40">{{ $achievement['icon'] }}</div>
                                <div class="flex-1">
                                    <h4 class="font-bold text-gray-700">{{ $achievement['name'] }}</h4>
                                    <p class="text-sm text-gray-600 mt-1">{{ $achievement['description'] }}</p>
                                    
                                    <!-- Progress Bar -->
                                    <div class="mt-3">
                                        <div class="flex items-center justify-between text-xs text-gray-600 mb-1">
                                            <span>Progress</span>
                                            <span>{{ $achievement['progress'] }} / {{ $achievement['requirement_value'] }}</span>
                                        </div>
                                        <div class="w-full bg-gray-200 rounded-full h-2">
                                            <div class="bg-indigo-600 h-2 rounded-full transition-all duration-500" 
                                                 style="width: {{ $achievement['progress_percentage'] }}%">
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="mt-2">
                                        <span class="text-xs text-indigo-600 font-semibold">
                                            +{{ $achievement['xp_reward'] }} XP
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif

            @if($earnedAchievements->count() === 0 && $lockedAchievements->count() === 0)
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-center">
                    <div class="text-6xl mb-4">🏆</div>
                    <h3 class="text-xl font-bold text-gray-900 mb-2">Your Speaking Journey Begins Here! 🌟</h3>
                    <p class="text-gray-600">Start conversations to unlock achievements and celebrate your progress!</p>
                </div>
            </div>
            @endif
        </div>
    </div>

    <!-- Achievement Unlock Modal -->
    <div x-data="achievementModal()" 
         x-show="showModal" 
         x-cloak
         @achievement-unlocked.window="handleAchievementUnlocked($event.detail)"
         class="fixed inset-0 z-50 overflow-y-auto"
         style="display: none;">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            <!-- Background overlay -->
            <div x-show="showModal"
                 x-transition:enter="ease-out duration-300"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="ease-in duration-200"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 class="fixed inset-0 transition-opacity bg-gray-500 bg-opacity-75"
                 @click="closeModal()">
            </div>

            <!-- Modal panel -->
            <div x-show="showModal"
                 x-transition:enter="ease-out duration-300"
                 x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave="ease-in duration-200"
                 x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 class="inline-block align-bottom bg-white rounded-lg px-4 pt-5 pb-4 text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full sm:p-6">
                
                <!-- Confetti effect -->
                <div class="absolute inset-0 pointer-events-none">
                    <div class="confetti">🎉</div>
                    <div class="confetti">⭐</div>
                    <div class="confetti">🏆</div>
                    <div class="confetti">✨</div>
                    <div class="confetti">🎊</div>
                </div>

                <div class="text-center">
                    <div class="text-6xl mb-4" x-text="achievement.icon"></div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-2">Achievement Unlocked!</h3>
                    <p class="text-xl font-semibold text-indigo-600 mb-2" x-text="achievement.name"></p>
                    <p class="text-gray-600 mb-4" x-text="achievement.description"></p>
                    <div class="inline-flex items-center px-4 py-2 bg-indigo-100 rounded-full">
                        <svg class="w-5 h-5 text-indigo-600 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                        </svg>
                        <span class="text-indigo-600 font-semibold" x-text="'+' + achievement.xp_reward + ' XP'"></span>
                    </div>
                </div>

                <div class="mt-6">
                    <button @click="closeModal()" 
                            type="button" 
                            class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-indigo-600 text-base font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:text-sm">
                        Awesome!
                    </button>
                </div>
            </div>
        </div>
    </div>

    <style>
        [x-cloak] { display: none !important; }
        
        .confetti {
            position: absolute;
            font-size: 2rem;
            animation: confetti-fall 3s ease-out forwards;
        }
        
        .confetti:nth-child(1) { left: 10%; animation-delay: 0s; }
        .confetti:nth-child(2) { left: 30%; animation-delay: 0.2s; }
        .confetti:nth-child(3) { left: 50%; animation-delay: 0.4s; }
        .confetti:nth-child(4) { left: 70%; animation-delay: 0.6s; }
        .confetti:nth-child(5) { left: 90%; animation-delay: 0.8s; }
        
        @keyframes confetti-fall {
            0% {
                top: -10%;
                opacity: 1;
                transform: rotate(0deg);
            }
            100% {
                top: 100%;
                opacity: 0;
                transform: rotate(720deg);
            }
        }
    </style>

    <script>
        function achievementModal() {
            return {
                showModal: false,
                achievement: {
                    name: '',
                    description: '',
                    icon: '',
                    xp_reward: 0
                },
                
                handleAchievementUnlocked(data) {
                    this.achievement = data;
                    this.showModal = true;
                },
                
                closeModal() {
                    this.showModal = false;
                }
            }
        }
    </script>
</x-app-layout>
