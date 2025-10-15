# Audio Player Components

This document describes the enhanced audio player components created for the Nihongo language learning application.

## Components Overview

We have created three audio player components:

1. **audio-player-enhanced** - Full-featured audio player with waveform visualization
2. **audio-player-mini** - Compact mobile-optimized player
3. **audio-player** - Original text-to-speech player (legacy)

## 1. Enhanced Audio Player

The enhanced audio player provides a full-featured experience with progress bars, seek functionality, speed controls, and optional waveform visualization.

### Features

- ✅ HTML5 audio element with custom controls
- ✅ Play/pause controls with visual feedback
- ✅ Playback speed controls (0.75x, 1x, 1.25x)
- ✅ Seekable progress bar
- ✅ Timestamp display (current/duration)
- ✅ Optional waveform visualization
- ✅ Loading states with skeleton
- ✅ Error handling with user-friendly messages
- ✅ Keyboard shortcuts (Space, Arrow keys)
- ✅ Accessible ARIA labels

### Usage

```blade
<x-audio-player-enhanced 
    audioUrl="/audio/lesson-1-dialogue-1.mp3"
    title="Asking for Directions"
    :showSpeedControl="true"
    :showWaveform="true"
    :compact="false"
    :autoPlay="false"
/>
```

### Props

| Prop | Type | Default | Description |
|------|------|---------|-------------|
| `audioUrl` | string | '' | URL to the audio file (required) |
| `title` | string | '' | Title displayed above the player |
| `showSpeedControl` | boolean | true | Show playback speed buttons |
| `showWaveform` | boolean | false | Show waveform visualization |
| `compact` | boolean | false | Use compact layout |
| `autoPlay` | boolean | false | Auto-play on load |

### Examples

#### Basic Player

```blade
<x-audio-player-enhanced 
    audioUrl="/audio/dialogue.mp3"
    title="Dialogue 1: Greetings"
/>
```

#### With Waveform

```blade
<x-audio-player-enhanced 
    audioUrl="/audio/dialogue.mp3"
    title="Dialogue 1: Greetings"
    :showWaveform="true"
/>
```

#### Compact Version

```blade
<x-audio-player-enhanced 
    audioUrl="/audio/dialogue.mp3"
    :compact="true"
/>
```

## 2. Mini Audio Player (Mobile-Optimized)

The mini audio player is designed for mobile devices with a compact, sticky layout that stays accessible during scrolling.

### Features

- ✅ Compact design optimized for mobile
- ✅ Touch-friendly controls (minimum 44px)
- ✅ Sticky positioning (stays visible during scroll)
- ✅ One-tap speed cycling
- ✅ Simplified progress bar
- ✅ Safe area support for iOS/Android
- ✅ Backdrop blur effect

### Usage

```blade
<x-audio-player-mini 
    audioUrl="/audio/lesson-1-dialogue-1.mp3"
    title="Asking for Directions"
    :sticky="true"
/>
```

### Props

| Prop | Type | Default | Description |
|------|------|---------|-------------|
| `audioUrl` | string | '' | URL to the audio file (required) |
| `title` | string | '' | Title displayed in the player |
| `sticky` | boolean | true | Enable sticky positioning |

### Examples

#### Sticky Mobile Player

```blade
<x-audio-player-mini 
    audioUrl="/audio/dialogue.mp3"
    title="Dialogue 1: Greetings"
    :sticky="true"
/>
```

#### Inline Mobile Player

```blade
<x-audio-player-mini 
    audioUrl="/audio/dialogue.mp3"
    title="Dialogue 1: Greetings"
    :sticky="false"
/>
```

## Keyboard Shortcuts

Both enhanced players support keyboard shortcuts:

- **Space**: Play/Pause (when player is focused)
- **Left Arrow**: Seek backward 5 seconds
- **Right Arrow**: Seek forward 5 seconds

## Responsive Behavior

### Desktop (≥1024px)
- Full-featured player with all controls visible
- Larger touch targets
- Waveform visualization (if enabled)

### Tablet (768px - 1023px)
- Slightly reduced size
- All features remain accessible

### Mobile (<768px)
- Recommend using `audio-player-mini` component
- Sticky positioning keeps player accessible
- Touch-optimized controls (44px minimum)
- Safe area insets for notched devices

## Accessibility

All audio players include:

- ✅ ARIA labels for screen readers
- ✅ Keyboard navigation support
- ✅ Focus indicators
- ✅ Error messages in accessible format
- ✅ Semantic HTML structure
- ✅ Color contrast compliance (WCAG AA)

## Browser Support

- Chrome/Edge (latest)
- Firefox (latest)
- Safari (latest)
- Mobile Safari (iOS 12+)
- Chrome Mobile (Android 8+)

## Audio File Requirements

- **Format**: MP3 (recommended), OGG, WAV
- **Bitrate**: 128kbps recommended for balance of quality/size
- **Sample Rate**: 44.1kHz or 48kHz
- **Channels**: Mono or Stereo

## Integration Examples

### In Lesson Detail Page

```blade
<div class="space-y-4">
    @foreach($lesson->dialogues as $dialogue)
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold mb-4">{{ $dialogue->title }}</h3>
            
            <x-audio-player-enhanced 
                audioUrl="{{ $dialogue->audio_url }}"
                title="{{ $dialogue->title }}"
                :showWaveform="true"
            />
            
            <div class="mt-4">
                <p class="text-japanese-body">{{ $dialogue->japanese_text }}</p>
                <p class="text-romaji mt-2">{{ $dialogue->romaji }}</p>
                <p class="text-gray-600 mt-2">{{ $dialogue->english_translation }}</p>
            </div>
        </div>
    @endforeach
</div>
```

### Mobile Sticky Player

```blade
<!-- Page content -->
<div class="pb-24">
    <!-- Your lesson content here -->
</div>

<!-- Sticky audio player at bottom -->
<x-audio-player-mini 
    audioUrl="{{ $currentDialogue->audio_url }}"
    title="{{ $currentDialogue->title }}"
    :sticky="true"
/>
```

## Styling Customization

The audio players use Tailwind CSS classes and can be customized via:

1. **Tailwind Config** - Modify colors in `tailwind.config.js`
2. **CSS Classes** - Override styles in `resources/css/app.css`
3. **Component Props** - Pass additional classes via attributes

Example:

```blade
<x-audio-player-enhanced 
    audioUrl="/audio/dialogue.mp3"
    class="my-custom-class"
/>
```

## Performance Considerations

- Audio files are loaded with `preload="metadata"` to minimize initial load
- Waveform visualization uses CSS transforms for smooth animations
- Lazy loading recommended for pages with multiple audio players
- Consider using a CDN for audio file delivery

## Troubleshooting

### Audio won't play
- Check that the audio URL is correct and accessible
- Verify browser autoplay policies (user interaction may be required)
- Check browser console for CORS errors

### Waveform not showing
- Ensure `showWaveform` prop is set to `true`
- Check that the component has loaded successfully

### Sticky player not sticking
- Verify `sticky` prop is set to `true`
- Check for conflicting CSS positioning

## Future Enhancements

Potential improvements for future iterations:

- Real-time waveform generation from audio data
- Recording functionality integration
- Playlist support
- Download option
- Loop/repeat controls
- Volume control
- Playback rate fine-tuning (more speed options)
