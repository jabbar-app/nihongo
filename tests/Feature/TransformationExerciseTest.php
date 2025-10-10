<?php

use App\Models\User;
use App\Models\Lesson;
use App\Models\Drill;
use App\Services\TransformationExercise;

test('transformation exercise generates questions correctly', function () {
    $lesson = Lesson::factory()->create();
    
    $drill = Drill::factory()->create([
        'lesson_id' => $lesson->id,
        'type' => 'transformation',
        'title' => 'Polite to Casual Transformation',
        'content' => [
            [
                'from' => '丁寧',
                'to' => 'カジュアル',
                'examples' => [
                    [
                        'source' => 'ありがとうございます',
                        'target' => 'ありがとう',
                    ],
                    [
                        'source' => 'すみません',
                        'target' => 'ごめん',
                    ],
                ],
            ],
        ],
        'answers' => [],
    ]);

    $exercise = new TransformationExercise();
    $result = $exercise->generate($drill);

    expect($result['type'])->toBe('transformation');
    expect($result['questions'])->toHaveCount(2);
    expect($result['questions'][0]['source_sentence'])->toBe('ありがとうございます');
    expect($result['questions'][0]['instruction'])->toBe('丁寧 → カジュアル');
});

test('transformation exercise validates answers correctly', function () {
    $lesson = Lesson::factory()->create();
    
    $drill = Drill::factory()->create([
        'lesson_id' => $lesson->id,
        'type' => 'transformation',
        'title' => 'Polite to Casual Transformation',
        'content' => [
            [
                'from' => '丁寧',
                'to' => 'カジュアル',
                'examples' => [
                    [
                        'source' => 'ありがとうございます',
                        'target' => 'ありがとう',
                    ],
                ],
            ],
        ],
        'answers' => [],
    ]);

    $exercise = new TransformationExercise();
    
    // Test exact match
    $results = $exercise->validate([
        0 => 'ありがとう',
    ], $drill);

    expect($results[0]['correct'])->toBeTrue();
    expect($results[0]['partial_credit'])->toBe(1.0);

    // Test incorrect answer
    $results = $exercise->validate([
        0 => 'こんにちは',
    ], $drill);

    expect($results[0]['correct'])->toBeFalse();
    expect($results[0]['partial_credit'])->toBeLessThan(0.7);
});

test('transformation exercise awards partial credit for close answers', function () {
    $lesson = Lesson::factory()->create();
    
    $drill = Drill::factory()->create([
        'lesson_id' => $lesson->id,
        'type' => 'transformation',
        'title' => 'Polite to Casual Transformation',
        'content' => [
            [
                'from' => '丁寧',
                'to' => 'カジュアル',
                'examples' => [
                    [
                        'source' => 'ありがとうございます',
                        'target' => 'ありがとう',
                    ],
                ],
            ],
        ],
        'answers' => [],
    ]);

    $exercise = new TransformationExercise();
    
    // Test close answer (missing punctuation)
    $results = $exercise->validate([
        0 => 'ありがと',
    ], $drill);

    expect($results[0]['correct'])->toBeFalse();
    expect($results[0]['partial_credit'])->toBeGreaterThan(0.7);
});

test('transformation exercise calculates score correctly', function () {
    $lesson = Lesson::factory()->create();
    
    $drill = Drill::factory()->create([
        'lesson_id' => $lesson->id,
        'type' => 'transformation',
        'title' => 'Polite to Casual Transformation',
        'content' => [
            [
                'from' => '丁寧',
                'to' => 'カジュアル',
                'examples' => [
                    [
                        'source' => 'ありがとうございます',
                        'target' => 'ありがとう',
                    ],
                    [
                        'source' => 'すみません',
                        'target' => 'ごめん',
                    ],
                ],
            ],
        ],
        'answers' => [],
    ]);

    $exercise = new TransformationExercise();
    
    $results = $exercise->validate([
        0 => 'ありがとう',
        1 => 'すみません', // Wrong answer
    ], $drill);

    $score = $exercise->getScore($results);

    expect($score)->toBe(50.0); // 1 correct out of 2
});

test('user can view transformation exercise', function () {
    $user = User::factory()->create();
    $lesson = Lesson::factory()->create();
    
    $drill = Drill::factory()->create([
        'lesson_id' => $lesson->id,
        'type' => 'transformation',
        'title' => 'Polite to Casual Transformation',
        'content' => [
            [
                'from' => '丁寧',
                'to' => 'カジュアル',
                'examples' => [
                    [
                        'source' => 'ありがとうございます',
                        'target' => 'ありがとう',
                    ],
                ],
            ],
        ],
        'answers' => [],
    ]);

    $response = $this->actingAs($user)
        ->get(route('exercises.show', $drill));

    $response->assertStatus(200);
    $response->assertSee('Polite to Casual Transformation');
    $response->assertSee('ありがとうございます');
    $response->assertSee('丁寧 → カジュアル');
});

test('user can submit transformation exercise answers', function () {
    $user = User::factory()->create();
    $lesson = Lesson::factory()->create();
    
    $drill = Drill::factory()->create([
        'lesson_id' => $lesson->id,
        'type' => 'transformation',
        'title' => 'Polite to Casual Transformation',
        'content' => [
            [
                'from' => '丁寧',
                'to' => 'カジュアル',
                'examples' => [
                    [
                        'source' => 'ありがとうございます',
                        'target' => 'ありがとう',
                    ],
                ],
            ],
        ],
        'answers' => [],
    ]);

    $response = $this->actingAs($user)
        ->postJson(route('exercises.submit', $drill), [
            'answers' => [
                0 => 'ありがとう',
            ],
            'duration_seconds' => 30,
        ]);

    $response->assertStatus(200);
    $response->assertJson([
        'success' => true,
        'score' => 100.0,
    ]);

    $this->assertDatabaseHas('exercise_attempts', [
        'user_id' => $user->id,
        'drill_id' => $drill->id,
        'score' => 100.0,
    ]);
});
