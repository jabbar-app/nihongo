# Design Document

## Overview

This document outlines the technical design for the Japanese Learning Application built on Laravel 12 with Tailwind CSS 4 and Vite. The application follows a traditional MVC architecture with additional service and repository layers for business logic and data access. The design emphasizes modularity, testability, and scalability while leveraging Laravel's built-in features for authentication, queuing, and caching.

The application will use SQLite for development (already configured) and can easily migrate to MySQL/PostgreSQL for production. The frontend will be built with Blade templates enhanced with Alpine.js for interactivity, providing a modern SPA-like experience without the complexity of a separate frontend framework.

---

## Architecture

### High-Level Architecture

```
┌─────────────────────────────────────────────────────────────┐
│                         Browser                              │
│  (Blade Templates + Alpine.js + Tailwind CSS)               │
└────────────────────────┬────────────────────────────────────┘
                         │ HTTP/AJAX
┌────────────────────────▼────────────────────────────────────┐
│                    Laravel Application                       │
│  ┌──────────────────────────────────────────────────────┐  │
│  │              Routes (web.php, api.php)               │  │
│  └────────────────────┬─────────────────────────────────┘  │
│  ┌────────────────────▼─────────────────────────────────┐  │
│  │                  Controllers                          │  │
│  │  (LessonController, FlashcardController, etc.)       │  │
│  └────────────────────┬─────────────────────────────────┘  │
│  ┌────────────────────▼─────────────────────────────────┐  │
│  │                   Services                            │  │
│  │  (SpacedRepetitionService, ProgressService, etc.)    │  │
│  └────────────────────┬─────────────────────────────────┘  │
│  ┌────────────────────▼─────────────────────────────────┐  │
│  │              Repositories/Models                      │  │
│  │  (Eloquent ORM + Repository Pattern)                 │  │
│  └────────────────────┬─────────────────────────────────┘  │
└────────────────────────┼────────────────────────────────────┘
                         │
┌────────────────────────▼────────────────────────────────────┐
│                    Database (SQLite)                         │
│  (Users, Lessons, Flashcards, Progress, Activities, etc.)   │
└─────────────────────────────────────────────────────────────┘
```

### Technology Stack

- **Backend**: Laravel 12 (PHP 8.2+)
- **Frontend**: Blade Templates, Alpine.js 3.x, Tailwind CSS 4
- **Database**: SQLite (dev), MySQL/PostgreSQL (production)
- **Asset Building**: Vite 7
- **Testing**: Pest PHP
- **Audio**: Web Speech API (text-to-speech), MediaRecorder API (recording)
- **Caching**: Laravel Cache (file/redis)
- **Queue**: Laravel Queue (database driver)

### Design Patterns

1. **MVC Pattern**: Controllers handle HTTP requests, Models represent data, Views render UI
2. **Repository Pattern**: Abstract data access logic for testability
3. **Service Layer**: Encapsulate complex business logic (spaced repetition, progress calculation)
4. **Observer Pattern**: Model observers for tracking activities and updating progress
5. **Strategy Pattern**: Different exercise types implement common interface
6. **Factory Pattern**: Content parsers for different markdown formats

---

## Components and Interfaces

### 1. Content Management System

#### Content Parser Service

```php
interface ContentParserInterface
{
    public function parse(string $markdown): array;
    public function extractPhrases(string $markdown): Collection;
    public function extractDialogues(string $markdown): Collection;
}

class MarkdownContentParser implements ContentParserInterface
{
    // Parse markdown files and extract structured data
    // Convert tables to arrays, extract dialogues with speakers
    // Handle special formatting (romaji, notes, etc.)
}
```

#### Lesson Model & Repository

```php
class Lesson extends Model
{
    // Relationships
    public function phrases(): HasMany;
    public function dialogues(): HasMany;
    public function drills(): HasMany;
    public function shadowingExercises(): HasMany;
    public function userProgress(): HasMany;
    
    // Attributes: id, slug, title, order, content_path
}

class LessonRepository
{
    public function getAllWithProgress(User $user): Collection;
    public function findBySlug(string $slug): ?Lesson;
    public function getNextLesson(Lesson $current): ?Lesson;
}
```

#### Content Models

```php
class Phrase extends Model
{
    // Attributes: lesson_id, japanese, romaji, english, notes, category, order
    public function lesson(): BelongsTo;
    public function flashcards(): HasMany;
}

class Dialogue extends Model
{
    // Attributes: lesson_id, title, content (JSON: [{speaker, line}]), order
    public function lesson(): BelongsTo;
}

class Drill extends Model
{
    // Attributes: lesson_id, type (substitution/transformation/cloze), 
    // content (JSON), answers (JSON), order
    public function lesson(): BelongsTo;
    public function attempts(): HasMany;
}
```

### 2. Spaced Repetition System

#### Flashcard Models

```php
class Flashcard extends Model
{
    // Attributes: user_id, phrase_id, front, back, romaji, 
    // ease_factor, interval, repetitions, next_review_at
    
    public function user(): BelongsTo;
    public function phrase(): BelongsTo;
    public function reviews(): HasMany;
}

class FlashcardReview extends Model
{
    // Attributes: flashcard_id, rating (1-4), duration_seconds, 
    // reviewed_at, previous_interval, new_interval
    
    public function flashcard(): BelongsTo;
}
```

#### Spaced Repetition Service

```php
class SpacedRepetitionService
{
    // SM-2 Algorithm implementation
    public function calculateNextReview(
        Flashcard $card, 
        int $rating
    ): array; // Returns [ease_factor, interval, next_review_at]
    
    public function getDueCards(User $user, int $limit = 20): Collection;
    public function getNewCards(User $user, int $limit = 10): Collection;
    public function recordReview(Flashcard $card, int $rating, int $duration): void;
}
```

### 3. Exercise System

#### Exercise Interface & Implementations

```php
interface ExerciseInterface
{
    public function generate(Drill $drill): array;
    public function validate(array $userAnswers, Drill $drill): array;
    public function getScore(array $results): float;
}

class SubstitutionExercise implements ExerciseInterface
{
    // Generate fill-in-the-blank exercises
    // Validate user input against correct answers
}

class TransformationExercise implements ExerciseInterface
{
    // Generate transformation tasks (polite->casual, etc.)
}

class ClozeExercise implements ExerciseInterface
{
    // Generate cloze deletion exercises
}
```

#### Exercise Attempt Tracking

```php
class ExerciseAttempt extends Model
{
    // Attributes: user_id, drill_id, answers (JSON), 
    // score, duration_seconds, completed_at
    
    public function user(): BelongsTo;
    public function drill(): BelongsTo;
}
```

### 4. Audio & Shadowing System

#### Audio Service

```php
class AudioService
{
    // Generate audio URLs for text-to-speech
    public function generateSpeechUrl(string $text, string $lang = 'ja-JP'): string;
    
    // Store user recordings
    public function storeRecording(
        User $user, 
        UploadedFile $audio, 
        string $type, 
        int $referenceId
    ): UserRecording;
}

class UserRecording extends Model
{
    // Attributes: user_id, recordable_type, recordable_id, 
    // file_path, duration_seconds, created_at
    
    public function user(): BelongsTo;
    public function recordable(): MorphTo;
}
```

#### Shadowing Exercise Model

```php
class ShadowingExercise extends Model
{
    // Attributes: lesson_id, title, content (JSON: script lines), 
    // audio_url, duration_seconds, order
    
    public function lesson(): BelongsTo;
    public function completions(): HasMany;
}

class ShadowingCompletion extends Model
{
    // Attributes: user_id, shadowing_exercise_id, 
    // duration_seconds, recording_id, completed_at
    
    public function user(): BelongsTo;
    public function exercise(): BelongsTo;
    public function recording(): BelongsTo;
}
```

### 5. Progress Tracking System

#### Progress Models

```php
class UserProgress extends Model
{
    // Attributes: user_id, lesson_id, phrases_viewed, dialogues_viewed,
    // drills_completed, shadowing_completed, completion_percentage
    
    public function user(): BelongsTo;
    public function lesson(): BelongsTo;
}

class StudyActivity extends Model
{
    // Attributes: user_id, activity_type, activityable_type, 
    // activityable_id, duration_seconds, xp_earned, created_at
    
    public function user(): BelongsTo;
    public function activityable(): MorphTo;
}

class DailyStreak extends Model
{
    // Attributes: user_id, date, activities_count, 
    // study_minutes, xp_earned
    
    public function user(): BelongsTo;
}
```

#### Progress Service

```php
class ProgressService
{
    public function calculateLessonProgress(User $user, Lesson $lesson): float;
    public function updateProgress(User $user, string $activityType, Model $model): void;
    public function getOverallProgress(User $user): array;
    public function getDailyStats(User $user, Carbon $date): array;
    public function getWeeklyStats(User $user): array;
    public function updateStreak(User $user): void;
}
```

### 6. Gamification System

#### User Profile & Gamification

```php
class UserProfile extends Model
{
    // Attributes: user_id, level, total_xp, current_streak, 
    // longest_streak, study_goal_minutes, cards_per_day_goal
    
    public function user(): BelongsTo;
    public function achievements(): BelongsToMany;
}

class Achievement extends Model
{
    // Attributes: slug, name, description, icon, xp_reward, 
    // requirement_type, requirement_value
    
    public function users(): BelongsToMany;
}

class UserAchievement extends Pivot
{
    // Attributes: user_id, achievement_id, earned_at, progress
}
```

#### Gamification Service

```php
class GamificationService
{
    public function awardXP(User $user, int $xp, string $reason): void;
    public function checkLevelUp(User $user): ?int; // Returns new level if leveled up
    public function checkAchievements(User $user): Collection; // Returns newly earned achievements
    public function calculateLevel(int $totalXp): int;
    public function getXpForNextLevel(int $currentLevel): int;
}
```

### 7. Daily Study Plan

#### Study Plan Models

```php
class DailyPlan extends Model
{
    // Attributes: user_id, date, plan_data (JSON), 
    // completed_activities, total_activities, completed_at
    
    public function user(): BelongsTo;
}
```

#### Study Plan Service

```php
class StudyPlanService
{
    public function generateDailyPlan(User $user, Carbon $date): DailyPlan;
    public function getRecommendedActivities(User $user): array;
    public function markActivityComplete(DailyPlan $plan, string $activityId): void;
    public function isPlanComplete(DailyPlan $plan): bool;
}
```

### 8. Search System

#### Search Service

```php
class SearchService
{
    public function search(string $query, ?User $user = null): array;
    public function searchPhrases(string $query): Collection;
    public function searchDialogues(string $query): Collection;
    public function searchDrills(string $query): Collection;
    
    // Returns: ['phrases' => [...], 'dialogues' => [...], 'drills' => [...]]
}
```

---

## Data Models

### Database Schema

#### Users & Authentication

```sql
users
- id: bigint (PK)
- name: string
- email: string (unique)
- password: string
- email_verified_at: timestamp
- remember_token: string
- created_at, updated_at: timestamps

user_profiles
- id: bigint (PK)
- user_id: bigint (FK -> users.id)
- level: int (default: 1)
- total_xp: int (default: 0)
- current_streak: int (default: 0)
- longest_streak: int (default: 0)
- study_goal_minutes: int (default: 120)
- cards_per_day_goal: int (default: 20)
- created_at, updated_at: timestamps
```

#### Content Tables

```sql
lessons
- id: bigint (PK)
- slug: string (unique)
- title: string
- description: text
- content_path: string
- order: int
- created_at, updated_at: timestamps

phrases
- id: bigint (PK)
- lesson_id: bigint (FK -> lessons.id)
- japanese: string
- romaji: string
- english: string
- notes: text (nullable)
- category: string (nullable)
- order: int
- created_at, updated_at: timestamps

dialogues
- id: bigint (PK)
- lesson_id: bigint (FK -> lessons.id)
- title: string
- content: json [{speaker: string, line: string}]
- order: int
- created_at, updated_at: timestamps

drills
- id: bigint (PK)
- lesson_id: bigint (FK -> lessons.id)
- type: enum (substitution, transformation, cloze)
- title: string
- content: json
- answers: json
- order: int
- created_at, updated_at: timestamps

shadowing_exercises
- id: bigint (PK)
- lesson_id: bigint (FK -> lessons.id)
- title: string
- content: json (script lines)
- audio_url: string (nullable)
- duration_seconds: int (nullable)
- order: int
- created_at, updated_at: timestamps
```

#### Flashcard Tables

```sql
flashcards
- id: bigint (PK)
- user_id: bigint (FK -> users.id)
- phrase_id: bigint (FK -> phrases.id, nullable)
- front: string (Japanese)
- back: string (English)
- romaji: string
- ease_factor: decimal(3,2) (default: 2.5)
- interval: int (default: 0, days)
- repetitions: int (default: 0)
- next_review_at: timestamp
- created_at, updated_at: timestamps

flashcard_reviews
- id: bigint (PK)
- flashcard_id: bigint (FK -> flashcards.id)
- rating: tinyint (1-4: Again, Hard, Good, Easy)
- duration_seconds: int
- previous_interval: int
- new_interval: int
- reviewed_at: timestamp
- created_at, updated_at: timestamps
```

#### Exercise & Progress Tables

```sql
exercise_attempts
- id: bigint (PK)
- user_id: bigint (FK -> users.id)
- drill_id: bigint (FK -> drills.id)
- answers: json
- score: decimal(5,2)
- duration_seconds: int
- completed_at: timestamp
- created_at, updated_at: timestamps

shadowing_completions
- id: bigint (PK)
- user_id: bigint (FK -> users.id)
- shadowing_exercise_id: bigint (FK -> shadowing_exercises.id)
- duration_seconds: int
- recording_id: bigint (FK -> user_recordings.id, nullable)
- completed_at: timestamp
- created_at, updated_at: timestamps

user_recordings
- id: bigint (PK)
- user_id: bigint (FK -> users.id)
- recordable_type: string
- recordable_id: bigint
- file_path: string
- duration_seconds: int
- created_at, updated_at: timestamps

user_progress
- id: bigint (PK)
- user_id: bigint (FK -> users.id)
- lesson_id: bigint (FK -> lessons.id)
- phrases_viewed: int (default: 0)
- dialogues_viewed: int (default: 0)
- drills_completed: int (default: 0)
- shadowing_completed: int (default: 0)
- completion_percentage: decimal(5,2) (default: 0)
- last_accessed_at: timestamp
- created_at, updated_at: timestamps
- UNIQUE(user_id, lesson_id)

study_activities
- id: bigint (PK)
- user_id: bigint (FK -> users.id)
- activity_type: enum (flashcard_review, exercise, shadowing, content_view)
- activityable_type: string
- activityable_id: bigint
- duration_seconds: int
- xp_earned: int
- created_at, updated_at: timestamps

daily_streaks
- id: bigint (PK)
- user_id: bigint (FK -> users.id)
- date: date
- activities_count: int
- study_minutes: int
- xp_earned: int
- created_at, updated_at: timestamps
- UNIQUE(user_id, date)
```

#### Gamification Tables

```sql
achievements
- id: bigint (PK)
- slug: string (unique)
- name: string
- description: text
- icon: string
- xp_reward: int
- requirement_type: string
- requirement_value: int
- created_at, updated_at: timestamps

user_achievements
- id: bigint (PK)
- user_id: bigint (FK -> users.id)
- achievement_id: bigint (FK -> achievements.id)
- progress: int (default: 0)
- earned_at: timestamp (nullable)
- created_at, updated_at: timestamps
- UNIQUE(user_id, achievement_id)
```

#### Study Plan Tables

```sql
daily_plans
- id: bigint (PK)
- user_id: bigint (FK -> users.id)
- date: date
- plan_data: json
- completed_activities: int (default: 0)
- total_activities: int
- completed_at: timestamp (nullable)
- created_at, updated_at: timestamps
- UNIQUE(user_id, date)
```

---

## Error Handling

### Exception Hierarchy

```php
// Base application exception
class AppException extends Exception {}

// Domain-specific exceptions
class ContentNotFoundException extends AppException {}
class InvalidFlashcardRatingException extends AppException {}
class ExerciseValidationException extends AppException {}
class AudioProcessingException extends AppException {}
class ProgressCalculationException extends AppException {}
```

### Error Handling Strategy

1. **Controller Level**: Catch exceptions and return appropriate HTTP responses
2. **Service Level**: Throw domain-specific exceptions with context
3. **Repository Level**: Throw not found exceptions for missing data
4. **Global Handler**: Log errors, send notifications for critical issues
5. **User Feedback**: Display user-friendly error messages in UI

### Validation

- Use Laravel Form Requests for input validation
- Custom validation rules for Japanese text, romaji format
- Client-side validation with Alpine.js for immediate feedback
- Server-side validation as source of truth

---

## Testing Strategy

### Unit Tests

- **Models**: Test relationships, scopes, accessors/mutators
- **Services**: Test business logic in isolation with mocked dependencies
- **Repositories**: Test data access patterns
- **Helpers**: Test utility functions

### Feature Tests

- **Authentication**: Registration, login, logout, password reset
- **Content Display**: Lesson listing, phrase display, dialogue rendering
- **Flashcards**: Card creation, review flow, spaced repetition algorithm
- **Exercises**: Exercise generation, answer validation, scoring
- **Progress**: Activity tracking, streak calculation, XP awards
- **Search**: Query parsing, result ranking, filtering

### Integration Tests

- **Complete User Flows**: 
  - New user registration → lesson selection → flashcard creation → review session
  - Exercise completion → progress update → achievement unlock → level up
  - Daily plan generation → activity completion → streak update

### Browser Tests (Optional)

- Use Laravel Dusk for critical user journeys
- Test audio recording functionality
- Test responsive layouts on different viewports

### Test Data

- Use factories for generating test data
- Seed database with sample lessons from content files
- Create realistic user progress scenarios

### Testing Tools

- **Pest PHP**: Primary testing framework
- **Mockery**: Mocking dependencies
- **Laravel Dusk**: Browser testing (optional)
- **PHPUnit**: Underlying test runner

---

## Frontend Architecture

### Blade Component Structure

```
resources/views/
├── layouts/
│   ├── app.blade.php (main layout with nav, footer)
│   └── guest.blade.php (auth pages layout)
├── components/
│   ├── lesson-card.blade.php
│   ├── flashcard.blade.php
│   ├── progress-bar.blade.php
│   ├── achievement-badge.blade.php
│   ├── audio-player.blade.php
│   └── exercise-question.blade.php
├── lessons/
│   ├── index.blade.php (lesson list)
│   ├── show.blade.php (lesson detail with tabs)
│   ├── phrases.blade.php
│   ├── dialogues.blade.php
│   ├── drills.blade.php
│   └── shadowing.blade.php
├── flashcards/
│   ├── index.blade.php (deck management)
│   └── review.blade.php (review session)
├── exercises/
│   └── attempt.blade.php (exercise interface)
├── dashboard/
│   └── index.blade.php (main dashboard)
└── profile/
    └── show.blade.php (user profile & stats)
```

### Alpine.js Components

```javascript
// Flashcard review component
Alpine.data('flashcardReview', () => ({
    currentCard: null,
    showAnswer: false,
    rating: null,
    // Methods: flipCard(), rateCard(), nextCard()
}))

// Exercise component
Alpine.data('exercise', () => ({
    answers: {},
    submitted: false,
    results: null,
    // Methods: submitAnswers(), checkAnswer()
}))

// Audio player component
Alpine.data('audioPlayer', () => ({
    playing: false,
    currentTime: 0,
    duration: 0,
    // Methods: play(), pause(), seek()
}))

// Audio recorder component
Alpine.data('audioRecorder', () => ({
    recording: false,
    audioBlob: null,
    // Methods: startRecording(), stopRecording(), playback()
}))
```

### Styling Approach

- **Tailwind CSS 4**: Utility-first styling
- **Custom Components**: Reusable UI components with consistent styling
- **Dark Mode**: Support for dark mode using Tailwind's dark: variant
- **Responsive Design**: Mobile-first approach with breakpoints
- **Animations**: Subtle transitions for better UX (card flips, progress bars)

### Asset Management

- **Vite**: Fast build tool for assets
- **Code Splitting**: Lazy load heavy components (audio recorder)
- **Image Optimization**: Compress and serve optimized images
- **Font Loading**: Use system fonts or load web fonts efficiently

---

## Performance Considerations

### Database Optimization

- **Indexes**: Add indexes on frequently queried columns (user_id, lesson_id, next_review_at, date)
- **Eager Loading**: Use `with()` to prevent N+1 queries
- **Query Optimization**: Use `select()` to load only needed columns
- **Database Caching**: Cache lesson content and user progress

### Caching Strategy

```php
// Cache lesson content (rarely changes)
Cache::remember("lesson.{$slug}", 3600, fn() => $lesson->load('phrases', 'dialogues'));

// Cache user progress (invalidate on update)
Cache::remember("user.{$userId}.progress", 600, fn() => $progressService->getOverallProgress($user));

// Cache due flashcards count
Cache::remember("user.{$userId}.due_cards_count", 300, fn() => $user->flashcards()->due()->count());
```

### Queue Jobs

- **Email Notifications**: Send via queue
- **Achievement Checks**: Process asynchronously after activities
- **Daily Plan Generation**: Generate plans in background
- **Audio Processing**: Process uploaded recordings asynchronously

### Frontend Performance

- **Lazy Loading**: Load images and heavy components on demand
- **Debouncing**: Debounce search input and auto-save
- **Local Storage**: Cache user preferences and temporary data
- **Service Worker**: Optional PWA support for offline access

---

## Security Considerations

### Authentication & Authorization

- **Laravel Breeze/Fortify**: Use built-in authentication scaffolding
- **Password Hashing**: Bcrypt for password storage
- **CSRF Protection**: Laravel's built-in CSRF tokens
- **Rate Limiting**: Throttle login attempts and API requests
- **Authorization**: Use policies for resource access control

### Data Protection

- **Input Sanitization**: Validate and sanitize all user input
- **XSS Prevention**: Blade's automatic escaping
- **SQL Injection**: Use Eloquent ORM and parameter binding
- **File Upload Security**: Validate file types, sizes, and scan for malware
- **Audio Storage**: Store recordings outside public directory, serve via controller

### API Security (if needed)

- **API Tokens**: Laravel Sanctum for API authentication
- **Rate Limiting**: Throttle API requests per user
- **CORS**: Configure allowed origins

---

## Deployment Considerations

### Environment Configuration

- **Production Settings**: Debug off, proper logging, cache drivers
- **Database**: Migrate to MySQL/PostgreSQL for production
- **File Storage**: Use S3 or similar for user recordings
- **Queue Worker**: Use Redis or database queue driver with supervisor
- **Cache**: Use Redis for better performance

### Monitoring & Logging

- **Error Tracking**: Integrate Sentry or similar
- **Performance Monitoring**: Track slow queries and requests
- **User Analytics**: Track feature usage and engagement
- **Logs**: Structured logging with context

### Backup Strategy

- **Database Backups**: Daily automated backups
- **User Recordings**: Backup to separate storage
- **Configuration**: Version control for all config files

---

## Future Enhancements

### Phase 2 Features

- **Community Features**: User-generated content, shared decks
- **Advanced Analytics**: Detailed learning insights, weak area identification
- **Mobile Apps**: Native iOS/Android apps
- **Offline Mode**: PWA with offline flashcard reviews
- **AI Features**: Speech recognition for pronunciation feedback
- **Social Features**: Study groups, leaderboards, challenges
- **Content Expansion**: More lessons, grammar explanations, kanji learning
- **Integration**: Export to Anki, import from other sources

### Scalability Considerations

- **Horizontal Scaling**: Stateless application design
- **Database Sharding**: Partition by user_id if needed
- **CDN**: Serve static assets via CDN
- **Microservices**: Extract audio processing to separate service if needed
