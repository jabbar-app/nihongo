<?php

namespace App\Services;

use App\Contracts\ExerciseInterface;
use App\Models\Drill;

class ClozeExercise implements ExerciseInterface
{
    /**
     * Generate cloze deletion exercises (fill-in-the-blank)
     *
     * @param Drill $drill
     * @return array
     */
    public function generate(Drill $drill): array
    {
        $content = $drill->content;
        $answers = $drill->answers;
        $questions = [];
        
        // Process each cloze question
        foreach ($content as $index => $item) {
            if (isset($item['question']) && isset($item['hint'])) {
                // Count the number of blanks in the question
                $blankCount = substr_count($item['question'], '___');
                
                // If no blanks found, look for underscore patterns
                if ($blankCount === 0) {
                    $blankCount = preg_match_all('/_+/', $item['question'], $matches);
                }
                
                $questions[] = [
                    'id' => $index,
                    'question' => $item['question'],
                    'hint' => $item['hint'],
                    'blank_count' => max(1, $blankCount), // At least 1 blank
                ];
            }
        }
        
        return [
            'type' => 'cloze',
            'title' => $drill->title,
            'instructions' => 'Fill in the blanks with the correct words or phrases. Use the hints provided in parentheses.',
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
        $answers = $drill->answers;
        $results = [];
        
        foreach ($userAnswers as $questionId => $userAnswer) {
            $correctAnswer = $answers[$questionId] ?? null;
            
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
