<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="mb-8">
                <h1 class="text-3xl font-bold text-gray-900">Lessons</h1>
                <p class="mt-2 text-gray-600">Choose a lesson to start learning Japanese</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse($lessons as $lesson)
                    <x-lesson-card :lesson="$lesson" />
                @empty
                    <div class="col-span-full text-center py-12">
                        <p class="text-gray-500">No lessons available yet.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</x-app-layout>
