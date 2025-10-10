# Implementation Plan

This implementation plan breaks down the Japanese Learning Application into discrete, manageable coding tasks. Each task builds incrementally on previous work, following test-driven development principles where appropriate. Tasks are organized to deliver core functionality first, then enhance with additional features.

---

## Phase 1: Foundation & Content System

- [x] 1. Set up database schema and migrations





  - Create migrations for all core tables: lessons, phrases, dialogues, drills, shadowing_exercises
  - Create migrations for user-related tables: user_profiles, flashcards, flashcard_reviews
  - Create migrations for progress tables: user_progress, study_activities, daily_streaks
  - Create migrations for gamification tables: achievements, user_achievements, daily_plans
  - Create migrations for media tables: user_recordings, exercise_attempts, shadowing_completions
  - Run migrations and verify schema in database
  - _Requirements: 1.1, 1.2, 1.3, 1.4, 1.5, 1.6, 1.7, 2.1, 2.2, 2.3, 2.4, 2.5, 2.6, 2.7, 2.8, 3.1, 3.2, 3.3, 3.4, 3.5, 3.6, 3.7, 3.8, 4.1, 4.2, 4.3, 4.4, 4.5, 4.6, 4.7, 4.8, 5.1, 5.2, 5.3, 5.4, 5.5, 5.6, 5.7, 5.8, 6.1, 6.2, 6.3, 6.4, 6.5, 6.6, 6.7, 6.8, 7.1, 7.2, 7.3, 7.4, 7.5, 7.6, 7.7, 7.8, 9.1, 9.2, 9.3, 9.4, 9.5, 9.6, 9.7, 9.8_

- [x] 2. Create Eloquent models with relationships





  - Create Lesson model with relationships to phrases, dialogues, drills, shadowing exercises
  - Create Phrase, Dialogue, Drill, ShadowingExercise models with lesson relationship
  - Create UserProfile model with user relationship and gamification attributes
  - Create Flashcard and FlashcardReview models with relationships
  - Create ExerciseAttempt, ShadowingCompletion, UserRecording models with polymorphic relationships
  - Create UserProgress, StudyActivity, DailyStreak models with user relationships
  - Create Achievement and UserAchievement models with many-to-many relationship
  - Create DailyPlan model with user relationship
  - Define scopes for common queries (due cards, completed lessons, active streaks)
  - _Requirements: 1.1, 1.2, 1.3, 1.4, 1.5, 1.6, 1.7, 2.1, 2.2, 2.3, 2.4, 2.5, 2.6, 2.7, 2.8, 3.1, 3.2, 3.3, 3.4, 3.5, 3.6, 3.7, 3.8, 4.1, 4.2, 4.3, 4.4, 4.5, 4.6, 4.7, 4.8, 5.1, 5.2, 5.3, 5.4, 5.5, 5.6, 5.7, 5.8, 6.1, 6.2, 6.3, 6.4, 6.5, 6.6, 6.7, 6.8, 7.1, 7.2, 7.3, 7.4, 7.5, 7.6, 7.7, 7.8_

- [x] 3. Build content parser service





  - Create ContentParserInterface with parse, extractPhrases, extractDialogues methods
  - Implement MarkdownContentParser to parse phrase tables from markdown
  - Add dialogue extraction with speaker/line parsing
  - Add drill extraction with type detection (substitution, transformation, cloze)
  - Add shadowing exercise extraction
  - Handle special formatting (romaji, notes, categories)
  - _Requirements: 1.1, 1.2, 1.3, 1.4, 1.5_

- [x] 4. Create content seeder





  - Create LessonSeeder to read markdown files from content directory
  - Parse and seed all 9 lessons with metadata (slug, title, order)
  - Seed phrases from each lesson's phrases.md file
  - Seed dialogues from each lesson's dialogues.md file
  - Seed drills from each lesson's drills-and-missions.md file
  - Seed shadowing exercises from each lesson's shadowing.md file
  - Run seeder and verify all content is imported correctly
  - _Requirements: 1.1, 1.2, 1.3, 1.4, 1.5_

- [x] 5. Build lesson display system





  - Create LessonController with index and show methods
  - Create lesson index view showing all lessons with cards/grid layout
  - Create lesson show view with tabbed interface (Phrases, Dialogues, Drills, Shadowing)
  - Create Blade component for phrase table display with Japanese, Romaji, English, Notes columns
  - Create Blade component for dialogue display with speaker labels and lines
  - Add navigation between lessons (previous/next)
  - Style with Tailwind CSS for responsive layout
  - _Requirements: 1.1, 1.2, 1.3, 1.4, 1.5_

## Phase 2: Authentication & User System

- [x] 6. Set up authentication





  - Install and configure Laravel Breeze for authentication scaffolding
  - Customize registration to create UserProfile on user creation
  - Add user profile fields to registration (study goals, cards per day)
  - Create profile edit page for updating study preferences
  - Add password reset functionality
  - Style authentication pages with Tailwind CSS
  - _Requirements: 9.1, 9.2, 9.3, 9.4, 9.5, 9.6, 9.7_

- [x] 7. Create user dashboard





  - Create DashboardController with index method
  - Build dashboard view showing welcome message and user stats
  - Display cards due today, new cards available, upcoming reviews
  - Show current level, XP, and progress to next level
  - Display current streak and study time today
  - Show recent lessons accessed
  - Add quick action buttons (Start Review, Continue Lesson, View Progress)
  - _Requirements: 2.6, 5.1, 5.2, 5.3, 6.1, 7.5_

## Phase 3: Spaced Repetition Flashcard System

- [x] 8. Implement spaced repetition algorithm





  - Create SpacedRepetitionService class
  - Implement SM-2 algorithm in calculateNextReview method
  - Add getDueCards method to fetch cards due for review
  - Add getNewCards method to fetch unlearned cards
  - Add recordReview method to update card state and create review record
  - Calculate ease factor, interval, and next review date based on rating
  - _Requirements: 2.1, 2.2, 2.3, 2.4, 2.5_

- [x] 9. Build flashcard management





  - Create FlashcardController with index, create, store, destroy methods
  - Create flashcard index view showing user's decks organized by lesson
  - Add "Create Flashcards" button on lesson phrase view
  - Implement bulk flashcard creation from selected phrases
  - Allow manual flashcard creation with custom front/back/romaji
  - Add flashcard deletion functionality
  - Display flashcard statistics (total, due, new, mastered)
  - _Requirements: 2.1, 2.8_

- [x] 10. Build flashcard review interface





  - Create FlashcardController review method to start review session
  - Create review view with flashcard display (front side initially)
  - Add flip animation to reveal back side (Japanese → English)
  - Display romaji below Japanese text
  - Add rating buttons (Again, Hard, Good, Easy) with keyboard shortcuts (1-4)
  - Show progress bar (cards reviewed / total cards in session)
  - Display session statistics on completion (cards reviewed, accuracy, time spent)
  - Auto-advance to next card after rating
  - _Requirements: 2.2, 2.3, 2.4, 2.5, 2.6, 2.7_

- [x] 11. Add flashcard audio playback





  - Create AudioService class with generateSpeechUrl method
  - Integrate Web Speech API for text-to-speech
  - Add audio play button on flashcard front (Japanese text)
  - Add audio play button on flashcard back (English text)
  - Create Alpine.js audioPlayer component with play/pause controls
  - Add playback speed control (0.5x, 0.75x, 1x, 1.25x, 1.5x)
  - Handle audio loading states and errors
  - _Requirements: 1.6, 4.1, 4.2, 4.8_

## Phase 4: Interactive Exercises

- [x] 12. Build exercise system foundation





  - Create ExerciseInterface with generate, validate, getScore methods
  - Create SubstitutionExercise class implementing the interface
  - Create TransformationExercise class implementing the interface
  - Create ClozeExercise class implementing the interface
  - Create ExerciseFactory to instantiate correct exercise type based on drill
  - _Requirements: 3.1, 3.2, 3.3, 3.4_

- [x] 13. Implement substitution drill exercises





  - Generate fill-in-the-blank questions from drill content
  - Create exercise attempt view with question display
  - Add input fields for user answers
  - Implement answer validation against correct answers
  - Display immediate feedback (correct/incorrect) with correct answer
  - Calculate and display score as percentage
  - _Requirements: 3.1, 3.2, 3.3, 3.4, 3.5_

- [x] 14. Implement transformation drill exercises





  - Generate transformation tasks (polite→casual, statement→question)
  - Create exercise interface with source sentence and transformation instruction
  - Add text input for transformed sentence
  - Validate user transformation against expected answer
  - Allow partial credit for close answers
  - Display feedback with correct transformation
  - _Requirements: 3.1, 3.2, 3.3, 3.4, 3.5_

- [ ] 15. Implement cloze deletion exercises
  - Generate cloze deletion questions from drill content
  - Display sentences with blanks to fill
  - Add input fields for each blank
  - Validate all answers and provide feedback
  - Highlight correct and incorrect answers
  - Show correct answers for missed items
  - _Requirements: 3.1, 3.2, 3.3, 3.4, 3.5_

- [ ] 16. Add exercise tracking and review queue
  - Create ExerciseController with attempt and complete methods
  - Save exercise attempts with answers, score, duration
  - Track incorrect answers and add phrases to review queue
  - Display exercise history on lesson page
  - Show average score by exercise type
  - Allow retrying exercises
  - _Requirements: 3.6, 3.7, 3.8_

## Phase 5: Audio Practice & Shadowing

- [ ] 17. Build shadowing exercise display
  - Create ShadowingController with show method
  - Create shadowing exercise view with script display
  - Display scripted dialogues line by line
  - Add audio playback controls (play, pause, seek)
  - Highlight current line during playback
  - Add loop functionality for repeated practice
  - Display exercise instructions and tips
  - _Requirements: 4.1, 4.2, 4.6, 4.7, 4.8_

- [ ] 18. Implement audio recording
  - Create Alpine.js audioRecorder component
  - Request microphone permission on first use
  - Implement recording start/stop with MediaRecorder API
  - Display recording indicator and timer
  - Allow playback of recorded audio
  - Add re-record functionality
  - Handle browser compatibility and errors
  - _Requirements: 4.3, 4.4_

- [ ] 19. Add recording storage and playback
  - Extend AudioService with storeRecording method
  - Save recorded audio files to storage (outside public directory)
  - Create UserRecording model records with metadata
  - Create route to serve recordings securely (auth required)
  - Display user's past recordings on shadowing exercise page
  - Allow deletion of old recordings
  - Track storage usage per user
  - _Requirements: 4.3, 4.4, 4.5_

- [ ] 20. Track shadowing completions
  - Create ShadowingCompletion records on exercise completion
  - Track duration and link to recording if available
  - Update lesson progress when shadowing exercise completed
  - Display completion status on lesson shadowing tab
  - Show completion history with dates and durations
  - Award XP for shadowing practice
  - _Requirements: 4.5, 4.7, 5.4_

## Phase 6: Progress Tracking & Analytics

- [ ] 21. Build progress tracking service
  - Create ProgressService class
  - Implement calculateLessonProgress method (percentage based on activities)
  - Implement updateProgress method to update UserProgress records
  - Implement getOverallProgress method for dashboard
  - Implement getDailyStats and getWeeklyStats methods
  - Implement updateStreak method to maintain daily streaks
  - _Requirements: 5.1, 5.2, 5.3, 5.4, 5.5, 5.6, 5.7, 5.8_

- [ ] 22. Create activity tracking system
  - Create model observers for Flashcard, ExerciseAttempt, ShadowingCompletion
  - Log StudyActivity records on model events (created, updated)
  - Track activity type, duration, and XP earned
  - Update UserProgress when activities completed
  - Update DailyStreak records for current date
  - Calculate and store daily study time
  - _Requirements: 5.4, 5.5, 5.8_

- [ ] 23. Build progress display pages
  - Create ProgressController with index method
  - Create progress view showing overall progress percentage
  - Display progress breakdown by lesson with completion bars
  - Show progress by content type (phrases, dialogues, drills, shadowing)
  - Display study time graphs (daily, weekly, monthly)
  - Show flashcard statistics (retention rate, cards mastered)
  - Display exercise statistics (average scores by type)
  - Highlight weak areas with low scores
  - _Requirements: 5.1, 5.2, 5.3, 5.6, 5.7, 5.8_

- [ ] 24. Create analytics dashboard
  - Add analytics section to dashboard
  - Display study time chart for past 7 days
  - Show activity breakdown pie chart (flashcards, exercises, shadowing)
  - Display streak calendar with active days highlighted
  - Show XP earned over time line chart
  - Add filters for date ranges (week, month, all time)
  - Display key metrics (total study hours, average daily time, completion rate)
  - _Requirements: 5.3, 5.5, 5.6, 5.7_

## Phase 7: Gamification System

- [ ] 25. Implement XP and leveling system
  - Create GamificationService class
  - Implement awardXP method to add XP to user profile
  - Implement calculateLevel method using XP thresholds
  - Implement checkLevelUp method to detect level increases
  - Implement getXpForNextLevel method for progress display
  - Award XP for different activities (flashcard review, exercise completion, shadowing)
  - Display level and XP on dashboard and profile
  - _Requirements: 7.1, 7.2, 7.3_

- [ ] 26. Create achievement system
  - Create achievement seeder with predefined achievements
  - Seed achievements: "First 100 Cards", "7-Day Streak", "30-Day Streak", "Lesson Master", etc.
  - Implement checkAchievements method in GamificationService
  - Check for newly earned achievements after activities
  - Create UserAchievement records when earned
  - Award bonus XP for achievement unlocks
  - _Requirements: 7.4, 7.5, 7.6, 7.7, 7.8_

- [ ] 27. Build achievement display
  - Create AchievementController with index method
  - Create achievements view showing all achievements (earned and locked)
  - Display achievement badges with icons and descriptions
  - Show progress toward locked achievements
  - Display earned date for unlocked achievements
  - Add achievement showcase on user profile
  - Show recent achievements on dashboard
  - Create celebration modal for new achievement unlocks
  - _Requirements: 7.4, 7.5, 7.6, 7.7, 7.8_

- [ ] 28. Add level-up celebrations
  - Create level-up notification component
  - Display celebration modal when user levels up
  - Show new level, XP earned, and unlocked features
  - Add confetti animation or similar visual effect
  - Display level-up history on profile
  - Award bonus XP for level milestones (level 10, 25, 50)
  - _Requirements: 7.2, 7.3_

## Phase 8: Daily Study Plan

- [ ] 29. Build study plan generation
  - Create StudyPlanService class
  - Implement generateDailyPlan method based on user goals
  - Implement getRecommendedActivities method considering user progress
  - Generate balanced plan: flashcards, new content, exercises, shadowing
  - Calculate estimated time for each activity
  - Store plan in DailyPlan model
  - _Requirements: 6.1, 6.2, 6.7_

- [ ] 30. Create daily plan interface
  - Create StudyPlanController with show and complete methods
  - Create daily plan view showing today's recommended activities
  - Display activity checklist with completion status
  - Show progress bar for daily plan completion
  - Display estimated time remaining
  - Add quick start buttons for each activity
  - Update plan as activities completed
  - _Requirements: 6.2, 6.3, 6.7, 6.8_

- [ ] 31. Implement study reminders
  - Add study time preference to user profile settings
  - Create notification system using browser notifications
  - Request notification permission on first login
  - Send reminder notification at scheduled study time
  - Display notification with daily plan summary
  - Allow snoozing or dismissing reminders
  - _Requirements: 6.4_

- [ ] 32. Build streak tracking
  - Implement streak calculation in ProgressService
  - Update current_streak on daily activity
  - Track longest_streak in user profile
  - Reset streak if day missed (with grace period)
  - Display streak count prominently on dashboard
  - Show streak calendar visualization
  - Award streak milestone achievements
  - Add streak recovery feature (one-time use)
  - _Requirements: 6.5, 6.6, 7.7_

## Phase 9: Search & Navigation

- [ ] 33. Implement search functionality
  - Create SearchService class
  - Implement search method to query across all content types
  - Implement searchPhrases method with Japanese, romaji, English matching
  - Implement searchDialogues method with content search
  - Implement searchDrills method with title and content search
  - Add search indexing for better performance
  - _Requirements: 8.1, 8.2, 8.4, 8.5_

- [ ] 34. Build search interface
  - Create SearchController with index method
  - Add search bar to main navigation
  - Create search results view with grouped results
  - Display results by type (phrases, dialogues, drills)
  - Highlight matching text in results
  - Add result count and search time
  - Link results to source lesson and section
  - _Requirements: 8.1, 8.2, 8.3, 8.6_

- [ ] 35. Add quick access features
  - Create RecentlyViewedService to track content access
  - Display "Recent" section on dashboard with last 5 items
  - Add bookmark functionality to lessons and content
  - Create bookmarks page showing saved content
  - Add "Continue Learning" button to resume last lesson
  - Create breadcrumb navigation for better orientation
  - _Requirements: 8.7, 8.8_

## Phase 10: UI/UX Polish & Responsive Design

- [ ] 36. Implement responsive layouts
  - Ensure all pages work on mobile (320px+), tablet (768px+), desktop (1024px+)
  - Create mobile-friendly navigation with hamburger menu
  - Optimize flashcard review for mobile touch interactions
  - Make tables responsive with horizontal scroll or card layout on mobile
  - Test and fix layout issues on different screen sizes
  - Optimize touch targets for mobile (minimum 44x44px)
  - _Requirements: 10.1, 10.2, 10.3_

- [ ] 37. Add keyboard navigation and shortcuts
  - Implement tab navigation for all interactive elements
  - Add keyboard shortcuts for flashcard review (1-4 for ratings, Space to flip)
  - Add keyboard shortcuts for audio playback (Space, Arrow keys)
  - Add keyboard shortcut for search (Ctrl/Cmd + K)
  - Display keyboard shortcut hints in UI
  - Ensure focus indicators are visible
  - _Requirements: 10.4_

- [ ] 38. Implement accessibility features
  - Add ARIA labels to all interactive elements
  - Ensure semantic HTML structure (headings, landmarks, lists)
  - Test color contrast ratios (WCAG AA standard)
  - Add alt text to all images and icons
  - Ensure form labels are properly associated
  - Test with screen reader (NVDA or VoiceOver)
  - Add skip navigation links
  - _Requirements: 10.5, 10.6_

- [ ] 39. Optimize performance
  - Add database indexes to frequently queried columns
  - Implement eager loading to prevent N+1 queries
  - Add caching for lesson content and user progress
  - Optimize images and assets
  - Implement lazy loading for images and heavy components
  - Minify and compress CSS/JS assets
  - Add loading states for async operations
  - _Requirements: 10.8_

- [ ] 40. Add animations and transitions
  - Add flashcard flip animation
  - Add smooth transitions for page navigation
  - Add progress bar animations
  - Add celebration animations for achievements and level-ups
  - Add loading spinners for async operations
  - Add hover effects on interactive elements
  - Keep animations subtle and performant
  - _Requirements: 7.3, 7.4_

## Phase 11: Testing & Quality Assurance

- [ ]* 41. Write model tests
  - Test model relationships (user->profile, lesson->phrases, etc.)
  - Test model scopes (due cards, completed lessons)
  - Test model accessors and mutators
  - Test model validation rules
  - _Requirements: All_

- [ ]* 42. Write service tests
  - Test SpacedRepetitionService algorithm accuracy
  - Test ProgressService calculations
  - Test GamificationService XP and level calculations
  - Test StudyPlanService plan generation
  - Test SearchService query results
  - Mock dependencies for isolated testing
  - _Requirements: 2.1, 2.2, 2.3, 2.4, 2.5, 5.1, 5.2, 5.3, 5.4, 5.5, 5.6, 5.7, 5.8, 6.1, 6.2, 7.1, 7.2, 7.3, 7.4, 8.1, 8.2, 8.4, 8.5_

- [ ]* 43. Write feature tests
  - Test authentication flows (registration, login, logout, password reset)
  - Test lesson display and navigation
  - Test flashcard creation and review flow
  - Test exercise completion and scoring
  - Test progress tracking and updates
  - Test achievement unlocking
  - Test search functionality
  - _Requirements: All_

- [ ]* 44. Write integration tests
  - Test complete user journey: register → create flashcards → review → level up
  - Test daily plan: generate → complete activities → update streak
  - Test exercise flow: attempt → score → add to review queue → retry
  - Test shadowing: view → record → save → track completion
  - _Requirements: All_

## Phase 12: Final Integration & Deployment Prep

- [ ] 45. Create comprehensive seeder
  - Seed all 9 lessons with complete content
  - Seed predefined achievements
  - Create demo user with sample progress
  - Seed sample flashcards and reviews
  - Verify all relationships and data integrity
  - _Requirements: 1.1, 1.2, 1.3, 1.4, 1.5, 7.4_

- [ ] 46. Add error handling and validation
  - Add form request validation for all user inputs
  - Add custom validation rules for Japanese text
  - Implement global exception handler
  - Add user-friendly error messages
  - Add error logging for debugging
  - Test error scenarios (invalid input, missing data, etc.)
  - _Requirements: All_

- [ ] 47. Optimize database queries
  - Add indexes to all foreign keys and frequently queried columns
  - Review and optimize N+1 query issues
  - Add query result caching where appropriate
  - Test query performance with large datasets
  - Add database query logging in development
  - _Requirements: All_

- [ ] 48. Create user documentation
  - Write README with setup instructions
  - Document environment variables and configuration
  - Create user guide for main features
  - Document keyboard shortcuts
  - Add inline help text and tooltips in UI
  - Create FAQ section
  - _Requirements: All_

- [ ] 49. Final UI/UX review
  - Review all pages for consistency
  - Test all user flows end-to-end
  - Fix any visual bugs or layout issues
  - Ensure consistent spacing and typography
  - Test on multiple browsers (Chrome, Firefox, Safari, Edge)
  - Test on multiple devices (mobile, tablet, desktop)
  - Gather feedback and make final adjustments
  - _Requirements: 10.1, 10.2, 10.3, 10.4, 10.5, 10.6, 10.7, 10.8_

- [ ] 50. Prepare for deployment
  - Configure production environment variables
  - Set up production database (MySQL/PostgreSQL)
  - Configure file storage for production (S3 or similar)
  - Set up queue worker with supervisor
  - Configure caching (Redis)
  - Set up error tracking (Sentry)
  - Create deployment documentation
  - Test deployment process
  - _Requirements: All_

---

## Notes

- Tasks marked with * are optional testing tasks that can be skipped for MVP
- Each task should be completed and tested before moving to the next
- Commit code after each task completion
- Update progress tracking as tasks are completed
- Refer to requirements.md and design.md for detailed specifications
- Focus on core functionality first, then enhance with polish and optimization
