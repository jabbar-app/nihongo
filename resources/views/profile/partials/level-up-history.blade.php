@php
    $levelUps = Auth::user()->levelUps()->orderBy('created_at', 'desc')->limit(10)->get();
@endphp

<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('Level-Up History') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            {{ __('Your journey through the levels.') }}
        </p>
    </header>

    <div class="mt-6">
        @if($levelUps->isEmpty())
            <div class="text-center py-8 bg-gray-50 rounded-lg">
                <svg class="w-12 h-12 text-gray-400 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                </svg>
                <p class="text-gray-600">No level-ups yet. Keep learning to level up!</p>
            </div>
        @else
            <div class="space-y-3">
                @foreach($levelUps as $levelUp)
                    <div class="bg-gradient-to-r from-purple-50 to-pink-50 rounded-lg p-4 border border-purple-200 hover:shadow-md transition">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-4">
                                <!-- Level Badge -->
                                <div class="flex-shrink-0">
                                    <div class="w-16 h-16 bg-gradient-to-br from-purple-500 to-pink-500 rounded-full flex items-center justify-center text-white font-bold text-xl shadow-lg">
                                        {{ $levelUp->new_level }}
                                    </div>
                                </div>

                                <!-- Level Info -->
                                <div>
                                    <div class="flex items-center gap-2 mb-1">
                                        <span class="text-lg font-bold text-gray-900">
                                            Level {{ $levelUp->old_level }} → {{ $levelUp->new_level }}
                                        </span>
                                        @if($levelUp->bonus_xp > 0)
                                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-semibold bg-yellow-100 text-yellow-800">
                                                🎉 Milestone
                                            </span>
                                        @endif
                                    </div>
                                    <div class="text-sm text-gray-600">
                                        <span class="font-medium">{{ number_format($levelUp->xp_at_levelup) }} XP</span>
                                        @if($levelUp->bonus_xp > 0)
                                            <span class="text-yellow-600 font-semibold">
                                                + {{ number_format($levelUp->bonus_xp) }} Bonus XP
                                            </span>
                                        @endif
                                    </div>
                                    <div class="text-xs text-gray-500 mt-1">
                                        {{ $levelUp->created_at->diffForHumans() }}
                                        <span class="text-gray-400">•</span>
                                        {{ $levelUp->created_at->format('M d, Y') }}
                                    </div>
                                </div>
                            </div>

                            <!-- Trophy Icon -->
                            <div class="flex-shrink-0">
                                <svg class="w-8 h-8 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"></path>
                                </svg>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            @if(Auth::user()->levelUps()->count() > 10)
                <div class="mt-4 text-center">
                    <p class="text-sm text-gray-600">
                        Showing 10 most recent level-ups out of {{ Auth::user()->levelUps()->count() }} total
                    </p>
                </div>
            @endif
        @endif
    </div>
</section>
