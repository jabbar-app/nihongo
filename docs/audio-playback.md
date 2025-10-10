# Audio Playback Feature Documentation

## Overview

The Japanese Learning Application now includes comprehensive audio playback functionality using the Web Speech API for text-to-speech. This feature allows users to hear Japanese phrases, dialogues, and flashcards pronounced correctly.

## Components

### 1. AudioService (Backend)

**Location:** `app/Services/AudioService.php`

The AudioService provides backend support for audio-related functionality:

- `generateSpeechUrl()` - Generates speech data for the frontend
- `storeRecording()` - Stores user audio recordings
- `getRecordingUrl()` - Gets URL for serving recordings
- `deleteRecording()` - Deletes user recordings

### 2. audioPlayer (Alpine.js Component)

**Location:** `resources/js/components/audioPlayer.js`

A reusable Alpine.js component that provides text-to-speech functionality:

**Methods:**
- `play(text, lang)` - Play text using TTS
- `stop()` - Stop current playback
- `pause()` - Pause playback
- `resume()` - Resume paused playback
- `togglePlayPause(text, lang)` - Toggle between play and pause
- `setSpeed(speed)` - Set playback speed (0.5x to 1.5x)

**Properties:**
- `playing` - Boolean indicating if audio is playing
- `loading` - Boolean indicating if audio is loading
- `error` - Error message if playback fails
- `playbackSpeed` - Current playback speed multiplier

### 3. Blade Components

#### audio-button.blade.php

**Location:** `resources/views/components/audio-button.blade.php`

A simple button component for playing audio.

**Usage:**
```blade
<x-audio-button 
    text="こんにちは" 
    lang="ja-JP" 
    size="sm"
    variant="primary">
    Play Audio
</x-audio-button>
```

**Props:**
- `text` (required) - The text to speak
- `lang` (optional, default: 'ja-JP') - Language code
- `size` (optional, default: 'md') - Button size: 'sm', 'md', 'lg'
- `variant` (optional, default: 'default') - Button style: 'default', 'primary', 'secondary'

#### audio-player.blade.php

**Location:** `resources/views/components/audio-player.blade.php`

A more advanced audio player with play/pause toggle and speed controls.

**Usage:**
```blade
<x-audio-player 
    text="こんにちは、元気ですか？" 
    lang="ja-JP"
    :showSpeedControl="true"
    :autoPlay="false" />
```

**Props:**
- `text` (required) - The text to speak
- `lang` (optional, default: 'ja-JP') - Language code
- `showSpeedControl` (optional, default: true) - Show speed control dropdown
- `autoPlay` (optional, default: false) - Auto-play on load

## Features

### 1. Flashcard Review Audio

**Location:** `resources/views/flashcards/review.blade.php`

- Audio playback buttons on both front (Japanese) and back (English) of flashcards
- Playback speed control (0.5x, 0.75x, 1x, 1.25x, 1.5x)
- Keyboard shortcuts work alongside audio playback
- Audio automatically stops when advancing to next card

### 2. Phrase Table Audio

**Location:** `resources/views/components/phrase-table.blade.php`

- Audio button for each phrase in the table
- Plays Japanese text with proper pronunciation
- Works on both desktop and mobile layouts

### 3. Dialogue Audio

**Location:** `resources/views/components/dialogue-display.blade.php`

- "Play All" button to play entire dialogue
- Individual play buttons for each line
- Speaker labels included in full dialogue playback

## Browser Support

The Web Speech API is supported in:
- Chrome/Edge (full support)
- Safari (full support)
- Firefox (limited support)

**Note:** Some browsers may require user interaction before allowing audio playback.

## Error Handling

The audio player includes comprehensive error handling:

- **not-allowed**: Audio blocked by browser permissions
- **network**: Network error during audio loading
- **synthesis-unavailable**: Speech synthesis not available
- **synthesis-failed**: Speech synthesis failed

Error messages are displayed to users in a user-friendly format.

## Playback Speed Control

Users can adjust playback speed for better learning:
- **0.5x** - Very slow (beginners)
- **0.75x** - Slow (learning)
- **1x** - Normal speed
- **1.25x** - Slightly faster
- **1.5x** - Fast (advanced)

## Language Support

The audio player supports multiple languages:
- **ja-JP** - Japanese
- **en-US** - English (US)
- **en-GB** - English (UK)
- And many more...

## Usage Examples

### Basic Audio Button
```blade
<x-audio-button text="ありがとう" lang="ja-JP">
    Play
</x-audio-button>
```

### Audio Player with Speed Control
```blade
<x-audio-player 
    text="おはようございます" 
    lang="ja-JP"
    :showSpeedControl="true" />
```

### Custom Alpine.js Implementation
```html
<div x-data="audioPlayer()" x-init="init()">
    <button @click="play('こんにちは', 'ja-JP')">
        Play Japanese
    </button>
    <button @click="play('Hello', 'en-US')">
        Play English
    </button>
    <button @click="stop()">Stop</button>
</div>
```

## Future Enhancements

Potential improvements for future versions:
- Pre-recorded native speaker audio files
- Voice selection (male/female voices)
- Pitch control
- Recording comparison (user vs. native)
- Offline audio support
- Audio caching for better performance

## Troubleshooting

### Audio Not Playing
1. Check browser console for errors
2. Ensure browser supports Web Speech API
3. Check browser permissions for audio
4. Try user interaction (click) before playing

### Audio Quality Issues
1. Try different playback speeds
2. Check browser's speech synthesis voices
3. Consider using pre-recorded audio for critical content

### Performance Issues
1. Avoid playing multiple audio sources simultaneously
2. Stop previous audio before starting new playback
3. Use loading states to prevent multiple clicks
