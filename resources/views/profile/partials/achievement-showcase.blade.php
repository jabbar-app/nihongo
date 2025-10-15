<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('Achievement Showcase') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            {{ __('Your most recent achievements and progress.') }}
        </p>
    </header>

    @php
        $user = auth()->user();
        
        // Get recent achievements (last 6 earned)
        $recentAchievements = $user->achievements()
            ->wherePivot('earned_at', '!=', null)
            ->orderByPivot('earned_at', 'desc')
            ->limit(6)
            ->get();
        
        // Get total achievement stats
        $totalAchievements = \App\Models\Achievement::count();
        $earnedCount = $user->achievements()
            ->wherePivot('earned_at', '!=', null)
            ->count();
    @endphp

    <div class="mt-6 space-y-6">
        <!-- Achievement Stats -->
        <div class="flex items-center justify-between p-4 bg-gradient-to-r from-indigo-50 to-purple-50 rounded-lg hover-lift">
            <div>
                <div class="text-2xl font-bold text-gray-900">{{ $earnedCount }} / {{ $totalAchievements }}</div>
                <div class="text-sm text-gray-600">Achievements Unlocked</div>
            </div>
            <div class="text-right">
                <div class="text-2xl font-bold text-indigo-600">
                    {{ $totalAchievements > 0 ? round(($earnedCount / $totalAchievements) * 100) : 0 }}%
                </div>
                <div class="text-sm text-gray-600">Complete</div>
            </div>
        </div>

        <!-- Recent Achievements -->
        @if($recentAchievements->count() > 0)
        <div>
            <h3 class="text-sm font-semibold text-gray-700 mb-3">Recent Achievements</h3>
            <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
                @foreach($recentAchievements as $achievement)
                <div class="border-2 border-yellow-400 rounded-lg p-3 bg-gradient-to-br from-yellow-50 to-white text-center hover-lift card-hover">
                    <div class="text-3xl mb-1 bounce-in">{{ $achievement->icon }}</div>
                    <div class="text-xs font-semibold text-gray-900 truncate">{{ $achievement->name }}</div>
                    <div class="text-xs text-gray-500 mt-1">
                        {{ $achievement->pivot->earned_at->diffForHumans() }}
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @else
        <div class="text-center py-8 bg-gray-50 rounded-lg">
            <div class="text-4xl mb-2">🏆</div>
            <p class="text-gray-600 text-sm">Your speaking achievements await! 🌟 Start conversations to unlock badges and celebrate your progress!</p>
        </div>
        @endif

        <!-- View All Link -->
        <div class="text-center">
            <a href="{{ route('achievements.index') }}" 
               class="inline-flex items-center text-sm font-semibold text-indigo-600 hover:text-indigo-800">
                View All Achievements
                <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
            </a>
        </div>
    </div>
</section>
