@props(['dialogue'])

<article class="border border-gray-200 rounded-lg p-4 sm:p-6 bg-white hover:border-indigo-200 transition-colors">
    <header class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-6">
        <div class="flex-1">
            <h3 class="text-lg sm:text-xl font-semibold text-gray-900 mb-1">{{ $dialogue->title }}</h3>
            <p class="text-xs sm:text-sm text-gray-500">Practice this conversation</p>
        </div>
        
        <!-- Play entire dialogue button -->
        @php
            $fullDialogue = collect($dialogue->content)
                ->map(fn($line) => ($line['speaker'] ?? 'Speaker') . ': ' . ($line['line'] ?? ''))
                ->join('. ');
        @endphp
        <div class="flex gap-2">
            <x-audio-button 
                :text="$fullDialogue" 
                lang="ja-JP" 
                size="md"
                variant="primary"
                :ariaLabel="'Play entire dialogue: ' . $dialogue->title">
                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span class="hidden sm:inline">Play All</span>
                <span class="sm:hidden">Play</span>
            </x-audio-button>
        </div>
    </header>
    
    <div class="space-y-4" role="list" aria-label="Dialogue lines">
        @foreach($dialogue->content as $index => $line)
            <div class="flex flex-col sm:flex-row sm:items-start gap-2 sm:gap-4 p-3 rounded-lg bg-gray-50 hover:bg-indigo-50 transition-colors" role="listitem">
                <div class="flex items-center justify-between sm:block">
                    <div class="flex-shrink-0 font-medium text-indigo-600 text-sm" aria-label="Speaker">
                        {{ $line['speaker'] ?? 'Speaker' }}
                    </div>
                    <div class="sm:hidden">
                        <x-audio-button 
                            :text="$line['line'] ?? ''" 
                            lang="ja-JP" 
                            size="sm"
                            :ariaLabel="'Play line ' . ($index + 1) . ' by ' . ($line['speaker'] ?? 'Speaker')">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM9.555 7.168A1 1 0 008 8v4a1 1 0 001.555.832l3-2a1 1 0 000-1.664l-3-2z" clip-rule="evenodd" />
                            </svg>
                        </x-audio-button>
                    </div>
                </div>
                <div class="flex-1 min-w-0">
                    <!-- Japanese text prominently displayed -->
                    <p class="text-japanese-lg font-medium text-gray-900 mb-1" lang="ja">{{ $line['line'] ?? '' }}</p>
                    
                    <!-- Romaji (if available) -->
                    @if(isset($line['romaji']))
                        <p class="text-sm text-gray-500 mb-1 font-mono">{{ $line['romaji'] }}</p>
                    @endif
                    
                    <!-- English translation -->
                    @if(isset($line['translation']))
                        <p class="text-sm text-gray-600 italic">{{ $line['translation'] }}</p>
                    @endif
                </div>
                <div class="hidden sm:flex flex-shrink-0">
                    <x-audio-button 
                        :text="$line['line'] ?? ''" 
                        lang="ja-JP" 
                        size="sm"
                        :ariaLabel="'Play line ' . ($index + 1) . ' by ' . ($line['speaker'] ?? 'Speaker')">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM9.555 7.168A1 1 0 008 8v4a1 1 0 001.555.832l3-2a1 1 0 000-1.664l-3-2z" clip-rule="evenodd" />
                        </svg>
                    </x-audio-button>
                </div>
            </div>
        @endforeach
    </div>
    
    <!-- Practice Speaking Button -->
    <div class="mt-6 pt-4 border-t border-gray-200">
        <button 
            class="w-full sm:w-auto inline-flex items-center justify-center px-6 py-3 bg-gradient-to-r from-indigo-600 to-indigo-700 border border-transparent rounded-lg font-semibold text-sm text-white hover:from-indigo-700 hover:to-indigo-800 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition-all duration-150 shadow-sm hover:shadow-md active:scale-95 min-h-[44px] touch-manipulation">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11a7 7 0 01-7 7m0 0a7 7 0 01-7-7m7 7v4m0 0H8m4 0h4m-4-8a3 3 0 01-3-3V5a3 3 0 116 0v6a3 3 0 01-3 3z" />
            </svg>
            Practice Speaking This Dialogue
        </button>
    </div>
</article>
