# Audio Recording Component API Reference

## Alpine.js Component: `audioRecorder`

### Overview
The `audioRecorder` component provides a complete audio recording solution using the MediaRecorder API. It handles microphone permissions, recording, playback, and download functionality.

---

## Usage

### Basic Implementation

```html
<div x-data="audioRecorder()">
  <!-- Your recording UI here -->
</div>
```

### Integration Example

```html
<div x-data="audioRecorder()">
  <!-- Browser Support Check -->
  <div x-show="!isSupported">
    <p>Audio recording not supported in your browser</p>
  </div>

  <!-- Permission Error -->
  <div x-show="permissionError">
    <p x-text="permissionError"></p>
  </div>

  <!-- Recording Controls -->
  <div x-show="isSupported && !permissionError">
    <!-- Start Recording -->
    <button x-show="!isRecording && !audioBlob" @click="startRecording">
      Start Recording
    </button>

    <!-- Stop Recording -->
    <button x-show="isRecording" @click="stopRecording">
      Stop
    </button>

    <!-- Re-record -->
    <button x-show="!isRecording && audioBlob" @click="reRecord">
      Re-record
    </button>

    <!-- Recording Status -->
    <div x-show="isRecording">
      <span>Recording...</span>
      <span x-text="formatTime(recordingDuration)"></span>
    </div>

    <!-- Playback Controls -->
    <div x-show="audioBlob">
      <button @click="togglePlayback">
        <span x-show="!isPlaying">Play</span>
        <span x-show="isPlaying">Pause</span>
      </button>
      
      <button @click="downloadRecording">
        Download
      </button>

      <!-- Progress Bar -->
      <div class="progress-bar">
        <div :style="`width: ${playbackProgress}%`"></div>
      </div>
    </div>
  </div>
</div>
```

---

## Properties

### State Properties

| Property | Type | Default | Description |
|----------|------|---------|-------------|
| `isSupported` | Boolean | `false` | Browser supports MediaRecorder API |
| `isRecording` | Boolean | `false` | Currently recording |
| `isPlaying` | Boolean | `false` | Currently playing back recording |
| `permissionError` | String\|null | `null` | Error message if permission denied |
| `mediaRecorder` | MediaRecorder\|null | `null` | MediaRecorder instance |
| `audioChunks` | Array | `[]` | Recorded audio data chunks |
| `audioBlob` | Blob\|null | `null` | Final audio blob |
| `audioUrl` | String\|null | `null` | Object URL for playback |
| `audioElement` | HTMLAudioElement\|null | `null` | Audio element for playback |
| `recordingDuration` | Number | `0` | Recording duration in seconds |
| `recordingTimer` | Number\|null | `null` | Interval timer reference |
| `playbackProgress` | Number | `0` | Playback progress (0-100) |

---

## Methods

### `init()`
Initializes the component and checks browser support.

**Called**: Automatically by Alpine.js on component mount

**Returns**: `void`

**Example**:
```javascript
// Called automatically, no manual invocation needed
```

---

### `startRecording()`
Requests microphone permission and starts recording.

**Returns**: `Promise<void>`

**Throws**: Sets `permissionError` if permission denied or microphone unavailable

**Side Effects**:
- Requests microphone permission (first time)
- Starts MediaRecorder
- Starts recording timer
- Sets `isRecording = true`

**Example**:
```html
<button @click="startRecording">Start Recording</button>
```

**Error Handling**:
- `NotAllowedError`: Permission denied
- `NotFoundError`: No microphone found
- `NotReadableError`: Microphone in use
- Generic errors: Displays error message

---

### `stopRecording()`
Stops the current recording and creates audio blob.

**Returns**: `void`

**Side Effects**:
- Stops MediaRecorder
- Creates audio blob from chunks
- Stops microphone stream
- Clears recording timer
- Sets `isRecording = false`

**Example**:
```html
<button @click="stopRecording">Stop</button>
```

---

### `reRecord()`
Cleans up previous recording and starts a new one.

**Returns**: `void`

**Side Effects**:
- Revokes previous audio URL
- Clears audio blob and chunks
- Resets duration and progress
- Calls `startRecording()`

**Example**:
```html
<button @click="reRecord">Re-record</button>
```

---

### `togglePlayback()`
Toggles between play and pause states.

**Returns**: `void`

**Side Effects**:
- Calls `playRecording()` if not playing
- Calls `pausePlayback()` if playing

**Example**:
```html
<button @click="togglePlayback">
  <span x-show="!isPlaying">Play</span>
  <span x-show="isPlaying">Pause</span>
</button>
```

---

### `playRecording()`
Starts playback of the recorded audio.

**Returns**: `void`

**Preconditions**: `audioUrl` must exist

**Side Effects**:
- Creates audio element if needed
- Starts audio playback
- Updates `playbackProgress` during playback
- Sets `isPlaying = true`

**Example**:
```html
<button @click="playRecording">Play</button>
```

---

### `pausePlayback()`
Pauses the current playback.

**Returns**: `void`

**Side Effects**:
- Pauses audio element
- Sets `isPlaying = false`

**Example**:
```html
<button @click="pausePlayback">Pause</button>
```

---

### `downloadRecording()`
Downloads the recorded audio as a file.

**Returns**: `void`

**Preconditions**: `audioBlob` must exist

**Side Effects**:
- Creates temporary download link
- Triggers browser download
- Cleans up temporary elements

**Filename Format**: `shadowing-recording-[timestamp].webm`

**Example**:
```html
<button @click="downloadRecording">Download</button>
```

---

### `formatTime(seconds)`
Formats seconds as MM:SS string.

**Parameters**:
- `seconds` (Number): Duration in seconds

**Returns**: `String` - Formatted time (e.g., "0:05", "1:23")

**Example**:
```html
<span x-text="formatTime(recordingDuration)"></span>
<!-- Output: "1:23" -->
```

---

### `destroy()`
Cleans up resources when component is destroyed.

**Returns**: `void`

**Side Effects**:
- Clears recording timer
- Revokes audio URL
- Stops audio playback
- Stops MediaRecorder

**Called**: Automatically by Alpine.js on component unmount

---

## Events

### MediaRecorder Events

The component handles the following MediaRecorder events:

#### `ondataavailable`
Collects audio chunks during recording.

#### `onstop`
Creates audio blob when recording stops.

#### `onerror`
Handles recording errors and updates UI.

### Audio Element Events

#### `ontimeupdate`
Updates playback progress bar.

#### `onended`
Resets playback state when audio finishes.

#### `onerror`
Handles playback errors.

---

## Audio Configuration

### MediaRecorder Constraints

```javascript
{
  audio: {
    echoCancellation: true,
    noiseSuppression: true,
    autoGainControl: true
  }
}
```

### MIME Type Detection

The component automatically selects the best available MIME type:

1. `audio/webm;codecs=opus` (preferred)
2. `audio/mp4` (Safari)
3. `audio/ogg;codecs=opus` (Firefox fallback)
4. `audio/webm` (default)

---

## Browser Support

### Supported Browsers

| Browser | Minimum Version | Notes |
|---------|----------------|-------|
| Chrome | 49+ | Full support |
| Edge | 79+ | Full support |
| Firefox | 25+ | Full support |
| Safari | 14.1+ | Limited MIME types |
| Opera | 36+ | Full support |

### Unsupported Browsers

- Internet Explorer (all versions)
- Safari < 14.1
- Older mobile browsers

---

## Error Handling

### Permission Errors

| Error Name | Message | User Action |
|------------|---------|-------------|
| `NotAllowedError` | Microphone access was denied | Allow in browser settings |
| `NotFoundError` | No microphone found | Connect microphone |
| `NotReadableError` | Microphone is already in use | Close other apps |
| Generic | Unable to access microphone | Check browser/system |

### Checking for Errors

```html
<div x-show="permissionError" class="error">
  <p x-text="permissionError"></p>
</div>
```

---

## Best Practices

### 1. Check Browser Support

Always check `isSupported` before showing recording UI:

```html
<div x-show="isSupported">
  <!-- Recording controls -->
</div>

<div x-show="!isSupported">
  <p>Recording not supported in your browser</p>
</div>
```

### 2. Handle Permission Errors

Display helpful error messages:

```html
<div x-show="permissionError" class="error">
  <p x-text="permissionError"></p>
</div>
```

### 3. Show Recording State

Provide clear visual feedback:

```html
<div x-show="isRecording" class="recording-indicator">
  <span class="pulse"></span>
  <span>Recording</span>
  <span x-text="formatTime(recordingDuration)"></span>
</div>
```

### 4. Cleanup on Navigation

The component automatically cleans up resources, but ensure proper Alpine.js lifecycle:

```html
<div x-data="audioRecorder()" x-init="init()" x-destroy="destroy()">
  <!-- Component content -->
</div>
```

---

## Common Use Cases

### 1. Simple Recording Button

```html
<div x-data="audioRecorder()">
  <button 
    x-show="!isRecording && !audioBlob" 
    @click="startRecording"
    class="btn-record">
    🎤 Start Recording
  </button>
  
  <button 
    x-show="isRecording" 
    @click="stopRecording"
    class="btn-stop">
    ⏹️ Stop
  </button>
</div>
```

### 2. Recording with Timer

```html
<div x-data="audioRecorder()">
  <div x-show="isRecording" class="timer">
    <span x-text="formatTime(recordingDuration)"></span>
  </div>
  
  <button @click="isRecording ? stopRecording() : startRecording()">
    <span x-text="isRecording ? 'Stop' : 'Record'"></span>
  </button>
</div>
```

### 3. Playback with Progress

```html
<div x-data="audioRecorder()">
  <div x-show="audioBlob">
    <button @click="togglePlayback">
      <span x-show="!isPlaying">▶️ Play</span>
      <span x-show="isPlaying">⏸️ Pause</span>
    </button>
    
    <div class="progress-bar">
      <div 
        class="progress-fill" 
        :style="`width: ${playbackProgress}%`">
      </div>
    </div>
  </div>
</div>
```

### 4. Complete Recording Interface

```html
<div x-data="audioRecorder()" class="recorder">
  <!-- Status -->
  <div class="status">
    <span x-show="!isRecording && !audioBlob">Ready</span>
    <span x-show="isRecording">Recording...</span>
    <span x-show="!isRecording && audioBlob">Saved</span>
  </div>
  
  <!-- Timer -->
  <div x-show="recordingDuration > 0" class="timer">
    <span x-text="formatTime(recordingDuration)"></span>
  </div>
  
  <!-- Controls -->
  <div class="controls">
    <button 
      x-show="!isRecording && !audioBlob" 
      @click="startRecording">
      Start
    </button>
    
    <button 
      x-show="isRecording" 
      @click="stopRecording">
      Stop
    </button>
    
    <button 
      x-show="!isRecording && audioBlob" 
      @click="reRecord">
      Re-record
    </button>
  </div>
  
  <!-- Playback -->
  <div x-show="audioBlob" class="playback">
    <button @click="togglePlayback">
      <span x-show="!isPlaying">Play</span>
      <span x-show="isPlaying">Pause</span>
    </button>
    
    <button @click="downloadRecording">
      Download
    </button>
    
    <div class="progress">
      <div :style="`width: ${playbackProgress}%`"></div>
    </div>
  </div>
</div>
```

---

## Troubleshooting

### Recording doesn't start
- Check browser support: `isSupported` should be `true`
- Check microphone permission: Look for `permissionError`
- Check browser console for errors

### No audio in playback
- Verify recording duration > 0
- Check `audioBlob` is not null
- Try different browser (MIME type compatibility)

### Permission denied
- User must manually allow in browser settings
- Check browser permission settings
- Try incognito/private mode to reset

### Poor audio quality
- Check microphone quality
- Ensure quiet environment
- Verify audio constraints are applied

---

## Performance Tips

1. **Limit recording length**: Very long recordings consume memory
2. **Clean up old recordings**: Call `reRecord()` to free memory
3. **Revoke URLs**: Component automatically revokes URLs on cleanup
4. **Avoid multiple instances**: One recorder per page recommended

---

## Security Considerations

1. **Permission required**: User must explicitly grant microphone access
2. **Client-side only**: Recordings stored in browser memory
3. **No auto-upload**: User controls when to download/save
4. **HTTPS required**: MediaRecorder requires secure context

---

## Related Documentation

- [Task 18 Implementation](./task-18-audio-recording.md)
- [Task 18 Testing Guide](./task-18-testing-guide.md)
- [MDN MediaRecorder API](https://developer.mozilla.org/en-US/docs/Web/API/MediaRecorder)
- [MDN getUserMedia](https://developer.mozilla.org/en-US/docs/Web/API/MediaDevices/getUserMedia)

---

## Version History

- **v1.0** (2025-10-10): Initial implementation
  - Basic recording functionality
  - Playback controls
  - Download feature
  - Error handling
  - Browser compatibility checks
