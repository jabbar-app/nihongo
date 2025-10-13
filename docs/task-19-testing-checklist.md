# Task 19 Testing Checklist

## Recording Storage and Playback Testing

### Pre-Testing Setup

- [ ] Ensure database is migrated
- [ ] Ensure at least one user account exists
- [ ] Ensure at least one shadowing exercise exists
- [ ] Verify storage directory exists: `storage/app/private/`
- [ ] Verify storage directory is writable

### Backend Testing

#### RecordingController Tests

**Store Recording (POST /recordings)**
- [ ] Upload valid audio file (webm format)
- [ ] Verify file is saved to `storage/app/private/recordings/{user_id}/`
- [ ] Verify database record is created in `user_recordings` table
- [ ] Verify response includes recording ID and URL
- [ ] Test with invalid file type (should fail)
- [ ] Test with file too large (>10MB, should fail)
- [ ] Test without authentication (should fail with 401)

**Serve Recording (GET /recordings/{recording})**
- [ ] Access own recording (should succeed)
- [ ] Access another user's recording (should fail with 403)
- [ ] Access non-existent recording (should fail with 404)
- [ ] Verify audio file is served correctly
- [ ] Verify MIME type is correct
- [ ] Test without authentication (should fail with 401)

**Delete Recording (DELETE /recordings/{recording})**
- [ ] Delete own recording (should succeed)
- [ ] Verify file is removed from storage
- [ ] Verify database record is deleted
- [ ] Delete another user's recording (should fail with 403)
- [ ] Delete non-existent recording (should fail with 404)
- [ ] Test without authentication (should fail with 401)

**Storage Usage (GET /recordings-usage)**
- [ ] Get storage usage for user with recordings
- [ ] Verify total_recordings count is correct
- [ ] Verify total_size_bytes is calculated correctly
- [ ] Verify total_size_mb is calculated correctly
- [ ] Get storage usage for user with no recordings
- [ ] Test without authentication (should fail with 401)

#### AudioService Tests

**storeRecording()**
- [ ] Store recording with duration
- [ ] Store recording without duration (defaults to 0)
- [ ] Verify file is saved to correct path
- [ ] Verify UserRecording model is created
- [ ] Verify all fields are populated correctly

**getUserStorageUsage()**
- [ ] Calculate usage for user with multiple recordings
- [ ] Calculate usage for user with no recordings
- [ ] Verify file sizes are summed correctly
- [ ] Verify MB conversion is accurate

**deleteRecording()**
- [ ] Delete recording and verify file is removed
- [ ] Delete recording and verify database record is removed
- [ ] Verify method returns true on success

**getRecordingUrl()**
- [ ] Generate URL for recording
- [ ] Verify URL format is correct
- [ ] Verify URL includes recording ID

### Frontend Testing

#### Shadowing Exercise Page

**Recording Controls**
- [ ] Navigate to shadowing exercise page
- [ ] Verify recording section is visible
- [ ] Verify browser compatibility check works
- [ ] Click "Start Recording" and allow microphone access
- [ ] Verify recording indicator appears
- [ ] Verify timer starts counting
- [ ] Click "Stop" and verify recording stops
- [ ] Verify playback controls appear
- [ ] Click "Play" and verify audio plays
- [ ] Click "Pause" and verify audio pauses
- [ ] Verify progress bar updates during playback
- [ ] Click "Download" and verify file downloads
- [ ] Click "Re-record" and verify new recording starts

**Save Functionality**
- [ ] Record audio and click "Save"
- [ ] Verify loading state appears (button disabled, spinner)
- [ ] Verify success message appears after save
- [ ] Verify page reloads after 2 seconds
- [ ] Verify new recording appears in practice history
- [ ] Test save with network error (should show error message)
- [ ] Test save without recording (button should not be visible)

**Practice History**
- [ ] Verify section appears when recordings exist
- [ ] Verify section is hidden when no recordings exist
- [ ] Verify recording count is displayed correctly
- [ ] Verify each recording shows date and time
- [ ] Verify duration is displayed (if available)
- [ ] Verify "X time ago" format is correct

**Play Recording from History**
- [ ] Click play button on a recording
- [ ] Verify audio plays correctly
- [ ] Click play on another recording while one is playing
- [ ] Verify previous audio stops and new one plays
- [ ] Test with missing file (should show error)

**Download Recording from History**
- [ ] Click download button
- [ ] Verify file downloads with correct name
- [ ] Verify downloaded file can be played

**Delete Recording from History**
- [ ] Click delete button
- [ ] Verify confirmation dialog appears
- [ ] Click "Cancel" and verify recording is not deleted
- [ ] Click delete button again
- [ ] Click "OK" and verify recording is deleted
- [ ] Verify page reloads
- [ ] Verify recording is removed from list
- [ ] Test delete with network error (should show error)

#### Error Handling

**Microphone Permission**
- [ ] Deny microphone permission
- [ ] Verify error message appears
- [ ] Verify error message is helpful
- [ ] Allow permission and verify recording works

**Browser Compatibility**
- [ ] Test in unsupported browser
- [ ] Verify compatibility warning appears
- [ ] Test in Chrome (should work)
- [ ] Test in Firefox (should work)
- [ ] Test in Edge (should work)
- [ ] Test in Safari (should work with limitations)

**Network Errors**
- [ ] Disconnect network and try to save
- [ ] Verify error message appears
- [ ] Reconnect and verify save works
- [ ] Test with slow network (verify loading state)

### Integration Testing

**Complete User Flow**
1. [ ] Login as user
2. [ ] Navigate to shadowing exercise
3. [ ] Record audio (10-15 seconds)
4. [ ] Play back recording
5. [ ] Save recording to server
6. [ ] Verify success message
7. [ ] Verify recording appears in history
8. [ ] Play recording from history
9. [ ] Download recording
10. [ ] Delete recording
11. [ ] Verify recording is removed

**Multiple Recordings Flow**
1. [ ] Create 3 recordings for same exercise
2. [ ] Verify all appear in history
3. [ ] Verify they are ordered by date (newest first)
4. [ ] Play each recording
5. [ ] Delete middle recording
6. [ ] Verify other two remain
7. [ ] Check storage usage endpoint
8. [ ] Verify count and size are correct

**Cross-Exercise Flow**
1. [ ] Create recording for exercise A
2. [ ] Navigate to exercise B
3. [ ] Verify exercise A recording doesn't appear
4. [ ] Create recording for exercise B
5. [ ] Navigate back to exercise A
6. [ ] Verify only exercise A recording appears

### Security Testing

**Authorization**
- [ ] Login as User A
- [ ] Create recording
- [ ] Note recording ID
- [ ] Logout and login as User B
- [ ] Try to access User A's recording URL
- [ ] Verify 403 Forbidden error
- [ ] Try to delete User A's recording
- [ ] Verify 403 Forbidden error

**File Upload Security**
- [ ] Try to upload PHP file (should fail)
- [ ] Try to upload executable (should fail)
- [ ] Try to upload image (should fail)
- [ ] Try to upload text file (should fail)
- [ ] Try to upload valid audio (should succeed)

**CSRF Protection**
- [ ] Try to save recording without CSRF token
- [ ] Verify request fails
- [ ] Try to delete recording without CSRF token
- [ ] Verify request fails

### Performance Testing

**File Size**
- [ ] Upload 1MB file (should be fast)
- [ ] Upload 5MB file (should work)
- [ ] Upload 10MB file (should work, at limit)
- [ ] Upload 11MB file (should fail)

**Multiple Recordings**
- [ ] Create 10 recordings
- [ ] Verify page loads quickly
- [ ] Verify history displays correctly
- [ ] Check storage usage
- [ ] Delete all recordings
- [ ] Verify cleanup is complete

**Concurrent Operations**
- [ ] Start recording
- [ ] Open another tab and start recording
- [ ] Verify both work independently
- [ ] Save both recordings
- [ ] Verify both appear in history

### Browser Testing

**Chrome**
- [ ] All recording features work
- [ ] Audio playback works
- [ ] File upload works
- [ ] UI displays correctly

**Firefox**
- [ ] All recording features work
- [ ] Audio playback works
- [ ] File upload works
- [ ] UI displays correctly

**Edge**
- [ ] All recording features work
- [ ] Audio playback works
- [ ] File upload works
- [ ] UI displays correctly

**Safari**
- [ ] Recording features work (may have limitations)
- [ ] Audio playback works
- [ ] File upload works
- [ ] UI displays correctly

### Mobile Testing (Optional)

**Mobile Chrome**
- [ ] Recording works on mobile
- [ ] Touch controls work
- [ ] UI is responsive
- [ ] Audio playback works

**Mobile Safari**
- [ ] Recording works on mobile
- [ ] Touch controls work
- [ ] UI is responsive
- [ ] Audio playback works

### Database Verification

**After Creating Recording**
```sql
SELECT * FROM user_recordings ORDER BY created_at DESC LIMIT 1;
```
- [ ] Verify user_id is correct
- [ ] Verify recordable_type is 'shadowing'
- [ ] Verify recordable_id matches exercise
- [ ] Verify file_path is set
- [ ] Verify duration_seconds is set
- [ ] Verify timestamps are set

**After Deleting Recording**
```sql
SELECT * FROM user_recordings WHERE id = ?;
```
- [ ] Verify record is deleted

### File System Verification

**After Creating Recording**
```bash
ls storage/app/private/recordings/{user_id}/
```
- [ ] Verify file exists
- [ ] Verify file has content (size > 0)
- [ ] Verify file is audio format

**After Deleting Recording**
```bash
ls storage/app/private/recordings/{user_id}/
```
- [ ] Verify file is deleted

### API Testing (Using Postman/Insomnia)

**POST /recordings**
```
POST /recordings
Content-Type: multipart/form-data
Authorization: Bearer {token}

audio: [audio file]
recordable_type: shadowing
recordable_id: 1
duration: 45
```
- [ ] Verify 200 response
- [ ] Verify JSON response structure
- [ ] Verify recording is created

**GET /recordings/{id}**
```
GET /recordings/1
Authorization: Bearer {token}
```
- [ ] Verify 200 response
- [ ] Verify audio file is returned
- [ ] Verify Content-Type header

**DELETE /recordings/{id}**
```
DELETE /recordings/1
Authorization: Bearer {token}
```
- [ ] Verify 200 response
- [ ] Verify success message
- [ ] Verify recording is deleted

**GET /recordings-usage**
```
GET /recordings-usage
Authorization: Bearer {token}
```
- [ ] Verify 200 response
- [ ] Verify JSON response structure
- [ ] Verify statistics are accurate

### Edge Cases

**Empty States**
- [ ] User with no recordings sees appropriate message
- [ ] Exercise with no recordings shows empty state

**Long Recordings**
- [ ] Record for 5 minutes
- [ ] Verify save works
- [ ] Verify playback works
- [ ] Verify file size is reasonable

**Special Characters**
- [ ] Exercise with special characters in title
- [ ] Verify recording saves correctly
- [ ] Verify playback works

**Rapid Actions**
- [ ] Click save multiple times rapidly
- [ ] Verify only one recording is created
- [ ] Click delete multiple times rapidly
- [ ] Verify only one delete request is sent

### Cleanup

After testing:
- [ ] Delete test recordings from database
- [ ] Delete test files from storage
- [ ] Clear browser cache
- [ ] Clear Laravel cache

### Test Results

**Date Tested**: ___________
**Tester**: ___________
**Browser**: ___________
**OS**: ___________

**Overall Result**: [ ] Pass [ ] Fail

**Issues Found**:
1. ___________
2. ___________
3. ___________

**Notes**:
___________
___________
___________
