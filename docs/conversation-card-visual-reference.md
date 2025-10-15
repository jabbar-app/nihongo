# Conversation Card Component - Visual Reference

## Component Structure

```
┌─────────────────────────────────────────────────────┐
│  ⓵                                            ✓     │  ← Badges (number + state)
│                                                      │
│  🗣️  Asking for Directions                         │  ← Icon + Title
│                                                      │
│      Speak confidently when navigating Japanese     │  ← Description
│      cities                                          │
│                                                      │
│  💬 5 Dialogues  🎤 3 Shadowing  ⏱️ 15 min        │  ← Metadata
│                                                      │
│  Progress: ████████░░░░░░░░ 40%                    │  ← Progress Bar
│                                                      │
│  [Practice Speaking →]                              │  ← CTA Button
│                                                      │
└─────────────────────────────────────────────────────┘
```

## State Variations

### 1. Available State
```
┌─────────────────────────────────────────────────────┐
│  ⓵                                                   │
│                                                      │
│  🗣️  Asking for Directions                         │
│                                                      │
│      Speak confidently when navigating Japanese     │
│      cities                                          │
│                                                      │
│  💬 5 Dialogues  🎤 3 Shadowing  ⏱️ 15 min        │
│                                                      │
│  [Practice Speaking →]                              │
│                                                      │
└─────────────────────────────────────────────────────┘

Colors:
- Border: Gray (1px)
- Background: White
- Badge: Indigo gradient
- Button: Indigo gradient
- Hover: Lifts up with shadow
```

### 2. In-Progress State
```
┏━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━┓  ← Blue border (2px)
┃  ⓶                                                   ┃
┃                                                      ┃
┃  🗣️  Ordering Food                                  ┃
┃                                                      ┃
┃      Order meals and drinks like a local at         ┃
┃      restaurants                                     ┃
┃                                                      ┃
┃  💬 6 Dialogues  🎤 4 Shadowing  ⏱️ 20 min        ┃
┃                                                      ┃
┃  Progress: ████████████░░░░░░░░ 45%                ┃  ← Progress bar
┃                                                      ┃
┃  [Continue Speaking →]                              ┃
┃                                                      ┃
┗━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━┛

Colors:
- Border: Indigo (2px)
- Background: White
- Badge: Indigo gradient
- Progress: Indigo gradient
- Button: Indigo gradient
- Hover: Lifts up with shadow
```

### 3. Completed State
```
┏━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━┓  ← Green border (2px)
┃  ⓷                                            ✓     ┃  ← Checkmark badge
┃                                                      ┃
┃  🗣️  Making Small Talk                             ┃
┃                                                      ┃
┃      Have casual conversations about weather,       ┃
┃      hobbies, and daily life                        ┃
┃                                                      ┃
┃  💬 7 Dialogues  🎤 5 Shadowing  ⏱️ 24 min        ┃
┃                                                      ┃
┃  Progress: ████████████████████ 100%                ┃  ← Full progress
┃                                                      ┃
┃  [Review Conversation →]                            ┃
┃                                                      ┃
┗━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━┛

Colors:
- Border: Green (2px)
- Background: White
- Number Badge: Indigo gradient
- Checkmark Badge: Green gradient
- Progress: Green gradient (100%)
- Button: Green gradient
- Hover: Lifts up with shadow
```

### 4. Locked State
```
┌─────────────────────────────────────────────────────┐
│  ⓸                                            🔒    │  ← Lock icon
│                                                      │
│  🗣️  Shopping & Bargaining                         │
│                                                      │
│      Navigate stores and markets with confidence    │
│                                                      │
│  💬 5 Dialogues  🎤 3 Shadowing  ⏱️ 15 min        │
│                                                      │
│  ┌────────────────────────────────────────────────┐ │
│  │ 🔒 Complete Making Small Talk to unlock this   │ │  ← Prerequisite
│  │    conversation                                 │ │
│  └────────────────────────────────────────────────┘ │
│                                                      │
│  [Locked]                                           │  ← Disabled
│                                                      │
└─────────────────────────────────────────────────────┘

Colors:
- Border: Gray (1px)
- Background: Gray-50 (75% opacity)
- Number Badge: Indigo gradient
- Lock Badge: Gray
- Button: Gray (disabled)
- Hover: None
```

## Badge Details

### Lesson Number Badge
```
    ╭─────────╮
   ╱  ⓵ or ⓶  ╲
  │   or ⓷    │
   ╲  or ⓸   ╱
    ╰─────────╯
```
- Size: 48px × 48px
- Position: Top-left corner (-12px offset)
- Background: Indigo gradient (500 → 600)
- Text: White, bold, 18px
- Shadow: Large

### Checkmark Badge (Completed)
```
    ╭─────────╮
   ╱     ✓     ╲
  │            │
   ╲          ╱
    ╰─────────╯
```
- Size: 48px × 48px
- Position: Top-right corner (-12px offset)
- Background: Green gradient (500 → 600)
- Icon: White checkmark, 24px
- Shadow: Large

### Lock Badge (Locked)
```
    ╭─────────╮
   ╱     🔒    ╲
  │            │
   ╲          ╱
    ╰─────────╯
```
- Size: 48px × 48px
- Position: Top-right corner (-12px offset)
- Background: Gray-400
- Icon: White lock, 24px
- Shadow: Large

## Metadata Icons

### Dialogue Count
```
💬 5 Dialogues
```
- Icon: Speech bubble (20px)
- Color: Indigo-500
- Text: Gray-600, 14px

### Shadowing Count
```
🎤 3 Shadowing
```
- Icon: Microphone (20px)
- Color: Indigo-500
- Text: Gray-600, 14px

### Estimated Time
```
⏱️ 15 min
```
- Icon: Clock (20px)
- Color: Indigo-500
- Text: Gray-600, 14px

## Progress Bar

### Structure
```
Progress: ████████░░░░░░░░ 40%
          ↑                ↑
       Filled          Empty
```

### In-Progress
- Height: 8px
- Filled: Indigo gradient (500 → 600)
- Empty: Gray-200
- Border radius: Full (pill shape)
- Animation: 500ms width transition

### Completed
- Height: 8px
- Filled: Green gradient (500 → 600)
- Empty: None (100% filled)
- Border radius: Full (pill shape)

## Button Styles

### Available / In-Progress
```
┌─────────────────────────────────────┐
│     Practice Speaking →             │
│     or Continue Speaking →          │
└─────────────────────────────────────┘
```
- Width: 100%
- Height: 48px
- Background: Indigo gradient (500 → 600)
- Text: White, semibold, 16px
- Border radius: 8px
- Shadow: Medium → Large on hover
- Hover: Darker gradient

### Completed
```
┌─────────────────────────────────────┐
│     Review Conversation →           │
└─────────────────────────────────────┘
```
- Width: 100%
- Height: 48px
- Background: Green gradient (500 → 600)
- Text: White, semibold, 16px
- Border radius: 8px
- Shadow: Medium → Large on hover
- Hover: Darker gradient

### Locked
```
┌─────────────────────────────────────┐
│     Locked                          │
└─────────────────────────────────────┘
```
- Width: 100%
- Height: 48px
- Background: Gray-300
- Text: Gray-500, semibold, 16px
- Border radius: 8px
- Cursor: Not allowed
- No hover effects

## Responsive Behavior

### Mobile (< 768px)
```
┌─────────────────┐
│   Card 1        │
└─────────────────┘

┌─────────────────┐
│   Card 2        │
└─────────────────┘

┌─────────────────┐
│   Card 3        │
└─────────────────┘
```
- Single column
- Full width
- 24px gap between cards

### Tablet (768px - 1023px)
```
┌─────────────┐  ┌─────────────┐
│   Card 1    │  │   Card 2    │
└─────────────┘  └─────────────┘

┌─────────────┐  ┌─────────────┐
│   Card 3    │  │   Card 4    │
└─────────────┘  └─────────────┘
```
- Two columns
- Equal width
- 24px gap

### Desktop (1024px+)
```
┌─────────┐  ┌─────────┐  ┌─────────┐
│ Card 1  │  │ Card 2  │  │ Card 3  │
└─────────┘  └─────────┘  └─────────┘

┌─────────┐  ┌─────────┐  ┌─────────┐
│ Card 4  │  │ Card 5  │  │ Card 6  │
└─────────┘  └─────────┘  └─────────┘
```
- Three columns
- Equal width
- 24px gap

## Hover Animation

### Before Hover
```
┌─────────────────────────────────────┐
│                                      │
│         Card Content                │
│                                      │
└─────────────────────────────────────┘
```

### On Hover (Available, In-Progress, Completed only)
```
        ↑ Lifts 4px
┌─────────────────────────────────────┐
│                                      │
│         Card Content                │
│                                      │
└─────────────────────────────────────┘
        ↓ Enhanced shadow
```
- Transform: translateY(-4px)
- Shadow: Increases from medium to large
- Transition: 200ms ease
- Locked cards: No hover effect

## Color Palette Reference

### Indigo (Primary)
- 50: #EEF2FF (very light backgrounds)
- 300: #A5B4FC (in-progress border)
- 500: #6366F1 (gradient start)
- 600: #4F46E5 (gradient end)
- 700: #4338CA (hover state)

### Green (Success)
- 300: #86EFAC (completed border)
- 500: #22C55E (gradient start)
- 600: #16A34A (gradient end)
- 700: #15803D (hover state)

### Gray (Neutral)
- 50: #F9FAFB (locked background)
- 200: #E5E7EB (borders, empty progress)
- 300: #D1D5DB (disabled button)
- 400: #9CA3AF (lock badge)
- 500: #6B7280 (disabled text)
- 600: #4B5563 (body text)
- 900: #111827 (headings)

## Spacing Reference

- Card padding: 24px (p-6)
- Badge offset: -12px (-top-3, -left-3, -right-3)
- Icon size: 20px (w-5 h-5)
- Badge icon size: 24px (w-6 h-6)
- Metadata gap: 16px (gap-4)
- Section spacing: 16px (mt-4)
- Button margin: 20px (mt-5)
- Progress bar height: 8px (h-2)

## Typography Reference

- Title: 20px (text-xl), semibold, gray-900
- Description: 14px (text-sm), regular, gray-600
- Metadata: 14px (text-sm), regular, gray-600
- Progress label: 14px (text-sm), medium, gray-600
- Progress percentage: 14px (text-sm), semibold, indigo-600
- Button text: 16px (base), semibold, white
