# Completion Feedback Components

This document describes the completion feedback components created for celebrating user achievements with Japanese expressions.

## Components

### 1. Completion Feedback Modal (`completion-feedback.blade.php`)

A full-screen modal with celebration animations, Japanese congratulatory text, and detailed feedback.

#### Usage

```blade
<x-completion-feedback 
    :show="true"
    type="dialogue"
    :xpGained="50"
    :score="85"
    encouragement="Your pronunciation is improving with every practice!">
    
    <!-- Action buttons (slot content) -->
    <button class="btn-primary">Practice Again</button>
    <button class="btn-secondary">Next Lesson</button>
</x-completion-feedback>
```

#### Props

- `show` (boolean, default: false) - Controls modal visibility
- `type` (string, default: 'exercise') - Type of completion: 'exercise', 'dialogue', 'shadowing'
- `xpGained` (integer, default: 0) - XP points earned
- `score` (integer|null, default: null) - Score percentage (0-100)
- `encouragement` (string|null, default: null) - Custom encouragement message

#### Features

- **Japanese Expressions**: Automatically selects appropriate Japanese congratulatory text based on score
  - 80%+: よくできました！ (Yoku dekimashita! - Well done!)
  - 60-79%: がんばりました！ (Ganbarimashita! - You did your best!)
  - <60%: もう少し！ (Mō sukoshi! - A little more!)
- **Confetti Animation**: CSS-based confetti celebration effect
- **Color-coded Performance**: Green (excellent), Yellow (good), Blue (keep going)
- **XP Display**: Shows XP gained with icon
- **Score Display**: Shows percentage score with visual indicator
- **Customizable Actions**: Slot for custom action buttons

### 2. Completion Toast (`completion-toast.blade.php`)

A lightweight toast notification for quick feedback without interrupting the user flow.

#### Usage

```blade
<x-completion-toast 
    :show="true"
    message="よくできました！"
    :xpGained="25"
    :duration="3000" />
```

#### Props

- `show` (boolean, default: false) - Controls toast visibility
- `message` (string, default: 'よくできました！') - Japanese message to display
- `xpGained` (integer, default: 0) - XP points earned
- `duration` (integer, default: 3000) - Auto-hide duration in milliseconds

#### Features

- **Auto-dismiss**: Automatically hides after specified duration
- **Bottom-right Position**: Fixed position, mobile-friendly
- **XP Badge**: Optional XP display
- **Close Button**: Manual dismiss option
- **Smooth Animations**: Slide-in and fade-out transitions

## Integration Examples

### Exercise Completion (Controller)

```php
// In ExerciseController@complete
public function complete(Request $request, Drill $drill)
{
    $score = $this->calculateScore($request->answers);
    $xpGained = $this->awardXP($score);
    
    return view('exercises.complete', [
        'drill' => $drill,
        'score' => $score,
        'xpGained' => $xpGained,
        'showFeedback' => true
    ]);
}
```

### Exercise Completion (View)

```blade
<x-completion-feedback 
    :show="$showFeedback ?? false"
    type="exercise"
    :xpGained="$xpGained"
    :score="$score">
    
    <a href="{{ route('exercises.show', $drill) }}" 
       class="btn-primary">
        Practice Again
    </a>
    <a href="{{ route('lessons.show', $drill->lesson) }}" 
       class="btn-secondary">
        Back to Lesson
    </a>
</x-completion-feedback>
```

### Dialogue Practice Completion

```blade
<x-completion-feedback 
    :show="$completed"
    type="dialogue"
    :xpGained="30"
    encouragement="You're speaking more naturally! Keep practicing this conversation.">
    
    <button @click="replayDialogue()" class="btn-primary">
        Practice Again
    </button>
    <a href="{{ route('lessons.show', $lesson) }}" class="btn-secondary">
        Continue Lesson
    </a>
</x-completion-feedback>
```

### Shadowing Exercise Completion

```blade
<x-completion-feedback 
    :show="$completed"
    type="shadowing"
    :xpGained="40">
    
    <button @click="restartShadowing()" class="btn-primary">
        Shadow Again
    </button>
    <a href="{{ route('lessons.show', $lesson) }}" class="btn-secondary">
        Next Exercise
    </a>
</x-completion-feedback>
```

### Quick Toast Notification (Alpine.js)

```blade
<div x-data="{ showToast: false }">
    <button @click="showToast = true">Complete Exercise</button>
    
    <x-completion-toast 
        x-bind:show="showToast"
        message="すごい！"
        :xpGained="15" />
</div>
```

## Styling

The components use Tailwind CSS classes and include custom CSS for animations. The confetti animation is defined in the component's `@push('styles')` section.

### Color Scheme

- **Excellent (80%+)**: Green (`green-100`, `green-600`)
- **Good (60-79%)**: Yellow (`yellow-100`, `yellow-600`)
- **Keep Going (<60%)**: Blue (`blue-100`, `blue-600`)
- **XP**: Indigo (`indigo-100`, `indigo-600`)

## Accessibility

- Modal uses proper ARIA attributes (`role="dialog"`, `aria-modal="true"`)
- Keyboard navigation supported (ESC to close)
- Screen reader friendly with semantic HTML
- Focus management for modal interactions
- Touch-friendly buttons (44px minimum height)

## Japanese Expressions Reference

| Expression | Romaji | Translation | When to Use |
|------------|--------|-------------|-------------|
| よくできました！ | Yoku dekimashita! | Well done! | Score ≥ 80% |
| がんばりました！ | Ganbarimashita! | You did your best! | Score 60-79% |
| もう少し！ | Mō sukoshi! | A little more! | Score < 60% |
| すごい！ | Sugoi! | Amazing! | Perfect score |
| おめでとう！ | Omedetō! | Congratulations! | Milestone achievements |

## Future Enhancements

- Sound effects for celebrations
- More varied Japanese expressions
- Streak milestone celebrations
- Level-up animations
- Social sharing options
- Achievement badge displays
