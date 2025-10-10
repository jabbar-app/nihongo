<?php

use App\Services\MarkdownContentParser;

test('it can parse actual phrases content file', function () {
    $content = file_get_contents(__DIR__ . '/../../content/lang-001-directions/phrases.md');
    
    $parser = new MarkdownContentParser();
    $phrases = $parser->extractPhrases($content);

    expect($phrases->count())->toBeGreaterThan(50);
    expect($phrases->first())->toHaveKeys(['japanese', 'romaji', 'english', 'notes', 'category', 'order']);
});

test('it can parse actual dialogues content file', function () {
    $content = file_get_contents(__DIR__ . '/../../content/lang-001-directions/dialogues.md');
    
    $parser = new MarkdownContentParser();
    $dialogues = $parser->extractDialogues($content);

    expect($dialogues->count())->toBeGreaterThan(5);
    expect($dialogues->first())->toHaveKeys(['title', 'content', 'order']);
    expect($dialogues->first()['content'])->toBeArray();
    expect($dialogues->first()['content'][0])->toHaveKeys(['speaker', 'line']);
});

test('it can parse actual drills content file', function () {
    $content = file_get_contents(__DIR__ . '/../../content/lang-001-directions/drills-and-missions.md');
    
    $parser = new MarkdownContentParser();
    $drills = $parser->extractDrills($content);

    expect($drills->count())->toBeGreaterThan(0);
    expect($drills->first())->toHaveKeys(['type', 'title', 'content', 'answers', 'order']);
});

test('it can parse actual shadowing content file', function () {
    $content = file_get_contents(__DIR__ . '/../../content/lang-001-directions/shadowing.md');
    
    $parser = new MarkdownContentParser();
    $exercises = $parser->extractShadowingExercises($content);

    expect($exercises->count())->toBeGreaterThan(0);
    expect($exercises->first())->toHaveKeys(['title', 'content', 'order']);
});
