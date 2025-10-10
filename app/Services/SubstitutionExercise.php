<?php

namespace App\Services;

use App\Contracts\ExerciseInterface;
use App\Models\Drill;

class SubstitutionExercise implements ExerciseInterface
{
    /**
     * Generate substitution drill exercises (fill-in-the-blank)
     *
     * @param Drill $drill
     * @return array
     */
    public function generate(Drill $drill): array
    {
        $content = $drill->content;
        $questions = [];
        
        // Process each pattern with substitutions
        foreach ($content as $index => $item) {
            if (isset($item['pattern']) && isset($item['substitutions'])) {
                // Create a question for each substitution option
                foreach ($item['substitutions'] as $subIndex => $substitution) {
                    $pattern = $item['pattern'];
                    
                    // Replace [placeholder] with blank
                    $question = preg_replace('/\[.+?\]/', '___', $pattern);
                    
                    $questions[] = [
                        'id' => $index . '_' . $subIndex,
                        'question' => $question,
                        'hint' => 'Use: ' . $substitution,
                    ];
                }
            }
        }
        
        return [
            'type' => 'substitution',
            'title' => $drill->title,
            'instructions' => 'Fill in the blanks with the correct words or phrases based on the hints provided.',
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
        $content = $drill->content;
        $results = [];
        
        foreach ($userAnswers as $questionId => $userAnswer) {
            // Parse question ID (format: "0_1" means pattern 0, substitution 1)
            [$patternIndex, $subIndex] = explode('_', $questionId);
            
            $correctAnswer = null;
            if (isset($content[$patternIndex]['substitutions'][$subIndex])) {
                $correctAnswer = $content[$patternIndex]['substitutions'][$subIndex];
            }
            
            if ($correctAnswer === null) {
                $results[$questionId] = [
                    'correct' => false,
                    'user_answer' => $userAnswer,
                    'correct_answer' => null,
                    'message' => 'No correct answer found',
                ];
                continue;
            }
            
            // Normalize answers for comparison
            $normalizedUserAnswer = $this->normalizeAnswer($userAnswer);
            $normalizedCorrectAnswer = $this->normalizeAnswer($correctAnswer);
            
            $isCorrect = $normalizedUserAnswer === $normalizedCorrectAnswer;
            
            $results[$questionId] = [
                'correct' => $isCorrect,
                'user_answer' => $userAnswer,
                'correct_answer' => $correctAnswer,
                'message' => $isCorrect ? 'Correct!' : 'Incorrect',
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
        
        $correctCount = 0;
        $totalCount = count($results);
        
        foreach ($results as $result) {
            if ($result['correct']) {
                $correctCount++;
            }
        }
        
        return round(($correctCount / $totalCount) * 100, 2);
    }

    /**
     * Normalize answer for comparison (trim, lowercase, remove extra spaces)
     *
     * @param string|array $answer
     * @return string
     */
    private function normalizeAnswer($answer): string
    {
        if (is_array($answer)) {
            $answer = implode(' ', $answer);
        }
        
        return mb_strtolower(trim(preg_replace('/\s+/', ' ', $answer)));
    }
}
