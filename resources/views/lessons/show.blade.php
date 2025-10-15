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
            <span class="hidden sm:inline">Back to Conversations</span>
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
                <p class="mt-1 text-sm sm:text-base text-gray-600">
                  <span class="inline-flex items-center">
                    <svg class="w-4 h-4 mr-1 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11a7 7 0 01-7 7m0 0a7 7 0 01-7-7m7 7v4m0 0H8m4 0h4m-4-8a3 3 0 01-3-3V5a3 3 0 116 0v6a3 3 0 01-3 3z" />
                    </svg>
                    {{ $lesson->description }}
                  </span>
                </p>
              @endif
              
              @auth
                <!-- Speaking Progress Indicator -->
                @if ($userProgress)
                  <div class="mt-3">
                    <div class="flex items-center justify-between text-xs sm:text-sm text-gray-600 mb-1">
                      <span class="font-medium">Conversation Progress</span>
                      <span class="font-semibold text-indigo-600">{{ number_format($userProgress->completion_percentage, 0) }}%</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2">
                      <div class="bg-gradient-to-r from-indigo-500 to-indigo-600 h-2 rounded-full transition-all duration-300"
                           style="width: {{ min($userProgress->completion_percentage, 100) }}%"></div>
                    </div>
                    
                    <!-- Speaking Time Stats -->
                    <div class="flex items-center gap-4 mt-2 text-xs text-gray-600">
                      <span class="flex items-center">
                        <svg class="w-4 h-4 mr-1 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span class="font-medium">{{ number_format($speakingTimePracticed, 0) }}</span> / {{ $estimatedTime }} min practiced
                      </span>
                      <span class="flex items-center">
                        <svg class="w-4 h-4 mr-1 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                        </svg>
                        {{ $userProgress->dialogues_viewed }} / {{ $lesson->dialogues->count() }} dialogues
                      </span>
                    </div>
                  </div>
                @else
                  <div class="mt-3 text-xs sm:text-sm text-gray-600">
                    <span class="flex items-center">
                      <svg class="w-4 h-4 mr-1 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                      </svg>
                      Estimated time: {{ $estimatedTime }} minutes
                    </span>
                  </div>
                @endif
              @endauth
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
      <div class="bg-white rounded-lg shadow-sm border border-gray-200" x-data="{ activeTab: 'dialogues' }">
        <!-- Sticky Tab Navigation -->
        <div class="border-b border-gray-200 sticky top-0 bg-white z-10 rounded-t-lg">
          <nav class="flex -mb-px overflow-x-auto scrollbar-hide" role="tablist" aria-label="Lesson content tabs">
            <!-- Dialogues Tab (Priority 1) -->
            <button @click="activeTab = 'dialogues'"
              :class="activeTab === 'dialogues' ? 'border-indigo-500 text-indigo-600' :
                  'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
              :aria-selected="activeTab === 'dialogues'"
              role="tab"
              class="whitespace-nowrap py-3 px-4 sm:py-4 sm:px-6 border-b-2 font-medium text-xs sm:text-sm min-h-[44px] touch-manipulation flex items-center">
              <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
              </svg>
              <span class="hidden sm:inline">Dialogues</span>
              <span class="sm:hidden">Dialog</span>
              <span class="ml-1 sm:ml-2 py-0.5 px-1.5 sm:px-2 rounded-full text-xs"
                :class="activeTab === 'dialogues' ? 'bg-indigo-100 text-indigo-600' : 'bg-gray-100 text-gray-600'">
                {{ $lesson->dialogues->count() }}
              </span>
            </button>

            <!-- Shadowing Tab (Priority 2) -->
            <button @click="activeTab = 'shadowing'"
              :class="activeTab === 'shadowing' ? 'border-indigo-500 text-indigo-600' :
                  'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
              :aria-selected="activeTab === 'shadowing'"
              role="tab"
              class="whitespace-nowrap py-3 px-4 sm:py-4 sm:px-6 border-b-2 font-medium text-xs sm:text-sm min-h-[44px] touch-manipulation flex items-center">
              <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11a7 7 0 01-7 7m0 0a7 7 0 01-7-7m7 7v4m0 0H8m4 0h4m-4-8a3 3 0 01-3-3V5a3 3 0 116 0v6a3 3 0 01-3 3z" />
              </svg>
              <span class="hidden sm:inline">Shadowing</span>
              <span class="sm:hidden">Shadow</span>
              <span class="ml-1 sm:ml-2 py-0.5 px-1.5 sm:px-2 rounded-full text-xs"
                :class="activeTab === 'shadowing' ? 'bg-indigo-100 text-indigo-600' : 'bg-gray-100 text-gray-600'">
                {{ $lesson->shadowingExercises->count() }}
              </span>
            </button>

            <!-- Phrases Tab -->
            <button @click="activeTab = 'phrases'"
              :class="activeTab === 'phrases' ? 'border-indigo-500 text-indigo-600' :
                  'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
              :aria-selected="activeTab === 'phrases'"
              role="tab"
              class="whitespace-nowrap py-3 px-4 sm:py-4 sm:px-6 border-b-2 font-medium text-xs sm:text-sm min-h-[44px] touch-manipulation flex items-center">
              <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z" />
              </svg>
              <span class="hidden sm:inline">Phrases</span>
              <span class="sm:hidden">Phrases</span>
              <span class="ml-1 sm:ml-2 py-0.5 px-1.5 sm:px-2 rounded-full text-xs"
                :class="activeTab === 'phrases' ? 'bg-indigo-100 text-indigo-600' : 'bg-gray-100 text-gray-600'">
                {{ $lesson->phrases->count() }}
              </span>
            </button>

            <!-- Drills Tab -->
            <button @click="activeTab = 'drills'"
              :class="activeTab === 'drills' ? 'border-indigo-500 text-indigo-600' :
                  'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
              :aria-selected="activeTab === 'drills'"
              role="tab"
              class="whitespace-nowrap py-3 px-4 sm:py-4 sm:px-6 border-b-2 font-medium text-xs sm:text-sm min-h-[44px] touch-manipulation flex items-center">
              <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
              </svg>
              <span>Drills</span>
              <span class="ml-1 sm:ml-2 py-0.5 px-1.5 sm:px-2 rounded-full text-xs"
                :class="activeTab === 'drills' ? 'bg-indigo-100 text-indigo-600' : 'bg-gray-100 text-gray-600'">
                {{ $lesson->drills->count() }}
              </span>
            </button>
          </nav>
        </div>

        <!-- Tab Content -->
        <div class="p-4 sm:p-6">
          <!-- Dialogues Tab (Priority 1) -->
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
                  Practice Speaking These Phrases
                </a>
              </div>
              <x-phrase-table :phrases="$lesson->phrases" />
            @else
              <p class="text-gray-500 text-center py-8">No phrases available for this lesson.</p>
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
                <div class="bg-gradient-to-r from-indigo-50 to-purple-50 rounded-lg p-4 sm:p-6 mb-6 border border-indigo-100">
                  <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div class="flex-1">
                      <div class="flex items-center gap-2 mb-3">
                        <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                        </svg>
                        <h3 class="text-base font-semibold text-gray-900">Your Speaking Practice Progress</h3>
                      </div>
                      
                      <div class="grid grid-cols-2 gap-4">
                        <!-- Attempts -->
                        <div class="bg-white rounded-lg p-3 shadow-sm">
                          <div class="flex items-center gap-2 mb-1">
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                            </svg>
                            <span class="text-xs text-gray-600 font-medium">Practice Sessions</span>
                          </div>
                          <div class="flex items-baseline">
                            <span class="text-2xl font-bold text-indigo-600">{{ $totalAttempts }}</span>
                            <span class="text-sm text-gray-500 ml-1">times</span>
                          </div>
                        </div>
                        
                        <!-- Average Score with Visual Indicator -->
                        <div class="bg-white rounded-lg p-3 shadow-sm">
                          <div class="flex items-center gap-2 mb-1">
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" />
                            </svg>
                            <span class="text-xs text-gray-600 font-medium">Average Score</span>
                          </div>
                          <div class="flex items-baseline">
                            <span class="text-2xl font-bold" 
                              x-data 
                              :class="{
                                'text-green-600': {{ $avgScore }} >= 70,
                                'text-yellow-600': {{ $avgScore }} >= 50 && {{ $avgScore }} < 70,
                                'text-red-600': {{ $avgScore }} < 50
                              }">
                              {{ number_format($avgScore, 0) }}%
                            </span>
                          </div>
                          <!-- Progress bar -->
                          <div class="mt-2 w-full bg-gray-200 rounded-full h-1.5">
                            <div class="h-1.5 rounded-full transition-all duration-300" 
                              x-data 
                              :class="{
                                'bg-green-500': {{ $avgScore }} >= 70,
                                'bg-yellow-500': {{ $avgScore }} >= 50 && {{ $avgScore }} < 70,
                                'bg-red-500': {{ $avgScore }} < 50
                              }"
                              style="width: {{ min($avgScore, 100) }}%"></div>
                          </div>
                        </div>
                      </div>
                    </div>
                    
                    <div class="flex-shrink-0">
                      <a href="{{ route('exercises.history', ['lesson_id' => $lesson->id]) }}"
                        class="inline-flex items-center px-4 py-2 bg-white border border-indigo-200 rounded-lg text-sm text-indigo-600 hover:bg-indigo-50 hover:border-indigo-300 font-medium transition-colors min-h-[44px] touch-manipulation">
                        View History
                        <svg class="w-4 h-4 ml-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                      </a>
                    </div>
                  </div>
                </div>
              @endif

              <!-- Drills List -->
              <div class="space-y-4">
                @foreach ($lesson->drills as $drill)
                  @php
                    $drillAttempts = $drill->attempts()->where('user_id', auth()->id())->get();
                    $attemptCount = $drillAttempts->count();
                    $isCompleted = $attemptCount > 0;
                    $questionCount = is_array($drill->content) ? count($drill->content) : 0;
                    $estimatedMinutes = ceil($questionCount * 0.5); // 30 seconds per question
                  @endphp
                  
                  <div class="border border-gray-200 rounded-lg p-4 sm:p-5 hover:border-indigo-300 hover:shadow-md transition-all bg-white">
                    <div class="flex flex-col sm:flex-row sm:items-start gap-4">
                      <div class="flex-1">
                        <div class="flex items-start gap-3 mb-3">
                          <!-- Audio Practice Indicator Icon -->
                          <div class="flex-shrink-0 w-10 h-10 rounded-lg bg-indigo-100 flex items-center justify-center">
                            <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19V6l12-3v13M9 19c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zm12-3c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zM9 10l12-3" />
                            </svg>
                          </div>
                          
                          <div class="flex-1 min-w-0">
                            <h3 class="font-semibold text-gray-900 mb-2 text-base sm:text-lg">{{ $drill->title }}</h3>
                            <div class="flex flex-wrap items-center gap-2">
                              <span class="inline-flex items-center text-xs px-2.5 py-1 bg-indigo-50 text-indigo-700 rounded-full font-medium border border-indigo-100">
                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z" />
                                </svg>
                                {{ ucfirst($drill->type) }}
                              </span>
                              <span class="inline-flex items-center text-xs text-gray-600">
                                <svg class="w-3 h-3 mr-1 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                {{ $questionCount }} questions
                              </span>
                              <span class="inline-flex items-center text-xs text-gray-600">
                                <svg class="w-3 h-3 mr-1 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                ~{{ $estimatedMinutes }} min
                              </span>
                              
                              @if ($isCompleted)
                                <span class="inline-flex items-center text-xs px-2.5 py-1 bg-green-50 text-green-700 rounded-full font-medium border border-green-100">
                                  <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                  </svg>
                                  Completed
                                </span>
                              @endif
                            </div>
                          </div>
                        </div>
                        
                        @if ($attemptCount > 0)
                          @php
                            $bestAttempt = $drillAttempts->sortByDesc('score')->first();
                            $lastAttempt = $drillAttempts->sortByDesc('completed_at')->first();
                          @endphp
                          <div class="mt-3 pt-3 border-t border-gray-200">
                            <div class="grid grid-cols-3 gap-3 text-sm">
                              <div class="text-center sm:text-left">
                                <div class="text-xs text-gray-500 mb-1">Attempts</div>
                                <div class="font-semibold text-gray-900">{{ $attemptCount }}</div>
                              </div>
                              <div class="text-center sm:text-left">
                                <div class="text-xs text-gray-500 mb-1">Best Score</div>
                                <div class="font-semibold flex items-center justify-center sm:justify-start" 
                                  x-data 
                                  :class="{
                                    'text-green-600': {{ $bestAttempt->score }} >= 70,
                                    'text-yellow-600': {{ $bestAttempt->score }} >= 50 && {{ $bestAttempt->score }} < 70,
                                    'text-red-600': {{ $bestAttempt->score }} < 50
                                  }">
                                  @if($bestAttempt->score >= 70)
                                    <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                      <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                    </svg>
                                  @endif
                                  {{ number_format($bestAttempt->score, 0) }}%
                                </div>
                              </div>
                              <div class="text-center sm:text-left">
                                <div class="text-xs text-gray-500 mb-1">Last Score</div>
                                <div class="font-semibold" 
                                  x-data 
                                  :class="{
                                    'text-green-600': {{ $lastAttempt->score }} >= 70,
                                    'text-yellow-600': {{ $lastAttempt->score }} >= 50 && {{ $lastAttempt->score }} < 70,
                                    'text-red-600': {{ $lastAttempt->score }} < 50
                                  }">
                                  {{ number_format($lastAttempt->score, 0) }}%
                                </div>
                              </div>
                            </div>
                          </div>
                        @endif
                      </div>
                      
                      <div class="flex-shrink-0 w-full sm:w-auto">
                        <a href="{{ route('exercises.show', $drill) }}"
                          class="w-full sm:w-auto inline-flex items-center justify-center px-5 py-2.5 bg-gradient-to-r from-indigo-600 to-indigo-700 border border-transparent rounded-lg font-semibold text-sm text-white hover:from-indigo-700 hover:to-indigo-800 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition-all duration-150 shadow-sm hover:shadow-md active:scale-95 min-h-[44px] touch-manipulation">
                          <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                          </svg>
                          {{ $isCompleted ? 'Practice Again' : 'Start Practice' }}
                        </a>
                      </div>
                    </div>
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
                    $isCompleted = $completionCount > 0;
                    $lineCount = is_array($exercise->content) ? count($exercise->content) : 0;
                    $estimatedMinutes = $exercise->duration_seconds ? ceil($exercise->duration_seconds / 60) : 5;
                  @endphp
                  
                  <div class="border border-gray-200 rounded-lg p-4 sm:p-5 hover:border-indigo-300 hover:shadow-md transition-all bg-white">
                    <div class="flex flex-col sm:flex-row sm:items-start gap-4">
                      <div class="flex-1">
                        <div class="flex items-start gap-3 mb-3">
                          <!-- Microphone Icon for Shadowing -->
                          <div class="flex-shrink-0 w-10 h-10 rounded-lg bg-purple-100 flex items-center justify-center">
                            <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11a7 7 0 01-7 7m0 0a7 7 0 01-7-7m7 7v4m0 0H8m4 0h4m-4-8a3 3 0 01-3-3V5a3 3 0 116 0v6a3 3 0 01-3 3z" />
                            </svg>
                          </div>
                          
                          <div class="flex-1 min-w-0">
                            <h3 class="font-semibold text-gray-900 mb-2 text-base sm:text-lg">{{ $exercise->title }}</h3>
                            <div class="flex flex-wrap items-center gap-2">
                              <span class="inline-flex items-center text-xs px-2.5 py-1 bg-purple-50 text-purple-700 rounded-full font-medium border border-purple-100">
                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.536 8.464a5 5 0 010 7.072m2.828-9.9a9 9 0 010 12.728M5.586 15H4a1 1 0 01-1-1v-4a1 1 0 011-1h1.586l4.707-4.707C10.923 3.663 12 4.109 12 5v14c0 .891-1.077 1.337-1.707.707L5.586 15z" />
                                </svg>
                                Shadowing
                              </span>
                              @if ($exercise->duration_seconds)
                                <span class="inline-flex items-center text-xs text-gray-600">
                                  <svg class="w-3 h-3 mr-1 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                  </svg>
                                  {{ gmdate('i:s', $exercise->duration_seconds) }}
                                </span>
                              @endif
                              @if ($lineCount > 0)
                                <span class="inline-flex items-center text-xs text-gray-600">
                                  <svg class="w-3 h-3 mr-1 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7" />
                                  </svg>
                                  {{ $lineCount }} lines
                                </span>
                              @endif
                              <span class="inline-flex items-center text-xs text-gray-600">
                                <svg class="w-3 h-3 mr-1 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                                </svg>
                                ~{{ $estimatedMinutes }} min
                              </span>
                              
                              @if ($isCompleted)
                                <span class="inline-flex items-center text-xs px-2.5 py-1 bg-green-50 text-green-700 rounded-full font-medium border border-green-100">
                                  <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                  </svg>
                                  Practiced
                                </span>
                              @endif
                            </div>
                          </div>
                        </div>
                        
                        @if ($completionCount > 0)
                          <div class="mt-3 pt-3 border-t border-gray-200">
                            <div class="flex items-center justify-between text-sm">
                              <div class="flex items-center">
                                <svg class="w-4 h-4 text-green-500 mr-1.5" fill="currentColor" viewBox="0 0 20 20">
                                  <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                </svg>
                                <span class="text-gray-600">Completed:</span>
                                <span class="font-semibold text-green-600 ml-1">{{ $completionCount }} {{ $completionCount === 1 ? 'time' : 'times' }}</span>
                              </div>
                              @if ($lastCompletion)
                                <div class="text-xs text-gray-500">
                                  Last: {{ $lastCompletion->completed_at->diffForHumans() }}
                                </div>
                              @endif
                            </div>
                          </div>
                        @endif
                      </div>
                      
                      <div class="flex-shrink-0 w-full sm:w-auto">
                        <a href="{{ route('shadowing.show', $exercise) }}"
                          class="w-full sm:w-auto inline-flex items-center justify-center px-5 py-2.5 bg-gradient-to-r from-purple-600 to-purple-700 border border-transparent rounded-lg font-semibold text-sm text-white hover:from-purple-700 hover:to-purple-800 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-offset-2 transition-all duration-150 shadow-sm hover:shadow-md active:scale-95 min-h-[44px] touch-manipulation">
                          <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11a7 7 0 01-7 7m0 0a7 7 0 01-7-7m7 7v4m0 0H8m4 0h4m-4-8a3 3 0 01-3-3V5a3 3 0 116 0v6a3 3 0 01-3 3z" />
                          </svg>
                          {{ $isCompleted ? 'Practice Again' : 'Start Shadowing' }}
                        </a>
                      </div>
                    </div>
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
