<?php

use App\Services\MarkdownContentParser;

test('it can extract phrases from markdown tables', function () {
    $markdown = <<<'MD'
### Orientation & Prefaces
| JP | Romaji | EN | Notes |
|---|---|---|---|
| すみません | Sumimasen | Excuse me | Polite opener |
| ありがとう | Arigatou | Thank you | Casual |

### Asking "Where is…?"
| JP | Romaji | EN | Notes |
|---|---|---|---|
| どこですか | Doko desu ka | Where is it? | Base pattern |
MD;

    $parser = new MarkdownContentParser();
    $phrases = $parser->extractPhrases($markdown);

    expect($phrases)->toHaveCount(3);
    expect($phrases->first())->toMatchArray([
        'japanese' => 'すみません',
        'romaji' => 'Sumimasen',
        'english' => 'Excuse me',
        'notes' => 'Polite opener',
        'category' => 'Orientation & Prefaces',
    ]);
});

test('it can extract dialogues with speakers', function () {
    $markdown = <<<'MD'
### D1: Platform and Transfer
A: すみません、渋谷へ行きたいのですが、どの線に乗ればいいですか。
B: 山手線です。何番ホームか電光掲示板で確認してください。
A: ありがとうございます。

---

### D2: Central Gate
A: 中央改札はどこですか。
B: あちらです。
MD;

    $parser = new MarkdownContentParser();
    $dialogues = $parser->extractDialogues($markdown);

    expect($dialogues)->toHaveCount(2);
    expect($dialogues->first()['title'])->toBe('Platform and Transfer');
    expect($dialogues->first()['content'])->toHaveCount(3);
    expect($dialogues->first()['content'][0])->toMatchArray([
        'speaker' => 'A',
        'line' => 'すみません、渋谷へ行きたいのですが、どの線に乗ればいいですか。',
    ]);
});

test('it can extract substitution drills', function () {
    $markdown = <<<'MD'
### Substitution Drills
Pattern: 「[場所] はどこですか。」
- 交番／観光案内所／東口／中央改札

Pattern: 「[道順] てください。」
- まっすぐ行っ／右に曲がっ／左に曲がっ
MD;

    $parser = new MarkdownContentParser();
    $drills = $parser->extractDrills($markdown);

    expect($drills)->toHaveCount(1);
    expect($drills->first()['type'])->toBe('substitution');
    expect($drills->first()['content'])->toHaveCount(2);
    expect($drills->first()['content'][0]['pattern'])->toContain('はどこですか');
    expect($drills->first()['content'][0]['substitutions'])->toContain('交番');
});

test('it can extract transformation drills', function () {
    $markdown = <<<'MD'
### Transformation Drills
- 丁寧 → カジュアル：
  - ここで合っていますか → ここで合ってる？
  - もう一度お願いします → もう一回お願い
MD;

    $parser = new MarkdownContentParser();
    $drills = $parser->extractDrills($markdown);

    expect($drills)->toHaveCount(1);
    expect($drills->first()['type'])->toBe('transformation');
    expect($drills->first()['content'][0]['from'])->toBe('丁寧');
    expect($drills->first()['content'][0]['to'])->toBe('カジュアル');
});

test('it can extract cloze drills with answers', function () {
    $markdown = <<<'MD'
### Cloze (Fill-in)
1. ________、最寄りの駅はどこですか。（Excuse me）
2. この道を ________ 行って、二つ目の角を右です。（straight）

Answers: 1) すみません 2) まっすぐ
MD;

    $parser = new MarkdownContentParser();
    $drills = $parser->extractDrills($markdown);

    expect($drills)->toHaveCount(1);
    expect($drills->first()['type'])->toBe('cloze');
    expect($drills->first()['content'])->toHaveCount(2);
    expect($drills->first()['content'][0]['hint'])->toBe('Excuse me');
    expect($drills->first()['answers'][0])->toBe('すみません');
});

test('it can extract shadowing exercises', function () {
    $markdown = <<<'MD'
### Scripted Mini-Shadow
S1 (Station):
- まもなく、三番線に各駅停車が参ります。
- 乗り換えのお客さまは中央改札をご利用ください。

S2 (Bus):
- 次は花町、花町です。
MD;

    $parser = new MarkdownContentParser();
    $exercises = $parser->extractShadowingExercises($markdown);

    expect($exercises)->toHaveCount(2);
    expect($exercises->first()['title'])->toBe('Station');
    expect($exercises->first()['content'])->toHaveCount(2);
    expect($exercises->first()['content'][0]['line'])->toContain('まもなく');
});

test('parse method returns all content types', function () {
    $markdown = <<<'MD'
### Test Section
| JP | Romaji | EN | Notes |
|---|---|---|---|
| テスト | Tesuto | Test | Note |
MD;
    
    $parser = new MarkdownContentParser();
    $result = $parser->parse($markdown);

    expect($result)->toHaveKeys(['phrases', 'dialogues', 'drills', 'shadowing']);
    expect($result['phrases'])->toBeInstanceOf(\Illuminate\Support\Collection::class);
    expect($result['dialogues'])->toBeInstanceOf(\Illuminate\Support\Collection::class);
    expect($result['drills'])->toBeInstanceOf(\Illuminate\Support\Collection::class);
    expect($result['shadowing'])->toBeInstanceOf(\Illuminate\Support\Collection::class);
});
