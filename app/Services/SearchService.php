<?php

namespace App\Services;

use App\Models\Phrase;
use App\Models\Dialogue;
use App\Models\Drill;
use Illuminate\Support\Collection;

class SearchService
{
    /**
     * Search across all content types
     *
     * @param string $query
     * @param \App\Models\User|null $user
     * @return array
     */
    public function search(string $query, $user = null): array
    {
        $query = trim($query);
        
        if (empty($query)) {
            return [
                'phrases' => collect(),
                'dialogues' => collect(),
                'drills' => collect(),
                'total' => 0,
            ];
        }

        $phrases = $this->searchPhrases($query);
        $dialogues = $this->searchDialogues($query);
        $drills = $this->searchDrills($query);

        return [
            'phrases' => $phrases,
            'dialogues' => $dialogues,
            'drills' => $drills,
            'total' => $phrases->count() + $dialogues->count() + $drills->count(),
        ];
    }

    /**
     * Search phrases with Japanese, romaji, and English matching
     *
     * @param string $query
     * @return \Illuminate\Support\Collection
     */
    public function searchPhrases(string $query): Collection
    {
        return Phrase::with('lesson')
            ->where(function ($q) use ($query) {
                $q->where('japanese', 'LIKE', "%{$query}%")
                  ->orWhere('romaji', 'LIKE', "%{$query}%")
                  ->orWhere('english', 'LIKE', "%{$query}%")
                  ->orWhere('notes', 'LIKE', "%{$query}%");
            })
            ->orderBy('lesson_id')
            ->orderBy('order')
            ->get();
    }

    /**
     * Search dialogues with content search
     *
     * @param string $query
     * @return \Illuminate\Support\Collection
     */
    public function searchDialogues(string $query): Collection
    {
        // Get all dialogues and filter by content
        // Since content is JSON, we need to search within the JSON structure
        $dialogues = Dialogue::with('lesson')
            ->where('title', 'LIKE', "%{$query}%")
            ->orderBy('lesson_id')
            ->orderBy('order')
            ->get();

        // Also search within dialogue content (JSON array)
        $contentMatches = Dialogue::with('lesson')
            ->get()
            ->filter(function ($dialogue) use ($query) {
                if (empty($dialogue->content)) {
                    return false;
                }
                
                // Search through dialogue lines
                foreach ($dialogue->content as $line) {
                    if (isset($line['line']) && stripos($line['line'], $query) !== false) {
                        return true;
                    }
                    if (isset($line['speaker']) && stripos($line['speaker'], $query) !== false) {
                        return true;
                    }
                }
                
                return false;
            });

        // Merge and remove duplicates
        return $dialogues->merge($contentMatches)->unique('id');
    }

    /**
     * Search drills with title and content search
     *
     * @param string $query
     * @return \Illuminate\Support\Collection
     */
    public function searchDrills(string $query): Collection
    {
        // Search by title first
        $drills = Drill::with('lesson')
            ->where('title', 'LIKE', "%{$query}%")
            ->orderBy('lesson_id')
            ->orderBy('order')
            ->get();

        // Also search within drill content (JSON)
        $contentMatches = Drill::with('lesson')
            ->get()
            ->filter(function ($drill) use ($query) {
                // Search in content array
                if (!empty($drill->content)) {
                    $contentString = json_encode($drill->content);
                    if (stripos($contentString, $query) !== false) {
                        return true;
                    }
                }
                
                // Search in answers array
                if (!empty($drill->answers)) {
                    $answersString = json_encode($drill->answers);
                    if (stripos($answersString, $query) !== false) {
                        return true;
                    }
                }
                
                return false;
            });

        // Merge and remove duplicates
        return $drills->merge($contentMatches)->unique('id');
    }
}
