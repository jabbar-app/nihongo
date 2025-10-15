<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Bookmarks') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Breadcrumb -->
            <x-breadcrumb :items="[
                ['label' => 'Bookmarks']
            ]" />
            @if($bookmarks->isEmpty())
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900 text-center">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z" />
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900">No bookmarks</h3>
                        <p class="mt-1 text-sm text-gray-500">Start bookmarking conversations and content to access them quickly for speaking practice.</p>
                        <div class="mt-6">
                            <a href="{{ route('lessons.index') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700">
                                Browse Conversations
                            </a>
                        </div>
                    </div>
                </div>
            @else
                <div class="space-y-6">
                    @foreach($bookmarks as $type => $items)
                        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                            <div class="p-6">
                                <h3 class="text-lg font-semibold text-gray-900 mb-4">
                                    {{ ucfirst(class_basename($type)) }}s
                                </h3>
                                <div class="space-y-3">
                                    @foreach($items as $bookmark)
                                        @php
                                            $model = $bookmark->bookmarkable;
                                            $modelType = class_basename($type);
                                        @endphp
                                        
                                        <div class="flex items-start justify-between p-4 border border-gray-200 rounded-lg hover:bg-gray-50">
                                            <div class="flex-1">
                                                @if($modelType === 'Lesson')
                                                    <a href="{{ route('lessons.show', $model->slug) }}" class="text-indigo-600 hover:text-indigo-900 font-medium">
                                                        {{ $model->title }}
                                                    </a>
                                                    <p class="text-sm text-gray-500 mt-1">{{ $model->description }}</p>
                                                @elseif($modelType === 'Phrase')
                                                    <a href="{{ route('lessons.show', $model->lesson->slug) }}#phrases" class="text-indigo-600 hover:text-indigo-900 font-medium">
                                                        {{ $model->japanese }} ({{ $model->romaji }})
                                                    </a>
                                                    <p class="text-sm text-gray-500 mt-1">{{ $model->english }}</p>
                                                    <p class="text-xs text-gray-400 mt-1">From: {{ $model->lesson->title }}</p>
                                                @elseif($modelType === 'Dialogue')
                                                    <a href="{{ route('lessons.show', $model->lesson->slug) }}#dialogues" class="text-indigo-600 hover:text-indigo-900 font-medium">
                                                        {{ $model->title }}
                                                    </a>
                                                    <p class="text-xs text-gray-400 mt-1">From: {{ $model->lesson->title }}</p>
                                                @elseif($modelType === 'Drill')
                                                    <a href="{{ route('exercises.show', $model->id) }}" class="text-indigo-600 hover:text-indigo-900 font-medium">
                                                        {{ $model->title }}
                                                    </a>
                                                    <p class="text-sm text-gray-500 mt-1">Type: {{ ucfirst($model->type) }}</p>
                                                    <p class="text-xs text-gray-400 mt-1">From: {{ $model->lesson->title }}</p>
                                                @elseif($modelType === 'ShadowingExercise')
                                                    <a href="{{ route('shadowing.show', $model->id) }}" class="text-indigo-600 hover:text-indigo-900 font-medium">
                                                        {{ $model->title }}
                                                    </a>
                                                    <p class="text-xs text-gray-400 mt-1">From: {{ $model->lesson->title }}</p>
                                                @endif
                                                
                                                @if($bookmark->notes)
                                                    <p class="text-sm text-gray-600 mt-2 italic">{{ $bookmark->notes }}</p>
                                                @endif
                                                
                                                <p class="text-xs text-gray-400 mt-2">
                                                    Bookmarked {{ $bookmark->created_at->diffForHumans() }}
                                                </p>
                                            </div>
                                            
                                            <div class="ml-4">
                                                <form action="{{ route('bookmarks.destroy', $bookmark) }}" method="POST" class="inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('Remove this bookmark?')">
                                                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                        </svg>
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
            @endif
        </div>
    </div>
</x-app-layout>
