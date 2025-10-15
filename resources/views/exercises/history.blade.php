<x-app-layout>
  <div class="py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <!-- Breadcrumb -->
      <x-breadcrumb :items="[
        ['label' => 'Exercise History']
      ]" />

      <!-- Header -->
      <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-900">Exercise History</h1>
        <p class="mt-2 text-gray-600">Track your progress and review past exercise attempts</p>
      </div>

      <!-- Average Scores by Type -->
      @if ($averageScores->isNotEmpty())
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-6">
          <h2 class="text-lg font-semibold text-gray-900 mb-4">Average Scores by Exercise Type</h2>
          <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            @foreach ($averageScores as $type => $avgScore)
              <div class="bg-gray-50 rounded-lg p-4">
                <div class="text-sm text-gray-600 mb-1">{{ ucfirst($type) }}</div>
                <div class="text-2xl font-bold" 
                  x-data 
                  :class="{
                    'text-green-600': {{ $avgScore }} >= 70,
                    'text-yellow-600': {{ $avgScore }} >= 50 && {{ $avgScore }} < 70,
                    'text-red-600': {{ $avgScore }} < 50
                  }">
                  {{ number_format($avgScore, 1) }}%
                </div>
              </div>
            @endforeach
          </div>
        </div>
      @endif

      <!-- Attempts List -->
      <div class="bg-white rounded-lg shadow-sm border border-gray-200">
        <div class="p-6 border-b border-gray-200">
          <h2 class="text-lg font-semibold text-gray-900">Recent Attempts</h2>
        </div>

        @if ($attempts->count() > 0)
          <div class="divide-y divide-gray-200">
            @foreach ($attempts as $attempt)
              <div class="p-6 hover:bg-gray-50 transition-colors">
                <div class="flex items-start justify-between">
                  <div class="flex-1">
                    <div class="flex items-center space-x-3 mb-2">
                      <h3 class="font-semibold text-gray-900">{{ $attempt->drill->title }}</h3>
                      <span class="text-xs px-2 py-1 bg-gray-100 text-gray-600 rounded">
                        {{ ucfirst($attempt->drill->type) }}
                      </span>
                    </div>
                    
                    <div class="flex items-center space-x-4 text-sm text-gray-600">
                      <span>{{ $attempt->drill->lesson->title }}</span>
                      <span>•</span>
                      <span>{{ $attempt->completed_at->diffForHumans() }}</span>
                      @if ($attempt->duration_seconds > 0)
                        <span>•</span>
                        <span>{{ gmdate('i:s', $attempt->duration_seconds) }}</span>
                      @endif
                    </div>
                  </div>

                  <div class="flex items-center space-x-4">
                    <div class="text-right">
                      <div class="text-2xl font-bold" 
                        x-data 
                        :class="{
                          'text-green-600': {{ $attempt->score }} >= 70,
                          'text-yellow-600': {{ $attempt->score }} >= 50 && {{ $attempt->score }} < 70,
                          'text-red-600': {{ $attempt->score }} < 50
                        }">
                        {{ number_format($attempt->score, 0) }}%
                      </div>
                      <div class="text-xs text-gray-500">Score</div>
                    </div>

                    <a href="{{ route('exercises.show', $attempt->drill) }}"
                      class="inline-flex items-center px-3 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                      Retry
                    </a>
                  </div>
                </div>
              </div>
            @endforeach
          </div>

          <!-- Pagination -->
          <div class="p-6 border-t border-gray-200">
            {{ $attempts->links() }}
          </div>
        @else
          <div class="p-12 text-center">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
            </svg>
            <h3 class="mt-2 text-sm font-medium text-gray-900">Ready to start speaking? 🎯</h3>
            <p class="mt-1 text-sm text-gray-500">Your conversation practice history will appear here. Let's begin your speaking journey!</p>
            <div class="mt-6">
              <a href="{{ route('lessons.index') }}"
                class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                Start Speaking Practice
              </a>
            </div>
          </div>
        @endif
      </div>
    </div>
  </div>
</x-app-layout>
