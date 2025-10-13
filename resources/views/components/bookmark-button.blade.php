@props(['type', 'id', 'bookmarked' => false])

<div x-data="bookmarkButton('{{ $type }}', {{ $id }}, {{ $bookmarked ? 'true' : 'false' }})">
    <button 
        @click="toggle"
        :disabled="loading"
        :aria-label="bookmarked ? 'Remove bookmark' : 'Add bookmark'"
        :aria-pressed="bookmarked.toString()"
        role="button"
        class="inline-flex items-center px-3 py-2 border border-gray-300 rounded-md text-xs sm:text-sm font-medium transition touch-manipulation min-h-[44px] active:scale-95"
        :class="bookmarked ? 'bg-yellow-50 text-yellow-700 border-yellow-300 hover:bg-yellow-100 active:bg-yellow-200' : 'bg-white text-gray-700 hover:bg-gray-50 active:bg-gray-100'"
    >
        <svg class="w-4 h-4 sm:w-5 sm:h-5 mr-1" :class="bookmarked ? 'fill-current' : 'fill-none'" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z" />
        </svg>
        <span class="hidden sm:inline" x-text="bookmarked ? 'Bookmarked' : 'Bookmark'"></span>
        <span class="sm:hidden">
            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20" x-show="bookmarked" aria-hidden="true">
                <path d="M5 4a2 2 0 012-2h6a2 2 0 012 2v14l-5-2.5L5 18V4z"/>
            </svg>
        </span>
        <span class="sr-only" x-text="loading ? 'Loading...' : ''"></span>
    </button>
</div>

@push('scripts')
<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('bookmarkButton', (type, id, initialBookmarked) => ({
        bookmarked: initialBookmarked,
        loading: false,

        async toggle() {
            this.loading = true;
            
            try {
                const response = await fetch('{{ route("bookmarks.toggle") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify({
                        type: type,
                        id: id,
                    }),
                });

                const data = await response.json();
                this.bookmarked = data.bookmarked;
            } catch (error) {
                console.error('Error toggling bookmark:', error);
            } finally {
                this.loading = false;
            }
        }
    }));
});
</script>
@endpush
