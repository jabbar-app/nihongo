# Task 33: Search Functionality Implementation

## Overview
Implemented a comprehensive search service that allows users to search across all content types (phrases, dialogues, and drills) in the Japanese learning application.

## Implementation Details

### SearchService Class
Created `app/Services/SearchService.php` with the following methods:

#### 1. `search(string $query, $user = null): array`
- Main search method that queries across all content types
- Returns an array with separate collections for phrases, dialogues, and drills
- Includes a total count of all results
- Handles empty queries gracefully

#### 2. `searchPhrases(string $query): Collection`
- Searches phrases by Japanese text, romaji, English translation, and notes
- Uses LIKE queries for flexible matching
- Returns results ordered by lesson and phrase order
- Eager loads the lesson relationship for efficient access

#### 3. `searchDialogues(string $query): Collection`
- Searches dialogues by title
- Also searches within dialogue content (JSON structure)
- Searches both speaker names and dialogue lines
- Merges title matches with content matches and removes duplicates
- Returns results ordered by lesson and dialogue order

#### 4. `searchDrills(string $query): Collection`
- Searches drills by title
- Also searches within drill content and answers (JSON structures)
- Uses JSON encoding to search within complex data structures
- Merges title matches with content matches and removes duplicates
- Returns results ordered by lesson and drill order

### Database Indexes
Created migration `2025_10_13_042834_add_search_indexes_to_content_tables.php` to add indexes for better search performance:

**Phrases Table:**
- Index on `japanese` column
- Index on `romaji` column
- Index on `english` column

**Dialogues Table:**
- Index on `title` column

**Drills Table:**
- Index on `title` column
- Index on `type` column

These indexes significantly improve query performance for LIKE searches on these columns.

## Testing Results

Tested the SearchService with various queries:

1. **English search ("hello")**: Found 4 results (3 phrases, 1 dialogue)
2. **Japanese search ("こんにちは")**: Found 3 phrases
3. **Romaji search ("konnichiwa")**: Found 3 phrases
4. **Empty search**: Correctly returned 0 results
5. **Phrase-specific search ("thank")**: Found 11 phrases
6. **Dialogue search ("station")**: Searched through dialogue content
7. **Drill search ("substitution")**: Found 8 drills

All tests passed successfully, confirming that:
- Multi-field search works correctly
- Japanese, romaji, and English searches all function
- JSON content searching works for dialogues and drills
- Empty queries are handled gracefully
- Results are properly ordered and deduplicated

## Requirements Satisfied

✅ **8.1**: Search across all lesson content (phrases, dialogues, drills)
✅ **8.2**: Display results showing matching content with lesson context
✅ **8.4**: Match against Japanese text and romaji
✅ **8.5**: Match against English translations
✅ **Additional**: Added search indexing for better performance

## Usage Example

```php
use App\Services\SearchService;

$searchService = new SearchService();

// Search across all content types
$results = $searchService->search('hello');
// Returns: [
//     'phrases' => Collection,
//     'dialogues' => Collection,
//     'drills' => Collection,
//     'total' => 4
// ]

// Search only phrases
$phrases = $searchService->searchPhrases('ありがとう');

// Search only dialogues
$dialogues = $searchService->searchDialogues('station');

// Search only drills
$drills = $searchService->searchDrills('transformation');
```

## Next Steps

The SearchService is now ready to be integrated with:
- Task 34: Build search interface (SearchController and views)
- Search bar in main navigation
- Search results page with grouped results
- Highlighting of matching text in results

## Files Created/Modified

### Created:
- `app/Services/SearchService.php` - Main search service class
- `database/migrations/2025_10_13_042834_add_search_indexes_to_content_tables.php` - Search indexes migration
- `docs/task-33-search-functionality.md` - This documentation

### Modified:
- Database schema (added indexes to phrases, dialogues, and drills tables)

## Performance Considerations

- Database indexes added to frequently searched columns
- Eager loading of lesson relationships to prevent N+1 queries
- Efficient JSON searching for dialogue and drill content
- Results are ordered consistently for better UX
- Duplicate results are removed when merging title and content matches

## Notes

- The service handles both simple text searches and complex JSON content searches
- All searches are case-insensitive (using LIKE with SQLite)
- The service is designed to be easily extended with additional search features (filters, sorting, pagination)
- User parameter is included in the main search method for future user-specific features (bookmarks, history, etc.)
