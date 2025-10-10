<?php

namespace App\Services;

use App\Contracts\ExerciseInterface;
use App\Models\Drill;

class ClozeExercise implements ExerciseInterface
{
    /**
     * Generate cloze deletion exercises
     *
     * @param Drill $drill
     * @return array
     */
    public function generate(Drill $drill): array
    {
        $content = $drill->content;
        
        $questions = [];
        
        // Process each cloze deletion item
        foreach ($content as $index => $item) {
            $sentence = $item['sentence'] ?? $item['text'] ?? '';
            $blanks = $this->extractBlanks($sentence);
            
            $questions[] = [
                'id' => $index,
                'sentence' => $sentence,
                'blank_count' => count($blanks),
                'blanks' => $blanks,
                'hint' => $item['hint'] ?? null,
            ];
        }
        
        return [
            'type' => 'cloze',
            'title' => $drill->title,
            'instructions' => 'Fill in the blanks to complete the sentences.',
            'questions' => $questions,
        ];
    }

    /**
     * Validate user answers against correct answers
     *
     * @param array $userAnswers
     * @param Drill $drill
     * @return array
     */
    public function validate(array $userAnswers, Drill $drill): array
    {
        $correctAnswers = $drill->answers;
        $results = [];
        
        foreach ($userAnswers as $questionId => $userBlanks) {
            $correctBlanks = $correctAnswers[$questionId] ?? null;
            
            if ($correctBlanks === null) {
                $results[$questionId] = [
                    'correct' => false,
                    'user_answers' => $userBlanks,
                    'correct_answers' => null,
                    'blank_results' => [],
                    'message' => 'No correct answers found',
                ];
                continue;
            }
            
            // Validate each blank
            $blankResults = [];
            $allCorrect = true;
            
            foreach ($userBlanks as $blankIndex => $userBlank) {
                $correctBlank = $correctBlanks[$blankIndex] ?? null;
                
                if ($correctBlank === null) {
                    $blankResults[$blankIndex] = [
                        'correct' => false,
                        'user_answer' => $userBlank,
                        'correct_answer' => null,
                    ];
                    $allCorrect = false;
                    continue;
                }
                
                // Normalize and compare
                $normalizedUserBlank = $this->normalizeAnswer($userBlank);
                $normalizedCorrectBlank = $this->normalizeAnswer($correctBlank);
                
                $isCorrect = $normalizedUserBlank === $normalizedCorrectBlank;
                
                $blankResults[$blankIndex] = [
                    'correct' => $isCorrect,
                    'user_answer' => $userBlank,
                    'correct_answer' => $correctBlank,
                ];
                
                if (!$isCorrect) {
                    $allCorrect = false;
                }
            }
            
            $results[$questionId] = [
                'correct' => $allCorrect,
                'user_answers' => $userBlanks,
                'correct_answers' => $correctBlanks,
                'blank_results' => $blankResults,
                'message' => $allCorrect ? 'All blanks correct!' : 'Some blanks are incorrect',
            ];
        }
        
        return $results;
    }

    /**
     * Calculate score from validation results
     *
     * @param array $results
     * @return float
     */
    public function getScore(array $results): float
    {
        if (empty($results)) {
            return 0.0;
        }
        
        $totalBlanks = 0;
        $correctBlanks = 0;
        
        foreach ($results as $result) {
            if (isset($result['blank_results'])) {
                foreach ($result['blank_results'] as $blankResult) {
                    $totalBlanks++;
                    if ($blankResult['correct']) {
                        $correctBlanks++;
                    }
                }
            }
        }
        
        if ($totalBlanks === 0) {
            return 0.0;
        }
        
        return round(($correctBlanks / $totalBlanks) * 100, 2);
    }

    /**
     * Extract blank positions from a sentence
     *
     * @param string $sentence
     * @return array
     */
    private function extractBlanks(string $sentence): array
    {
        $blanks = [];
        $pattern = '/___+|____+|\[blank\]/i';
        
        preg_match_all($pattern, $sentence, $matches, PREG_OFFSET_MATCH);
        
        foreach ($matches[0] as $index => $match) {
            $blanks[] = [
                'index' => $index,
                'position' => $match[1],
                'placeholder' => $match[0],
            ];
        }
        
        return $blanks;
    }

    /**
     * Normalize answer for comparison
     *
     * @param string|array $answer
     * @return string
     */
    private function normalizeAnswer($answer): string
    {
        if (is_array($answer)) {
            $answer = implode(' ', $answer);
        }
        
        // Remove extra whitespace and normalize
        return mb_strtolower(trim(preg_replace('/\s+/', ' ', $answer)));
    }
}
