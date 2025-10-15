<x-app-layout>
  <div class="py-12">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
      <!-- Breadcrumb -->
      <x-breadcrumb :items="[
        ['label' => 'Flashcards', 'url' => route('flashcards.index')],
        ['label' => 'Create']
      ]" />

      <!-- Header -->
      <div class="mb-6">
        <div class="flex items-center justify-between">
          <div>
            <a href="{{ route('flashcards.index') }}"
              class="text-sm text-gray-600 hover:text-gray-900 flex items-center mb-2">
              <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
              </svg>
              Back to Flashcards
            </a>
            <h1 class="text-3xl font-bold text-gray-900">Practice Speaking These Phrases</h1>
            @if ($lesson)
              <p class="mt-1 text-gray-600">From conversation: {{ $lesson->title }}</p>
            @endif
          </div>
        </div>
      </div>

      <div x-data="{ mode: '{{ $lesson ? 'bulk' : 'manual' }}' }">
        <!-- Mode Toggle -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-6">
          <div class="flex items-center space-x-4">
            <button @click="mode = 'bulk'" :class="mode === 'bulk' ? 'bg-indigo-600 text-white' :
                'bg-gray-100 text-gray-700 hover:bg-gray-200'"
              class="px-4 py-2 rounded-md font-medium text-sm transition-colors">
              Bulk Create from Phrases
            </button>
            <button @click="mode = 'manual'" :class="mode === 'manual' ? 'bg-indigo-600 text-white' :
                'bg-gray-100 text-gray-700 hover:bg-gray-200'"
              class="px-4 py-2 rounded-md font-medium text-sm transition-colors">
              Create Custom Card
            </button>
          </div>
        </div>

        <form action="{{ route('flashcards.store') }}" method="POST">
          @csrf

          <!-- Bulk Creation Mode -->
          <div x-show="mode === 'bulk'" x-cloak>
            @if ($lesson && $phrases->count() > 0)
              <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <div class="mb-4">
                  <h2 class="text-lg font-semibold text-gray-900 mb-2">Select Phrases to Practice</h2>
                  <p class="text-sm text-gray-600">Choose the phrases you want to practice speaking.</p>
                </div>

                <div class="space-y-3 max-h-96 overflow-y-auto">
                  @foreach ($phrases as $phrase)
                    <label class="flex items-start p-4 border border-gray-200 rounded-lg hover:bg-gray-50 cursor-pointer">
                      <input type="checkbox" name="phrase_ids[]" value="{{ $phrase->id }}"
                        class="mt-1 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                      <div class="ml-3 flex-1">
                        <div class="font-medium text-gray-900">{{ $phrase->japanese }}</div>
                        <div class="text-sm text-gray-600">{{ $phrase->romaji }}</div>
                        <div class="text-sm text-gray-700 mt-1">{{ $phrase->english }}</div>
                        @if ($phrase->notes)
                          <div class="text-xs text-gray-500 mt-1">{{ $phrase->notes }}</div>
                        @endif
                      </div>
                    </label>
                  @endforeach
                </div>

                <div class="mt-6 flex items-center justify-between">
                  <a href="{{ route('flashcards.index') }}"
                    class="text-sm text-gray-600 hover:text-gray-900 font-medium">
                    Cancel
                  </a>
                  <x-primary-button>
                    Create Selected Flashcards
                  </x-primary-button>
                </div>
              </div>
            @else
              <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-12 text-center">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900">No lesson selected</h3>
                <p class="mt-1 text-sm text-gray-500">Go to a lesson and click "Practice Speaking These Phrases" to create flashcards from
                  phrases.</p>
                <div class="mt-6">
                  <a href="{{ route('lessons.index') }}"
                    class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700">
                    Browse Conversations
                  </a>
                </div>
              </div>
            @endif
          </div>

          <!-- Manual Creation Mode -->
          <div x-show="mode === 'manual'" x-cloak>
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
              <div class="mb-4">
                <h2 class="text-lg font-semibold text-gray-900 mb-2">Create Custom Flashcard</h2>
                <p class="text-sm text-gray-600">Create a flashcard with your own content.</p>
              </div>

              <div class="space-y-4">
                <div>
                  <x-input-label for="front" value="Front (Japanese)" />
                  <x-text-input id="front" name="front" type="text" class="mt-1 block w-full"
                    :value="old('front')" required />
                  <x-input-error class="mt-2" :messages="$errors->get('front')" />
                </div>

                <div>
                  <x-input-label for="romaji" value="Romaji (Optional)" />
                  <x-text-input id="romaji" name="romaji" type="text" class="mt-1 block w-full"
                    :value="old('romaji')" />
                  <x-input-error class="mt-2" :messages="$errors->get('romaji')" />
                </div>

                <div>
                  <x-input-label for="back" value="Back (English)" />
                  <x-text-input id="back" name="back" type="text" class="mt-1 block w-full"
                    :value="old('back')" required />
                  <x-input-error class="mt-2" :messages="$errors->get('back')" />
                </div>
              </div>

              <div class="mt-6 flex items-center justify-between">
                <a href="{{ route('flashcards.index') }}"
                  class="text-sm text-gray-600 hover:text-gray-900 font-medium">
                  Cancel
                </a>
                <x-primary-button>
                  Create Flashcard
                </x-primary-button>
              </div>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</x-app-layout>
