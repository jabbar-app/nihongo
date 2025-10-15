@props([
    'show' => false,
    'type' => 'exercise', // exercise, dialogue, shadowing
    'xpGained' => 0,
    'score' => null,
    'encouragement' => null
])

@php
    // Japanese congratulatory expressions based on performance
    $japaneseExpressions = [
        'excellent' => [
            'text' => 'よくできました！',
            'romaji' => 'Yoku dekimashita!',
            'translation' => 'Well done!'
        ],
        'good' => [
            'text' => 'がんばりました！',
            'romaji' => 'Ganbarimashita!',
            'translation' => 'You did your best!'
        ],
        'keep_going' => [
            'text' => 'もう少し！',
            'romaji' => 'Mō sukoshi!',
            'translation' => 'A little more!'
        ]
    ];
    
    // Determine which expression to use based on score
    if ($score !== null) {
        if ($score >= 80) {
            $expression = $japaneseExpressions['excellent'];
            $performanceLevel = 'excellent';
        } elseif ($score >= 60) {
            $expression = $japaneseExpressions['good'];
            $performanceLevel = 'good';
        } else {
            $expression = $japaneseExpressions['keep_going'];
            $performanceLevel = 'keep_going';
        }
    } else {
        $expression = $japaneseExpressions['excellent'];
        $performanceLevel = 'excellent';
    }
    
    // Default encouragement messages
    if (!$encouragement) {
        $encouragementMessages = [
            'excellent' => 'Your pronunciation is getting better with each practice!',
            'good' => 'Keep practicing and you\'ll master this conversation!',
            'keep_going' => 'Every practice session brings you closer to fluency!'
        ];
        $encouragement = $encouragementMessages[$performanceLevel];
    }
@endphp

<div x-data="{ show: @js($show) }" 
     x-show="show"
     x-cloak
     @keydown.escape.window="show = false"
     class="fixed inset-0 z-50 overflow-y-auto"
     aria-labelledby="modal-title" 
     role="dialog" 
     aria-modal="true">
    
    <!-- Background overlay -->
    <div x-show="show"
         x-transition:enter="ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"
         @click="show = false"></div>

    <!-- Modal panel -->
    <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
        <div x-show="show"
             x-transition:enter="ease-out duration-300"
             x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
             x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
             x-transition:leave="ease-in duration-200"
             x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
             x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
             class="relative transform overflow-hidden rounded-lg bg-white px-4 pb-4 pt-5 text-center shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg sm:p-6">
            
            <!-- Celebration Animation Container -->
            <div class="absolute inset-0 pointer-events-none overflow-hidden">
                <!-- Confetti effect using CSS -->
                <div class="confetti-container">
                    @for ($i = 0; $i < 20; $i++)
                        <div class="confetti" style="left: {{ rand(0, 100) }}%; animation-delay: {{ rand(0, 1000) }}ms; animation-duration: {{ rand(2000, 4000) }}ms;"></div>
                    @endfor
                </div>
            </div>
            
            <div class="relative">
                <!-- Success Icon -->
                <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-full mb-4"
                     :class="{
                         'bg-green-100': '{{ $performanceLevel }}' === 'excellent',
                         'bg-yellow-100': '{{ $performanceLevel }}' === 'good',
                         'bg-blue-100': '{{ $performanceLevel }}' === 'keep_going'
                     }">
                    <svg class="h-10 w-10"
                         :class="{
                             'text-green-600': '{{ $performanceLevel }}' === 'excellent',
                             'text-yellow-600': '{{ $performanceLevel }}' === 'good',
                             'text-blue-600': '{{ $performanceLevel }}' === 'keep_going'
                         }"
                         fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>

                <!-- Japanese Expression -->
                <div class="mb-4">
                    <h3 class="text-3xl font-bold text-gray-900 mb-1" lang="ja">
                        {{ $expression['text'] }}
                    </h3>
                    <p class="text-sm text-gray-500 font-mono mb-1">{{ $expression['romaji'] }}</p>
                    <p class="text-base text-gray-600 italic">{{ $expression['translation'] }}</p>
                </div>

                <!-- Completion Message -->
                <div class="mb-6">
                    <p class="text-lg font-semibold text-gray-900 mb-2">
                        You completed the {{ $type }}!
                    </p>
                    <p class="text-sm text-gray-600">
                        {{ $encouragement }}
                    </p>
                </div>

                <!-- Stats Display -->
                <div class="flex items-center justify-center gap-6 mb-6">
                    @if ($xpGained > 0)
                        <div class="flex items-center gap-2">
                            <div class="flex items-center justify-center w-10 h-10 rounded-full bg-indigo-100">
                                <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                                </svg>
                            </div>
                            <div class="text-left">
                                <div class="text-2xl font-bold text-indigo-600">+{{ $xpGained }}</div>
                                <div class="text-xs text-gray-500">XP Earned</div>
                            </div>
                        </div>
                    @endif

                    @if ($score !== null)
                        <div class="flex items-center gap-2">
                            <div class="flex items-center justify-center w-10 h-10 rounded-full"
                                 :class="{
                                     'bg-green-100': {{ $score }} >= 80,
                                     'bg-yellow-100': {{ $score }} >= 60 && {{ $score }} < 80,
                                     'bg-blue-100': {{ $score }} < 60
                                 }">
                                <svg class="w-5 h-5"
                                     :class="{
                                         'text-green-600': {{ $score }} >= 80,
                                         'text-yellow-600': {{ $score }} >= 60 && {{ $score }} < 80,
                                         'text-blue-600': {{ $score }} < 60
                                     }"
                                     fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" />
                                </svg>
                            </div>
                            <div class="text-left">
                                <div class="text-2xl font-bold"
                                     :class="{
                                         'text-green-600': {{ $score }} >= 80,
                                         'text-yellow-600': {{ $score }} >= 60 && {{ $score }} < 80,
                                         'text-blue-600': {{ $score }} < 60
                                     }">
                                    {{ $score }}%
                                </div>
                                <div class="text-xs text-gray-500">Score</div>
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Action Buttons -->
                <div class="flex flex-col sm:flex-row gap-3 justify-center">
                    {{ $slot }}
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    .confetti-container {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        overflow: hidden;
    }

    .confetti {
        position: absolute;
        width: 10px;
        height: 10px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        animation: confetti-fall linear infinite;
        opacity: 0;
    }

    .confetti:nth-child(2n) {
        background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
    }

    .confetti:nth-child(3n) {
        background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
    }

    .confetti:nth-child(4n) {
        background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);
    }

    @keyframes confetti-fall {
        0% {
            top: -10%;
            opacity: 1;
            transform: translateX(0) rotateZ(0deg);
        }
        100% {
            top: 110%;
            opacity: 0;
            transform: translateX(100px) rotateZ(720deg);
        }
    }
</style>
@endpush
