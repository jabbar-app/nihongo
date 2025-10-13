@props(['dialogue'])

<article class="border border-gray-200 rounded-lg p-6 bg-white">
    <header class="flex items-center justify-between mb-4">
        <h3 class="text-lg font-semibold text-gray-900">{{ $dialogue->title }}</h3>
        
        <!-- Play entire dialogue button -->
        @php
            $fullDialogue = collect($dialogue->content)
                ->map(fn($line) => ($line['speaker'] ?? 'Speaker') . ': ' . ($line['line'] ?? ''))
                ->join('. ');
        @endphp
        <x-audio-button 
            :text="$fullDialogue" 
            lang="ja-JP" 
            size="sm"
            variant="primary"
            :ariaLabel="'Play entire dialogue: ' . $dialogue->title">
            Play All
        </x-audio-button>
    </header>
    
    <div class="space-y-3" role="list" aria-label="Dialogue lines">
        @foreach($dialogue->content as $index => $line)
            <div class="flex items-start gap-3" role="listitem">
                <div class="flex-shrink-0 w-24 font-medium text-indigo-600 text-sm" aria-label="Speaker">
                    {{ $line['speaker'] ?? 'Speaker' }}:
                </div>
                <div class="flex-1">
                    <p class="text-gray-900" lang="ja">{{ $line['line'] ?? '' }}</p>
                </div>
                <div class="flex-shrink-0">
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
</article>
