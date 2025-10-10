<x-app-layout>
  <div class="py-12">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
      <!-- Header -->
      <div class="mb-6">
        <a href="{{ route('lessons.show', $drill->lesson->slug) }}"
          class="text-sm text-gray-600 hover:text-gray-900 flex items-center mb-4">
          <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
          </svg>
          Back to Lesson
        </a>

        <div class="flex items-center justify-between">
          <div>
            <h1 class="text-3xl font-bold text-gray-900">{{ $drill->title }}</h1>
            <p class="mt-1 text-sm text-gray-600">
              {{ $drill->lesson->title }} • {{ ucfirst($drill->type) }} Exercise
            </p>
          </div>
          <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-indigo-100 text-indigo-800">
            {{ ucfirst($drill->type) }}
          </span>
        </div>
      </div>

      <!-- Exercise Content -->
      <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6"
        x-data="exerciseAttempt(@js($exerciseData), {{ $drill->id }})">

        <!-- Instructions -->
        <div class="mb-6 p-4 bg-blue-50 border border-blue-200 rounded-lg">
          <h2 class="text-sm font-semibold text-blue-900 mb-1">Instructions</h2>
          <p class="text-sm text-blue-800">{{ $exerciseData['instructions'] }}</p>
        </div>

        <!-- Timer -->
        <div class="mb-6 flex justify-between items-center">
          <div class="text-sm text-gray-600">
            <span class="font-medium">Time:</span>
            <span x-text="formatTime(elapsedTime)" class="font-mono"></span>
          </div>
          <div class="text-sm text-gray-600">
            <span class="font-medium">Questions:</span>
            <span x-text="questions.length"></span>
          </div>
        </div>

        <!-- Questions -->
        <form @submit.prevent="submitAnswers" class="space-y-6">
          <template x-for="(question, index) in questions" :key="question.id">
            <div class="border border-gray-200 rounded-lg p-4"
              :class="{ 'border-green-500 bg-green-50': submitted && results[question.id]?.correct, 'border-red-500 bg-red-50': submitted && results[question.id] && !results[question.id].correct }">

              <!-- Question Number -->
              <div class="flex items-start justify-between mb-3">
                <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-gray-100 text-gray-700 font-semibold text-sm"
                  x-text="index + 1"></span>

                <!-- Feedback Icon -->
                <template x-if="submitted && results[question.id]">
                  <div>
                    <svg x-show="results[question.id].correct" class="w-6 h-6 text-green-600" fill="none"
                      stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    <svg x-show="!results[question.id].correct" class="w-6 h-6 text-red-600" fill="none"
                      stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                  </div>
                </template>
              </div>

              <!-- Question Text -->
              <div class="mb-3">
                <!-- For transformation exercises, show source sentence and instruction -->
                <template x-if="question.source_sentence">
                  <div class="space-y-2">
                    <div class="p-3 bg-gray-50 border border-gray-200 rounded-lg">
                      <p class="text-sm text-gray-600 font-medium mb-1">Source Sentence:</p>
                      <p class="text-lg text-gray-900 font-japanese" x-text="question.source_sentence"></p>
                    </div>
                    <div class="p-3 bg-blue-50 border border-blue-200 rounded-lg">
                      <p class="text-sm text-blue-600 font-medium mb-1">Transformation:</p>
                      <p class="text-base text-blue-900" x-text="question.instruction"></p>
                    </div>
                  </div>
                </template>
                
                <!-- For other exercise types, show regular question -->
                <template x-if="!question.source_sentence">
                  <p class="text-lg text-gray-900" x-html="question.question"></p>
                </template>
                
                <template x-if="question.hint">
                  <p class="mt-2 text-sm text-gray-500 italic" x-text="'Hint: ' + question.hint"></p>
                </template>
              </div>

              <!-- Answer Input -->
              <div class="mb-3">
                <input type="text" x-model="answers[question.id]" :disabled="submitted"
                  class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 disabled:bg-gray-100 disabled:cursor-not-allowed"
                  :class="{ 'border-green-500': submitted && results[question.id]?.correct, 'border-red-500': submitted && results[question.id] && !results[question.id].correct }"
                  placeholder="Type your answer here...">
              </div>

              <!-- Feedback -->
              <template x-if="submitted && results[question.id]">
                <div class="mt-3 p-3 rounded-lg"
                  :class="results[question.id].correct ? 'bg-green-100' : (results[question.id].partial_credit >= 0.7 ? 'bg-yellow-100' : 'bg-red-100')">
                  <p class="text-sm font-medium"
                    :class="results[question.id].correct ? 'text-green-800' : (results[question.id].partial_credit >= 0.7 ? 'text-yellow-800' : 'text-red-800')"
                    x-text="results[question.id].message"></p>
                  
                  <!-- Show partial credit score if applicable -->
                  <template x-if="!results[question.id].correct && results[question.id].partial_credit > 0">
                    <p class="text-xs mt-1"
                      :class="results[question.id].partial_credit >= 0.7 ? 'text-yellow-700' : 'text-red-700'">
                      Partial credit: <span x-text="Math.round(results[question.id].partial_credit * 100)"></span>%
                    </p>
                  </template>
                  
                  <template x-if="!results[question.id].correct">
                    <div class="mt-2 space-y-1">
                      <p class="text-sm"
                        :class="results[question.id].partial_credit >= 0.7 ? 'text-yellow-700' : 'text-red-700'">
                        <span class="font-medium">Your answer:</span>
                        <span class="font-japanese" x-text="results[question.id].user_answer"></span>
                      </p>
                      <p class="text-sm text-green-700">
                        <span class="font-medium">Correct answer:</span>
                        <span class="font-japanese" x-text="results[question.id].correct_answer"></span>
                      </p>
                    </div>
                  </template>
                </div>
              </template>
            </div>
          </template>

          <!-- Submit Button -->
          <div class="flex items-center justify-between pt-4 border-t border-gray-200">
            <template x-if="submitted">
              <div class="flex items-center space-x-4">
                <div class="text-2xl font-bold" :class="score >= 70 ? 'text-green-600' : 'text-red-600'">
                  <span x-text="score"></span>%
                </div>
                <div class="text-sm text-gray-600">
                  <span x-text="correctCount"></span> out of <span x-text="questions.length"></span> correct
                </div>
              </div>
            </template>

            <div class="flex space-x-3" :class="{ 'ml-auto': !submitted }">
              <template x-if="!submitted">
                <button type="submit" :disabled="!canSubmit()"
                  class="inline-flex items-center px-6 py-3 bg-indigo-600 border border-transparent rounded-lg font-semibold text-sm text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150 disabled:opacity-50 disabled:cursor-not-allowed">
                  Submit Answers
                </button>
              </template>

              <template x-if="submitted">
                <a href="{{ route('exercises.show', $drill) }}"
                  class="inline-flex items-center px-6 py-3 bg-gray-600 border border-transparent rounded-lg font-semibold text-sm text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                  Try Again
                </a>
              </template>

              <a href="{{ route('lessons.show', $drill->lesson->slug) }}"
                class="inline-flex items-center px-6 py-3 bg-white border border-gray-300 rounded-lg font-semibold text-sm text-gray-700 uppercase tracking-widest hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                Back to Lesson
              </a>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</x-app-layout>
