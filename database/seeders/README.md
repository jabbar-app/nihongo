# Database Seeders

This directory contains comprehensive seeders for the Japanese Learning Application.

## Available Seeders

### 1. LessonSeeder
Seeds all 9 lessons with complete content from the `content/` directory:
- Phrases (Japanese, Romaji, English translations)
- Dialogues (conversational scripts)
- Drills (substitution, transformation, cloze exercises)
- Shadowing exercises

**Content Seeded:**
- Lesson 1: Directions & Navigation (80 phrases, 10 dialogues, 3 drills, 4 shadowing)
- Lesson 2: Food & Dining
- Lesson 3: Invitations & Social Plans
- Lesson 4: Small Talk & Casual Conversation
- Lesson 5: Opinions & Preferences
- Lesson 6: Gratitude & Apologies
- Lesson 7: Administrative Tasks
- Lesson 8: NSICU (Medical/Emergency)
- Lesson 9: Jabbar (Advanced Conversations)

### 2. AchievementSeeder
Seeds 24 predefined achievements across multiple categories:
- **Flashcard Achievements**: First Review, Card Collector, Card Master, Card Legend
- **Streak Achievements**: Getting Started, Week Warrior, Month Master, Dedication Champion
- **Lesson Achievements**: First Lesson, Lesson Master, Course Champion
- **Exercise Achievements**: Exercise Beginner, Enthusiast, Expert, Perfectionist
- **Shadowing Achievements**: First Shadow, Voice Actor, Pronunciation Pro
- **Study Time Achievements**: Study Starter, Dedicated Learner, Study Marathon, Time Master
- **Level Achievements**: Rising Star (Level 5), Skilled Learner (Level 10), Expert Student (Level 25), Master of Japanese (Level 50)

### 3. DemoUserSeeder
Creates a demo user with realistic sample progress:

**Demo User Credentials:**
- Email: `demo@example.com`
- Password: `password`

**Sample Data Created:**
- User Profile (Level 8, 2450 XP, 12-day streak)
- 50 Flashcards from first 3 lessons
- 175 Flashcard Reviews with varied performance
- 16 Exercise Attempts with scores ranging from 60-100%
- 3 Shadowing Completions
- 4 Lesson Progress Records (100%, 85%, 60%, 25% completion)
- 77 Study Activities over 15 days
- 12 Daily Streak Records
- 9 Earned Achievements

## Usage

### Seed Everything
To seed the entire database with all content and demo data:

```bash
php artisan db:seed
```

or

```bash
php artisan db:seed --class=DatabaseSeeder
```

### Seed Individual Seeders

Seed only lessons:
```bash
php artisan db:seed --class=LessonSeeder
```

Seed only achievements:
```bash
php artisan db:seed --class=AchievementSeeder
```

Seed only demo user:
```bash
php artisan db:seed --class=DemoUserSeeder
```

### Fresh Migration with Seeding

To reset the database and seed everything:
```bash
php artisan migrate:fresh --seed
```

## Data Integrity Verification

The `DatabaseSeeder` automatically verifies data integrity after seeding:
- Counts all seeded records
- Verifies relationships between models
- Confirms demo user has proper associations

## Notes

### Observer Handling
The `DemoUserSeeder` temporarily disables model observers to prevent conflicts when creating historical data. This ensures:
- No duplicate DailyStreak records
- No automatic XP awards during seeding
- Clean, predictable seeding process

### Idempotency
All seeders use `updateOrCreate` where appropriate, making them safe to run multiple times. The `DemoUserSeeder` specifically:
- Deletes existing demo user data before creating new data
- Prevents duplicate records
- Ensures clean state on each run

### Content Source
Lesson content is parsed from markdown files in the `content/` directory. The structure is:
```
content/
├── lang-001-directions/
│   ├── phrases.md
│   ├── dialogues.md
│   ├── drills-and-missions.md
│   └── shadowing.md
├── lang-002-food/
│   └── ...
└── ...
```

## Troubleshooting

### "No lessons found" Error
Make sure the `content/` directory exists and contains lesson folders with markdown files.

### Unique Constraint Violations
If you encounter unique constraint errors:
1. Run `php artisan migrate:fresh` to reset the database
2. Then run `php artisan db:seed`

### Missing Relationships
If relationships aren't working:
1. Check that migrations have been run: `php artisan migrate:status`
2. Verify foreign key constraints in migration files
3. Ensure models have proper relationship methods defined

## Development

When adding new seeders:
1. Create the seeder class in this directory
2. Add it to `DatabaseSeeder::run()` method
3. Update this README with documentation
4. Test with `php artisan db:seed --class=YourSeeder`
