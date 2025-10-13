<x-app-layout>
  <div class="py-12">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
      <!-- Header with back navigation -->
      <div class="mb-6">
        <a href="{{ route('lessons.show', $shadowingExercise->lesson->slug) }}"
          class="text-sm text-gray-600 hover:text-gray-900 flex items-center mb-4">
          <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
          </svg>
          Back to {{ $shadowingExercise->lesson->title }}
        </a>

        <div class="flex items-center justify-between">
          <div>
            <h1 class="text-3xl font-bold text-gray-900">{{ $shadowingExercise->title }}</h1>
            <p class="mt-1 text-sm text-gray-600">
              Lesson {{ $shadowingExercise->lesson->order }}: {{ $shadowingExercise->lesson->title }}
            </p>
          </div>
          @if ($shadowingExercise->duration_seconds)
            <div class="text-right">
              <div class="text-sm text-gray-600">Duration</div>
              <div class="text-lg font-semibold text-gray-900">
                {{ gmdate('i:s', $shadowingExercise->duration_seconds) }}
              </div>
            </div>
          @endif
        </div>
      </div>

      <!-- Completion Stats -->
      @if ($completionCount > 0)
        <div class="bg-gradient-to-r from-green-50 to-emerald-50 rounded-lg p-4 mb-6">
          <div class="flex items-center justify-between">
            <div>
              <h3 class="text-sm font-medium text-gray-700 mb-1">Your Progress</h3>
              <div class="flex items-center space-x-4">
                <div>
                  <span class="text-2xl font-bold text-green-600">{{ $completionCount }}</span>
                  <span class="text-sm text-gray-600 ml-1">{{ $completionCount === 1 ? 'completion' : 'completions' }}</span>
                </div>
                @if ($lastCompletion)
                  <div class="h-8 w-px bg-gray-300"></div>
                  <div>
                    <span class="text-sm text-gray-600">Last practiced:</span>
                    <span class="text-sm font-medium text-gray-900 ml-1">
                      {{ $lastCompletion->completed_at->diffForHumans() }}
                    </span>
                  </div>
                @endif
              </div>
            </div>
          </div>
        </div>
      @endif

      <!-- Instructions -->
      <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
        <h3 class="font-semibold text-blue-900 mb-2 flex items-center">
          <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
          </svg>
          Shadowing Instructions
        </h3>
        <ul class="text-sm text-blue-900 space-y-1 ml-7">
          <li>• Listen to each line carefully and pay attention to pronunciation and rhythm</li>
          <li>• Repeat immediately after hearing (shadow) or pause and repeat</li>
          <li>• Practice multiple times - aim for 3-5 repetitions per session</li>
          <li>• Focus on natural flow and intonation, not just individual words</li>
          <li>• Use the loop feature to practice difficult sections</li>
        </ul>
      </div>

      <!-- Audio Player and Script Display -->
      <div class="bg-white rounded-lg shadow-sm border border-gray-200"
        x-data="shadowingPlayer({
          lines: {{ json_encode($shadowingExercise->content) }},
          exerciseId: {{ $shadowingExercise->id }}
        })">

        <!-- Audio Controls -->
        <div class="p-6 border-b border-gray-200 bg-gray-50">
          <div class="flex items-center justify-between mb-4">
            <h3 class="font-semibold text-gray-900">Audio Playback</h3>
            <div class="flex items-center space-x-2">
              <button @click="toggleLoop"
                :class="loopEnabled ? 'bg-indigo-100 text-indigo-700' : 'bg-gray-100 text-gray-600'"
                class="px-3 py-1 rounded-md text-sm font-medium transition-colors">
                <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                </svg>
                <span x-text="loopEnabled ? 'Loop On' : 'Loop Off'"></span>
              </button>
            </div>
          </div>

          <!-- Playback Controls -->
          <div class="flex items-center space-x-4">
            <button @click="playPause"
              class="flex items-center justify-center w-12 h-12 rounded-full bg-indigo-600 text-white hover:bg-indigo-700 transition-colors">
              <svg x-show="!isPlaying" class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                <path d="M8 5v14l11-7z" />
              </svg>
              <svg x-show="isPlaying" class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                <path d="M6 4h4v16H6V4zm8 0h4v16h-4V4z" />
              </svg>
            </button>

            <button @click="stop"
              class="flex items-center justify-center w-10 h-10 rounded-full bg-gray-200 text-gray-700 hover:bg-gray-300 transition-colors">
              <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                <path d="M6 6h12v12H6z" />
              </svg>
            </button>

            <div class="flex-1">
              <div class="text-sm text-gray-600 mb-1">
                Line <span x-text="currentLineIndex + 1"></span> of <span x-text="lines.length"></span>
              </div>
              <div class="w-full bg-gray-200 rounded-full h-2">
                <div class="bg-indigo-600 h-2 rounded-full transition-all duration-300"
                  :style="`width: ${progress}%`"></div>
              </div>
            </div>

            <!-- Playback Speed -->
            <div class="flex items-center space-x-2">
              <label class="text-sm text-gray-600">Speed:</label>
              <select @change="changeSpeed($event.target.value)"
                class="text-sm border-gray-300 rounded-md focus:border-indigo-500 focus:ring-indigo-500">
                <option value="0.5">0.5x</option>
                <option value="0.75">0.75x</option>
                <option value="1" selected>1x</option>
                <option value="1.25">1.25x</option>
                <option value="1.5">1.5x</option>
              </select>
            </div>
          </div>
        </div>

        <!-- Script Display -->
        <div class="p-6">
          <h3 class="font-semibold text-gray-900 mb-4">Script</h3>
          <div class="space-y-4">
            <template x-for="(line, index) in lines" :key="index">
              <div @click="playLine(index)"
                :class="{
                  'bg-indigo-50 border-indigo-300': currentLineIndex === index && isPlaying,
                  'border-gray-200 hover:border-indigo-200': currentLineIndex !== index || !isPlaying
                }"
                class="border-2 rounded-lg p-4 cursor-pointer transition-all duration-200">
                <div class="flex items-start space-x-3">
                  <div class="flex-shrink-0">
                    <div
                      :class="{
                        'bg-indigo-600 text-white': currentLineIndex === index && isPlaying,
                        'bg-gray-200 text-gray-600': currentLineIndex !== index || !isPlaying
                      }"
                      class="w-8 h-8 rounded-full flex items-center justify-center font-semibold text-sm transition-colors">
                      <span x-text="index + 1"></span>
                    </div>
                  </div>
                  <div class="flex-1">
                    <div class="text-lg font-medium text-gray-900 mb-1" x-text="line.japanese || line.line || line.text"></div>
                    <div class="text-sm text-gray-600" x-show="line.romaji" x-text="line.romaji"></div>
                    <div class="text-sm text-gray-500 mt-1" x-show="line.english" x-text="line.english"></div>
                    <div class="text-xs text-gray-400 mt-1" x-show="line.speaker" x-text="'Speaker: ' + line.speaker"></div>
                  </div>
                  <button @click.stop="playLine(index)"
                    class="flex-shrink-0 p-2 rounded-full hover:bg-gray-100 transition-colors">
                    <svg class="w-5 h-5 text-gray-600" fill="currentColor" viewBox="0 0 24 24">
                      <path d="M8 5v14l11-7z" />
                    </svg>
                  </button>
                </div>
              </div>
            </template>
          </div>
        </div>

        <!-- Audio Recording Section -->
        <div class="p-6 border-t border-gray-200" x-data="audioRecorder()">
          <h3 class="font-semibold text-gray-900 mb-4">Record Your Practice</h3>
          
          <!-- Browser Compatibility Warning -->
          <div x-show="!isSupported" class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-4">
            <div class="flex items-start">
              <svg class="w-5 h-5 text-yellow-600 mt-0.5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
              </svg>
              <div>
                <p class="text-sm font-medium text-yellow-800">Audio recording not supported</p>
                <p class="text-sm text-yellow-700 mt-1">Your browser doesn't support audio recording. Please use a modern browser like Chrome, Firefox, or Edge.</p>
              </div>
            </div>
          </div>

          <!-- Permission Error -->
          <div x-show="permissionError" class="bg-red-50 border border-red-200 rounded-lg p-4 mb-4">
            <div class="flex items-start">
              <svg class="w-5 h-5 text-red-600 mt-0.5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
              </svg>
              <div>
                <p class="text-sm font-medium text-red-800">Microphone access denied</p>
                <p class="text-sm text-red-700 mt-1" x-text="permissionError"></p>
              </div>
            </div>
          </div>

          <!-- Recording Controls -->
          <div x-show="isSupported && !permissionError" class="space-y-4">
            <!-- Recording Status -->
            <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
              <div class="flex items-center space-x-3">
                <!-- Recording Indicator -->
                <div x-show="isRecording" class="flex items-center space-x-2">
                  <div class="w-3 h-3 bg-red-600 rounded-full animate-pulse"></div>
                  <span class="text-sm font-medium text-red-600">Recording</span>
                </div>
                <div x-show="!isRecording && !audioBlob" class="flex items-center space-x-2">
                  <div class="w-3 h-3 bg-gray-400 rounded-full"></div>
                  <span class="text-sm font-medium text-gray-600">Ready to record</span>
                </div>
                <div x-show="!isRecording && audioBlob" class="flex items-center space-x-2">
                  <div class="w-3 h-3 bg-green-600 rounded-full"></div>
                  <span class="text-sm font-medium text-green-600">Recording saved</span>
                </div>

                <!-- Timer -->
                <div x-show="isRecording || recordingDuration > 0" class="text-sm font-mono text-gray-700">
                  <span x-text="formatTime(recordingDuration)"></span>
                </div>
              </div>

              <!-- Action Buttons -->
              <div class="flex items-center space-x-2">
                <!-- Start Recording -->
                <button x-show="!isRecording && !audioBlob" @click="startRecording"
                  class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors flex items-center space-x-2">
                  <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M12 14c1.66 0 3-1.34 3-3V5c0-1.66-1.34-3-3-3S9 3.34 9 5v6c0 1.66 1.34 3 3 3z"/>
                    <path d="M17 11c0 2.76-2.24 5-5 5s-5-2.24-5-5H5c0 3.53 2.61 6.43 6 6.92V21h2v-3.08c3.39-.49 6-3.39 6-6.92h-2z"/>
                  </svg>
                  <span>Start Recording</span>
                </button>

                <!-- Stop Recording -->
                <button x-show="isRecording" @click="stopRecording"
                  class="px-4 py-2 bg-gray-800 text-white rounded-lg hover:bg-gray-900 transition-colors flex items-center space-x-2">
                  <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M6 6h12v12H6z"/>
                  </svg>
                  <span>Stop</span>
                </button>

                <!-- Re-record -->
                <button x-show="!isRecording && audioBlob" @click="reRecord"
                  class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors flex items-center space-x-2">
                  <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                  </svg>
                  <span>Re-record</span>
                </button>
              </div>
            </div>

            <!-- Playback Controls -->
            <div x-show="audioBlob" class="p-4 bg-white border border-gray-200 rounded-lg">
              <div class="flex items-center justify-between mb-3">
                <h4 class="font-medium text-gray-900">Your Recording</h4>
                <span class="text-sm text-gray-600" x-text="formatTime(recordingDuration)"></span>
              </div>

              <div class="flex items-center space-x-3">
                <!-- Play/Pause Button -->
                <button @click="togglePlayback"
                  class="flex items-center justify-center w-10 h-10 rounded-full bg-indigo-600 text-white hover:bg-indigo-700 transition-colors">
                  <svg x-show="!isPlaying" class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M8 5v14l11-7z"/>
                  </svg>
                  <svg x-show="isPlaying" class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M6 4h4v16H6V4zm8 0h4v16h-4V4z"/>
                  </svg>
                </button>

                <!-- Waveform/Progress Bar -->
                <div class="flex-1">
                  <div class="w-full bg-gray-200 rounded-full h-2">
                    <div class="bg-indigo-600 h-2 rounded-full transition-all duration-100"
                      :style="`width: ${playbackProgress}%`"></div>
                  </div>
                </div>

                <!-- Download Button -->
                <button @click="downloadRecording"
                  class="p-2 text-gray-600 hover:text-gray-900 hover:bg-gray-100 rounded-lg transition-colors"
                  title="Download recording">
                  <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                  </svg>
                </button>

                <!-- Save Button -->
                <button @click="saveRecording"
                  :disabled="isSaving"
                  class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors disabled:opacity-50 disabled:cursor-not-allowed flex items-center space-x-2"
                  title="Save recording">
                  <svg x-show="!isSaving" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4" />
                  </svg>
                  <svg x-show="isSaving" class="w-5 h-5 animate-spin" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                  </svg>
                  <span x-text="isSaving ? 'Saving...' : 'Save'"></span>
                </button>
              </div>
            </div>

            <!-- Save Success Message -->
            <div x-show="saveSuccess" x-transition class="bg-green-50 border border-green-200 rounded-lg p-4">
              <div class="flex items-start">
                <svg class="w-5 h-5 text-green-600 mt-0.5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <div>
                  <p class="text-sm font-medium text-green-800">Recording saved successfully!</p>
                  <p class="text-sm text-green-700 mt-1">Your recording has been saved and will appear in your practice history below.</p>
                </div>
              </div>
            </div>

            <!-- Save Error Message -->
            <div x-show="saveError" x-transition class="bg-red-50 border border-red-200 rounded-lg p-4">
              <div class="flex items-start">
                <svg class="w-5 h-5 text-red-600 mt-0.5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <div>
                  <p class="text-sm font-medium text-red-800">Failed to save recording</p>
                  <p class="text-sm text-red-700 mt-1" x-text="saveError"></p>
                </div>
              </div>
            </div>
          </div>

            <!-- Recording Tips -->
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
              <h4 class="text-sm font-medium text-blue-900 mb-2">Recording Tips</h4>
              <ul class="text-sm text-blue-800 space-y-1">
                <li>• Find a quiet environment to minimize background noise</li>
                <li>• Speak clearly and at a natural pace</li>
                <li>• Listen to the original audio first, then record your attempt</li>
                <li>• Compare your recording with the original to identify areas for improvement</li>
              </ul>
            </div>
          </div>
        </div>

        <!-- Past Recordings Section -->
        @if($recordings->count() > 0)
        <div class="p-6 border-t border-gray-200" x-data="{ deletingId: null }">
          <div class="flex items-center justify-between mb-4">
            <h3 class="font-semibold text-gray-900">Your Practice History</h3>
            <span class="text-sm text-gray-600">{{ $recordings->count() }} {{ $recordings->count() === 1 ? 'recording' : 'recordings' }}</span>
          </div>

          <div class="space-y-3">
            @foreach($recordings as $recording)
            <div class="bg-white border border-gray-200 rounded-lg p-4 hover:border-gray-300 transition-colors">
              <div class="flex items-center justify-between">
                <div class="flex items-center space-x-3 flex-1">
                  <!-- Play Button -->
                  <button 
                    onclick="playRecording('{{ route('recordings.serve', $recording) }}')"
                    class="flex items-center justify-center w-10 h-10 rounded-full bg-indigo-100 text-indigo-600 hover:bg-indigo-200 transition-colors">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                      <path d="M8 5v14l11-7z"/>
                    </svg>
                  </button>

                  <!-- Recording Info -->
                  <div class="flex-1">
                    <div class="flex items-center space-x-2">
                      <span class="text-sm font-medium text-gray-900">
                        Recording from {{ $recording->created_at->format('M j, Y') }}
                      </span>
                      @if($recording->duration_seconds > 0)
                      <span class="text-xs text-gray-500">
                        ({{ gmdate('i:s', $recording->duration_seconds) }})
                      </span>
                      @endif
                    </div>
                    <div class="text-xs text-gray-500 mt-1">
                      {{ $recording->created_at->diffForHumans() }}
                    </div>
                  </div>
                </div>

                <!-- Actions -->
                <div class="flex items-center space-x-2">
                  <!-- Download -->
                  <a href="{{ route('recordings.serve', $recording) }}" 
                    download
                    class="p-2 text-gray-600 hover:text-gray-900 hover:bg-gray-100 rounded-lg transition-colors"
                    title="Download">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                    </svg>
                  </a>

                  <!-- Delete -->
                  <button 
                    @click="deleteRecording({{ $recording->id }})"
                    :disabled="deletingId === {{ $recording->id }}"
                    class="p-2 text-red-600 hover:text-red-900 hover:bg-red-50 rounded-lg transition-colors disabled:opacity-50"
                    title="Delete">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                    </svg>
                  </button>
                </div>
              </div>
            </div>
            @endforeach
          </div>
        </div>
        @endif
      </div>

        <!-- Practice Tips -->
        <div class="p-6 border-t border-gray-200 bg-gray-50">
          <h3 class="font-semibold text-gray-900 mb-3">Practice Tips</h3>
          <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm text-gray-700">
            <div class="flex items-start space-x-2">
              <svg class="w-5 h-5 text-indigo-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor"
                viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
              </svg>
              <span>Listen first without repeating to understand the rhythm</span>
            </div>
            <div class="flex items-start space-x-2">
              <svg class="w-5 h-5 text-indigo-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor"
                viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
              </svg>
              <span>Shadow with the text first, then try without looking</span>
            </div>
            <div class="flex items-start space-x-2">
              <svg class="w-5 h-5 text-indigo-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor"
                viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
              </svg>
              <span>Use slower speeds (0.5x-0.75x) when starting out</span>
            </div>
            <div class="flex items-start space-x-2">
              <svg class="w-5 h-5 text-indigo-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor"
                viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
              </svg>
              <span>Practice daily for 10-15 minutes for best results</span>
            </div>
          </div>
        </div>
      </div>

      <!-- Mark as Complete Section -->
      <div class="mt-6 bg-white rounded-lg shadow-sm border border-gray-200 p-6" 
        x-data="completionTracker({{ $shadowingExercise->id }})">
        <div class="flex items-center justify-between mb-4">
          <div>
            <h3 class="font-semibold text-gray-900">Complete This Exercise</h3>
            <p class="text-sm text-gray-600 mt-1">Mark this exercise as complete to track your progress and earn XP</p>
          </div>
        </div>

        <!-- Completion Form -->
        <div class="space-y-4">
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Practice Duration (minutes)</label>
            <input type="number" 
              x-model="durationMinutes" 
              min="1" 
              max="120"
              class="w-full sm:w-48 px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
              placeholder="e.g., 15">
          </div>

          <button @click="markComplete" 
            :disabled="isSubmitting || durationMinutes < 1"
            class="px-6 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors disabled:opacity-50 disabled:cursor-not-allowed flex items-center space-x-2">
            <svg x-show="!isSubmitting" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <svg x-show="isSubmitting" class="w-5 h-5 animate-spin" fill="none" viewBox="0 0 24 24">
              <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
              <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            <span x-text="isSubmitting ? 'Marking Complete...' : 'Mark as Complete'"></span>
          </button>

          <!-- Success Message -->
          <div x-show="successMessage" x-transition class="bg-green-50 border border-green-200 rounded-lg p-4">
            <div class="flex items-start">
              <svg class="w-5 h-5 text-green-600 mt-0.5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
              </svg>
              <div>
                <p class="text-sm font-medium text-green-800">Exercise completed!</p>
                <p class="text-sm text-green-700 mt-1" x-text="successMessage"></p>
              </div>
            </div>
          </div>

          <!-- Error Message -->
          <div x-show="errorMessage" x-transition class="bg-red-50 border border-red-200 rounded-lg p-4">
            <div class="flex items-start">
              <svg class="w-5 h-5 text-red-600 mt-0.5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
              </svg>
              <div>
                <p class="text-sm font-medium text-red-800">Failed to mark as complete</p>
                <p class="text-sm text-red-700 mt-1" x-text="errorMessage"></p>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Completion History -->
      @if($completions->count() > 0)
      <div class="mt-6 bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <div class="flex items-center justify-between mb-4">
          <h3 class="font-semibold text-gray-900">Completion History</h3>
          <span class="text-sm text-gray-600">{{ $completions->count() }} {{ $completions->count() === 1 ? 'completion' : 'completions' }}</span>
        </div>

        <div class="space-y-3">
          @foreach($completions as $completion)
          <div class="bg-gray-50 border border-gray-200 rounded-lg p-4">
            <div class="flex items-center justify-between">
              <div class="flex items-center space-x-3 flex-1">
                <!-- Completion Icon -->
                <div class="flex items-center justify-center w-10 h-10 rounded-full bg-green-100 text-green-600">
                  <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                  </svg>
                </div>

                <!-- Completion Info -->
                <div class="flex-1">
                  <div class="flex items-center space-x-2">
                    <span class="text-sm font-medium text-gray-900">
                      Completed on {{ $completion->completed_at->format('M j, Y \a\t g:i A') }}
                    </span>
                    @if($completion->duration_seconds > 0)
                    <span class="text-xs text-gray-500">
                      ({{ gmdate('i:s', $completion->duration_seconds) }} practice time)
                    </span>
                    @endif
                  </div>
                  <div class="text-xs text-gray-500 mt-1">
                    {{ $completion->completed_at->diffForHumans() }}
                  </div>
                  @if($completion->recording)
                  <div class="mt-2">
                    <a href="{{ route('recordings.serve', $completion->recording) }}" 
                      class="text-xs text-indigo-600 hover:text-indigo-800 flex items-center space-x-1">
                      <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.536 8.464a5 5 0 010 7.072m2.828-9.9a9 9 0 010 12.728M5.586 15H4a1 1 0 01-1-1v-4a1 1 0 011-1h1.586l4.707-4.707C10.923 3.663 12 4.109 12 5v14c0 .891-1.077 1.337-1.707.707L5.586 15z" />
                      </svg>
                      <span>Recording available</span>
                    </a>
                  </div>
                  @endif
                </div>
              </div>

              <!-- XP Badge -->
              <div class="flex items-center space-x-1 px-3 py-1 bg-indigo-100 text-indigo-700 rounded-full text-xs font-medium">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" />
                </svg>
                <span>+50 XP</span>
              </div>
            </div>
          </div>
          @endforeach
        </div>
      </div>
      @endif
    </div>
    </div>
  </div>

  @push('scripts')
    <script>
      document.addEventListener('alpine:init', () => {
        // Completion Tracker Component
        Alpine.data('completionTracker', (exerciseId) => ({
          exerciseId: exerciseId,
          durationMinutes: 15,
          isSubmitting: false,
          successMessage: null,
          errorMessage: null,

          async markComplete() {
            if (this.durationMinutes < 1) {
              this.errorMessage = 'Please enter a valid duration';
              return;
            }

            this.isSubmitting = true;
            this.successMessage = null;
            this.errorMessage = null;

            try {
              const response = await fetch(`/shadowing/${this.exerciseId}/complete`, {
                method: 'POST',
                headers: {
                  'Content-Type': 'application/json',
                  'X-CSRF-TOKEN': '{{ csrf_token() }}',
                  'Accept': 'application/json'
                },
                body: JSON.stringify({
                  duration_seconds: this.durationMinutes * 60,
                  recording_id: null
                })
              });

              const data = await response.json();

              if (response.ok && data.success) {
                this.successMessage = `You earned ${data.xp_earned} XP!`;
                
                if (data.level_up) {
                  this.successMessage += ` Congratulations! You leveled up to level ${data.level_up}!`;
                }

                // Reload page after 2 seconds to show updated completion history
                setTimeout(() => {
                  window.location.reload();
                }, 2000);
              } else {
                throw new Error(data.message || 'Failed to mark as complete');
              }
            } catch (error) {
              console.error('Error marking complete:', error);
              this.errorMessage = error.message || 'An error occurred while marking the exercise as complete';
            } finally {
              this.isSubmitting = false;
            }
          }
        }));

        // Audio Recorder Component
        Alpine.data('audioRecorder', () => ({
          isSupported: false,
          isRecording: false,
          isPlaying: false,
          permissionError: null,
          mediaRecorder: null,
          audioChunks: [],
          audioBlob: null,
          audioUrl: null,
          audioElement: null,
          recordingDuration: 0,
          recordingTimer: null,
          playbackProgress: 0,
          isSaving: false,
          saveSuccess: false,
          saveError: null,

          init() {
            // Check browser support for MediaRecorder
            this.isSupported = !!(navigator.mediaDevices && navigator.mediaDevices.getUserMedia && window.MediaRecorder);
            
            if (!this.isSupported) {
              console.warn('MediaRecorder API not supported in this browser');
            }
          },

          async startRecording() {
            try {
              // Request microphone permission
              const stream = await navigator.mediaDevices.getUserMedia({ 
                audio: {
                  echoCancellation: true,
                  noiseSuppression: true,
                  autoGainControl: true
                } 
              });

              // Clear any previous recording
              this.audioChunks = [];
              this.audioBlob = null;
              this.audioUrl = null;
              this.recordingDuration = 0;
              this.permissionError = null;

              // Determine the best MIME type
              let mimeType = 'audio/webm';
              if (MediaRecorder.isTypeSupported('audio/webm;codecs=opus')) {
                mimeType = 'audio/webm;codecs=opus';
              } else if (MediaRecorder.isTypeSupported('audio/mp4')) {
                mimeType = 'audio/mp4';
              } else if (MediaRecorder.isTypeSupported('audio/ogg;codecs=opus')) {
                mimeType = 'audio/ogg;codecs=opus';
              }

              // Create MediaRecorder instance
              this.mediaRecorder = new MediaRecorder(stream, { mimeType });

              // Handle data available event
              this.mediaRecorder.ondataavailable = (event) => {
                if (event.data.size > 0) {
                  this.audioChunks.push(event.data);
                }
              };

              // Handle recording stop
              this.mediaRecorder.onstop = () => {
                // Create blob from chunks
                this.audioBlob = new Blob(this.audioChunks, { type: mimeType });
                this.audioUrl = URL.createObjectURL(this.audioBlob);
                
                // Stop all tracks
                stream.getTracks().forEach(track => track.stop());
                
                // Clear timer
                if (this.recordingTimer) {
                  clearInterval(this.recordingTimer);
                  this.recordingTimer = null;
                }
              };

              // Handle errors
              this.mediaRecorder.onerror = (event) => {
                console.error('MediaRecorder error:', event);
                this.permissionError = 'An error occurred during recording. Please try again.';
                this.isRecording = false;
                
                if (this.recordingTimer) {
                  clearInterval(this.recordingTimer);
                  this.recordingTimer = null;
                }
              };

              // Start recording
              this.mediaRecorder.start();
              this.isRecording = true;

              // Start timer
              this.recordingTimer = setInterval(() => {
                this.recordingDuration++;
              }, 1000);

            } catch (error) {
              console.error('Error accessing microphone:', error);
              
              if (error.name === 'NotAllowedError' || error.name === 'PermissionDeniedError') {
                this.permissionError = 'Microphone access was denied. Please allow microphone access in your browser settings and try again.';
              } else if (error.name === 'NotFoundError') {
                this.permissionError = 'No microphone found. Please connect a microphone and try again.';
              } else if (error.name === 'NotReadableError') {
                this.permissionError = 'Microphone is already in use by another application.';
              } else {
                this.permissionError = 'Unable to access microphone: ' + error.message;
              }
            }
          },

          stopRecording() {
            if (this.mediaRecorder && this.mediaRecorder.state !== 'inactive') {
              this.mediaRecorder.stop();
              this.isRecording = false;
            }
          },

          reRecord() {
            // Clean up previous recording
            if (this.audioUrl) {
              URL.revokeObjectURL(this.audioUrl);
            }
            if (this.audioElement) {
              this.audioElement.pause();
              this.audioElement = null;
            }
            
            this.audioBlob = null;
            this.audioUrl = null;
            this.audioChunks = [];
            this.recordingDuration = 0;
            this.playbackProgress = 0;
            this.isPlaying = false;
            
            // Start new recording
            this.startRecording();
          },

          togglePlayback() {
            if (!this.audioUrl) return;

            if (this.isPlaying) {
              this.pausePlayback();
            } else {
              this.playRecording();
            }
          },

          playRecording() {
            if (!this.audioUrl) return;

            // Create audio element if it doesn't exist
            if (!this.audioElement) {
              this.audioElement = new Audio(this.audioUrl);
              
              // Update progress during playback
              this.audioElement.ontimeupdate = () => {
                if (this.audioElement.duration) {
                  this.playbackProgress = (this.audioElement.currentTime / this.audioElement.duration) * 100;
                }
              };

              // Handle playback end
              this.audioElement.onended = () => {
                this.isPlaying = false;
                this.playbackProgress = 0;
              };

              // Handle errors
              this.audioElement.onerror = (error) => {
                console.error('Audio playback error:', error);
                this.isPlaying = false;
              };
            }

            this.audioElement.play();
            this.isPlaying = true;
          },

          pausePlayback() {
            if (this.audioElement) {
              this.audioElement.pause();
              this.isPlaying = false;
            }
          },

          downloadRecording() {
            if (!this.audioBlob) return;

            const url = URL.createObjectURL(this.audioBlob);
            const a = document.createElement('a');
            a.style.display = 'none';
            a.href = url;
            a.download = `shadowing-recording-${Date.now()}.webm`;
            document.body.appendChild(a);
            a.click();
            
            // Clean up
            setTimeout(() => {
              document.body.removeChild(a);
              URL.revokeObjectURL(url);
            }, 100);
          },

          async saveRecording() {
            if (!this.audioBlob) return;

            this.isSaving = true;
            this.saveSuccess = false;
            this.saveError = null;

            try {
              // Create FormData
              const formData = new FormData();
              formData.append('audio', this.audioBlob, 'recording.webm');
              formData.append('recordable_type', 'shadowing');
              formData.append('recordable_id', '{{ $shadowingExercise->id }}');
              formData.append('duration', this.recordingDuration);

              // Send to server
              const response = await fetch('{{ route("recordings.store") }}', {
                method: 'POST',
                headers: {
                  'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: formData
              });

              const data = await response.json();

              if (response.ok && data.success) {
                this.saveSuccess = true;
                
                // Clear the recording after successful save
                setTimeout(() => {
                  this.saveSuccess = false;
                  // Reload page to show new recording in history
                  window.location.reload();
                }, 2000);
              } else {
                throw new Error(data.message || 'Failed to save recording');
              }
            } catch (error) {
              console.error('Error saving recording:', error);
              this.saveError = error.message || 'An error occurred while saving the recording';
            } finally {
              this.isSaving = false;
            }
          },

          formatTime(seconds) {
            const mins = Math.floor(seconds / 60);
            const secs = seconds % 60;
            return `${mins}:${secs.toString().padStart(2, '0')}`;
          },

          // Cleanup when component is destroyed
          destroy() {
            if (this.recordingTimer) {
              clearInterval(this.recordingTimer);
            }
            if (this.audioUrl) {
              URL.revokeObjectURL(this.audioUrl);
            }
            if (this.audioElement) {
              this.audioElement.pause();
              this.audioElement = null;
            }
            if (this.mediaRecorder && this.mediaRecorder.state !== 'inactive') {
              this.mediaRecorder.stop();
            }
          }
        }));

        // Shadowing Player Component
        Alpine.data('shadowingPlayer', (config) => ({
          lines: config.lines || [],
          exerciseId: config.exerciseId,
          currentLineIndex: 0,
          isPlaying: false,
          loopEnabled: false,
          playbackRate: 1,
          utterance: null,
          synth: window.speechSynthesis,

          init() {
            // Ensure speech synthesis is available
            if (!this.synth) {
              console.error('Speech synthesis not supported');
            }
          },

          get progress() {
            if (this.lines.length === 0) return 0;
            return ((this.currentLineIndex + 1) / this.lines.length) * 100;
          },

          playPause() {
            if (this.isPlaying) {
              this.pause();
            } else {
              this.play();
            }
          },

          play() {
            if (this.currentLineIndex >= this.lines.length) {
              this.currentLineIndex = 0;
            }
            this.playCurrentLine();
          },

          pause() {
            this.isPlaying = false;
            if (this.synth.speaking) {
              this.synth.cancel();
            }
          },

          stop() {
            this.isPlaying = false;
            this.currentLineIndex = 0;
            if (this.synth.speaking) {
              this.synth.cancel();
            }
          },

          playLine(index) {
            this.currentLineIndex = index;
            this.playCurrentLine();
          },

          playCurrentLine() {
            if (this.currentLineIndex >= this.lines.length) {
              if (this.loopEnabled) {
                this.currentLineIndex = 0;
              } else {
                this.isPlaying = false;
                return;
              }
            }

            const line = this.lines[this.currentLineIndex];
            const text = line.japanese || line.line || line.text || '';

            if (!text) {
              this.nextLine();
              return;
            }

            // Cancel any ongoing speech
            if (this.synth.speaking) {
              this.synth.cancel();
            }

            // Create new utterance
            this.utterance = new SpeechSynthesisUtterance(text);
            this.utterance.lang = 'ja-JP';
            this.utterance.rate = this.playbackRate;

            // Handle end of utterance
            this.utterance.onend = () => {
              if (this.isPlaying) {
                // Add a small pause between lines
                setTimeout(() => {
                  this.nextLine();
                }, 500);
              }
            };

            this.utterance.onerror = (event) => {
              console.error('Speech synthesis error:', event);
              this.isPlaying = false;
            };

            this.isPlaying = true;
            this.synth.speak(this.utterance);
          },

          nextLine() {
            this.currentLineIndex++;
            if (this.currentLineIndex < this.lines.length) {
              this.playCurrentLine();
            } else if (this.loopEnabled) {
              this.currentLineIndex = 0;
              this.playCurrentLine();
            } else {
              this.isPlaying = false;
              this.currentLineIndex = 0;
            }
          },

          toggleLoop() {
            this.loopEnabled = !this.loopEnabled;
          },

          changeSpeed(rate) {
            this.playbackRate = parseFloat(rate);
            if (this.isPlaying && this.synth.speaking) {
              // Restart current line with new speed
              const currentIndex = this.currentLineIndex;
              this.pause();
              this.currentLineIndex = currentIndex;
              this.playCurrentLine();
            }
          }
        }));
      });

      // Global functions for recording playback and deletion
      let currentPlayingAudio = null;

      function playRecording(url) {
        // Stop any currently playing audio
        if (currentPlayingAudio) {
          currentPlayingAudio.pause();
          currentPlayingAudio = null;
        }

        // Create and play new audio
        currentPlayingAudio = new Audio(url);
        currentPlayingAudio.play().catch(error => {
          console.error('Error playing recording:', error);
          alert('Failed to play recording');
        });

        // Clean up when finished
        currentPlayingAudio.onended = () => {
          currentPlayingAudio = null;
        };
      }

      async function deleteRecording(recordingId) {
        if (!confirm('Are you sure you want to delete this recording? This action cannot be undone.')) {
          return;
        }

        try {
          const response = await fetch(`/recordings/${recordingId}`, {
            method: 'DELETE',
            headers: {
              'X-CSRF-TOKEN': '{{ csrf_token() }}',
              'Accept': 'application/json'
            }
          });

          const data = await response.json();

          if (response.ok && data.success) {
            // Reload page to update the list
            window.location.reload();
          } else {
            throw new Error(data.message || 'Failed to delete recording');
          }
        } catch (error) {
          console.error('Error deleting recording:', error);
          alert('Failed to delete recording: ' + error.message);
        }
      }
    </script>
  @endpush
</x-app-layout>
