<?php

use App\Models\Lesson;
use App\Models\Phrase;
use App\Models\Dialogue;

test('lessons index page displays all lessons', function () {
    $lessons = Lesson::factory()->count(3)->create();
    
    $response = $this->get(route('lessons.index'));
    
    $response->assertStatus(200);
    $response->assertViewIs('lessons.index');
    $response->assertViewHas('lessons');
    
    foreach ($lessons as $lesson) {
        $response->assertSee($lesson->title);
    }
});

test('lesson show page displays lesson details', function () {
    $lesson = Lesson::factory()->create();
    
    $response = $this->get(route('lessons.show', $lesson->slug));
    
    $response->assertStatus(200);
    $response->assertViewIs('lessons.show');
    $response->assertViewHas('lesson');
    $response->assertSee($lesson->title);
});

test('lesson show page displays phrases', function () {
    $lesson = Lesson::factory()->create();
    $phrases = Phrase::factory()->count(3)->create(['lesson_id' => $lesson->id]);
    
    $response = $this->get(route('lessons.show', $lesson->slug));
    
    $response->assertStatus(200);
    
    foreach ($phrases as $phrase) {
        $response->assertSee($phrase->japanese);
        $response->assertSee($phrase->english);
    }
});

test('lesson show page displays dialogues', function () {
    $lesson = Lesson::factory()->create();
    $dialogue = Dialogue::factory()->create([
        'lesson_id' => $lesson->id,
        'title' => 'Test Dialogue',
        'content' => [
            ['speaker' => 'A', 'line' => 'Hello'],
            ['speaker' => 'B', 'line' => 'Hi there'],
        ],
    ]);
    
    $response = $this->get(route('lessons.show', $lesson->slug));
    
    $response->assertStatus(200);
    $response->assertSee('Test Dialogue');
    $response->assertSee('Hello');
    $response->assertSee('Hi there');
});

test('lesson show page includes navigation to previous and next lessons', function () {
    $lesson1 = Lesson::factory()->create(['order' => 1]);
    $lesson2 = Lesson::factory()->create(['order' => 2]);
    $lesson3 = Lesson::factory()->create(['order' => 3]);
    
    $response = $this->get(route('lessons.show', $lesson2->slug));
    
    $response->assertStatus(200);
    $response->assertViewHas('previousLesson', $lesson1);
    $response->assertViewHas('nextLesson', $lesson3);
});

test('lesson show page handles first lesson without previous', function () {
    $lesson1 = Lesson::factory()->create(['order' => 1]);
    $lesson2 = Lesson::factory()->create(['order' => 2]);
    
    $response = $this->get(route('lessons.show', $lesson1->slug));
    
    $response->assertStatus(200);
    $response->assertViewHas('previousLesson', null);
    $response->assertViewHas('nextLesson', $lesson2);
});

test('lesson show page handles last lesson without next', function () {
    $lesson1 = Lesson::factory()->create(['order' => 1]);
    $lesson2 = Lesson::factory()->create(['order' => 2]);
    
    $response = $this->get(route('lessons.show', $lesson2->slug));
    
    $response->assertStatus(200);
    $response->assertViewHas('previousLesson', $lesson1);
    $response->assertViewHas('nextLesson', null);
});
