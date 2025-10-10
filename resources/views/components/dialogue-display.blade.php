@props(['dialogue'])

<div class="border border-gray-200 rounded-lg p-6 bg-white">
    <div class="flex items-center justify-between mb-4">
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
            variant="primary">
            Play All
        </x-audio-button>
    </div>
    
    <div class="space-y-3">
        @foreach($dialogue->content as $line)
            <div class="flex items-start gap-3">
                <div class="flex-shrink-0 w-24 font-medium text-indigo-600 text-sm">
                    {{ $line['speaker'] ?? 'Speaker' }}:
                </div>
                <div class="flex-1">
                    <p class="text-gray-900">{{ $line['line'] ?? '' }}</p>
                </div>
                <div class="flex-shrink-0">
                    <x-audio-button 
                        :text="$line['line'] ?? ''" 
                        lang="ja-JP" 
                        size="sm">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM9.555 7.168A1 1 0 008 8v4a1 1 0 001.555.832l3-2a1 1 0 000-1.664l-3-2z" clip-rule="evenodd" />
                        </svg>
                    </x-audio-button>
                </div>
            </div>
        @endforeach
    </div>
</div>
