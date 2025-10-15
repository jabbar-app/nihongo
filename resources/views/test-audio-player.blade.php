<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Audio Player Components Test</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50 min-h-screen py-8">
    <div class="max-w-4xl mx-auto px-4 space-y-8">
        <!-- Header -->
        <div class="text-center mb-12">
            <h1 class="text-4xl font-bold text-gray-900 mb-2">Audio Player Components</h1>
            <p class="text-gray-600">Test page for the enhanced audio player components</p>
        </div>

        <!-- Enhanced Audio Player - Full Featured -->
        <section class="bg-white rounded-lg shadow-lg p-6">
            <h2 class="text-2xl font-semibold text-gray-900 mb-4">Enhanced Audio Player (Full)</h2>
            <p class="text-gray-600 mb-6">Full-featured player with all controls and waveform visualization</p>
            
            <x-audio-player-enhanced 
                audioUrl="https://www.soundhelix.com/examples/mp3/SoundHelix-Song-1.mp3"
                title="Sample Audio Track"
                :showSpeedControl="true"
                :showWaveform="true"
                :compact="false"
            />
        </section>

        <!-- Enhanced Audio Player - Compact -->
        <section class="bg-white rounded-lg shadow-lg p-6">
            <h2 class="text-2xl font-semibold text-gray-900 mb-4">Enhanced Audio Player (Compact)</h2>
            <p class="text-gray-600 mb-6">Compact version without waveform</p>
            
            <x-audio-player-enhanced 
                audioUrl="https://www.soundhelix.com/examples/mp3/SoundHelix-Song-2.mp3"
                title="Compact Player"
                :showSpeedControl="true"
                :showWaveform="false"
                :compact="true"
            />
        </section>

        <!-- Mini Audio Player -->
        <section class="bg-white rounded-lg shadow-lg p-6">
            <h2 class="text-2xl font-semibold text-gray-900 mb-4">Mini Audio Player (Mobile-Optimized)</h2>
            <p class="text-gray-600 mb-6">Compact player designed for mobile with sticky positioning</p>
            
            <div class="border-2 border-dashed border-gray-300 rounded-lg p-4 bg-gray-50">
                <p class="text-sm text-gray-500 mb-4">Scroll down to see sticky behavior</p>
                <x-audio-player-mini 
                    audioUrl="https://www.soundhelix.com/examples/mp3/SoundHelix-Song-3.mp3"
                    title="Mobile Mini Player"
                    :sticky="true"
                />
            </div>
        </section>

        <!-- Usage Examples -->
        <section class="bg-white rounded-lg shadow-lg p-6">
            <h2 class="text-2xl font-semibold text-gray-900 mb-4">Usage Examples</h2>
            
            <div class="space-y-4">
                <div>
                    <h3 class="text-lg font-semibold text-gray-800 mb-2">Enhanced Player</h3>
                    <pre class="bg-gray-900 text-gray-100 p-4 rounded-lg overflow-x-auto text-sm"><code>&lt;x-audio-player-enhanced 
    audioUrl="/audio/dialogue.mp3"
    title="Asking for Directions"
    :showSpeedControl="true"
    :showWaveform="true"
/&gt;</code></pre>
                </div>

                <div>
                    <h3 class="text-lg font-semibold text-gray-800 mb-2">Mini Player</h3>
                    <pre class="bg-gray-900 text-gray-100 p-4 rounded-lg overflow-x-auto text-sm"><code>&lt;x-audio-player-mini 
    audioUrl="/audio/dialogue.mp3"
    title="Asking for Directions"
    :sticky="true"
/&gt;</code></pre>
                </div>
            </div>
        </section>

        <!-- Features List -->
        <section class="bg-white rounded-lg shadow-lg p-6">
            <h2 class="text-2xl font-semibold text-gray-900 mb-4">Features</h2>
            
            <div class="grid md:grid-cols-2 gap-6">
                <div>
                    <h3 class="text-lg font-semibold text-gray-800 mb-3">Enhanced Player</h3>
                    <ul class="space-y-2 text-gray-600">
                        <li class="flex items-start gap-2">
                            <svg class="w-5 h-5 text-green-500 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                            <span>Play/pause with visual feedback</span>
                        </li>
                        <li class="flex items-start gap-2">
                            <svg class="w-5 h-5 text-green-500 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                            <span>Seekable progress bar</span>
                        </li>
                        <li class="flex items-start gap-2">
                            <svg class="w-5 h-5 text-green-500 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                            <span>Speed controls (0.75x, 1x, 1.25x)</span>
                        </li>
                        <li class="flex items-start gap-2">
                            <svg class="w-5 h-5 text-green-500 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                            <span>Waveform visualization</span>
                        </li>
                        <li class="flex items-start gap-2">
                            <svg class="w-5 h-5 text-green-500 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                            <span>Keyboard shortcuts</span>
                        </li>
                        <li class="flex items-start gap-2">
                            <svg class="w-5 h-5 text-green-500 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                            <span>Loading states</span>
                        </li>
                    </ul>
                </div>

                <div>
                    <h3 class="text-lg font-semibold text-gray-800 mb-3">Mini Player</h3>
                    <ul class="space-y-2 text-gray-600">
                        <li class="flex items-start gap-2">
                            <svg class="w-5 h-5 text-green-500 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                            <span>Compact mobile design</span>
                        </li>
                        <li class="flex items-start gap-2">
                            <svg class="w-5 h-5 text-green-500 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                            <span>Sticky positioning</span>
                        </li>
                        <li class="flex items-start gap-2">
                            <svg class="w-5 h-5 text-green-500 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                            <span>Touch-friendly (44px targets)</span>
                        </li>
                        <li class="flex items-start gap-2">
                            <svg class="w-5 h-5 text-green-500 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                            <span>One-tap speed cycling</span>
                        </li>
                        <li class="flex items-start gap-2">
                            <svg class="w-5 h-5 text-green-500 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                            <span>Safe area support</span>
                        </li>
                        <li class="flex items-start gap-2">
                            <svg class="w-5 h-5 text-green-500 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                            <span>Backdrop blur effect</span>
                        </li>
                    </ul>
                </div>
            </div>
        </section>

        <!-- Keyboard Shortcuts -->
        <section class="bg-white rounded-lg shadow-lg p-6">
            <h2 class="text-2xl font-semibold text-gray-900 mb-4">Keyboard Shortcuts</h2>
            <div class="grid md:grid-cols-3 gap-4">
                <div class="flex items-center gap-3 p-3 bg-gray-50 rounded-lg">
                    <kbd class="kbd">Space</kbd>
                    <span class="text-gray-600">Play/Pause</span>
                </div>
                <div class="flex items-center gap-3 p-3 bg-gray-50 rounded-lg">
                    <kbd class="kbd">←</kbd>
                    <span class="text-gray-600">Seek -5s</span>
                </div>
                <div class="flex items-center gap-3 p-3 bg-gray-50 rounded-lg">
                    <kbd class="kbd">→</kbd>
                    <span class="text-gray-600">Seek +5s</span>
                </div>
            </div>
        </section>

        <!-- Spacer for sticky demo -->
        <div class="h-96 bg-gradient-to-b from-gray-50 to-white rounded-lg flex items-center justify-center">
            <p class="text-gray-400 text-lg">Scroll to see sticky player behavior</p>
        </div>
    </div>
</body>
</html>
