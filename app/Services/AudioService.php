<?php

namespace App\Services;

use App\Models\User;
use App\Models\UserRecording;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class AudioService
{
    /**
     * Generate a speech URL for text-to-speech.
     * This returns a data structure that the frontend can use with Web Speech API.
     * 
     * @param string $text The text to convert to speech
     * @param string $lang The language code (default: ja-JP for Japanese)
     * @return array
     */
    public function generateSpeechUrl(string $text, string $lang = 'ja-JP'): array
    {
        return [
            'text' => $text,
            'lang' => $lang,
            'type' => 'web-speech-api'
        ];
    }

    /**
     * Store a user recording.
     * 
     * @param User $user
     * @param UploadedFile $audio
     * @param string $type The type of recording (e.g., 'shadowing', 'flashcard')
     * @param int $referenceId The ID of the related model
     * @param int $duration Duration in seconds (optional)
     * @return UserRecording
     */
    public function storeRecording(
        User $user,
        UploadedFile $audio,
        string $type,
        int $referenceId,
        int $duration = 0
    ): UserRecording {
        // Store the audio file in a private directory
        $path = $audio->store("recordings/{$user->id}", 'private');

        // Create the recording record
        return UserRecording::create([
            'user_id' => $user->id,
            'recordable_type' => $type,
            'recordable_id' => $referenceId,
            'file_path' => $path,
            'duration_seconds' => $duration,
        ]);
    }

    /**
     * Get the URL to serve a recording.
     * 
     * @param UserRecording $recording
     * @return string
     */
    public function getRecordingUrl(UserRecording $recording): string
    {
        return route('recordings.serve', ['recording' => $recording->id]);
    }

    /**
     * Delete a recording.
     * 
     * @param UserRecording $recording
     * @return bool
     */
    public function deleteRecording(UserRecording $recording): bool
    {
        // Delete the file from storage
        Storage::disk('private')->delete($recording->file_path);

        // Delete the database record
        return $recording->delete();
    }

    /**
     * Get user's total storage usage.
     * 
     * @param User $user
     * @return array
     */
    public function getUserStorageUsage(User $user): array
    {
        $recordings = UserRecording::where('user_id', $user->id)->get();
        $totalSize = 0;
        
        foreach ($recordings as $recording) {
            if (Storage::disk('private')->exists($recording->file_path)) {
                $totalSize += Storage::disk('private')->size($recording->file_path);
            }
        }

        return [
            'total_recordings' => $recordings->count(),
            'total_size_bytes' => $totalSize,
            'total_size_mb' => round($totalSize / 1024 / 1024, 2),
        ];
    }
}
