<x-app-layout>
  <div class="py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <!-- Header with navigation -->
      <div class="mb-6">
        <div class="flex items-center justify-between mb-4">
          <a href="{{ route('lessons.index') }}" class="text-sm text-gray-600 hover:text-gray-900 flex items-center">
            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
            </svg>
            Back to Lessons
          </a>

          <div class="flex items-center space-x-4">
            @if ($previousLesson)
              <a href="{{ route('lessons.show', $previousLesson->slug) }}"
                class="text-sm text-gray-600 hover:text-gray-900 flex items-center">
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
                Previous
              </a>
            @endif

            @if ($nextLesson)
              <a href="{{ route('lessons.show', $nextLesson->slug) }}"
                class="text-sm text-gray-600 hover:text-gray-900 flex items-center">
                Next
                <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
              </a>
            @endif
          </div>
        </div>

        <div class="flex items-center space-x-3">
          <span
            class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-indigo-100 text-indigo-600 font-semibold">
            {{ $lesson->order }}
          </span>
          <div>
            <h1 class="text-3xl font-bold text-gray-900">{{ $lesson->title }}</h1>
            @if ($lesson->description)
              <p class="mt-1 text-gray-600">{{ $lesson->description }}</p>
            @endif
          </div>
        </div>
      </div>

      <!-- Tabs -->
      <div class="bg-white rounded-lg shadow-sm border border-gray-200" x-data="{ activeTab: 'phrases' }">
        <div class="border-b border-gray-200">
          <nav class="flex -mb-px overflow-x-auto" aria-label="Tabs">
            <button @click="activeTab = 'phrases'"
              :class="activeTab === 'phrases' ? 'border-indigo-500 text-indigo-600' :
                  'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
              class="whitespace-nowrap py-4 px-6 border-b-2 font-medium text-sm">
              Phrases
              <span class="ml-2 py-0.5 px-2 rounded-full text-xs"
                :class="activeTab === 'phrases' ? 'bg-indigo-100 text-indigo-600' : 'bg-gray-100 text-gray-600'">
                {{ $lesson->phrases->count() }}
              </span>
            </button>

            <button @click="activeTab = 'dialogues'"
              :class="activeTab === 'dialogues' ? 'border-indigo-500 text-indigo-600' :
                  'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
              class="whitespace-nowrap py-4 px-6 border-b-2 font-medium text-sm">
              Dialogues
              <span class="ml-2 py-0.5 px-2 rounded-full text-xs"
                :class="activeTab === 'dialogues' ? 'bg-indigo-100 text-indigo-600' : 'bg-gray-100 text-gray-600'">
                {{ $lesson->dialogues->count() }}
              </span>
            </button>

            <button @click="activeTab = 'drills'"
              :class="activeTab === 'drills' ? 'border-indigo-500 text-indigo-600' :
                  'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
              class="whitespace-nowrap py-4 px-6 border-b-2 font-medium text-sm">
              Drills
              <span class="ml-2 py-0.5 px-2 rounded-full text-xs"
                :class="activeTab === 'drills' ? 'bg-indigo-100 text-indigo-600' : 'bg-gray-100 text-gray-600'">
                {{ $lesson->drills->count() }}
              </span>
            </button>

            <button @click="activeTab = 'shadowing'"
              :class="activeTab === 'shadowing' ? 'border-indigo-500 text-indigo-600' :
                  'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
              class="whitespace-nowrap py-4 px-6 border-b-2 font-medium text-sm">
              Shadowing
              <span class="ml-2 py-0.5 px-2 rounded-full text-xs"
                :class="activeTab === 'shadowing' ? 'bg-indigo-100 text-indigo-600' : 'bg-gray-100 text-gray-600'">
                {{ $lesson->shadowingExercises->count() }}
              </span>
            </button>
          </nav>
        </div>

        <!-- Tab Content -->
        <div class="p-6">
          <!-- Phrases Tab -->
          <div x-show="activeTab === 'phrases'" x-cloak>
            @if ($lesson->phrases->count() > 0)
              <div class="mb-4 flex justify-end">
                <a href="{{ route('flashcards.create', ['lesson_id' => $lesson->id]) }}"
                  class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                  <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M12 4v16m8-8H4" />
                  </svg>
                  Create Flashcards
                </a>
              </div>
              <x-phrase-table :phrases="$lesson->phrases" />
            @else
              <p class="text-gray-500 text-center py-8">No phrases available for this lesson.</p>
            @endif
          </div>

          <!-- Dialogues Tab -->
          <div x-show="activeTab === 'dialogues'" x-cloak>
            @if ($lesson->dialogues->count() > 0)
              <div class="space-y-6">
                @foreach ($lesson->dialogues as $dialogue)
                  <x-dialogue-display :dialogue="$dialogue" />
                @endforeach
              </div>
            @else
              <p class="text-gray-500 text-center py-8">No dialogues available for this lesson.</p>
            @endif
          </div>

          <!-- Drills Tab -->
          <div x-show="activeTab === 'drills'" x-cloak>
            @if ($lesson->drills->count() > 0)
              <div class="space-y-4">
                @foreach ($lesson->drills as $drill)
                  <div class="border border-gray-200 rounded-lg p-4 hover:border-indigo-300 transition-colors">
                    <div class="flex items-center justify-between mb-3">
                      <div class="flex-1">
                        <h3 class="font-semibold text-gray-900 mb-1">{{ $drill->title }}</h3>
                        <div class="flex items-center space-x-2">
                          <span class="text-xs px-2 py-1 bg-gray-100 text-gray-600 rounded">
                            {{ ucfirst($drill->type) }}
                          </span>
                          <span class="text-xs text-gray-500">
                            {{ is_array($drill->content) ? count($drill->content) : 0 }} questions
                          </span>
                        </div>
                      </div>
                      <a href="{{ route('exercises.show', $drill) }}"
                        class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z" />
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        Start Exercise
                      </a>
                    </div>
                    
                    @if ($drill->attempts()->where('user_id', auth()->id())->exists())
                      @php
                        $bestAttempt = $drill->attempts()
                          ->where('user_id', auth()->id())
                          ->orderBy('score', 'desc')
                          ->first();
                      @endphp
                      <div class="mt-3 pt-3 border-t border-gray-200">
                        <div class="flex items-center justify-between text-sm">
                          <span class="text-gray-600">Best Score:</span>
                          <span class="font-semibold" 
                            :class="{ 'text-green-600': {{ $bestAttempt->score }} >= 70, 'text-yellow-600': {{ $bestAttempt->score }} >= 50 && {{ $bestAttempt->score }} < 70, 'text-red-600': {{ $bestAttempt->score }} < 50 }">
                            {{ number_format($bestAttempt->score, 0) }}%
                          </span>
                        </div>
                      </div>
                    @endif
                  </div>
                @endforeach
              </div>
            @else
              <p class="text-gray-500 text-center py-8">No drills available for this lesson.</p>
            @endif
          </div>

          <!-- Shadowing Tab -->
          <div x-show="activeTab === 'shadowing'" x-cloak>
            @if ($lesson->shadowingExercises->count() > 0)
              <div class="space-y-4">
                @foreach ($lesson->shadowingExercises as $exercise)
                  <div class="border border-gray-200 rounded-lg p-4">
                    <h3 class="font-semibold text-gray-900 mb-2">{{ $exercise->title }}</h3>
                    @if ($exercise->duration_seconds)
                      <p class="text-sm text-gray-600">Duration: {{ gmdate('i:s', $exercise->duration_seconds) }}</p>
                    @endif
                  </div>
                @endforeach
              </div>
            @else
              <p class="text-gray-500 text-center py-8">No shadowing exercises available for this lesson.</p>
            @endif
          </div>
        </div>
      </div>
    </div>
  </div>
</x-app-layout>
