<x-app-layout>
  <div class="py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <!-- Breadcrumb -->
      <x-breadcrumb :items="[
        ['label' => 'Flashcards']
      ]" />

      <!-- Header -->
      <div class="mb-6">
        <div class="flex items-center justify-between gap-4">
          <h1 class="text-3xl font-bold text-gray-900">My Flashcards</h1>
          <div class="flex items-center gap-2 sm:gap-3">
            <a href="{{ route('flashcards.review', ['mode' => 'due']) }}"
              class="inline-flex items-center px-4 py-2 bg-orange-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-orange-700 focus:bg-orange-700 active:bg-orange-800 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:ring-offset-2 transition ease-in-out duration-150">
              <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
              </svg>
              Review Due
            </a>
            <a href="{{ route('flashcards.review', ['mode' => 'new']) }}"
              class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-800 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
              <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
              </svg>
              Learn New
            </a>
            <a href="{{ route('flashcards.review') }}"
              class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
              <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
              </svg>
              Mixed Session
            </a>
            <a href="{{ route('flashcards.create') }}"
              class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-900 focus:bg-gray-900 active:bg-black focus:outline-none focus:ring-2 focus:ring-gray-700 focus:ring-offset-2 transition ease-in-out duration-150">
              <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
              </svg>
              Create
            </a>
          </div>
        </div>
      </div>

      <!-- Statistics Cards -->
      <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-8">
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
          <div class="flex items-center justify-between">
            <div>
              <p class="text-sm font-medium text-gray-600">Total Cards</p>
              <p class="text-3xl font-bold text-gray-900 mt-1">{{ $totalCards }}</p>
            </div>
            <div class="p-3 bg-indigo-100 rounded-full">
              <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
              </svg>
            </div>
          </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
          <div class="flex items-center justify-between">
            <div>
              <p class="text-sm font-medium text-gray-600">Due Today</p>
              <p class="text-3xl font-bold text-orange-600 mt-1">{{ $dueCards }}</p>
            </div>
            <div class="p-3 bg-orange-100 rounded-full">
              <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
              </svg>
            </div>
          </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
          <div class="flex items-center justify-between">
            <div>
              <p class="text-sm font-medium text-gray-600">New Cards</p>
              <p class="text-3xl font-bold text-blue-600 mt-1">{{ $newCards }}</p>
            </div>
            <div class="p-3 bg-blue-100 rounded-full">
              <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
              </svg>
            </div>
          </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
          <div class="flex items-center justify-between">
            <div>
              <p class="text-sm font-medium text-gray-600">Mastered</p>
              <p class="text-3xl font-bold text-green-600 mt-1">{{ $masteredCards }}</p>
            </div>
            <div class="p-3 bg-green-100 rounded-full">
              <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
              </svg>
            </div>
          </div>
        </div>
      </div>

      <!-- Flashcard Decks -->
      @if ($flashcards->count() > 0)
        <div class="space-y-6">
          @foreach ($flashcards as $lessonTitle => $cards)
            <div class="bg-white rounded-lg shadow-sm border border-gray-200">
              <div class="px-6 py-4 border-b border-gray-200">
                <div class="flex items-center justify-between">
                  <h2 class="text-xl font-semibold text-gray-900">{{ $lessonTitle }}</h2>
                  <span class="text-sm text-gray-600">{{ $cards->count() }} cards</span>
                </div>
              </div>

              <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                  @foreach ($cards as $flashcard)
                    <div class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition-shadow">
                      <div class="mb-3">
                        <div class="text-lg font-semibold text-gray-900 mb-1">{{ $flashcard->front }}</div>
                        <div class="text-sm text-gray-600 mb-1">{{ $flashcard->romaji }}</div>
                        <div class="text-sm text-gray-700">{{ $flashcard->back }}</div>
                      </div>

                      <div class="flex items-center justify-between pt-3 border-t border-gray-200">
                        <div class="text-xs text-gray-500">
                          @if ($flashcard->repetitions == 0)
                            <span class="px-2 py-1 bg-blue-100 text-blue-700 rounded">New</span>
                          @elseif($flashcard->ease_factor >= 2.5 && $flashcard->interval >= 21)
                            <span class="px-2 py-1 bg-green-100 text-green-700 rounded">Mastered</span>
                          @elseif($flashcard->next_review_at <= now())
                            <span class="px-2 py-1 bg-orange-100 text-orange-700 rounded">Due</span>
                          @else
                            <span class="px-2 py-1 bg-gray-100 text-gray-700 rounded">Learning</span>
                          @endif
                        </div>

                        <form action="{{ route('flashcards.destroy', $flashcard) }}" method="POST"
                          onsubmit="return confirm('Are you sure you want to delete this flashcard?');">
                          @csrf
                          @method('DELETE')
                          <button type="submit"
                            class="text-red-600 hover:text-red-800 text-sm font-medium transition-colors">
                            Delete
                          </button>
                        </form>
                      </div>
                    </div>
                  @endforeach
                </div>
              </div>
            </div>
          @endforeach
        </div>
      @else
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-12 text-center">
          <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
          </svg>
          <h3 class="mt-2 text-sm font-medium text-gray-900">No flashcards</h3>
          <p class="mt-1 text-sm text-gray-500">Get started by creating your first flashcard.</p>
          <div class="mt-6">
            <a href="{{ route('flashcards.create') }}"
              class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
              <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
              </svg>
              Create Flashcard
            </a>
          </div>
        </div>
      @endif
    </div>
  </div>
</x-app-layout>
