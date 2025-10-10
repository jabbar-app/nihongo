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
     * @return UserRecording
     */
    public function storeRecording(
        User $user,
        UploadedFile $audio,
        string $type,
        int $referenceId
    ): UserRecording {
        // Store the audio file in a private directory
        $path = $audio->store("recordings/{$user->id}", 'private');

        // Get audio duration if possible (this is a simplified version)
        $duration = 0; // In a real implementation, you'd extract this from the audio file

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
}
