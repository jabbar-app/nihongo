# Conversation Card Component

## Overview

The conversation card component is a reusable Blade component that displays lesson information in a card format with a speaking-first design philosophy. It supports multiple states (available, in-progress, completed, locked) and is fully responsive for mobile devices.

## Component Location

`resources/views/components/conversation-card.blade.php`

## Usage

### Basic Usage

```blade
<x-conversation-card 
    :lesson="$lesson"
    status="available"
/>
```

### With Progress

```blade
<x-conversation-card 
    :lesson="$lesson"
    :progress="$userProgress"
    status="in-progress"
/>
```

### Locked State

```blade
<x-conversation-card 
    :lesson="$lesson"
    :prerequisiteLesson="$previousLesson"
    status="locked"
/>
```

## Props

### Required Props

- **lesson** (object): The lesson model instance
  - Must have: `order`, `title`, `description`, `slug`
  - Must have relationships: `dialogues()`, `shadowingExercises()`

### Optional Props

- **progress** (object|null): UserProgress model instance
  - Used to display completion percentage
  - Default: `null`

- **status** (string): Card state
  - Options: `'available'`, `'in-progress'`, `'completed'`, `'locked'`
  - Default: `'available'`

- **prerequisiteLesson** (object|null): Required lesson for locked state
  - Used to display prerequisite message
  - Default: `null`

## Card States

### 1. Available State
- **Visual**: White background, gray border
- **Badge**: Lesson number in indigo gradient circle
- **Button**: "Practice Speaking →" in indigo gradient
- **Hover**: Lift animation (-translate-y-1) with enhanced shadow

### 2. In-Progress State
- **Visual**: White background, 2px indigo border
- **Badge**: Lesson number in indigo gradient circle
- **Progress Bar**: Shows completion percentage with indigo gradient
- **Button**: "Continue Speaking →" in indigo gradient
- **Hover**: Lift animation with enhanced shadow

### 3. Completed State
- **Visual**: White background, 2px green border
- **Badges**: Lesson number + green checkmark badge
- **Progress Bar**: 100% completion with green gradient
- **Button**: "Review Conversation →" in green gradient
- **Hover**: Lift animation with enhanced shadow

### 4. Locked State
- **Visual**: Gray background (bg-gray-50), gray border, 75% opacity
- **Badges**: Lesson number + lock icon badge
- **Message**: Prerequisite requirement displayed
- **Button**: Disabled "Locked" button
- **Hover**: No hover effects

## Features

### Lesson Number Badge
- Positioned at top-left corner (-top-3, -left-3)
- 48px × 48px circular badge
- Indigo gradient background
- White text with lesson order number

### Metadata Display
- **Dialogues**: Speech bubble icon + count
- **Shadowing**: Microphone icon + count
- **Estimated Time**: Clock icon + calculated minutes
- Formula: `(dialogues × 2) + (shadowing × 3)` minutes

### Progress Bar
- Only shown for in-progress and completed states
- 8px height, rounded-full
- Animated width transition (duration-500)
- Color: Indigo for in-progress, green for completed
- Displays percentage above bar

### CTA Buttons
- Full-width on all screen sizes
- 48px height (py-3 = 12px padding + text)
- Gradient backgrounds with hover effects
- Shadow effects (shadow-md → shadow-lg on hover)
- Text changes based on state:
  - Available: "Practice Speaking →"
  - In-Progress: "Continue Speaking →"
  - Completed: "Review Conversation →"
  - Locked: "Locked" (disabled)

## Responsive Design

### Mobile (< 768px)
- Single-column layout when used in grid
- Full-width cards
- Touch-friendly buttons (48px+ height)
- Optimized spacing (p-6 = 24px padding)

### Tablet (768px - 1023px)
- 2-column grid layout
- Same card styling as mobile

### Desktop (1024px+)
- 3-column grid layout
- Hover effects enabled
- Enhanced shadows on hover

## Accessibility

### Touch Targets
- All buttons meet 44px minimum (48px actual)
- Icon sizes: 20px (w-5 h-5) for metadata, 24px (w-6 h-6) for badges

### Color Contrast
- Text colors meet WCAG AA standards
- Gray-900 for headings (high contrast)
- Gray-600 for descriptions (sufficient contrast)
- Indigo-600 for interactive elements

### Semantic HTML
- Proper heading hierarchy (h3 for titles)
- Descriptive button text
- SVG icons with proper viewBox

## Animation & Transitions

### Hover Animation
```css
hover:-translate-y-1 hover:shadow-lg transition-all duration-200
```
- Lifts card 4px upward
- Enhances shadow
- 200ms transition duration

### Progress Bar Animation
```css
transition-all duration-500
```
- Smooth width animation when percentage changes
- 500ms duration for noticeable but not jarring effect

## Integration Example

### In Lessons Index Page

```blade
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    @foreach($lessons as $lesson)
        @php
            $userProgress = $lesson->userProgress()
                ->where('user_id', auth()->id())
                ->first();
            
            $status = match(true) {
                $lesson->isLocked(auth()->user()) => 'locked',
                $userProgress?->completion_percentage >= 100 => 'completed',
                $userProgress?->completion_percentage > 0 => 'in-progress',
                default => 'available'
            };
            
            $prerequisite = $lesson->isLocked(auth()->user()) 
                ? $lesson->prerequisiteLesson 
                : null;
        @endphp
        
        <x-conversation-card 
            :lesson="$lesson"
            :progress="$userProgress"
            :status="$status"
            :prerequisiteLesson="$prerequisite"
        />
    @endforeach
</div>
```

## Testing

### Test Page
Visit `/test-conversation-cards` to see all card states in action.

### Manual Testing Checklist
- [ ] All four states render correctly
- [ ] Hover effects work on non-locked cards
- [ ] Progress bars animate smoothly
- [ ] Buttons are touch-friendly (48px height)
- [ ] Cards stack properly on mobile
- [ ] Icons display correctly
- [ ] Text is readable at all sizes
- [ ] Links navigate to correct lesson pages

## Design Specifications

### Colors
- **Primary**: Indigo-500 to Indigo-600 gradient
- **Success**: Green-500 to Green-600 gradient
- **Locked**: Gray-400
- **Text**: Gray-900 (headings), Gray-600 (body)

### Spacing
- **Card padding**: 24px (p-6)
- **Gap between cards**: 24px (gap-6)
- **Button padding**: 12px vertical, 24px horizontal
- **Metadata gap**: 16px (gap-4)

### Typography
- **Title**: text-xl (20px), font-semibold
- **Description**: text-sm (14px), text-gray-600
- **Metadata**: text-sm (14px), text-gray-600
- **Button**: font-semibold

### Borders & Shadows
- **Default border**: 1px solid gray-200
- **In-progress border**: 2px solid indigo-300
- **Completed border**: 2px solid green-300
- **Shadow**: shadow-md (default), shadow-lg (hover)

## Requirements Satisfied

This component satisfies the following requirements from the spec:

- **3.3**: Visual indicators of completion status with speaking-focused icons
- **3.4**: Preview of conversations to practice with dialogue snippets
- **3.5**: Hover effects showing additional context (metadata)
- **3.6**: Achievement badges for completed lessons
- **3.7**: Clear prerequisite communication for locked lessons
- **3.8**: Mobile-optimized single-column layout
- **8.1**: Responsive layout for screens 375px and wider
- **8.2**: Touch-friendly controls (44px+ targets)

## Future Enhancements

Potential improvements for future iterations:

1. **Animation on completion**: Confetti or celebration animation when marking complete
2. **Difficulty indicator**: Show lesson difficulty level
3. **Last practiced date**: Display when user last accessed the lesson
4. **Bookmark toggle**: Quick bookmark button on card
5. **Share functionality**: Share progress on social media
6. **Audio preview**: Play sample dialogue directly from card
