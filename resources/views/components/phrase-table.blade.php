@props(['phrases'])

<!-- Desktop table view -->
<div class="hidden sm:block overflow-x-auto">
    <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
            <tr>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    Japanese
                </th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    Romaji
                </th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    English
                </th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    Notes
                </th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    Audio
                </th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
            @foreach($phrases as $phrase)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm font-medium text-gray-900">
                            {{ $phrase->japanese }}
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm text-gray-600 italic">
                            {{ $phrase->romaji }}
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <div class="text-sm text-gray-900">
                            {{ $phrase->english }}
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        @if($phrase->notes)
                            <div class="text-sm text-gray-500">
                                {{ $phrase->notes }}
                            </div>
                        @else
                            <span class="text-gray-400">—</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <x-audio-button 
                            :text="$phrase->japanese" 
                            lang="ja-JP" 
                            size="sm"
                            variant="primary">
                            Play
                        </x-audio-button>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

<!-- Mobile-friendly card layout for small screens -->
<div class="sm:hidden space-y-4">
    @foreach($phrases as $phrase)
        <div class="bg-white border border-gray-200 rounded-lg p-4">
            <div class="mb-2">
                <div class="flex items-start justify-between">
                    <div class="flex-1">
                        <div class="text-lg font-medium text-gray-900 mb-1">
                            {{ $phrase->japanese }}
                        </div>
                        <div class="text-sm text-gray-600 italic">
                            {{ $phrase->romaji }}
                        </div>
                    </div>
                    <div class="ml-2">
                        <x-audio-button 
                            :text="$phrase->japanese" 
                            lang="ja-JP" 
                            size="sm"
                            variant="primary">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM9.555 7.168A1 1 0 008 8v4a1 1 0 001.555.832l3-2a1 1 0 000-1.664l-3-2z" clip-rule="evenodd" />
                            </svg>
                        </x-audio-button>
                    </div>
                </div>
            </div>
            <div class="text-sm text-gray-900 mb-2">
                {{ $phrase->english }}
            </div>
            @if($phrase->notes)
                <div class="text-xs text-gray-500 pt-2 border-t border-gray-100">
                    {{ $phrase->notes }}
                </div>
            @endif
        </div>
    @endforeach
</div>
