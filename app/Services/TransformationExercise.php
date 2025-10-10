<?php

namespace App\Services;

use App\Contracts\ExerciseInterface;
use App\Models\Drill;

class TransformationExercise implements ExerciseInterface
{
    /**
     * Generate transformation drill exercises
     *
     * @param Drill $drill
     * @return array
     */
    public function generate(Drill $drill): array
    {
        $content = $drill->content;
        $answers = $drill->answers;
        
        $questions = [];
        $questionId = 0;
        
        // Process each transformation category (e.g., polite → casual)
        foreach ($content as $categoryIndex => $category) {
            $transformationType = ($category['from'] ?? '') . ' → ' . ($category['to'] ?? '');
            
            // Process each example in the category
            if (isset($category['examples']) && is_array($category['examples'])) {
                foreach ($category['examples'] as $exampleIndex => $example) {
                    $questions[] = [
                        'id' => $questionId,
                        'source_sentence' => $example['source'] ?? '',
                        'instruction' => $transformationType,
                        'hint' => null,
                    ];
                    
                    // Store the answer mapping
                    $answers[$questionId] = $example['target'] ?? '';
                    $questionId++;
                }
            }
        }
        
        return [
            'type' => 'transformation',
            'title' => $drill->title,
            'instructions' => 'Transform the sentences according to the given instructions. Pay attention to the transformation type (polite → casual, statement → question, etc.).',
            'questions' => $questions,
            'answers' => $answers, // Include answers for validation
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
        $content = $drill->content;
        $results = [];
        
        // Build correct answers from content
        $correctAnswers = [];
        $questionId = 0;
        
        foreach ($content as $category) {
            if (isset($category['examples']) && is_array($category['examples'])) {
                foreach ($category['examples'] as $example) {
                    $correctAnswers[$questionId] = $example['target'] ?? '';
                    $questionId++;
                }
            }
        }
        
        foreach ($userAnswers as $questionId => $userAnswer) {
            $correctAnswer = $correctAnswers[$questionId] ?? null;
            
            if ($correctAnswer === null) {
                $results[$questionId] = [
                    'correct' => false,
                    'user_answer' => $userAnswer,
                    'correct_answer' => null,
                    'partial_credit' => 0,
                    'message' => 'No correct answer found',
                ];
                continue;
            }
            
            // Check for exact match
            $normalizedUserAnswer = $this->normalizeAnswer($userAnswer);
            $normalizedCorrectAnswer = $this->normalizeAnswer($correctAnswer);
            
            $isExactMatch = $normalizedUserAnswer === $normalizedCorrectAnswer;
            
            // Calculate partial credit based on similarity
            $partialCredit = $this->calculateSimilarity($normalizedUserAnswer, $normalizedCorrectAnswer);
            
            $results[$questionId] = [
                'correct' => $isExactMatch,
                'user_answer' => $userAnswer,
                'correct_answer' => $correctAnswer,
                'partial_credit' => $partialCredit,
                'message' => $this->getResultMessage($isExactMatch, $partialCredit),
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
        
        $totalScore = 0;
        $totalCount = count($results);
        
        foreach ($results as $result) {
            if ($result['correct']) {
                $totalScore += 1.0;
            } else {
                // Award partial credit for close answers
                $totalScore += $result['partial_credit'];
            }
        }
        
        return round(($totalScore / $totalCount) * 100, 2);
    }

    /**
     * Calculate similarity between two strings (0-1 scale)
     * Uses similar_text for better multibyte character support
     *
     * @param string $str1
     * @param string $str2
     * @return float
     */
    private function calculateSimilarity(string $str1, string $str2): float
    {
        if (empty($str1) || empty($str2)) {
            return 0.0;
        }
        
        // Use similar_text which works better with multibyte strings
        $percent = 0;
        similar_text($str1, $str2, $percent);
        $similarity = $percent / 100;
        
        // Only award partial credit if similarity is above 70%
        return $similarity >= 0.7 ? $similarity : 0.0;
    }

    /**
     * Get result message based on correctness and partial credit
     *
     * @param bool $isExactMatch
     * @param float $partialCredit
     * @return string
     */
    private function getResultMessage(bool $isExactMatch, float $partialCredit): string
    {
        if ($isExactMatch) {
            return 'Correct!';
        }
        
        if ($partialCredit >= 0.9) {
            return 'Very close! Minor differences.';
        }
        
        if ($partialCredit >= 0.7) {
            return 'Partially correct. Check the transformation.';
        }
        
        return 'Incorrect. Review the correct answer.';
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
