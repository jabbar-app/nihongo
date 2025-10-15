<x-app-layout>
  <div class="py-8">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
      <!-- Breadcrumb -->
      <x-breadcrumb :items="[
        ['label' => 'Lessons']
      ]" />

      <!-- Page Header -->
      <div class="mb-8">
        <h1 class="text-2xl font-semibold text-gray-900">Lessons</h1>
      </div>

      <!-- Progress Stats -->
      <div class="mb-8 flex items-center gap-6 text-sm text-gray-600">
        <div>
          <span class="font-medium text-gray-900">{{ $conversationsMastered }}</span> /
          {{ $totalConversations }} completed
        </div>
        <div class="h-4 w-px bg-gray-300"></div>
        <div>
          <span class="font-medium text-gray-900">{{ $speakingStreak }}</span> day streak
        </div>
        <div class="h-4 w-px bg-gray-300"></div>
        <div>
          <span class="font-medium text-gray-900">{{ $totalSpeakingTime }}</span> hrs practice
        </div>
      </div>

      <!-- Lessons List -->
      <div class="space-y-10">
        @forelse($lessons as $lesson)
          <x-lesson-card :lesson="$lesson" />
        @empty
          <div class="text-center py-12">
            <p class="text-gray-500 mb-4">No lessons available yet</p>
            <a href="{{ route('dashboard') }}" class="text-indigo-600 hover:text-indigo-700 font-medium">
              Return to Dashboard
            </a>
          </div>
        @endforelse
      </div>
    </div>
  </div>
</x-app-layout>
