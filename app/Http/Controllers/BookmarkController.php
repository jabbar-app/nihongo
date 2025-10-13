<?php

namespace App\Http\Controllers;

use App\Models\Bookmark;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BookmarkController extends Controller
{
    /**
     * Display all bookmarks for the authenticated user
     */
    public function index()
    {
        $user = Auth::user();
        
        $bookmarks = Bookmark::where('user_id', $user->id)
            ->with('bookmarkable')
            ->latest()
            ->get()
            ->groupBy('bookmarkable_type');

        return view('bookmarks.index', compact('bookmarks'));
    }

    /**
     * Toggle bookmark for a content item
     */
    public function toggle(Request $request)
    {
        $request->validate([
            'type' => 'required|string|in:lesson,phrase,dialogue,drill,shadowing',
            'id' => 'required|integer',
        ]);

        $user = Auth::user();
        $type = $this->getModelClass($request->type);
        $id = $request->id;

        $bookmark = Bookmark::where('user_id', $user->id)
            ->where('bookmarkable_type', $type)
            ->where('bookmarkable_id', $id)
            ->first();

        if ($bookmark) {
            $bookmark->delete();
            return response()->json([
                'bookmarked' => false,
                'message' => 'Bookmark removed',
            ]);
        } else {
            Bookmark::create([
                'user_id' => $user->id,
                'bookmarkable_type' => $type,
                'bookmarkable_id' => $id,
            ]);
            return response()->json([
                'bookmarked' => true,
                'message' => 'Bookmark added',
            ]);
        }
    }

    /**
     * Update bookmark notes
     */
    public function updateNotes(Request $request, Bookmark $bookmark)
    {
        $this->authorize('update', $bookmark);

        $request->validate([
            'notes' => 'nullable|string|max:1000',
        ]);

        $bookmark->update([
            'notes' => $request->notes,
        ]);

        return response()->json([
            'message' => 'Notes updated successfully',
        ]);
    }

    /**
     * Delete a bookmark
     */
    public function destroy(Bookmark $bookmark)
    {
        $this->authorize('delete', $bookmark);

        $bookmark->delete();

        return redirect()->route('bookmarks.index')
            ->with('success', 'Bookmark removed successfully');
    }

    /**
     * Get model class from type string
     */
    private function getModelClass(string $type): string
    {
        return match ($type) {
            'lesson' => \App\Models\Lesson::class,
            'phrase' => \App\Models\Phrase::class,
            'dialogue' => \App\Models\Dialogue::class,
            'drill' => \App\Models\Drill::class,
            'shadowing' => \App\Models\ShadowingExercise::class,
        };
    }
}
