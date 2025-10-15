# Design System Documentation

## Overview

This document describes the speaking-focused design system for the Nihongo language learning application. The design system emphasizes conversation practice, audio-first learning, and Japanese cultural aesthetics.

## Color Palette

### Brand Colors (Speaking Focus)
- **Primary**: `brand-primary` (#4F46E5) - Main speaking action buttons
- **Secondary**: `brand-secondary` (#6366F1) - Hover states and accents
- **Accent**: `brand-accent` (#818CF8) - Highlights and emphasis

### Japanese-Inspired Colors

#### Sakura (Cherry Blossom)
- `sakura-50` to `sakura-500` - Used for celebrations, achievements, and feminine touches
- Example: `bg-sakura-100 text-sakura-500`

#### Matcha (Green Tea)
- `matcha-50` to `matcha-500` - Used for success states, completion indicators
- Example: `bg-matcha-50 border-matcha-400`

### Audio-Specific Colors
- **Wave**: `audio-wave` (#60A5FA) - Audio waveform visualizations
- **Playing**: `audio-playing` (#22C55E) - Active playback state
- **Recording**: `audio-recording` (#DC2626) - Recording indicators
- **Paused**: `audio-paused` (#94A3B8) - Paused state

### Accent Colors
- **Sunset Orange**: `sunset-orange` (#FF6B35) - Streak indicators, urgency
- **Midnight Blue**: `midnight-blue` (#1A1B4B) - Dark mode primary

## Typography

### Font Families

```html
<!-- Default sans-serif with Japanese support -->
<p class="font-sans">English and 日本語 text</p>

<!-- Japanese-optimized font stack -->
<p class="font-japanese">日本語のテキスト</p>

<!-- Monospace for romaji and code -->
<p class="font-mono">Romaji text</p>
```

### Japanese Text Sizes

```html
<!-- Large Japanese text (dialogues, phrases) -->
<p class="text-japanese-lg">すみません、駅はどこですか？</p>

<!-- Extra large Japanese text (headings) -->
<h2 class="text-japanese-xl">会話練習</h2>

<!-- Display Japanese text (hero sections) -->
<h1 class="text-japanese-2xl">日本語を話そう</h1>
```

## Spacing

### Safe Area Insets (Mobile)
```html
<!-- Bottom padding with safe area -->
<div class="pb-safe">Content</div>

<!-- Top padding with safe area -->
<div class="pt-safe">Content</div>
```

## Animations

### Available Animations

```html
<!-- Slow pulse for loading states -->
<div class="animate-pulse-slow">Loading...</div>

<!-- Subtle bounce for achievements -->
<div class="animate-bounce-subtle">🎉</div>

<!-- Slide up for notifications -->
<div class="animate-slide-up">+50 XP</div>

<!-- Fade in for content -->
<div class="animate-fade-in">Content</div>

<!-- Glow effect for special elements -->
<div class="animate-glow">🔥 5 Day Streak</div>

<!-- Wave animation for audio visualizations -->
<div class="animate-wave">|</div>
```

## Component Classes

### Speaking-Focused Buttons

```html
<!-- Primary speaking action button -->
<button class="btn-speaking">
  Practice Speaking
</button>

<!-- Audio control button -->
<button class="btn-audio">
  ▶️
</button>

<!-- Recording button -->
<button class="btn-audio recording">
  ⏺️
</button>

<!-- Playing button -->
<button class="btn-audio playing">
  ▶️
</button>
```

### Conversation Cards

```html
<!-- Default conversation card -->
<div class="card-conversation">
  <h3>Lesson 1: Asking for Directions</h3>
  <p>Speak confidently when navigating cities</p>
</div>

<!-- In-progress conversation card -->
<div class="card-conversation in-progress">
  <!-- Content -->
</div>

<!-- Completed conversation card -->
<div class="card-conversation completed">
  <!-- Content -->
</div>

<!-- Locked conversation card -->
<div class="card-conversation locked">
  <!-- Content -->
</div>
```

### Progress Indicators

```html
<!-- Speaking progress bar -->
<div class="w-full bg-gray-200 rounded-full h-2">
  <div class="progress-speaking" style="width: 60%"></div>
</div>

<!-- Streak indicator -->
<div class="streak-indicator">
  🔥 <span>5 Day Speaking Streak!</span>
</div>
```

### Audio Waveform

```html
<!-- Audio waveform visualization -->
<div class="flex items-center gap-1">
  <span class="audio-wave"></span>
  <span class="audio-wave"></span>
  <span class="audio-wave"></span>
  <span class="audio-wave"></span>
  <span class="audio-wave"></span>
</div>
```

### Japanese Text Display

```html
<!-- Japanese phrase with romaji -->
<div>
  <p class="text-japanese-body">すみません、駅はどこですか？</p>
  <p class="text-romaji">Sumimasen, eki wa doko desu ka?</p>
  <p class="text-sm text-gray-600">Excuse me, where is the station?</p>
</div>
```

### Badges

```html
<!-- Speaking level badge -->
<span class="badge-level">Level 5: Master</span>

<!-- Achievement badge -->
<span class="badge-achievement">
  ✓ Conversation Mastered
</span>
```

### Celebration Modal

```html
<!-- Celebration modal overlay -->
<div class="modal-celebration">
  <div class="modal-celebration-content">
    <h2>よくできました！</h2>
    <p>You completed the conversation!</p>
  </div>
</div>
```

### Touch-Friendly Controls

```html
<!-- Minimum 44x44px touch target -->
<button class="touch-target">
  ▶️
</button>
```

## Utility Classes

### Gradients

```html
<!-- Speaking gradient background -->
<div class="bg-speaking-gradient">
  <!-- Content -->
</div>

<!-- Success gradient background -->
<div class="bg-success-gradient">
  <!-- Content -->
</div>

<!-- Text gradient for emphasis -->
<h1 class="text-gradient-speaking">Speak Japanese</h1>
<p class="text-gradient-success">Completed!</p>
```

### Audio State Indicators

```html
<!-- Playing indicator (green dot) -->
<span class="audio-playing">Now Playing</span>

<!-- Paused indicator (gray dot) -->
<span class="audio-paused">Paused</span>
```

### Sticky Audio Controls

```html
<!-- Sticky audio player for mobile -->
<div class="sticky-audio">
  <!-- Audio controls -->
</div>
```

### Focus States

```html
<!-- Speaking-focused focus ring -->
<button class="focus-speaking">
  Button
</button>
```

## Accessibility

### Reduced Motion Support
The design system automatically respects `prefers-reduced-motion` settings, disabling animations for users who prefer reduced motion.

### Focus Indicators
All interactive elements have visible focus indicators for keyboard navigation:
- 2px solid indigo outline
- 2px offset for clarity
- Rounded corners for consistency

### Touch Targets
All interactive elements meet the minimum 44x44px touch target size for mobile accessibility.

## Responsive Design

### Breakpoints
- **Mobile**: 375px - 767px
- **Tablet**: 768px - 1023px
- **Desktop**: 1024px+
- **Large Desktop**: 1440px+

### Mobile-First Approach
All components are designed mobile-first with progressive enhancement for larger screens.

## Usage Examples

### Speaking Practice Button
```html
<button class="btn-speaking hover:shadow-card-hover">
  Start Speaking Japanese →
</button>
```

### Lesson Card with Progress
```html
<div class="card-conversation in-progress">
  <div class="flex items-center gap-2 mb-2">
    <span class="badge-level">Lesson 1</span>
  </div>
  <h3 class="text-xl font-bold mb-2">Asking for Directions</h3>
  <p class="text-gray-600 mb-4">Speak confidently when navigating cities</p>
  
  <div class="flex items-center gap-4 text-sm text-gray-500 mb-4">
    <span>🎯 5 Dialogues</span>
    <span>🎤 3 Shadowing</span>
    <span>⏱️ 15 min</span>
  </div>
  
  <div class="mb-4">
    <div class="w-full bg-gray-200 rounded-full h-2">
      <div class="progress-speaking" style="width: 40%"></div>
    </div>
    <p class="text-xs text-gray-500 mt-1">40% complete</p>
  </div>
  
  <button class="btn-speaking w-full">
    Continue Speaking →
  </button>
</div>
```

### Audio Player Component
```html
<div class="bg-white border border-gray-200 rounded-lg p-4">
  <div class="flex items-center gap-3 mb-3">
    <button class="btn-audio playing">
      ▶️
    </button>
    <div class="flex-1">
      <p class="font-semibold text-sm">Asking for Directions</p>
      <p class="text-xs text-gray-500">0:15 / 1:23</p>
    </div>
  </div>
  
  <div class="w-full bg-gray-200 rounded-full h-1 mb-2">
    <div class="bg-audio-wave rounded-full h-1" style="width: 30%"></div>
  </div>
  
  <div class="flex items-center justify-between text-xs">
    <button class="text-gray-600 hover:text-brand-primary">0.75x</button>
    <button class="text-brand-primary font-semibold">1x</button>
    <button class="text-gray-600 hover:text-brand-primary">1.25x</button>
  </div>
</div>
```

### Celebration Toast
```html
<div class="fixed bottom-4 right-4 animate-slide-up">
  <div class="bg-white rounded-lg shadow-lg p-4 border-2 border-matcha-400">
    <p class="text-japanese-lg mb-1">よくできました！</p>
    <p class="text-sm text-gray-600">You completed the conversation!</p>
    <p class="xp-gain mt-2">+50 XP</p>
  </div>
</div>
```

## Best Practices

1. **Always use speaking-focused language** in buttons and CTAs
2. **Prioritize audio controls** - make them large and accessible
3. **Use Japanese text with romaji** for pronunciation guidance
4. **Celebrate progress** with animations and Japanese expressions
5. **Ensure touch targets** are minimum 44x44px on mobile
6. **Test with reduced motion** enabled
7. **Verify color contrast** meets WCAG AA standards
8. **Use semantic HTML** with proper ARIA labels

## Resources

- [Tailwind CSS Documentation](https://tailwindcss.com/docs)
- [WCAG 2.1 Guidelines](https://www.w3.org/WAI/WCAG21/quickref/)
- [Japanese Typography Best Practices](https://www.w3.org/TR/jlreq/)
