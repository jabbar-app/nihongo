<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Collection;

class RecentlyViewedService
{
    private const CACHE_KEY_PREFIX = 'recently_viewed:';
    private const MAX_ITEMS = 10;
    private const CACHE_TTL = 86400; // 24 hours

    /**
     * Track a content view for a user
     */
    public function trackView(User $user, string $type, int $id, array $metadata = []): void
    {
        $cacheKey = $this->getCacheKey($user->id);
        $recentItems = $this->getRecentItems($user);

        // Create new item
        $newItem = [
            'type' => $type,
            'id' => $id,
            'viewed_at' => now()->toIso8601String(),
            'metadata' => $metadata,
        ];

        // Remove existing entry for this item if it exists
        $recentItems = $recentItems->reject(function ($item) use ($type, $id) {
            return $item['type'] === $type && $item['id'] === $id;
        });

        // Add new item to the beginning
        $recentItems->prepend($newItem);

        // Keep only the most recent items
        $recentItems = $recentItems->take(self::MAX_ITEMS);

        // Store in cache
        Cache::put($cacheKey, $recentItems->toArray(), self::CACHE_TTL);
    }

    /**
     * Get recent items for a user
     */
    public function getRecentItems(User $user, int $limit = 5): Collection
    {
        $cacheKey = $this->getCacheKey($user->id);
        $items = Cache::get($cacheKey, []);

        return collect($items)->take($limit);
    }

    /**
     * Get recent items with loaded models
     */
    public function getRecentItemsWithModels(User $user, int $limit = 5): Collection
    {
        $recentItems = $this->getRecentItems($user, $limit);

        return $recentItems->map(function ($item) {
            $model = $this->loadModel($item['type'], $item['id']);
            
            if (!$model) {
                return null;
            }

            return [
                'type' => $item['type'],
                'model' => $model,
                'viewed_at' => $item['viewed_at'],
                'metadata' => $item['metadata'] ?? [],
            ];
        })->filter();
    }

    /**
     * Clear recent items for a user
     */
    public function clearRecentItems(User $user): void
    {
        $cacheKey = $this->getCacheKey($user->id);
        Cache::forget($cacheKey);
    }

    /**
     * Get the last viewed item of a specific type
     */
    public function getLastViewedOfType(User $user, string $type): ?array
    {
        $recentItems = $this->getRecentItems($user, self::MAX_ITEMS);

        $item = $recentItems->firstWhere('type', $type);

        if (!$item) {
            return null;
        }

        $model = $this->loadModel($item['type'], $item['id']);

        if (!$model) {
            return null;
        }

        return [
            'type' => $item['type'],
            'model' => $model,
            'viewed_at' => $item['viewed_at'],
            'metadata' => $item['metadata'] ?? [],
        ];
    }

    /**
     * Load the model for a given type and id
     */
    private function loadModel(string $type, int $id)
    {
        return match ($type) {
            'lesson' => \App\Models\Lesson::find($id),
            'phrase' => \App\Models\Phrase::with('lesson')->find($id),
            'dialogue' => \App\Models\Dialogue::with('lesson')->find($id),
            'drill' => \App\Models\Drill::with('lesson')->find($id),
            'shadowing' => \App\Models\ShadowingExercise::with('lesson')->find($id),
            default => null,
        };
    }

    /**
     * Get cache key for a user
     */
    private function getCacheKey(int $userId): string
    {
        return self::CACHE_KEY_PREFIX . $userId;
    }
}
