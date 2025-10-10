<?php

namespace App\Services;

use App\Contracts\ContentParserInterface;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class MarkdownContentParser implements ContentParserInterface
{
    /**
     * Parse markdown content and extract all structured data
     */
    public function parse(string $markdown): array
    {
        return [
            'phrases' => $this->extractPhrases($markdown),
            'dialogues' => $this->extractDialogues($markdown),
            'drills' => $this->extractDrills($markdown),
            'shadowing' => $this->extractShadowingExercises($markdown),
        ];
    }

    /**
     * Extract phrases from markdown phrase tables
     */
    public function extractPhrases(string $markdown): Collection
    {
        $phrases = collect();
        $currentCategory = null;
        $order = 0;

        $lines = explode("\n", $markdown);
        $inTable = false;
        $headerPassed = false;

        foreach ($lines as $line) {
            $line = trim($line);

            // Detect category headers (### heading)
            if (preg_match('/^###\s+(.+)$/', $line, $matches)) {
                $currentCategory = trim($matches[1]);
                $inTable = false;
                $headerPassed = false;
                continue;
            }

            // Detect table start
            if (preg_match('/^\|\s*JP\s*\|/', $line)) {
                $inTable = true;
                continue;
            }

            // Skip separator line
            if (preg_match('/^\|[\s\-:]+\|/', $line)) {
                $headerPassed = true;
                continue;
            }

            // Parse table rows
            if ($inTable && $headerPassed && str_starts_with($line, '|')) {
                $phrase = $this->parseTableRow($line, $currentCategory, $order);
                if ($phrase) {
                    $phrases->push($phrase);
                    $order++;
                }
            }

            // End table when we hit empty line or new section
            if ($inTable && (empty($line) || str_starts_with($line, '#'))) {
                $inTable = false;
                $headerPassed = false;
            }
        }

        return $phrases;
    }

    /**
     * Parse a single table row into phrase data
     */
    private function parseTableRow(string $line, ?string $category, int $order): ?array
    {
        // Split by pipe and clean up
        $columns = array_map('trim', explode('|', $line));
        
        // Remove empty first and last elements from split
        $columns = array_filter($columns, fn($col) => $col !== '');
        $columns = array_values($columns);

        // Need at least 3 columns (JP, Romaji, EN)
        if (count($columns) < 3) {
            return null;
        }

        // Skip if it's a header or separator row
        if (in_array(strtolower($columns[0]), ['jp', 'japanese', '---'])) {
            return null;
        }

        return [
            'japanese' => $columns[0] ?? '',
            'romaji' => $columns[1] ?? '',
            'english' => $columns[2] ?? '',
            'notes' => $columns[3] ?? null,
            'category' => $category,
            'order' => $order,
        ];
    }

    /**
     * Extract dialogues with speaker/line parsing
     */
    public function extractDialogues(string $markdown): Collection
    {
        $dialogues = collect();
        $lines = explode("\n", $markdown);
        $currentDialogue = null;
        $order = 0;

        foreach ($lines as $line) {
            $line = trim($line);

            // Detect dialogue headers (### D1: Title)
            if (preg_match('/^###\s+(D\d+):\s*(.+)$/', $line, $matches)) {
                // Save previous dialogue if exists
                if ($currentDialogue) {
                    $currentDialogue['order'] = $order++;
                    $dialogues->push($currentDialogue);
                }

                // Start new dialogue
                $currentDialogue = [
                    'title' => trim($matches[2]),
                    'content' => [],
                ];
                continue;
            }

            // Parse dialogue lines (A: text or B: text)
            if ($currentDialogue && preg_match('/^([AB]):\s*(.+)$/', $line, $matches)) {
                $speaker = $matches[1];
                $text = trim($matches[2]);

                $currentDialogue['content'][] = [
                    'speaker' => $speaker,
                    'line' => $text,
                ];
            }

            // End dialogue on separator or new section
            if ($currentDialogue && (str_starts_with($line, '---') || str_starts_with($line, '##'))) {
                if (!empty($currentDialogue['content'])) {
                    $currentDialogue['order'] = $order++;
                    $dialogues->push($currentDialogue);
                }
                $currentDialogue = null;
            }
        }

        // Add last dialogue if exists
        if ($currentDialogue && !empty($currentDialogue['content'])) {
            $currentDialogue['order'] = $order;
            $dialogues->push($currentDialogue);
        }

        return $dialogues;
    }

    /**
     * Extract drills with type detection
     */
    public function extractDrills(string $markdown): Collection
    {
        $drills = collect();
        $lines = explode("\n", $markdown);
        $currentDrill = null;
        $currentType = null;
        $order = 0;

        foreach ($lines as $originalLine) {
            $line = trim($originalLine);

            // Detect drill section headers (### Substitution Drills, ### Transformation Drills, etc.)
            if (preg_match('/^###\s+(.+)$/i', $line, $matches)) {
                $sectionTitle = trim($matches[1]);
                $detectedType = $this->detectDrillType($sectionTitle);
                
                // Only start new drill if we detect a drill type
                if ($detectedType) {
                    // Save previous drill if exists
                    if ($currentDrill && !empty($currentDrill['content'])) {
                        $currentDrill['order'] = $order++;
                        $drills->push($currentDrill);
                    }

                    $currentType = $detectedType;
                    $currentDrill = [
                        'type' => $currentType,
                        'title' => $sectionTitle,
                        'content' => [],
                        'answers' => [],
                    ];
                }
                continue;
            }

            // Parse drill content based on type (pass original line for indentation detection)
            if ($currentDrill && $currentType) {
                if ($currentType === 'substitution') {
                    $this->parseSubstitutionDrill($line, $currentDrill);
                } elseif ($currentType === 'transformation') {
                    $this->parseTransformationDrill($originalLine, $currentDrill);
                } elseif ($currentType === 'cloze') {
                    $this->parseClozeDrill($line, $currentDrill);
                }
            }
        }

        // Add last drill if exists
        if ($currentDrill && !empty($currentDrill['content'])) {
            $currentDrill['order'] = $order;
            $drills->push($currentDrill);
        }

        return $drills;
    }

    /**
     * Detect drill type from section title
     */
    private function detectDrillType(string $title): ?string
    {
        $title = strtolower($title);

        if (str_contains($title, 'substitution')) {
            return 'substitution';
        } elseif (str_contains($title, 'transformation')) {
            return 'transformation';
        } elseif (str_contains($title, 'cloze') || str_contains($title, 'fill')) {
            return 'cloze';
        }

        return null; // Not a drill section
    }

    /**
     * Parse substitution drill content
     */
    private function parseSubstitutionDrill(string $line, array &$drill): void
    {
        // Pattern: 「[場所] はどこですか。」
        if (preg_match('/^Pattern:\s*(.+)$/u', $line, $matches)) {
            // Remove quotes if present
            $pattern = trim($matches[1]);
            $pattern = preg_replace('/^[「『](.+?)[」』]$/u', '$1', $pattern);
            
            $drill['content'][] = [
                'pattern' => $pattern,
                'substitutions' => [],
            ];
        }
        // Substitution options: - 交番／観光案内所／東口
        elseif (preg_match('/^[-\*]\s*(.+)$/u', $line, $matches)) {
            $lastIndex = count($drill['content']) - 1;
            if ($lastIndex >= 0 && isset($drill['content'][$lastIndex]['pattern'])) {
                // Split by full-width or half-width slash
                $options = preg_split('/[／\/]/u', $matches[1]);
                $options = array_map('trim', $options);
                $options = array_filter($options, fn($opt) => $opt !== '');
                
                $drill['content'][$lastIndex]['substitutions'] = array_merge(
                    $drill['content'][$lastIndex]['substitutions'] ?? [],
                    array_values($options)
                );
            }
        }
    }

    /**
     * Parse transformation drill content
     */
    private function parseTransformationDrill(string $line, array &$drill): void
    {
        $trimmed = trim($line);
        
        // - 丁寧 → カジュアル：
        if (preg_match('/^[-\*]\s*(.+?)\s*(?:→|->)\s*(.+?)(?:：|:)\s*$/u', $trimmed, $matches)) {
            $drill['content'][] = [
                'from' => trim($matches[1]),
                'to' => trim($matches[2]),
                'examples' => [],
            ];
        }
        // Example: - ここで合っていますか → ここで合ってる？ (can be indented)
        elseif (preg_match('/^\s+[-\*]\s*(.+?)\s*(?:→|->)\s*(.+)$/u', $line, $matches)) {
            $lastIndex = count($drill['content']) - 1;
            if ($lastIndex >= 0 && isset($drill['content'][$lastIndex]['examples'])) {
                $drill['content'][$lastIndex]['examples'][] = [
                    'source' => trim($matches[1]),
                    'target' => trim($matches[2]),
                ];
            }
        }
    }

    /**
     * Parse cloze drill content
     */
    private function parseClozeDrill(string $line, array &$drill): void
    {
        // Numbered questions: 1. ________, 最寄りの駅はどこですか。（Excuse me）
        if (preg_match('/^(\d+)\.\s*(.+?)\s*[（(](.+?)[）)]\s*$/u', $line, $matches)) {
            $drill['content'][] = [
                'question' => trim($matches[2]),
                'hint' => trim($matches[3]),
            ];
        }
        // Answers line: Answers: 1) すみません 2) まっすぐ
        elseif (preg_match('/^Answers?:/i', $line)) {
            preg_match_all('/(\d+)\)\s*([^\d]+?)(?=\s*\d+\)|$)/', $line, $matches, PREG_SET_ORDER);
            foreach ($matches as $match) {
                $index = (int)$match[1] - 1;
                if (isset($drill['content'][$index])) {
                    $drill['answers'][$index] = trim($match[2]);
                }
            }
        }
    }

    /**
     * Extract shadowing exercises
     */
    public function extractShadowingExercises(string $markdown): Collection
    {
        $exercises = collect();
        $lines = explode("\n", $markdown);
        $currentExercise = null;
        $order = 0;

        foreach ($lines as $line) {
            $line = trim($line);

            // Detect scripted sections (S1, S2, etc.)
            if (preg_match('/^(S\d+)\s*[\(（](.+?)[\)）][:：]?/', $line, $matches)) {
                // Save previous exercise if exists
                if ($currentExercise) {
                    $currentExercise['order'] = $order++;
                    $exercises->push($currentExercise);
                }

                // Start new exercise
                $currentExercise = [
                    'title' => trim($matches[2]),
                    'content' => [],
                ];
                continue;
            }

            // Parse script lines (- text)
            if ($currentExercise && preg_match('/^[-\*]\s*(.+)$/', $line, $matches)) {
                $text = trim($matches[1]);
                
                // Check if it contains speaker notation (A: or B:)
                if (preg_match('/^([AB]):\s*(.+)$/', $text, $speakerMatch)) {
                    $currentExercise['content'][] = [
                        'speaker' => $speakerMatch[1],
                        'line' => trim($speakerMatch[2]),
                    ];
                } else {
                    // Single line without speaker
                    $currentExercise['content'][] = [
                        'speaker' => null,
                        'line' => $text,
                    ];
                }
            }

            // End exercise on new section
            if ($currentExercise && (str_starts_with($line, '###') || str_starts_with($line, 'S') && preg_match('/^S\d+/', $line))) {
                if (!empty($currentExercise['content'])) {
                    $currentExercise['order'] = $order++;
                    $exercises->push($currentExercise);
                }
                $currentExercise = null;
            }
        }

        // Add last exercise if exists
        if ($currentExercise && !empty($currentExercise['content'])) {
            $currentExercise['order'] = $order;
            $exercises->push($currentExercise);
        }

        return $exercises;
    }
}
