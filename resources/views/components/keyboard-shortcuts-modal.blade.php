<!-- Keyboard Shortcuts Help Modal -->
<div x-data="keyboardShortcuts()" x-init="init()">
    <!-- Help Button (Fixed Position) -->
    <button 
        @click="toggleHelp()"
        :aria-expanded="showHelp.toString()"
        class="fixed bottom-4 right-4 z-40 inline-flex items-center justify-center w-12 h-12 bg-indigo-600 hover:bg-indigo-700 text-white rounded-full shadow-lg transition-all duration-200 hover:scale-110 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
        aria-label="Show keyboard shortcuts"
        title="Keyboard shortcuts (?)">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"></path>
        </svg>
    </button>

    <!-- Modal Overlay -->
    <div 
        x-show="showHelp"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        @click="showHelp = false"
        @keydown.escape="showHelp = false"
        role="dialog"
        aria-modal="true"
        aria-labelledby="shortcuts-title"
        class="fixed inset-0 z-50 bg-gray-900 bg-opacity-50 flex items-center justify-center p-4"
        style="display: none;">
        
        <!-- Modal Content -->
        <div 
            @click.stop
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 transform scale-95"
            x-transition:enter-end="opacity-100 transform scale-100"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100 transform scale-100"
            x-transition:leave-end="opacity-0 transform scale-95"
            class="bg-white rounded-lg shadow-xl max-w-3xl w-full max-h-[90vh] overflow-y-auto">
            
            <!-- Header -->
            <header class="sticky top-0 bg-white border-b border-gray-200 px-6 py-4 flex items-center justify-between">
                <h2 id="shortcuts-title" class="text-2xl font-bold text-gray-900">Keyboard Shortcuts</h2>
                <button 
                    @click="showHelp = false"
                    class="text-gray-400 hover:text-gray-600 transition focus:outline-none focus:ring-2 focus:ring-indigo-500 rounded-lg p-1 min-h-[44px] min-w-[44px] flex items-center justify-center"
                    aria-label="Close keyboard shortcuts dialog">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </header>

            <!-- Shortcuts List -->
            <div class="px-6 py-4 space-y-6">
                <template x-for="category in shortcuts" :key="category.category">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-3" x-text="category.category"></h3>
                        <div class="space-y-2">
                            <template x-for="shortcut in category.items" :key="shortcut.action">
                                <div class="flex items-center justify-between py-2 px-3 rounded-lg hover:bg-gray-50">
                                    <span class="text-gray-700" x-text="shortcut.description"></span>
                                    <div class="flex items-center gap-1">
                                        <template x-if="isMac() && shortcut.mac">
                                            <template x-for="key in shortcut.mac" :key="key">
                                                <kbd class="px-2 py-1 text-sm font-semibold text-gray-800 bg-gray-100 border border-gray-300 rounded-lg shadow-sm" x-text="key"></kbd>
                                            </template>
                                        </template>
                                        <template x-if="!isMac() || !shortcut.mac">
                                            <template x-for="key in shortcut.keys" :key="key">
                                                <kbd class="px-2 py-1 text-sm font-semibold text-gray-800 bg-gray-100 border border-gray-300 rounded-lg shadow-sm" x-text="key"></kbd>
                                            </template>
                                        </template>
                                    </div>
                                </div>
                            </template>
                        </div>
                    </div>
                </template>
            </div>

            <!-- Footer -->
            <div class="sticky bottom-0 bg-gray-50 border-t border-gray-200 px-6 py-4">
                <p class="text-sm text-gray-600 text-center">
                    Press <kbd class="px-2 py-1 text-xs font-semibold text-gray-800 bg-white border border-gray-300 rounded shadow-sm">?</kbd> 
                    anytime to show this help, or 
                    <kbd class="px-2 py-1 text-xs font-semibold text-gray-800 bg-white border border-gray-300 rounded shadow-sm">Esc</kbd> 
                    to close
                </p>
            </div>
        </div>
    </div>
</div>
