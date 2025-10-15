<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Search Results') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Breadcrumb -->
            <x-breadcrumb :items="[
                ['label' => 'Search']
            ]" />
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <!-- Search Summary -->
                    <div class="mb-6">
                        @if($query)
                            <p class="text-gray-600">
                                Found <span class="font-semibold text-gray-900">{{ $results['total'] }}</span> results for 
                                "<span class="font-semibold text-gray-900">{{ $query }}</span>"
                                <span class="text-sm text-gray-500">({{ $searchTime }}ms)</span>
                            </p>
                        @else
                            <p class="text-gray-600">Enter a search query to find phrases, dialogues, and drills.</p>
                        @endif
                    </div>

                    @if($query && $results['total'] > 0)
                        <!-- Phrases Results -->
                        @if($results['phrases']->count() > 0)
                            <div class="mb-8">
                                <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                                    <svg class="w-5 h-5 mr-2 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"></path>
                                    </svg>
                                    Phrases ({{ $results['phrases']->count() }})
                                </h3>
                                <div class="space-y-3">
                                    @foreach($results['phrases'] as $phrase)
                                        <div class="border border-gray-200 rounded-lg p-4 hover:border-indigo-300 transition-colors">
                                            <a href="{{ route('lessons.show', $phrase->lesson->slug) }}#phrases" class="block">
                                                <div class="flex justify-between items-start mb-2">
                                                    <div class="flex-1">
                                                        <p class="text-lg font-medium text-gray-900">
                                                            {!! str_ireplace($query, '<mark class="bg-yellow-200 px-1 rounded">' . $query . '</mark>', e($phrase->japanese)) !!}
                                                        </p>
                                                        <p class="text-sm text-gray-600">
                                                            {!! str_ireplace($query, '<mark class="bg-yellow-200 px-1 rounded">' . $query . '</mark>', e($phrase->romaji)) !!}
                                                        </p>
                                                        <p class="text-sm text-gray-700 mt-1">
                                                            {!! str_ireplace($query, '<mark class="bg-yellow-200 px-1 rounded">' . $query . '</mark>', e($phrase->english)) !!}
                                                        </p>
                                                        @if($phrase->notes)
                                                            <p class="text-xs text-gray-500 mt-1">
                                                                {!! str_ireplace($query, '<mark class="bg-yellow-200 px-1 rounded">' . $query . '</mark>', e($phrase->notes)) !!}
                                                            </p>
                                                        @endif
                                                    </div>
                                                    <div class="ml-4 flex-shrink-0">
                                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800">
                                                            {{ $phrase->lesson->title }}
                                                        </span>
                                                    </div>
                                                </div>
                                            </a>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        <!-- Dialogues Results -->
                        @if($results['dialogues']->count() > 0)
                            <div class="mb-8">
                                <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                                    <svg class="w-5 h-5 mr-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8h2a2 2 0 012 2v6a2 2 0 01-2 2h-2v4l-4-4H9a1.994 1.994 0 01-1.414-.586m0 0L11 14h4a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2v4l.586-.586z"></path>
                                    </svg>
                                    Dialogues ({{ $results['dialogues']->count() }})
                                </h3>
                                <div class="space-y-3">
                                    @foreach($results['dialogues'] as $dialogue)
                                        <div class="border border-gray-200 rounded-lg p-4 hover:border-green-300 transition-colors">
                                            <a href="{{ route('lessons.show', $dialogue->lesson->slug) }}#dialogues" class="block">
                                                <div class="flex justify-between items-start mb-2">
                                                    <div class="flex-1">
                                                        <p class="text-lg font-medium text-gray-900 mb-2">
                                                            {!! str_ireplace($query, '<mark class="bg-yellow-200 px-1 rounded">' . $query . '</mark>', e($dialogue->title)) !!}
                                                        </p>
                                                        @if($dialogue->content && is_array($dialogue->content))
                                                            <div class="space-y-1">
                                                                @foreach(array_slice($dialogue->content, 0, 3) as $line)
                                                                    <p class="text-sm text-gray-700">
                                                                        <span class="font-medium">{{ $line['speaker'] ?? '' }}:</span>
                                                                        {!! str_ireplace($query, '<mark class="bg-yellow-200 px-1 rounded">' . $query . '</mark>', e($line['line'] ?? '')) !!}
                                                                    </p>
                                                                @endforeach
                                                                @if(count($dialogue->content) > 3)
                                                                    <p class="text-xs text-gray-500 italic">... and {{ count($dialogue->content) - 3 }} more lines</p>
                                                                @endif
                                                            </div>
                                                        @endif
                                                    </div>
                                                    <div class="ml-4 flex-shrink-0">
                                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                            {{ $dialogue->lesson->title }}
                                                        </span>
                                                    </div>
                                                </div>
                                            </a>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        <!-- Drills Results -->
                        @if($results['drills']->count() > 0)
                            <div class="mb-8">
                                <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                                    <svg class="w-5 h-5 mr-2 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                                    </svg>
                                    Drills ({{ $results['drills']->count() }})
                                </h3>
                                <div class="space-y-3">
                                    @foreach($results['drills'] as $drill)
                                        <div class="border border-gray-200 rounded-lg p-4 hover:border-purple-300 transition-colors">
                                            <a href="{{ route('exercises.show', $drill->id) }}" class="block">
                                                <div class="flex justify-between items-start mb-2">
                                                    <div class="flex-1">
                                                        <div class="flex items-center gap-2 mb-2">
                                                            <p class="text-lg font-medium text-gray-900">
                                                                {!! str_ireplace($query, '<mark class="bg-yellow-200 px-1 rounded">' . $query . '</mark>', e($drill->title)) !!}
                                                            </p>
                                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-purple-100 text-purple-800">
                                                                {{ ucfirst($drill->type) }}
                                                            </span>
                                                        </div>
                                                        @if($drill->content)
                                                            <p class="text-sm text-gray-600">
                                                                @php
                                                                    $contentPreview = is_array($drill->content) 
                                                                        ? implode(' ', array_slice(array_values($drill->content), 0, 2))
                                                                        : (is_string($drill->content) ? $drill->content : '');
                                                                    $contentPreview = substr($contentPreview, 0, 150);
                                                                @endphp
                                                                {!! str_ireplace($query, '<mark class="bg-yellow-200 px-1 rounded">' . $query . '</mark>', e($contentPreview)) !!}
                                                                @if(strlen($contentPreview) >= 150)...@endif
                                                            </p>
                                                        @endif
                                                    </div>
                                                    <div class="ml-4 flex-shrink-0">
                                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                                            {{ $drill->lesson->title }}
                                                        </span>
                                                    </div>
                                                </div>
                                            </a>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    @elseif($query && $results['total'] === 0)
                        <!-- No Results -->
                        <div class="text-center py-12">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900">No results found</h3>
                            <p class="mt-1 text-sm text-gray-500">Try searching with different keywords or check your spelling.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
