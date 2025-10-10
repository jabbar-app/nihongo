<?php

namespace Database\Seeders;

use App\Models\Lesson;
use App\Models\Phrase;
use App\Models\Dialogue;
use App\Models\Drill;
use App\Models\ShadowingExercise;
use App\Services\MarkdownContentParser;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;

class LessonSeeder extends Seeder
{
    private MarkdownContentParser $parser;

    public function __construct()
    {
        $this->parser = new MarkdownContentParser();
    }

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Define lesson metadata
        $lessons = [
            [
                'slug' => 'lang-001-directions',
                'title' => 'Directions & Navigation',
                'description' => 'Learn how to ask for and give directions, navigate stations, and use public transportation.',
                'order' => 1,
            ],
            [
                'slug' => 'lang-002-food',
                'title' => 'Food & Dining',
                'description' => 'Master restaurant conversations, ordering food, and discussing dietary preferences.',
                'order' => 2,
            ],
            [
                'slug' => 'lang-003-invites',
                'title' => 'Invitations & Social Plans',
                'description' => 'Learn how to invite others, accept or decline invitations, and make social arrangements.',
                'order' => 3,
            ],
            [
                'slug' => 'lang-004-smalltalk',
                'title' => 'Small Talk & Casual Conversation',
                'description' => 'Practice everyday conversations, weather talk, and building rapport.',
                'order' => 4,
            ],
            [
                'slug' => 'lang-005-opinions',
                'title' => 'Opinions & Preferences',
                'description' => 'Express your opinions, preferences, and engage in discussions.',
                'order' => 5,
            ],
            [
                'slug' => 'lang-006-gratitude',
                'title' => 'Gratitude & Apologies',
                'description' => 'Learn various ways to express thanks, apologize, and show appreciation.',
                'order' => 6,
            ],
            [
                'slug' => 'lang-007-admin',
                'title' => 'Administrative Tasks',
                'description' => 'Handle administrative situations like banking, post office, and official procedures.',
                'order' => 7,
            ],
            [
                'slug' => 'lang-008-nsicu',
                'title' => 'NSICU (Medical/Emergency)',
                'description' => 'Essential phrases for medical situations and emergencies.',
                'order' => 8,
            ],
            [
                'slug' => 'lang-009-jabbar',
                'title' => 'Jabbar (Advanced Conversations)',
                'description' => 'Advanced conversational patterns and complex interactions.',
                'order' => 9,
            ],
        ];

        foreach ($lessons as $lessonData) {
            $this->seedLesson($lessonData);
        }

        $this->command->info('Successfully seeded all 9 lessons with content!');
    }

    /**
     * Seed a single lesson with all its content
     */
    private function seedLesson(array $lessonData): void
    {
        $contentPath = "content/{$lessonData['slug']}";
        $lessonData['content_path'] = $contentPath;

        // Create or update lesson
        $lesson = Lesson::updateOrCreate(
            ['slug' => $lessonData['slug']],
            $lessonData
        );

        $this->command->info("Seeding lesson: {$lesson->title}");

        // Seed phrases
        $this->seedPhrases($lesson, $contentPath);

        // Seed dialogues
        $this->seedDialogues($lesson, $contentPath);

        // Seed drills
        $this->seedDrills($lesson, $contentPath);

        // Seed shadowing exercises
        $this->seedShadowingExercises($lesson, $contentPath);
    }

    /**
     * Seed phrases from phrases.md file
     */
    private function seedPhrases(Lesson $lesson, string $contentPath): void
    {
        $phrasesFile = base_path("{$contentPath}/phrases.md");

        if (!File::exists($phrasesFile)) {
            $this->command->warn("  Phrases file not found: {$phrasesFile}");
            return;
        }

        $markdown = File::get($phrasesFile);
        $phrases = $this->parser->extractPhrases($markdown);

        // Delete existing phrases for this lesson
        $lesson->phrases()->delete();

        // Insert new phrases
        foreach ($phrases as $phraseData) {
            Phrase::create([
                'lesson_id' => $lesson->id,
                'japanese' => $phraseData['japanese'],
                'romaji' => $phraseData['romaji'],
                'english' => $phraseData['english'],
                'notes' => $phraseData['notes'],
                'category' => $phraseData['category'],
                'order' => $phraseData['order'],
            ]);
        }

        $this->command->info("  ✓ Seeded {$phrases->count()} phrases");
    }

    /**
     * Seed dialogues from dialogues.md file
     */
    private function seedDialogues(Lesson $lesson, string $contentPath): void
    {
        $dialoguesFile = base_path("{$contentPath}/dialogues.md");

        if (!File::exists($dialoguesFile)) {
            $this->command->warn("  Dialogues file not found: {$dialoguesFile}");
            return;
        }

        $markdown = File::get($dialoguesFile);
        $dialogues = $this->parser->extractDialogues($markdown);

        // Delete existing dialogues for this lesson
        $lesson->dialogues()->delete();

        // Insert new dialogues
        foreach ($dialogues as $dialogueData) {
            Dialogue::create([
                'lesson_id' => $lesson->id,
                'title' => $dialogueData['title'],
                'content' => $dialogueData['content'],
                'order' => $dialogueData['order'],
            ]);
        }

        $this->command->info("  ✓ Seeded {$dialogues->count()} dialogues");
    }

    /**
     * Seed drills from drills-and-missions.md file
     */
    private function seedDrills(Lesson $lesson, string $contentPath): void
    {
        $drillsFile = base_path("{$contentPath}/drills-and-missions.md");

        if (!File::exists($drillsFile)) {
            $this->command->warn("  Drills file not found: {$drillsFile}");
            return;
        }

        $markdown = File::get($drillsFile);
        $drills = $this->parser->extractDrills($markdown);

        // Delete existing drills for this lesson
        $lesson->drills()->delete();

        // Insert new drills
        foreach ($drills as $drillData) {
            // Ensure UTF-8 encoding for all string data
            $content = $this->ensureUtf8($drillData['content']);
            $answers = $this->ensureUtf8($drillData['answers']);
            
            Drill::create([
                'lesson_id' => $lesson->id,
                'type' => $drillData['type'],
                'title' => $drillData['title'],
                'content' => $content,
                'answers' => $answers,
                'order' => $drillData['order'],
            ]);
        }

        $this->command->info("  ✓ Seeded {$drills->count()} drills");
    }

    /**
     * Ensure UTF-8 encoding for data
     */
    private function ensureUtf8($data)
    {
        if (is_array($data)) {
            return array_map([$this, 'ensureUtf8'], $data);
        }
        
        if (is_string($data)) {
            // Remove any invalid UTF-8 characters
            return mb_convert_encoding($data, 'UTF-8', 'UTF-8');
        }
        
        return $data;
    }

    /**
     * Seed shadowing exercises from shadowing.md file
     */
    private function seedShadowingExercises(Lesson $lesson, string $contentPath): void
    {
        $shadowingFile = base_path("{$contentPath}/shadowing.md");

        if (!File::exists($shadowingFile)) {
            $this->command->warn("  Shadowing file not found: {$shadowingFile}");
            return;
        }

        $markdown = File::get($shadowingFile);
        $exercises = $this->parser->extractShadowingExercises($markdown);

        // Delete existing shadowing exercises for this lesson
        $lesson->shadowingExercises()->delete();

        // Insert new shadowing exercises
        foreach ($exercises as $exerciseData) {
            ShadowingExercise::create([
                'lesson_id' => $lesson->id,
                'title' => $exerciseData['title'],
                'content' => $exerciseData['content'],
                'audio_url' => null, // Will be populated later if audio files are added
                'duration_seconds' => null,
                'order' => $exerciseData['order'],
            ]);
        }

        $this->command->info("  ✓ Seeded {$exercises->count()} shadowing exercises");
    }
}
