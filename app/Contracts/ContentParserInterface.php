<?php

namespace App\Contracts;

use Illuminate\Support\Collection;

interface ContentParserInterface
{
    /**
     * Parse markdown content and extract all structured data
     *
     * @param string $markdown The markdown content to parse
     * @return array Array containing parsed data structure
     */
    public function parse(string $markdown): array;

    /**
     * Extract phrases from markdown phrase tables
     *
     * @param string $markdown The markdown content containing phrase tables
     * @return Collection Collection of phrase arrays with japanese, romaji, english, notes, category
     */
    public function extractPhrases(string $markdown): Collection;

    /**
     * Extract dialogues with speaker/line parsing
     *
     * @param string $markdown The markdown content containing dialogues
     * @return Collection Collection of dialogue arrays with title and content
     */
    public function extractDialogues(string $markdown): Collection;

    /**
     * Extract drills with type detection
     *
     * @param string $markdown The markdown content containing drills
     * @return Collection Collection of drill arrays with type, title, content, and answers
     */
    public function extractDrills(string $markdown): Collection;

    /**
     * Extract shadowing exercises
     *
     * @param string $markdown The markdown content containing shadowing exercises
     * @return Collection Collection of shadowing exercise arrays
     */
    public function extractShadowingExercises(string $markdown): Collection;
}
