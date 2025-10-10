<?php

namespace App\Services;

use App\Contracts\ExerciseInterface;
use App\Models\Drill;
use InvalidArgumentException;

class ExerciseFactory
{
    /**
     * Create an exercise instance based on drill type
     *
     * @param Drill $drill
     * @return ExerciseInterface
     * @throws InvalidArgumentException
     */
    public static function create(Drill $drill): ExerciseInterface
    {
        return match ($drill->type) {
            'substitution' => new SubstitutionExercise(),
            'transformation' => new TransformationExercise(),
            'cloze' => new ClozeExercise(),
            default => throw new InvalidArgumentException("Unknown exercise type: {$drill->type}"),
        };
    }

    /**
     * Create an exercise instance by type string
     *
     * @param string $type
     * @return ExerciseInterface
     * @throws InvalidArgumentException
     */
    public static function createByType(string $type): ExerciseInterface
    {
        return match ($type) {
            'substitution' => new SubstitutionExercise(),
            'transformation' => new TransformationExercise(),
            'cloze' => new ClozeExercise(),
            default => throw new InvalidArgumentException("Unknown exercise type: {$type}"),
        };
    }

    /**
     * Get all supported exercise types
     *
     * @return array
     */
    public static function getSupportedTypes(): array
    {
        return [
            'substitution',
            'transformation',
            'cloze',
        ];
    }

    /**
     * Check if a type is supported
     *
     * @param string $type
     * @return bool
     */
    public static function isTypeSupported(string $type): bool
    {
        return in_array($type, self::getSupportedTypes());
    }
}
