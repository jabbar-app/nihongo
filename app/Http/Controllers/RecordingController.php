<?php

namespace App\Http\Controllers;

use App\Models\UserRecording;
use App\Services\AudioService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class RecordingController extends Controller
{
    public function __construct(
        private AudioService $audioService
    ) {}

    /**
     * Store a new recording.
     */
    public function store(Request $request)
    {
        $request->validate([
            'audio' => 'required|file|mimes:webm,mp4,ogg,wav,mp3|max:10240', // 10MB max
            'recordable_type' => 'required|string',
            'recordable_id' => 'required|integer',
            'duration' => 'nullable|integer|min:0',
        ]);

        $recording = $this->audioService->storeRecording(
            $request->user(),
            $request->file('audio'),
            $request->input('recordable_type'),
            $request->input('recordable_id'),
            $request->input('duration', 0)
        );

        return response()->json([
            'success' => true,
            'recording' => [
                'id' => $recording->id,
                'duration' => $recording->duration_seconds,
                'created_at' => $recording->created_at->diffForHumans(),
                'url' => $this->audioService->getRecordingUrl($recording),
            ],
        ]);
    }

    /**
     * Serve a recording file.
     */
    public function serve(UserRecording $recording)
    {
        // Ensure the user owns this recording
        if ($recording->user_id !== auth()->id()) {
            abort(403, 'Unauthorized access to recording');
        }

        // Check if file exists
        if (!Storage::disk('private')->exists($recording->file_path)) {
            abort(404, 'Recording file not found');
        }

        // Get file contents
        $file = Storage::disk('private')->get($recording->file_path);
        $mimeType = Storage::disk('private')->mimeType($recording->file_path);

        // Return streamed response
        return response($file, 200)
            ->header('Content-Type', $mimeType)
            ->header('Content-Disposition', 'inline; filename="recording-' . $recording->id . '"');
    }

    /**
     * Delete a recording.
     */
    public function destroy(UserRecording $recording)
    {
        // Ensure the user owns this recording
        if ($recording->user_id !== auth()->id()) {
            abort(403, 'Unauthorized access to recording');
        }

        $this->audioService->deleteRecording($recording);

        return response()->json([
            'success' => true,
            'message' => 'Recording deleted successfully',
        ]);
    }

    /**
     * Get user's storage usage.
     */
    public function storageUsage(Request $request)
    {
        $user = $request->user();
        
        $recordings = UserRecording::where('user_id', $user->id)->get();
        $totalSize = 0;
        
        foreach ($recordings as $recording) {
            if (Storage::disk('private')->exists($recording->file_path)) {
                $totalSize += Storage::disk('private')->size($recording->file_path);
            }
        }

        return response()->json([
            'total_recordings' => $recordings->count(),
            'total_size_bytes' => $totalSize,
            'total_size_mb' => round($totalSize / 1024 / 1024, 2),
        ]);
    }
}
