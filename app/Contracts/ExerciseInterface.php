<?php

namespace App\Contracts;

use App\Models\Drill;

interface ExerciseInterface
{
    /**
     * Generate exercise questions from a drill
     *
     * @param Drill $drill
     * @return array Exercise data structure with questions
     */
    public function generate(Drill $drill): array;

    /**
     * Validate user answers against correct answers
     *
     * @param array $userAnswers User's submitted answers
     * @param Drill $drill The drill being validated
     * @return array Validation results with correct/incorrect flags
     */
    public function validate(array $userAnswers, Drill $drill): array;

    /**
     * Calculate score from validation results
     *
     * @param array $results Validation results from validate method
     * @return float Score as percentage (0-100)
     */
    public function getScore(array $results): float;
}
