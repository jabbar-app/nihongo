# Task 19: Recording Storage and Playback Implementation

## Overview

This document describes the implementation of recording storage and playback functionality for the Japanese Learning Application. Users can now save their shadowing practice recordings to the server, view their practice history, and manage their recordings.

---

## Features Implemented

### 1. Recording Storage

- **Private Storage**: Recordings are stored in `storage/app/private/recordings/{user_id}/` directory
- **Secure Access**: Files are not publicly accessible and require authentication
- **File Validation**: Supports webm, mp4, ogg, wav, and mp3 formats with 10MB max size
- **Metadata Tracking**: Duration, timestamps, and relationships are stored in the database

### 2. Recording Playback

- **Secure Serving**: Recordings are served through authenticated routes
- **Authorization**: Users can only access their own recordings
- **Inline Playback**: Audio files are served with inline disposition for browser playback

### 3. Recording Management

- **Save Functionality**: Users can save recordings directly from the shadowing exercise page
- **Practice History**: All past recordings are displayed with timestamps and durations
- **Delete Functionality**: Users can delete old recordings with confirmation
- **Download Option**: Users can download their recordings for offline use

### 4. Storage Tracking

- **Usage Monitoring**: Track total storage used per user
- **Recording Count**: Display number of recordings per user
- **Size Calculation**: Calculate total storage in bytes and MB

---

## API Endpoints

### POST /recordings

Store a new recording.

**Authentication**: Required

**Request**:
```
Content-Type: multipart/form-data

audio: File (required, max 10MB)
recordable_type: string (required, e.g., "shadowing")
recordable_id: integer (required)
duration: integer (optional, seconds)
```

**Response**:
```json
{
  "success": true,
  "recording": {
    "id": 1,
    "duration": 45,
    "created_at": "2 minutes ago",
    "url": "/recordings/1"
  }
}
```

### GET /recordings/{recording}

Serve a recording file.

**Authentication**: Required (must own the recording)

**Response**: Audio file stream

### DELETE /recordings/{recording}

Delete a recording.

**Authentication**: Required (must own the recording)

**Response**:
```json
{
  "success": true,
  "message": "Recording deleted successfully"
}
```

### GET /recordings-usage

Get user's storage usage statistics.

**Authentication**: Required

**Response**:
```json
{
  "total_recordings": 5,
  "total_size_bytes": 2457600,
  "total_size_mb": 2.34
}
```

---

## Database Schema

### user_recordings Table

| Column | Type | Description |
|--------|------|-------------|
| id | bigint | Primary key |
| user_id | bigint | Foreign key to users |
| recordable_type | string | Polymorphic type (e.g., "shadowing") |
| recordable_id | bigint | Polymorphic ID |
| file_path | string | Path to audio file in storage |
| duration_seconds | integer | Recording duration |
| created_at | timestamp | Creation timestamp |
| updated_at | timestamp | Update timestamp |

---

## Frontend Implementation

### Recording Controls

The shadowing exercise page includes:

1. **Record Button**: Start recording with microphone permission
2. **Stop Button**: Stop recording and create audio blob
3. **Playback Controls**: Play/pause recorded audio with progress bar
4. **Save Button**: Upload recording to server
5. **Re-record Button**: Discard and start new recording

### Practice History

Displays all user's past recordings with:

- Recording date and time
- Duration (if available)
- Play button for inline playback
- Download button
- Delete button with confirmation

### User Feedback

- Success message when recording is saved
- Error messages for failed operations
- Loading states during save/delete operations
- Confirmation dialog before deletion

---

## Security Considerations

### Authorization

- All recording routes require authentication
- Users can only access their own recordings
- Ownership is verified before serving or deleting files

### File Validation

- File type validation (audio formats only)
- File size limit (10MB max)
- MIME type checking on upload

### Storage Security

- Files stored outside public directory
- No direct file access via URL
- Files served through controller with auth check

---

## Usage Example

### Saving a Recording

```javascript
// In Alpine.js component
async saveRecording() {
  const formData = new FormData();
  formData.append('audio', this.audioBlob, 'recording.webm');
  formData.append('recordable_type', 'shadowing');
  formData.append('recordable_id', exerciseId);
  formData.append('duration', this.recordingDuration);

  const response = await fetch('/recordings', {
    method: 'POST',
    headers: { 'X-CSRF-TOKEN': csrfToken },
    body: formData
  });

  const data = await response.json();
  if (data.success) {
    // Recording saved successfully
  }
}
```

### Playing a Recording

```javascript
function playRecording(url) {
  const audio = new Audio(url);
  audio.play();
}
```

### Deleting a Recording

```javascript
async function deleteRecording(recordingId) {
  if (!confirm('Delete this recording?')) return;

  const response = await fetch(`/recordings/${recordingId}`, {
    method: 'DELETE',
    headers: {
      'X-CSRF-TOKEN': csrfToken,
      'Accept': 'application/json'
    }
  });

  if (response.ok) {
    window.location.reload();
  }
}
```

---

## Service Layer

### AudioService Methods

#### storeRecording()

```php
public function storeRecording(
    User $user,
    UploadedFile $audio,
    string $type,
    int $referenceId,
    int $duration = 0
): UserRecording
```

Stores an audio file and creates a database record.

#### getRecordingUrl()

```php
public function getRecordingUrl(UserRecording $recording): string
```

Returns the URL to serve a recording.

#### deleteRecording()

```php
public function deleteRecording(UserRecording $recording): bool
```

Deletes both the file and database record.

#### getUserStorageUsage()

```php
public function getUserStorageUsage(User $user): array
```

Returns storage statistics for a user.

---

## Testing

### Manual Testing Steps

1. **Record Audio**:
   - Navigate to a shadowing exercise
   - Click "Start Recording"
   - Allow microphone access
   - Speak for a few seconds
   - Click "Stop"

2. **Save Recording**:
   - Click "Save" button
   - Verify success message appears
   - Check that recording appears in practice history

3. **Play Recording**:
   - Click play button on a saved recording
   - Verify audio plays correctly

4. **Delete Recording**:
   - Click delete button
   - Confirm deletion
   - Verify recording is removed from list

5. **Download Recording**:
   - Click download button
   - Verify file downloads correctly

### Storage Verification

```bash
# Check storage directory
ls storage/app/private/recordings/

# Check database records
php artisan tinker
>>> App\Models\UserRecording::count()
>>> App\Models\UserRecording::first()
```

---

## File Structure

```
app/
├── Http/
│   └── Controllers/
│       └── RecordingController.php    # Recording CRUD operations
├── Models/
│   └── UserRecording.php              # Recording model
└── Services/
    └── AudioService.php               # Audio storage logic

resources/
└── views/
    └── shadowing/
        └── show.blade.php             # Updated with recording UI

routes/
└── web.php                            # Recording routes

storage/
└── app/
    └── private/
        └── recordings/                # Recording storage
            └── {user_id}/
                └── *.webm
```

---

## Configuration

### Filesystem Configuration

```php
// config/filesystems.php
'private' => [
    'driver' => 'local',
    'root' => storage_path('app/private'),
    'serve' => true,
    'throw' => false,
    'report' => false,
],
```

### Validation Rules

```php
'audio' => 'required|file|mimes:webm,mp4,ogg,wav,mp3|max:10240'
```

---

## Future Enhancements

### Potential Improvements

1. **Audio Processing**:
   - Extract actual duration from audio file
   - Generate waveform visualization
   - Audio compression for storage optimization

2. **Advanced Features**:
   - Speech recognition for pronunciation feedback
   - Comparison with original audio
   - Progress tracking based on recordings

3. **Storage Management**:
   - Storage quota per user
   - Automatic cleanup of old recordings
   - Cloud storage integration (S3)

4. **Analytics**:
   - Recording frequency tracking
   - Practice time analytics
   - Most practiced exercises

---

## Troubleshooting

### Recording Not Saving

- Check microphone permissions
- Verify CSRF token is included
- Check file size (must be under 10MB)
- Verify storage directory is writable

### Recording Not Playing

- Check file exists in storage
- Verify user owns the recording
- Check browser audio support
- Verify MIME type is correct

### Storage Issues

- Check disk space
- Verify directory permissions
- Check storage path configuration

---

## Related Documentation

- [Task 18: Audio Recording Implementation](./task-18-audio-recording.md)
- [Audio Recording API Reference](./audio-recording-api.md)
- [Task 18 Testing Guide](./task-18-testing-guide.md)

---

## Version History

- **v1.0** (2025-10-10): Initial implementation
  - Recording storage and retrieval
  - Practice history display
  - Delete functionality
  - Storage usage tracking
