# Task 9: Lesson Detail Page - Visual Reference

## Page Layout Overview

```
┌─────────────────────────────────────────────────────────────┐
│  Navigation Bar                                              │
├─────────────────────────────────────────────────────────────┤
│  Breadcrumb: Lessons > Lesson Title                         │
│                                                              │
│  ┌──────────────────────────────────────────────────────┐  │
│  │  LESSON HEADER                                        │  │
│  │  ┌──┐                                                 │  │
│  │  │ 1│  Lesson Title                      [Bookmark]  │  │
│  │  └──┘  🎤 Description                                │  │
│  │                                                       │  │
│  │  Speaking Progress: ████████░░░░░░░░ 40%            │  │
│  │  ⏱️ 15 / 20 min practiced  💬 3 / 5 dialogues       │  │
│  └──────────────────────────────────────────────────────┘  │
│                                                              │
│  ┌─────────────────────────────────────────────────────┐   │
│  │ STICKY TAB NAVIGATION                                │   │
│  │ [💬 Dialogues 5] [🎤 Shadowing 3] [💭 Phrases 12]  │   │
│  │ [✓ Drills 4]                                        │   │
│  └─────────────────────────────────────────────────────┘   │
│                                                              │
│  TAB CONTENT AREA                                           │
│  (Dialogues, Shadowing, Phrases, or Drills)                │
│                                                              │
└─────────────────────────────────────────────────────────────┘
```

## Component Details

### 1. Lesson Header

```
┌──────────────────────────────────────────────────────────┐
│  ┌──┐                                                     │
│  │ 1│  Asking for Directions              [⭐ Bookmark] │
│  └──┘  🎤 Speak confidently when navigating cities      │
│                                                           │
│  Speaking Progress                                  40%  │
│  ████████░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░  │
│                                                           │
│  ⏱️ 15 / 20 min practiced  💬 3 / 5 dialogues           │
└──────────────────────────────────────────────────────────┘
```

**Elements:**
- Lesson number badge (indigo circle)
- Lesson title (bold, large)
- Microphone icon + description
- Progress bar (indigo gradient)
- Speaking time stats
- Dialogue completion stats
- Bookmark button (top right)

### 2. Tab Navigation (Sticky)

```
┌─────────────────────────────────────────────────────────┐
│ [💬 Dialogues 5] [🎤 Shadowing 3] [💭 Phrases 12]     │
│ [✓ Drills 4]                                           │
└─────────────────────────────────────────────────────────┘
```

**Tab Order (Speaking-First):**
1. 💬 Dialogues (speech bubbles icon)
2. 🎤 Shadowing (microphone icon)
3. 💭 Phrases (chat icon)
4. ✓ Drills (checklist icon)

**States:**
- Active: Indigo border bottom, indigo text
- Inactive: Gray text, transparent border
- Hover: Gray-700 text, gray-300 border

### 3. Dialogue Card

```
┌──────────────────────────────────────────────────────────┐
│  Dialogue 1: At the Station                [▶ Play All] │
│  Practice this conversation                              │
│  ─────────────────────────────────────────────────────  │
│                                                           │
│  ┌─────────────────────────────────────────────────┐    │
│  │ Person A                                    [▶] │    │
│  │ すみません、駅はどこですか？                      │    │
│  │ Sumimasen, eki wa doko desu ka?                 │    │
│  │ Excuse me, where is the station?                │    │
│  └─────────────────────────────────────────────────┘    │
│                                                           │
│  ┌─────────────────────────────────────────────────┐    │
│  │ Person B                                    [▶] │    │
│  │ まっすぐ行って、右に曲がってください。            │    │
│  │ Massugu itte, migi ni magatte kudasai.          │    │
│  │ Go straight and turn right.                     │    │
│  └─────────────────────────────────────────────────┘    │
│                                                           │
│  [🎤 Practice Speaking This Dialogue]                   │
└──────────────────────────────────────────────────────────┘
```

**Features:**
- Japanese text: 1.125rem, bold
- Romaji: 0.875rem, monospace, gray
- Translation: 1rem, italic, gray
- Individual play buttons per line
- "Play All" button in header
- Speaking practice CTA button (gradient)

### 4. Exercise Statistics

```
┌──────────────────────────────────────────────────────────┐
│  📊 Your Speaking Practice Progress                      │
│                                                           │
│  ┌──────────────────┐  ┌──────────────────┐            │
│  │ 🔄 Practice      │  │ ⭐ Average Score │            │
│  │    Sessions      │  │                  │            │
│  │                  │  │                  │            │
│  │      12          │  │      85%         │            │
│  │    times         │  │ ████████░░       │            │
│  └──────────────────┘  └──────────────────┘            │
│                                                           │
│                                    [View History →]      │
└──────────────────────────────────────────────────────────┘
```

**Color Coding:**
- Green (≥70%): Excellent
- Yellow (50-69%): Good
- Red (<50%): Needs work

### 5. Drill Card

```
┌──────────────────────────────────────────────────────────┐
│  ┌──┐                                                     │
│  │🎵│  Particle Practice                                 │
│  └──┘                                                     │
│       [Multiple Choice] 10 questions  ~5 min  [✓ Done]  │
│                                                           │
│  ─────────────────────────────────────────────────────  │
│  Attempts: 3    Best Score: 90% ✓    Last Score: 85%   │
│                                                           │
│                              [▶ Practice Again]          │
└──────────────────────────────────────────────────────────┘
```

**Elements:**
- Music note icon (indigo background)
- Exercise type badge
- Question count + estimated time
- Completion badge (green)
- Performance stats grid
- Action button (gradient)

### 6. Shadowing Card

```
┌──────────────────────────────────────────────────────────┐
│  ┌──┐                                                     │
│  │🎤│  Restaurant Conversation                           │
│  └──┘                                                     │
│       [Shadowing] 2:30  8 lines  ~3 min  [✓ Practiced] │
│                                                           │
│  ─────────────────────────────────────────────────────  │
│  ✓ Completed: 5 times    Last: 2 hours ago             │
│                                                           │
│                              [🎤 Start Shadowing]        │
└──────────────────────────────────────────────────────────┘
```

**Elements:**
- Microphone icon (purple background)
- Shadowing badge (purple)
- Duration + line count + estimated time
- Completion badge (green)
- Completion stats
- Action button (purple gradient)

### 7. Completion Modal

```
┌─────────────────────────────────────────────────────────┐
│                    ✨ Confetti ✨                        │
│                                                          │
│                      ┌────┐                             │
│                      │ ✓  │                             │
│                      └────┘                             │
│                                                          │
│                よくできました！                           │
│              Yoku dekimashita!                          │
│                  Well done!                             │
│                                                          │
│          You completed the dialogue!                    │
│   Your pronunciation is improving with every practice!  │
│                                                          │
│     ┌──────────┐        ┌──────────┐                   │
│     │ ⚡ +50   │        │ ⭐ 85%   │                   │
│     │ XP Earned│        │  Score   │                   │
│     └──────────┘        └──────────┘                   │
│                                                          │
│  [Practice Again]  [Next Lesson]                        │
└─────────────────────────────────────────────────────────┘
```

**Features:**
- Animated confetti background
- Success icon (color-coded)
- Japanese expression (large, bold)
- Romaji pronunciation
- English translation
- Completion message
- Encouragement text
- XP and score display
- Action buttons (customizable)

### 8. Completion Toast

```
                                    ┌──────────────────────┐
                                    │ ✓  よくできました！  │
                                    │    ⚡ +25 XP      [×]│
                                    └──────────────────────┘
```

**Features:**
- Bottom-right position
- Auto-dismiss (3 seconds)
- Japanese message
- XP badge
- Close button
- Slide-in animation

## Color Palette

### Primary Colors
- **Indigo-600**: `#4F46E5` - Primary actions, progress
- **Purple-600**: `#9333EA` - Shadowing exercises
- **Green-600**: `#16A34A` - Success, completion
- **Yellow-600**: `#CA8A04` - Good performance
- **Red-600**: `#DC2626` - Needs improvement

### Background Colors
- **Indigo-50**: `#EEF2FF` - Primary backgrounds
- **Purple-50**: `#FAF5FF` - Shadowing backgrounds
- **Green-50**: `#F0FDF4` - Success backgrounds
- **Gray-50**: `#F9FAFB` - Neutral backgrounds

## Typography

### Font Sizes
- **Japanese Display**: 1.125rem (18px)
- **Body Text**: 1rem (16px)
- **Small Text**: 0.875rem (14px)
- **Caption**: 0.75rem (12px)

### Font Weights
- **Bold**: 700 - Headings, emphasis
- **Semibold**: 600 - Subheadings, labels
- **Medium**: 500 - Body text
- **Regular**: 400 - Secondary text

## Spacing

### Component Padding
- **Cards**: 1rem (16px) mobile, 1.5rem (24px) desktop
- **Sections**: 1rem (16px) mobile, 1.5rem (24px) desktop
- **Buttons**: 0.75rem 1.5rem (12px 24px)

### Gaps
- **Small**: 0.5rem (8px)
- **Medium**: 1rem (16px)
- **Large**: 1.5rem (24px)

## Responsive Breakpoints

- **Mobile**: < 640px (sm)
- **Tablet**: 640px - 1024px (sm to lg)
- **Desktop**: ≥ 1024px (lg)

### Mobile Adjustments
- Single column layouts
- Stacked elements
- Larger touch targets (44px minimum)
- Simplified navigation
- Bottom navigation bar

### Desktop Enhancements
- Multi-column grids
- Side-by-side layouts
- Hover effects
- Larger text sizes
- More detailed information
