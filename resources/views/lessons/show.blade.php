<x-app-layout>
  <div class="py-4 sm:py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <!-- Breadcrumb -->
      <x-breadcrumb :items="[
        ['label' => 'Lessons', 'url' => route('lessons.index')],
        ['label' => $lesson->title]
      ]" />

      <!-- Header with navigation -->
      <div class="mb-4 sm:mb-6">
        <div class="flex items-center justify-between mb-4">
          <a href="{{ route('lessons.index') }}" class="text-xs sm:text-sm text-gray-600 hover:text-gray-900 flex items-center min-h-[44px] touch-manipulation">
            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
            </svg>
            <span class="hidden sm:inline">Back to Lessons</span>
            <span class="sm:hidden">Back</span>
          </a>

          <div class="flex items-center space-x-2 sm:space-x-4">
            @if ($previousLesson)
              <a href="{{ route('lessons.show', $previousLesson->slug) }}"
                class="text-xs sm:text-sm text-gray-600 hover:text-gray-900 flex items-center min-h-[44px] touch-manipulation px-2">
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
                <span class="hidden sm:inline">Previous</span>
              </a>
            @endif

            @if ($nextLesson)
              <a href="{{ route('lessons.show', $nextLesson->slug) }}"
                class="text-xs sm:text-sm text-gray-600 hover:text-gray-900 flex items-center min-h-[44px] touch-manipulation px-2">
                <span class="hidden sm:inline">Next</span>
                <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
              </a>
            @endif
          </div>
        </div>

        <div class="flex items-start justify-between gap-3">
          <div class="flex items-start space-x-2 sm:space-x-3 flex-1 min-w-0">
            <span
              class="inline-flex items-center justify-center w-10 h-10 sm:w-12 sm:h-12 rounded-full bg-indigo-100 text-indigo-600 font-semibold text-sm sm:text-base flex-shrink-0">
              {{ $lesson->order }}
            </span>
            <div class="min-w-0 flex-1">
              <h1 class="text-xl sm:text-3xl font-bold text-gray-900 break-words">{{ $lesson->title }}</h1>
              @if ($lesson->description)
                <p class="mt-1 text-sm sm:text-base text-gray-600">{{ $lesson->description }}</p>
              @endif
            </div>
          </div>
          
          @auth
            @php
              $isBookmarked = Auth::user()->bookmarks()
                ->where('bookmarkable_type', App\Models\Lesson::class)
                ->where('bookmarkable_id', $lesson->id)
                ->exists();
            @endphp
            <div class="flex-shrink-0">
              <x-bookmark-button type="lesson" :id="$lesson->id" :bookmarked="$isBookmarked" />
            </div>
          @endauth
        </div>
      </div>

      <!-- Tabs -->
      <div class="bg-white rounded-lg shadow-sm border border-gray-200" x-data="{ activeTab: 'phrases' }">
        <div class="border-b border-gray-200">
          <nav class="flex -mb-px overflow-x-auto scrollbar-hide" role="tablist" aria-label="Lesson content tabs">
            <button @click="activeTab = 'phrases'"
              :class="activeTab === 'phrases' ? 'border-indigo-500 text-indigo-600' :
                  'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
              :aria-selected="activeTab === 'phrases'"
              role="tab"
              class="whitespace-nowrap py-3 px-4 sm:py-4 sm:px-6 border-b-2 font-medium text-xs sm:text-sm min-h-[44px] touch-manipulation">
              <span class="hidden sm:inline">Phrases</span>
              <span class="sm:hidden">Phrases</span>
              <span class="ml-1 sm:ml-2 py-0.5 px-1.5 sm:px-2 rounded-full text-xs"
                :class="activeTab === 'phrases' ? 'bg-indigo-100 text-indigo-600' : 'bg-gray-100 text-gray-600'">
                {{ $lesson->phrases->count() }}
              </span>
            </button>

            <button @click="activeTab = 'dialogues'"
              :class="activeTab === 'dialogues' ? 'border-indigo-500 text-indigo-600' :
                  'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
              :aria-selected="activeTab === 'dialogues'"
              role="tab"
              class="whitespace-nowrap py-3 px-4 sm:py-4 sm:px-6 border-b-2 font-medium text-xs sm:text-sm min-h-[44px] touch-manipulation">
              <span class="hidden sm:inline">Dialogues</span>
              <span class="sm:hidden">Dialog</span>
              <span class="ml-1 sm:ml-2 py-0.5 px-1.5 sm:px-2 rounded-full text-xs"
                :class="activeTab === 'dialogues' ? 'bg-indigo-100 text-indigo-600' : 'bg-gray-100 text-gray-600'">
                {{ $lesson->dialogues->count() }}
              </span>
            </button>

            <button @click="activeTab = 'drills'"
              :class="activeTab === 'drills' ? 'border-indigo-500 text-indigo-600' :
                  'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
              :aria-selected="activeTab === 'drills'"
              role="tab"
              class="whitespace-nowrap py-3 px-4 sm:py-4 sm:px-6 border-b-2 font-medium text-xs sm:text-sm min-h-[44px] touch-manipulation">
              Drills
              <span class="ml-1 sm:ml-2 py-0.5 px-1.5 sm:px-2 rounded-full text-xs"
                :class="activeTab === 'drills' ? 'bg-indigo-100 text-indigo-600' : 'bg-gray-100 text-gray-600'">
                {{ $lesson->drills->count() }}
              </span>
            </button>

            <button @click="activeTab = 'shadowing'"
              :class="activeTab === 'shadowing' ? 'border-indigo-500 text-indigo-600' :
                  'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
              :aria-selected="activeTab === 'shadowing'"
              role="tab"
              class="whitespace-nowrap py-3 px-4 sm:py-4 sm:px-6 border-b-2 font-medium text-xs sm:text-sm min-h-[44px] touch-manipulation">
              <span class="hidden sm:inline">Shadowing</span>
              <span class="sm:hidden">Shadow</span>
              <span class="ml-1 sm:ml-2 py-0.5 px-1.5 sm:px-2 rounded-full text-xs"
                :class="activeTab === 'shadowing' ? 'bg-indigo-100 text-indigo-600' : 'bg-gray-100 text-gray-600'">
                {{ $lesson->shadowingExercises->count() }}
              </span>
            </button>
          </nav>
        </div>

        <!-- Tab Content -->
        <div class="p-4 sm:p-6">
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
              <!-- Exercise Statistics -->
              @php
                $lessonAttempts = \App\Models\ExerciseAttempt::whereHas('drill', function($q) use ($lesson) {
                    $q->where('lesson_id', $lesson->id);
                })->where('user_id', auth()->id())->get();
                
                $totalAttempts = $lessonAttempts->count();
                $avgScore = $totalAttempts > 0 ? $lessonAttempts->avg('score') : 0;
              @endphp

              @if ($totalAttempts > 0)
                <div class="bg-gradient-to-r from-indigo-50 to-purple-50 rounded-lg p-4 mb-6">
                  <div class="flex items-center justify-between">
                    <div>
                      <h3 class="text-sm font-medium text-gray-700 mb-1">Your Progress</h3>
                      <div class="flex items-center space-x-4">
                        <div>
                          <span class="text-2xl font-bold text-indigo-600">{{ $totalAttempts }}</span>
                          <span class="text-sm text-gray-600 ml-1">attempts</span>
                        </div>
                        <div class="h-8 w-px bg-gray-300"></div>
                        <div>
                          <span class="text-2xl font-bold" 
                            x-data 
                            :class="{
                              'text-green-600': {{ $avgScore }} >= 70,
                              'text-yellow-600': {{ $avgScore }} >= 50 && {{ $avgScore }} < 70,
                              'text-red-600': {{ $avgScore }} < 50
                            }">
                            {{ number_format($avgScore, 0) }}%
                          </span>
                          <span class="text-sm text-gray-600 ml-1">avg score</span>
                        </div>
                      </div>
                    </div>
                    <a href="{{ route('exercises.history', ['lesson_id' => $lesson->id]) }}"
                      class="text-sm text-indigo-600 hover:text-indigo-700 font-medium">
                      View History →
                    </a>
                  </div>
                </div>
              @endif

              <!-- Drills List -->
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
                    
                    @php
                      $drillAttempts = $drill->attempts()->where('user_id', auth()->id())->get();
                      $attemptCount = $drillAttempts->count();
                    @endphp

                    @if ($attemptCount > 0)
                      @php
                        $bestAttempt = $drillAttempts->sortByDesc('score')->first();
                        $lastAttempt = $drillAttempts->sortByDesc('completed_at')->first();
                      @endphp
                      <div class="mt-3 pt-3 border-t border-gray-200">
                        <div class="grid grid-cols-3 gap-4 text-sm">
                          <div>
                            <span class="text-gray-600">Attempts:</span>
                            <span class="font-semibold text-gray-900 ml-1">{{ $attemptCount }}</span>
                          </div>
                          <div>
                            <span class="text-gray-600">Best:</span>
                            <span class="font-semibold ml-1" 
                              x-data 
                              :class="{
                                'text-green-600': {{ $bestAttempt->score }} >= 70,
                                'text-yellow-600': {{ $bestAttempt->score }} >= 50 && {{ $bestAttempt->score }} < 70,
                                'text-red-600': {{ $bestAttempt->score }} < 50
                              }">
                              {{ number_format($bestAttempt->score, 0) }}%
                            </span>
                          </div>
                          <div>
                            <span class="text-gray-600">Last:</span>
                            <span class="font-semibold ml-1" 
                              x-data 
                              :class="{
                                'text-green-600': {{ $lastAttempt->score }} >= 70,
                                'text-yellow-600': {{ $lastAttempt->score }} >= 50 && {{ $lastAttempt->score }} < 70,
                                'text-red-600': {{ $lastAttempt->score }} < 50
                              }">
                              {{ number_format($lastAttempt->score, 0) }}%
                            </span>
                          </div>
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
                  @php
                    $completions = $exercise->completions()->where('user_id', auth()->id())->get();
                    $completionCount = $completions->count();
                    $lastCompletion = $completions->sortByDesc('completed_at')->first();
                  @endphp
                  
                  <div class="border border-gray-200 rounded-lg p-4 hover:border-indigo-300 transition-colors">
                    <div class="flex items-center justify-between mb-3">
                      <div class="flex-1">
                        <h3 class="font-semibold text-gray-900 mb-1">{{ $exercise->title }}</h3>
                        <div class="flex items-center space-x-2">
                          @if ($exercise->duration_seconds)
                            <span class="text-xs px-2 py-1 bg-gray-100 text-gray-600 rounded">
                              {{ gmdate('i:s', $exercise->duration_seconds) }}
                            </span>
                          @endif
                          @if (is_array($exercise->content))
                            <span class="text-xs text-gray-500">
                              {{ count($exercise->content) }} lines
                            </span>
                          @endif
                        </div>
                      </div>
                      <a href="{{ route('shadowing.show', $exercise) }}"
                        class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                        </svg>
                        Practice
                      </a>
                    </div>
                    
                    @if ($completionCount > 0)
                      <div class="mt-3 pt-3 border-t border-gray-200">
                        <div class="flex items-center justify-between text-sm">
                          <div>
                            <span class="text-gray-600">Completions:</span>
                            <span class="font-semibold text-green-600 ml-1">{{ $completionCount }}</span>
                          </div>
                          @if ($lastCompletion)
                            <div>
                              <span class="text-gray-600">Last practiced:</span>
                              <span class="font-semibold text-gray-900 ml-1">
                                {{ $lastCompletion->completed_at->diffForHumans() }}
                              </span>
                            </div>
                          @endif
                        </div>
                      </div>
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
