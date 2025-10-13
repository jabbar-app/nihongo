@props(['lesson'])

<a href="{{ route('lessons.show', $lesson->slug) }}" 
   class="block bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden hover-lift card-hover">
    <div class="p-6">
        <div class="flex items-center justify-between mb-3">
            <span class="inline-flex items-center justify-center w-10 h-10 rounded-full bg-indigo-100 text-indigo-600 font-semibold text-sm">
                {{ $lesson->order }}
            </span>
        </div>
        
        <h3 class="text-lg font-semibold text-gray-900 mb-2">
            {{ $lesson->title }}
        </h3>
        
        @if($lesson->description)
            <p class="text-sm text-gray-600 line-clamp-2">
                {{ $lesson->description }}
            </p>
        @endif
        
        <div class="mt-4 flex items-center text-sm text-indigo-600 font-medium">
            <span>Start lesson</span>
            <svg class="ml-1 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
            </svg>
        </div>
    </div>
</a>
