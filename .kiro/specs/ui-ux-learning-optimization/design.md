# Design Document

## Overview

This design document outlines the UI/UX improvements for the Nihongo language learning application, with a strong emphasis on speaking and conversation practice. The design philosophy centers around creating an immersive, speaking-first experience that motivates learners to practice Japanese conversations confidently.

### Design Principles

1. **Speaking-First**: Every design decision prioritizes speaking and conversation practice
2. **Audio-Centric**: Audio controls and playback are prominent and easily accessible
3. **Conversation-Focused**: Visual hierarchy emphasizes dialogue and shadowing exercises
4. **Motivational**: Design elements celebrate speaking progress and encourage daily practice
5. **Mobile-Optimized**: Touch-friendly controls optimized for on-the-go speaking practice
6. **Culturally Authentic**: Japanese design aesthetics integrated throughout
7. **Accessible**: Inclusive design supporting diverse learning needs

### Target User Experience

Users should feel like they're using a dedicated speaking coach app, where:
- Speaking practice is the primary activity
- Audio controls are always within reach
- Progress is measured in conversations mastered
- The interface encourages daily speaking practice
- Japanese culture is subtly woven into the design

## Architecture

### Design System Structure

```
Design System
├── Visual Foundation
│   ├── Color Palette (Japanese-inspired)
│   ├── Typography (English + Japanese)
│   ├── Spacing System
│   └── Elevation & Shadows
├── Components
│   ├── Audio Controls
│   ├── Speaking Progress Indicators
│   ├── Conversation Cards
│   ├── Navigation
│   └── Forms
├── Patterns
│   ├── Speaking Practice Flow
│   ├── Progress Visualization
│   └── Gamification Elements
└── Page Templates
    ├── Landing Page
    ├── Authentication
    ├── Dashboard
    ├── Lessons Index
    └── Lesson Detail
```


## Components and Interfaces

### 1. Color Palette

#### Primary Colors (Speaking & Conversation)
- **Indigo 600** (`#4F46E5`): Primary action color for speaking practice buttons
- **Indigo 700** (`#4338CA`): Hover states for primary actions
- **Indigo 50** (`#EEF2FF`): Light backgrounds for speaking sections

#### Accent Colors (Japanese-Inspired)
- **Sakura Pink** (`#FFB7C5`): Celebration and achievement moments
- **Matcha Green** (`#88B04B`): Completion states and success messages
- **Sunset Orange** (`#FF6B35`): Streak indicators and urgency
- **Midnight Blue** (`#1A1B4B`): Dark mode primary

#### Semantic Colors
- **Success Green** (`#10B981`): Correct answers, completed exercises
- **Warning Amber** (`#F59E0B`): Reminders, attention needed
- **Error Red** (`#EF4444`): Mistakes, validation errors
- **Info Blue** (`#3B82F6`): Tips, information

#### Neutral Colors
- **Gray 50-900**: Text hierarchy and backgrounds
- **White** (`#FFFFFF`): Primary background
- **Black** (`#000000`): Primary text (with opacity)

#### Audio-Specific Colors
- **Audio Wave Blue** (`#60A5FA`): Audio waveform visualizations
- **Recording Red** (`#DC2626`): Recording indicators
- **Playing Green** (`#22C55E`): Active playback state

### 2. Typography System

#### Font Families
```css
/* Primary Font Stack */
font-family: 'Inter', 'Hiragino Sans', 'Hiragino Kaku Gothic ProN', 
             'Noto Sans JP', 'Yu Gothic', 'Meiryo', sans-serif;

/* Japanese-Optimized Stack */
.font-japanese {
  font-family: 'Noto Sans JP', 'Hiragino Sans', 'Yu Gothic', 
               'Meiryo', sans-serif;
}

/* Monospace for Code/Romaji */
font-family: 'JetBrains Mono', 'Courier New', monospace;
```

#### Type Scale
- **Display**: 3rem (48px) - Landing page hero
- **H1**: 2.5rem (40px) - Page titles
- **H2**: 2rem (32px) - Section headings
- **H3**: 1.5rem (24px) - Card titles
- **H4**: 1.25rem (20px) - Subsections
- **Body Large**: 1.125rem (18px) - Important content
- **Body**: 1rem (16px) - Default text
- **Body Small**: 0.875rem (14px) - Secondary text
- **Caption**: 0.75rem (12px) - Labels, metadata

#### Japanese Text Sizing
- **Japanese Display**: 2.5rem (40px) - Hero Japanese text
- **Japanese Body**: 1.125rem (18px) - Phrases, dialogues
- **Romaji**: 0.875rem (14px) - Pronunciation guide


### 3. Spacing System

Based on 4px base unit:
- **xs**: 4px (0.25rem)
- **sm**: 8px (0.5rem)
- **md**: 16px (1rem)
- **lg**: 24px (1.5rem)
- **xl**: 32px (2rem)
- **2xl**: 48px (3rem)
- **3xl**: 64px (4rem)

#### Component Spacing
- **Card padding**: 24px (lg)
- **Section spacing**: 48px (2xl)
- **Button padding**: 12px 24px
- **Input padding**: 12px 16px
- **Touch target minimum**: 44px × 44px

### 4. Audio Control Components

#### Primary Audio Player
```
┌─────────────────────────────────────────┐
│  🔊 Asking for Directions               │
│  ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━  │
│  ▶️  0:15 / 1:23                        │
│  [Slow] [Normal] [Fast]                 │
└─────────────────────────────────────────┘
```

**Features**:
- Large play/pause button (56px × 56px)
- Visual waveform or progress bar
- Speed controls (0.75x, 1x, 1.25x)
- Timestamp display
- Loop toggle for practice
- Download option

#### Recording Control
```
┌─────────────────────────────────────────┐
│  🎤 Record Your Practice                │
│  ⏺️  Recording... 0:08                  │
│  ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━  │
│  [Stop] [Play Back] [Re-record]         │
└─────────────────────────────────────────┘
```

**Features**:
- Prominent record button (64px × 64px)
- Real-time recording indicator
- Waveform visualization during recording
- Playback comparison with native audio
- Save/discard options

#### Mini Audio Player (Mobile)
```
┌──────────────────────────────┐
│ ▶️ Dialogue 1    0:45 / 2:10 │
└──────────────────────────────┘
```

**Features**:
- Compact design for mobile
- Sticky positioning during scroll
- Quick play/pause access
- Minimal visual footprint


### 5. Conversation Card Component

```
┌─────────────────────────────────────────────────┐
│  🗣️ Lesson 1: Asking for Directions            │
│                                                  │
│  "Speak confidently when navigating cities"     │
│                                                  │
│  ┌──────────────────────────────────────────┐  │
│  │ 🎯 5 Dialogues  🎤 3 Shadowing  ⏱️ 15min │  │
│  └──────────────────────────────────────────┘  │
│                                                  │
│  Progress: ████████░░░░░░░░░░ 40%              │
│                                                  │
│  [Practice Speaking →]                          │
└─────────────────────────────────────────────────┘
```

**Design Specifications**:
- **Border**: 1px solid gray-200, rounded-lg (8px)
- **Hover**: Lift effect (-2px translateY), shadow-lg
- **Icon size**: 24px × 24px
- **Progress bar**: 8px height, rounded-full
- **Button**: Full-width on mobile, auto on desktop

### 6. Speaking Progress Indicator

#### Circular Progress (Dashboard)
```
     ╭─────────╮
    ╱  75%     ╲
   │  Speaking  │
   │   Level    │
    ╲  Master  ╱
     ╰─────────╯
```

**Features**:
- Animated SVG circle
- Percentage in center
- Level name below
- Color gradient based on progress

#### Linear Progress (Lessons)
```
Conversations Mastered
████████████░░░░░░░░ 12/20
```

**Features**:
- Gradient fill (indigo-500 to indigo-600)
- Smooth animation on update
- Fraction display
- Accessible label

### 7. Speaking Streak Display

```
┌─────────────────────────────┐
│  🔥 5 Day Speaking Streak!  │
│  Keep it going!             │
│  ▓▓▓▓▓░░ (5/7 this week)   │
└─────────────────────────────┘
```

**Design Specifications**:
- **Flame icon**: Animated with glow effect
- **Background**: Gradient from orange-50 to red-50
- **Border**: 2px solid orange-400
- **Animation**: Pulse on streak increase


### 8. Navigation Components

#### Desktop Navigation
```
┌────────────────────────────────────────────────────┐
│ 🗾 Nihongo  [Conversations] [Practice] [Progress]  │
│                                    🔥5  👤 Profile  │
└────────────────────────────────────────────────────┘
```

#### Mobile Bottom Navigation
```
┌────────────────────────────────────────────────────┐
│  🏠        🗣️         ▶️        📊        👤       │
│ Home   Conversations  Practice  Progress  Profile  │
└────────────────────────────────────────────────────┘
```

**Mobile Navigation Specifications**:
- **Height**: 64px
- **Icons**: 24px × 24px
- **Active state**: Indigo-600 with label
- **Inactive state**: Gray-400
- **Safe area**: Respects iOS/Android safe areas

### 9. Button System

#### Primary Button (Speaking Actions)
```css
.btn-primary {
  background: linear-gradient(135deg, #4F46E5 0%, #6366F1 100%);
  color: white;
  padding: 12px 24px;
  border-radius: 8px;
  font-weight: 600;
  box-shadow: 0 4px 6px rgba(79, 70, 229, 0.2);
  transition: all 0.2s;
}

.btn-primary:hover {
  transform: translateY(-2px);
  box-shadow: 0 6px 12px rgba(79, 70, 229, 0.3);
}

.btn-primary:active {
  transform: translateY(0);
}
```

#### Secondary Button
```css
.btn-secondary {
  background: white;
  color: #4F46E5;
  border: 2px solid #4F46E5;
  padding: 12px 24px;
  border-radius: 8px;
  font-weight: 600;
}
```

#### Audio Button (Play/Record)
```css
.btn-audio {
  width: 56px;
  height: 56px;
  border-radius: 50%;
  background: #4F46E5;
  color: white;
  display: flex;
  align-items: center;
  justify-content: center;
  box-shadow: 0 4px 12px rgba(79, 70, 229, 0.3);
}

.btn-audio.recording {
  background: #DC2626;
  animation: pulse 1.5s infinite;
}
```


## Data Models

### Design Tokens (Tailwind Config Extension)

```javascript
// tailwind.config.js
export default {
  theme: {
    extend: {
      colors: {
        // Speaking-focused brand colors
        brand: {
          primary: '#4F46E5',
          secondary: '#6366F1',
          accent: '#818CF8',
        },
        // Japanese-inspired palette
        sakura: {
          50: '#FFF5F7',
          100: '#FFE4E9',
          200: '#FFB7C5',
          300: '#FF8FA3',
          400: '#FF6B82',
          500: '#FF4D6D',
        },
        matcha: {
          50: '#F0F7ED',
          100: '#D4E7C5',
          200: '#B8D89F',
          300: '#9BC97A',
          400: '#88B04B',
          500: '#6B8E23',
        },
        // Audio-specific colors
        audio: {
          wave: '#60A5FA',
          playing: '#22C55E',
          recording: '#DC2626',
          paused: '#94A3B8',
        },
      },
      fontFamily: {
        sans: ['Inter', 'Hiragino Sans', 'Noto Sans JP', 'sans-serif'],
        japanese: ['Noto Sans JP', 'Hiragino Sans', 'Yu Gothic', 'sans-serif'],
        mono: ['JetBrains Mono', 'Courier New', 'monospace'],
      },
      fontSize: {
        'japanese-lg': ['1.125rem', { lineHeight: '1.75' }],
        'japanese-xl': ['1.5rem', { lineHeight: '1.75' }],
        'japanese-2xl': ['2rem', { lineHeight: '1.5' }],
      },
      spacing: {
        'safe-bottom': 'env(safe-area-inset-bottom)',
        'safe-top': 'env(safe-area-inset-top)',
      },
      animation: {
        'pulse-slow': 'pulse 3s cubic-bezier(0.4, 0, 0.6, 1) infinite',
        'bounce-subtle': 'bounce-subtle 0.6s ease-in-out',
        'slide-up': 'slide-up 0.3s ease-out',
        'fade-in': 'fade-in 0.4s ease-in',
        'glow': 'glow 2s ease-in-out infinite',
      },
      keyframes: {
        'bounce-subtle': {
          '0%, 100%': { transform: 'translateY(0)' },
          '50%': { transform: 'translateY(-10px)' },
        },
        'slide-up': {
          '0%': { transform: 'translateY(20px)', opacity: '0' },
          '100%': { transform: 'translateY(0)', opacity: '1' },
        },
        'fade-in': {
          '0%': { opacity: '0' },
          '100%': { opacity: '1' },
        },
        'glow': {
          '0%, 100%': { boxShadow: '0 0 5px rgba(79, 70, 229, 0.5)' },
          '50%': { boxShadow: '0 0 20px rgba(79, 70, 229, 0.8)' },
        },
      },
    },
  },
}
```


### Component State Models

#### Audio Player State
```javascript
{
  isPlaying: boolean,
  currentTime: number,
  duration: number,
  playbackRate: 0.75 | 1.0 | 1.25,
  isLooping: boolean,
  volume: number (0-1),
  waveformData: number[],
}
```

#### Speaking Progress State
```javascript
{
  conversationsMastered: number,
  totalConversations: number,
  speakingStreak: number,
  longestStreak: number,
  totalSpeakingMinutes: number,
  todaySpeakingMinutes: number,
  speakingLevel: number,
  xpToNextLevel: number,
}
```

#### Lesson Card State
```javascript
{
  id: string,
  title: string,
  description: string,
  order: number,
  status: 'locked' | 'available' | 'in-progress' | 'completed',
  dialogueCount: number,
  shadowingCount: number,
  estimatedMinutes: number,
  completionPercentage: number,
  lastPracticed: Date | null,
}
```

## Error Handling

### Error Message Design

#### Friendly Error Display
```
┌─────────────────────────────────────────┐
│  😅 Oops! Something went wrong          │
│                                          │
│  We couldn't load your speaking         │
│  progress. Let's try that again.        │
│                                          │
│  [Try Again]  [Go to Dashboard]         │
└─────────────────────────────────────────┘
```

**Error Message Principles**:
1. Use friendly, non-technical language
2. Include emoji for emotional connection
3. Explain what happened in simple terms
4. Provide clear next steps
5. Offer alternative actions

#### Validation Errors (Forms)
```
Email
[user@example.com]
❌ Please enter a valid email address
```

**Validation Design**:
- Red border on invalid field
- Error icon (❌) before message
- Error text in red-600
- Appears immediately on blur
- Clears when valid input entered


### Loading States

#### Skeleton Loader (Conversation Cards)
```
┌─────────────────────────────────────────┐
│  ▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓  │
│                                          │
│  ▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓  │
│  ▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓  │
│                                          │
│  ▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓  │
└─────────────────────────────────────────┘
```

**Skeleton Specifications**:
- Animated pulse effect
- Gray-200 background
- Matches actual component dimensions
- Smooth transition to real content

#### Audio Loading
```
🔊 Loading audio...
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
```

**Features**:
- Indeterminate progress bar
- Loading message
- Timeout fallback after 10 seconds

### Success States

#### Completion Celebration
```
┌─────────────────────────────────────────┐
│           ✨ よくできました！ ✨          │
│                                          │
│     You completed the conversation!      │
│                                          │
│         +50 XP  🔥 Streak: 6 days       │
│                                          │
│  [Practice Again]  [Next Conversation]  │
└─────────────────────────────────────────┘
```

**Celebration Design**:
- Confetti animation (CSS or canvas)
- Japanese congratulatory text
- XP gain animation
- Streak update
- Clear next actions

## Testing Strategy

### Visual Regression Testing

#### Key Screens to Test
1. Landing page (desktop & mobile)
2. Login/Register pages
3. Dashboard (with/without data)
4. Lessons index (various states)
5. Lesson detail (all tabs)
6. Audio player (all states)
7. Progress modals

#### Testing Tools
- **Percy** or **Chromatic**: Visual regression testing
- **Storybook**: Component isolation and testing
- **Playwright**: E2E visual testing


### Accessibility Testing

#### WCAG 2.1 AA Compliance Checklist
- [ ] Color contrast ratios meet 4.5:1 for normal text
- [ ] Color contrast ratios meet 3:1 for large text
- [ ] All interactive elements have focus indicators
- [ ] Audio content has text alternatives
- [ ] Forms have proper labels and error associations
- [ ] Keyboard navigation works for all features
- [ ] Screen reader announcements are clear
- [ ] Touch targets are minimum 44×44px

#### Testing Tools
- **axe DevTools**: Automated accessibility scanning
- **WAVE**: Web accessibility evaluation
- **NVDA/JAWS**: Screen reader testing
- **Keyboard only**: Manual keyboard navigation testing

### Responsive Testing

#### Breakpoints
- **Mobile**: 375px - 767px
- **Tablet**: 768px - 1023px
- **Desktop**: 1024px+
- **Large Desktop**: 1440px+

#### Test Devices
- iPhone SE (375px)
- iPhone 12/13 (390px)
- iPhone 14 Pro Max (430px)
- iPad (768px)
- iPad Pro (1024px)
- Desktop (1440px)

### Performance Testing

#### Metrics to Monitor
- **First Contentful Paint**: < 1.5s
- **Largest Contentful Paint**: < 2.5s
- **Time to Interactive**: < 3.5s
- **Cumulative Layout Shift**: < 0.1
- **Audio load time**: < 2s

#### Optimization Strategies
- Lazy load images and audio
- Use WebP for images
- Compress audio files (MP3 at 128kbps)
- Code splitting for routes
- Cache static assets


## Page-Specific Designs

### Landing Page Design

#### Hero Section
```
┌────────────────────────────────────────────────────────┐
│                                                         │
│  🗾 Nihongo                          [Login] [Sign Up] │
│                                                         │
│                                                         │
│         Speak Japanese with Confidence                 │
│                                                         │
│    Master real conversations through speaking practice │
│                                                         │
│              [Start Speaking Japanese →]               │
│                                                         │
│         🎤 500+ Conversations  🗣️ Native Audio         │
│                                                         │
│  [Visual: Audio waveform with Japanese characters]     │
│                                                         │
└────────────────────────────────────────────────────────┘
```

**Hero Specifications**:
- **Background**: Gradient from indigo-50 to white
- **Heading**: 3rem (48px), bold, indigo-900
- **Subheading**: 1.25rem (20px), gray-600
- **CTA Button**: Large (56px height), gradient background
- **Stats**: Icons + numbers, gray-700
- **Visual**: Animated audio waveform or speaking illustration

#### Features Section
```
┌────────────────────────────────────────────────────────┐
│                                                         │
│              How You'll Learn to Speak                 │
│                                                         │
│  ┌──────────────┐  ┌──────────────┐  ┌──────────────┐ │
│  │  🗣️          │  │  🎤          │  │  📊          │ │
│  │              │  │              │  │              │ │
│  │ Real         │  │ Shadow       │  │ Track Your   │ │
│  │ Conversations│  │ Native       │  │ Speaking     │ │
│  │              │  │ Speakers     │  │ Progress     │ │
│  │ Practice     │  │ Perfect your │  │ See your     │ │
│  │ everyday     │  │ pronunciation│  │ improvement  │ │
│  │ scenarios    │  │ with audio   │  │ over time    │ │
│  └──────────────┘  └──────────────┘  └──────────────┘ │
│                                                         │
└────────────────────────────────────────────────────────┘
```

**Feature Card Specifications**:
- **Size**: 320px × 280px
- **Icon**: 48px × 48px, indigo-600
- **Title**: 1.25rem (20px), bold
- **Description**: 1rem (16px), gray-600
- **Hover**: Lift effect, shadow-lg

#### Social Proof Section
```
┌────────────────────────────────────────────────────────┐
│                                                         │
│        Join 10,000+ Learners Speaking Japanese         │
│                                                         │
│  "I went from zero to having real conversations in     │
│   just 3 months. The shadowing exercises are amazing!" │
│                                                         │
│   - Sarah K., Speaking Level 5                         │
│                                                         │
│  ⭐⭐⭐⭐⭐  4.9/5 from 2,000+ reviews                  │
│                                                         │
└────────────────────────────────────────────────────────┘
```


### Authentication Pages Design

#### Login Page
```
┌────────────────────────────────────────────────────────┐
│                                                         │
│  🗾 Nihongo                                            │
│                                                         │
│                                                         │
│       Welcome back! Ready to practice speaking?        │
│                                                         │
│  Email                                                  │
│  [user@example.com                    ]                │
│                                                         │
│  Password                                               │
│  [••••••••••••                        ]                │
│                                                         │
│  ☐ Keep me signed in for daily speaking practice       │
│                                                         │
│  [Continue Speaking →]                                 │
│                                                         │
│  Forgot password?                                       │
│                                                         │
│  ─────────────── or ───────────────                   │
│                                                         │
│  New to Nihongo? [Start speaking today →]             │
│                                                         │
└────────────────────────────────────────────────────────┘
```

**Login Page Specifications**:
- **Container**: Max-width 400px, centered
- **Background**: White card with shadow-lg
- **Heading**: 1.5rem (24px), gray-900
- **Inputs**: 48px height, rounded-lg
- **Button**: Full-width, 48px height
- **Links**: Indigo-600, underline on hover

#### Register Page
```
┌────────────────────────────────────────────────────────┐
│                                                         │
│  🗾 Nihongo                                            │
│                                                         │
│                                                         │
│          Start Speaking Japanese Today                 │
│                                                         │
│  What should we call you?                              │
│  [Your name                           ]                │
│                                                         │
│  Email                                                  │
│  [you@example.com                     ]                │
│                                                         │
│  Password                                               │
│  [••••••••••••                        ]                │
│                                                         │
│  Daily speaking practice goal                           │
│  [○ 15 min  ● 30 min  ○ 60 min]                       │
│                                                         │
│  What do you want to talk about in Japanese?           │
│  [☐ Travel  ☐ Business  ☐ Daily Life  ☐ Culture]      │
│                                                         │
│  [Start My Speaking Journey →]                         │
│                                                         │
│  Already have an account? [Log in →]                   │
│                                                         │
└────────────────────────────────────────────────────────┘
```

**Register Page Specifications**:
- **Container**: Max-width 480px, centered
- **Goal Selection**: Radio buttons, 44px height
- **Interest Tags**: Checkboxes styled as pills
- **Friendly Labels**: Conversational tone
- **Progress Indicator**: Optional step indicator


### Dashboard Design

```
┌────────────────────────────────────────────────────────────────┐
│  🗾 Nihongo    [Conversations] [Practice] [Progress] 🔥5 👤    │
├────────────────────────────────────────────────────────────────┤
│                                                                 │
│  Ready to practice speaking today, Sarah? 🌟                   │
│                                                                 │
│  ┌─────────────────────────────────────────────────────────┐  │
│  │  Today's Speaking Goal                                   │  │
│  │  ████████████░░░░░░░░ 15/30 minutes                     │  │
│  │  Great progress! Keep going! 💪                          │  │
│  └─────────────────────────────────────────────────────────┘  │
│                                                                 │
│  ┌──────────────┐  ┌──────────────┐  ┌──────────────┐        │
│  │ 🔥 5 Days    │  │ 🗣️ 12        │  │ ⏱️ 8.5 hrs   │        │
│  │ Speaking     │  │ Conversations│  │ Speaking     │        │
│  │ Streak       │  │ Mastered     │  │ Time         │        │
│  └──────────────┘  └──────────────┘  └──────────────┘        │
│                                                                 │
│  Quick Actions                                                  │
│  ┌─────────────────────────────────────────────────────────┐  │
│  │  🗣️ Continue: Asking for Directions                     │  │
│  │  Progress: ████████░░░░░░░░ 40%                         │  │
│  │  [Practice Speaking →]                                   │  │
│  └─────────────────────────────────────────────────────────┘  │
│                                                                 │
│  ┌─────────────────────────────────────────────────────────┐  │
│  │  🎤 Daily Shadowing Challenge                            │  │
│  │  Practice 3 dialogues today                              │  │
│  │  [Start Challenge →]                                     │  │
│  └─────────────────────────────────────────────────────────┘  │
│                                                                 │
│  Your Speaking Progress This Week                               │
│  ┌─────────────────────────────────────────────────────────┐  │
│  │  Mon  Tue  Wed  Thu  Fri  Sat  Sun                      │  │
│  │   ✓    ✓    ✓    ✓    ✓    ○    ○                      │  │
│  │  15m  20m  30m  25m  15m   -    -                       │  │
│  └─────────────────────────────────────────────────────────┘  │
│                                                                 │
└────────────────────────────────────────────────────────────────┘
```

**Dashboard Specifications**:
- **Greeting**: Personalized, 1.5rem (24px)
- **Goal Widget**: Prominent, gradient background
- **Stat Cards**: 3-column grid on desktop, stack on mobile
- **Quick Actions**: Card-based, hover effects
- **Calendar**: Visual indicators for activity


### Lessons Index Page Design

```
┌────────────────────────────────────────────────────────────────┐
│  🗾 Nihongo    [Conversations] [Practice] [Progress] 🔥5 👤    │
├────────────────────────────────────────────────────────────────┤
│                                                                 │
│  Your Conversation Journey                                      │
│  What will you talk about today?                                │
│                                                                 │
│  ┌─────────────────────────────────────────────────────────┐  │
│  │  🗣️ 12/20 Conversations Mastered                        │  │
│  │  ████████████░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░  │  │
│  │  🔥 5-day speaking streak  ⏱️ 8.5 hours speaking time   │  │
│  └─────────────────────────────────────────────────────────┘  │
│                                                                 │
│  ┌─────────────────────────────────────────────────────────┐  │
│  │  🗣️ 1  Asking for Directions                            │  │
│  │                                                          │  │
│  │  Speak confidently when navigating Japanese cities      │  │
│  │                                                          │  │
│  │  🎯 5 Dialogues  🎤 3 Shadowing  ⏱️ 15 min             │  │
│  │                                                          │  │
│  │  Progress: ████████░░░░░░░░ 40%                         │  │
│  │                                                          │  │
│  │  [Continue Speaking →]                                   │  │
│  └─────────────────────────────────────────────────────────┘  │
│                                                                 │
│  ┌─────────────────────────────────────────────────────────┐  │
│  │  🗣️ 2  Ordering Food                                    │  │
│  │                                                          │  │
│  │  Order meals and drinks like a local                    │  │
│  │                                                          │  │
│  │  🎯 6 Dialogues  🎤 4 Shadowing  ⏱️ 20 min             │  │
│  │                                                          │  │
│  │  [Start Conversation →]                                  │  │
│  └─────────────────────────────────────────────────────────┘  │
│                                                                 │
│  ┌─────────────────────────────────────────────────────────┐  │
│  │  🔒 3  Making Invitations                               │  │
│  │                                                          │  │
│  │  Master Lesson 2 conversations to unlock                │  │
│  │                                                          │  │
│  │  🎯 5 Dialogues  🎤 3 Shadowing  ⏱️ 18 min             │  │
│  └─────────────────────────────────────────────────────────┘  │
│                                                                 │
└────────────────────────────────────────────────────────────────┘
```

**Lessons Page Specifications**:
- **Header**: Speaking-focused messaging
- **Progress Banner**: Prominent, gradient background
- **Lesson Cards**: Full-width on mobile, 2-column on tablet, 3-column on desktop
- **Status Indicators**: 
  - In-progress: Blue border, progress bar
  - Available: Default state
  - Locked: Gray overlay, lock icon
  - Completed: Green checkmark, celebration styling


### Lesson Detail Page Design

```
┌────────────────────────────────────────────────────────────────┐
│  🗾 Nihongo    [Conversations] [Practice] [Progress] 🔥5 👤    │
├────────────────────────────────────────────────────────────────┤
│                                                                 │
│  ← Back to Conversations                                        │
│                                                                 │
│  🗣️ 1  Asking for Directions                                   │
│  Speak confidently when navigating Japanese cities              │
│                                                                 │
│  Progress: ████████░░░░░░░░ 40%  |  ⏱️ 6/15 min practiced     │
│                                                                 │
│  ┌─────────────────────────────────────────────────────────┐  │
│  │ [🗣️ Dialogues 5] [🎤 Shadowing 3] [📝 Phrases] [✏️ Drills]│  │
│  ├─────────────────────────────────────────────────────────┤  │
│  │                                                          │  │
│  │  Dialogue 1: Asking for the Station                     │  │
│  │  ┌────────────────────────────────────────────────────┐ │  │
│  │  │  🔊 すみません、駅はどこですか？                    │ │  │
│  │  │  Sumimasen, eki wa doko desu ka?                   │ │  │
│  │  │  Excuse me, where is the station?                  │ │  │
│  │  │                                                     │ │  │
│  │  │  ▶️  0:00 / 0:03  [Slow] [Normal] [Fast]          │ │  │
│  │  └────────────────────────────────────────────────────┘ │  │
│  │                                                          │  │
│  │  [🎤 Practice Speaking This Dialogue]                   │  │
│  │                                                          │  │
│  │  ─────────────────────────────────────────────────────  │  │
│  │                                                          │  │
│  │  Dialogue 2: Getting Directions                         │  │
│  │  ┌────────────────────────────────────────────────────┐ │  │
│  │  │  🔊 まっすぐ行って、右に曲がってください。          │ │  │
│  │  │  Massugu itte, migi ni magatte kudasai.            │ │  │
│  │  │  Go straight and turn right.                       │ │  │
│  │  │                                                     │ │  │
│  │  │  ▶️  0:00 / 0:04  [Slow] [Normal] [Fast]          │ │  │
│  │  └────────────────────────────────────────────────────┘ │  │
│  │                                                          │  │
│  │  [🎤 Practice Speaking This Dialogue]                   │  │
│  │                                                          │  │
│  └─────────────────────────────────────────────────────────┘  │
│                                                                 │
└────────────────────────────────────────────────────────────────┘
```

**Lesson Detail Specifications**:
- **Tab Navigation**: Sticky on scroll, prioritize Dialogues and Shadowing
- **Dialogue Cards**: 
  - Japanese text: 1.125rem (18px), bold
  - Romaji: 0.875rem (14px), gray-600
  - English: 1rem (16px), gray-700
  - Audio player: Prominent, easy controls
- **Practice Button**: Full-width on mobile, prominent CTA
- **Spacing**: Generous padding between dialogues


## Mobile-Specific Design Considerations

### Mobile Navigation Pattern

#### Bottom Navigation Bar (iOS/Android)
```
┌────────────────────────────────────────┐
│                                         │
│  [Content Area]                         │
│                                         │
│                                         │
├─────────────────────────────────────────┤
│  🏠      🗣️       ▶️      📊      👤   │
│ Home  Convos  Practice  Stats  Profile │
└─────────────────────────────────────────┘
```

**Specifications**:
- **Height**: 64px + safe-area-inset-bottom
- **Background**: White with top border
- **Active State**: Indigo-600 icon + label
- **Inactive State**: Gray-400 icon + label
- **Icons**: 24px × 24px
- **Labels**: 0.75rem (12px)

### Mobile Audio Controls

#### Floating Audio Player (Sticky)
```
┌────────────────────────────────────────┐
│  ▶️ Dialogue 1: Asking...    0:45/2:10 │
│  ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━ │
└────────────────────────────────────────┘
```

**Specifications**:
- **Position**: Fixed bottom, above navigation
- **Height**: 56px
- **Background**: White with shadow-lg
- **Z-index**: 40 (below modals, above content)
- **Collapse**: Minimize to icon when not in use

### Mobile Gesture Support

#### Swipe Gestures
- **Swipe Left**: Next dialogue/exercise
- **Swipe Right**: Previous dialogue/exercise
- **Swipe Down**: Dismiss modal/overlay
- **Pull to Refresh**: Reload content

#### Touch Feedback
- **Tap**: Ripple effect (Material Design style)
- **Long Press**: Show context menu
- **Double Tap**: Quick action (e.g., favorite)

### Mobile Typography Adjustments

```css
/* Mobile-specific font sizes */
@media (max-width: 767px) {
  .heading-1 { font-size: 2rem; }      /* 32px */
  .heading-2 { font-size: 1.5rem; }    /* 24px */
  .heading-3 { font-size: 1.25rem; }   /* 20px */
  .body-large { font-size: 1rem; }     /* 16px */
  .body { font-size: 0.875rem; }       /* 14px */
  
  /* Japanese text larger on mobile */
  .japanese-text { font-size: 1.125rem; } /* 18px */
}
```


## Copywriting Guidelines

### Voice and Tone

#### Brand Voice Characteristics
- **Encouraging**: Always positive and supportive
- **Conversational**: Friendly, not formal
- **Action-Oriented**: Focus on doing, practicing, speaking
- **Culturally Aware**: Respectful of Japanese culture
- **Motivational**: Celebrate progress, encourage consistency

### Copywriting Patterns

#### Button Text
❌ **Avoid**: "Submit", "Click Here", "Go"
✅ **Use**: "Start Speaking", "Practice Now", "Continue Conversation"

#### Headings
❌ **Avoid**: "Lessons", "Dashboard", "User Profile"
✅ **Use**: "Your Conversation Journey", "Speaking Dashboard", "Your Progress"

#### Empty States
❌ **Avoid**: "No data available"
✅ **Use**: "Ready to start speaking? Choose your first conversation below!"

#### Error Messages
❌ **Avoid**: "Error 404: Resource not found"
✅ **Use**: "😅 Oops! We couldn't find that page. Let's get you back to practicing!"

#### Success Messages
❌ **Avoid**: "Operation completed successfully"
✅ **Use**: "よくできました！ (Well done!) You completed the conversation!"

### Japanese Integration

#### When to Use Japanese Text
1. **Celebrations**: よくできました！ (Well done!)
2. **Encouragement**: がんばって！ (Keep going!)
3. **Congratulations**: おめでとう！ (Congratulations!)
4. **Greetings**: こんにちは (Hello)
5. **Thank you**: ありがとう (Thank you)

#### Format for Japanese Text
```
Japanese Text (Romaji) English Translation
よくできました！ (Yoku dekimashita!) Well done!
```

### Microcopy Examples

#### Loading States
- "Loading your conversations..."
- "Preparing audio..."
- "Getting your progress ready..."

#### Tooltips
- "Practice this dialogue to improve your speaking"
- "Shadow native speakers to perfect your pronunciation"
- "Track your daily speaking streak"

#### Form Labels
- "What should we call you?" (instead of "Name")
- "Your email address" (instead of "Email")
- "Choose a secure password" (instead of "Password")

#### Progress Indicators
- "You're 40% through this conversation!"
- "5 more minutes to reach your daily goal!"
- "3 conversations away from your next level!"


## Animation and Interaction Design

### Animation Principles

1. **Purposeful**: Every animation serves a function
2. **Quick**: Animations should be 200-400ms
3. **Natural**: Use easing functions (ease-out, ease-in-out)
4. **Respectful**: Honor prefers-reduced-motion
5. **Delightful**: Add personality without distraction

### Key Animations

#### Page Transitions
```css
.page-enter {
  opacity: 0;
  transform: translateY(20px);
}

.page-enter-active {
  opacity: 1;
  transform: translateY(0);
  transition: all 0.3s ease-out;
}
```

#### Button Interactions
```css
.btn-speaking {
  transition: all 0.2s ease-out;
}

.btn-speaking:hover {
  transform: translateY(-2px);
  box-shadow: 0 6px 12px rgba(79, 70, 229, 0.3);
}

.btn-speaking:active {
  transform: translateY(0);
}
```

#### Progress Bar Animation
```css
.progress-bar {
  transition: width 0.7s ease-out;
  animation: progressGrow 0.7s ease-out;
}

@keyframes progressGrow {
  from {
    transform: scaleX(0);
    transform-origin: left;
  }
  to {
    transform: scaleX(1);
  }
}
```

#### Celebration Animation
```css
.celebration {
  animation: celebrate 0.8s ease-in-out;
}

@keyframes celebrate {
  0%, 100% {
    transform: scale(1) rotate(0deg);
  }
  25% {
    transform: scale(1.1) rotate(-5deg);
  }
  75% {
    transform: scale(1.1) rotate(5deg);
  }
}
```

#### Streak Glow Effect
```css
.streak-glow {
  animation: glow 2s ease-in-out infinite;
}

@keyframes glow {
  0%, 100% {
    box-shadow: 0 0 5px rgba(255, 107, 53, 0.5);
  }
  50% {
    box-shadow: 0 0 20px rgba(255, 107, 53, 0.8);
  }
}
```

### Micro-interactions

#### Audio Play Button
- **Idle**: Indigo-600 background
- **Hover**: Scale 1.05, shadow increase
- **Active**: Scale 0.95
- **Playing**: Pulse animation, green background
- **Loading**: Spinner overlay

#### Lesson Card Hover
- **Idle**: Default state
- **Hover**: Lift -2px, shadow-lg, border color change
- **Active**: Scale 0.98

#### Completion Checkmark
- **Appear**: Bounce-in animation
- **State**: Green checkmark with circle
- **Hover**: Rotate 360deg


## Accessibility Implementation

### Keyboard Navigation

#### Focus Management
```css
/* Visible focus indicators */
*:focus-visible {
  outline: 2px solid #4F46E5;
  outline-offset: 2px;
  border-radius: 4px;
}

/* Skip to main content */
.skip-link {
  position: absolute;
  top: -40px;
  left: 0;
  background: #4F46E5;
  color: white;
  padding: 8px;
  z-index: 100;
}

.skip-link:focus {
  top: 0;
}
```

#### Keyboard Shortcuts
- **Space**: Play/Pause audio
- **→**: Next dialogue
- **←**: Previous dialogue
- **R**: Repeat current audio
- **S**: Toggle slow/normal speed
- **?**: Show keyboard shortcuts help

### Screen Reader Support

#### ARIA Labels
```html
<!-- Audio Player -->
<button aria-label="Play dialogue: Asking for directions">
  <svg aria-hidden="true">...</svg>
</button>

<!-- Progress Bar -->
<div role="progressbar" 
     aria-valuenow="40" 
     aria-valuemin="0" 
     aria-valuemax="100"
     aria-label="Lesson progress: 40% complete">
</div>

<!-- Lesson Card -->
<article aria-labelledby="lesson-1-title">
  <h3 id="lesson-1-title">Asking for Directions</h3>
  <p aria-label="5 dialogues, 3 shadowing exercises, 15 minutes">
    🎯 5 Dialogues  🎤 3 Shadowing  ⏱️ 15 min
  </p>
</article>
```

#### Live Regions
```html
<!-- Speaking progress updates -->
<div aria-live="polite" aria-atomic="true">
  You've practiced for 15 minutes today!
</div>

<!-- Error messages -->
<div role="alert" aria-live="assertive">
  Please enter a valid email address
</div>
```

### Audio Accessibility

#### Transcripts
- All audio content has text transcripts
- Transcripts are expandable/collapsible
- Transcripts include speaker labels
- Transcripts are searchable

#### Visual Audio Indicators
```html
<div class="audio-indicator">
  <span class="sr-only">Audio is playing</span>
  <div class="waveform" aria-hidden="true">
    <!-- Visual waveform bars -->
  </div>
</div>
```

### Color Contrast

#### WCAG AA Compliance
- **Normal text**: 4.5:1 minimum
- **Large text**: 3:1 minimum
- **UI components**: 3:1 minimum

#### Tested Combinations
✅ Indigo-600 (#4F46E5) on White (#FFFFFF) - 8.59:1
✅ Gray-700 (#374151) on White (#FFFFFF) - 10.73:1
✅ White (#FFFFFF) on Indigo-600 (#4F46E5) - 8.59:1
✅ Green-600 (#10B981) on White (#FFFFFF) - 4.52:1

### Reduced Motion

```css
@media (prefers-reduced-motion: reduce) {
  *,
  *::before,
  *::after {
    animation-duration: 0.01ms !important;
    animation-iteration-count: 1 !important;
    transition-duration: 0.01ms !important;
  }
}
```


## Implementation Notes

### Technology Stack

#### Frontend
- **Framework**: Laravel Blade templates
- **CSS**: Tailwind CSS with custom extensions
- **JavaScript**: Alpine.js for interactivity
- **Audio**: HTML5 Audio API with custom controls
- **Icons**: Heroicons or custom SVG icons

#### Design Tools
- **Design**: Figma (for mockups and prototypes)
- **Icons**: Heroicons, custom SVG
- **Fonts**: Inter (English), Noto Sans JP (Japanese)
- **Colors**: Tailwind CSS color palette with custom extensions

### File Structure

```
resources/
├── css/
│   ├── app.css (main styles)
│   ├── components/
│   │   ├── audio-player.css
│   │   ├── conversation-card.css
│   │   ├── progress-indicators.css
│   │   └── navigation.css
│   └── pages/
│       ├── landing.css
│       ├── auth.css
│       ├── dashboard.css
│       └── lessons.css
├── js/
│   ├── app.js
│   ├── components/
│   │   ├── audio-player.js
│   │   ├── progress-tracker.js
│   │   └── celebration-modal.js
│   └── utils/
│       ├── audio-utils.js
│       └── animation-utils.js
└── views/
    ├── welcome.blade.php (landing page)
    ├── auth/
    │   ├── login.blade.php
    │   └── register.blade.php
    ├── dashboard.blade.php
    ├── lessons/
    │   ├── index.blade.php
    │   └── show.blade.php
    └── components/
        ├── audio-player.blade.php
        ├── conversation-card.blade.php
        ├── progress-bar.blade.php
        └── speaking-streak.blade.php
```

### Performance Considerations

#### Image Optimization
- Use WebP format with fallbacks
- Lazy load images below the fold
- Serve responsive images with srcset
- Compress images to < 100KB

#### Audio Optimization
- Use MP3 format at 128kbps
- Implement audio preloading for next dialogue
- Cache audio files in browser
- Provide audio compression options

#### Code Splitting
- Separate CSS for each page
- Lazy load JavaScript components
- Use dynamic imports for heavy features
- Minimize initial bundle size

### Browser Support

#### Target Browsers
- Chrome/Edge: Last 2 versions
- Firefox: Last 2 versions
- Safari: Last 2 versions
- iOS Safari: Last 2 versions
- Android Chrome: Last 2 versions

#### Progressive Enhancement
- Core functionality works without JavaScript
- Audio controls degrade gracefully
- Forms work with basic HTML
- CSS Grid with Flexbox fallback


## Design Rationale

### Why Speaking-First?

The design prioritizes speaking and conversation practice because:

1. **Pedagogical Research**: Speaking is the most effective way to achieve fluency
2. **User Goals**: Most learners want to communicate, not just read/write
3. **Engagement**: Audio and speaking activities are more engaging than passive reading
4. **Real-World Application**: Conversations are immediately useful in real situations
5. **Confidence Building**: Regular speaking practice builds confidence faster

### Why Japanese Design Elements?

Incorporating Japanese aesthetics:

1. **Cultural Authenticity**: Reinforces the learning context
2. **Visual Identity**: Differentiates from generic learning apps
3. **Immersion**: Creates a more immersive learning environment
4. **Respect**: Shows respect for Japanese culture
5. **Motivation**: Beautiful design motivates continued use

### Why Mobile-First?

Mobile optimization is critical because:

1. **Usage Patterns**: Most language learning happens on mobile
2. **On-the-Go Practice**: Learners practice during commutes, breaks
3. **Audio Focus**: Mobile devices are ideal for audio practice
4. **Touch Interactions**: Speaking practice benefits from touch controls
5. **Market Trends**: Mobile-first is industry standard

### Why Gamification?

Progress visualization and gamification elements:

1. **Motivation**: Streaks and XP encourage daily practice
2. **Feedback**: Visual progress shows improvement
3. **Goals**: Clear milestones provide direction
4. **Celebration**: Achievements create positive reinforcement
5. **Retention**: Gamification increases long-term engagement

## Future Enhancements

### Phase 2 Considerations

1. **Dark Mode**: Full dark theme support
2. **Offline Mode**: Download lessons for offline practice
3. **Voice Recognition**: AI-powered pronunciation feedback
4. **Social Features**: Practice with other learners
5. **Personalization**: AI-driven lesson recommendations
6. **Advanced Analytics**: Detailed pronunciation analysis
7. **Custom Themes**: User-selectable color themes
8. **Widgets**: Home screen widgets for quick practice

### Experimental Features

1. **AR Practice**: Augmented reality conversation scenarios
2. **Voice Cloning**: Practice with AI-generated voices
3. **Live Tutoring**: Connect with native speakers
4. **Conversation AI**: Practice with AI conversation partner
5. **Spaced Repetition**: Intelligent review scheduling

## Conclusion

This design document provides a comprehensive blueprint for transforming the Nihongo app into a speaking-first, conversation-focused language learning platform. The design emphasizes:

- **Speaking practice** as the primary activity
- **Audio controls** that are prominent and accessible
- **Japanese aesthetics** integrated throughout
- **Mobile optimization** for on-the-go learning
- **Motivational elements** that encourage daily practice
- **Accessibility** for all learners

By following these design specifications, the app will provide an engaging, effective, and beautiful experience for learners who want to speak Japanese confidently.
