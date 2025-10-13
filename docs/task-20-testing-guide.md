# Task 20: Shadowing Completion Tracking - Testing Guide

## Prerequisites
- User must be logged in
- Database must have lessons and shadowing exercises seeded
- User must have a profile record

## Manual Testing Steps

### 1. View Shadowing Exercise
1. Navigate to a lesson (e.g., `/lessons/lang-001-directions`)
2. Click on the "Shadowing" tab
3. Click on a shadowing exercise
4. Verify the exercise page loads with:
   - Exercise title and lesson info
   - Audio playback controls
   - Script display
   - Recording section
   - Practice tips

### 2. Test Completion Tracking (First Time)
1. On the shadowing exercise page, scroll to "Mark as Complete" section
2. Enter a practice duration (e.g., 15 minutes)
3. Click "Mark as Complete" button
4. Verify:
   - Button shows loading state ("Marking Complete...")
   - Success message appears with XP earned ("+50 XP")
   - Page reloads after 2 seconds
   - Completion count appears at top (green gradient box)
   - "Completion History" section appears below

### 3. Test Completion History Display
1. After completing an exercise, verify the history shows:
   - Green checkmark icon
   - Completion date and time
   - Practice duration
   - "X time ago" relative timestamp
   - "+50 XP" badge
2. If a recording was saved during the session, verify:
   - "Recording available" link appears
   - Link opens/downloads the recording

### 4. Test Multiple Completions
1. Complete the same exercise again with different duration
2. Verify:
   - Completion count increments (e.g., "2 completions")
   - New completion appears at top of history
   - Both completions show in history with correct details
   - XP is awarded for each completion

### 5. Test Progress Updates
1. Navigate back to the lesson page
2. Check if lesson progress has updated
3. Verify shadowing completion count increased in UserProgress table

### 6. Test XP and Leveling
1. Check user profile to verify XP increased by 50
2. Complete multiple exercises to accumulate XP
3. Verify level up notification appears when threshold reached
4. Check that success message includes level up info

### 7. Test Error Handling
1. Try to submit without entering duration
2. Verify button is disabled
3. Try entering invalid duration (0 or negative)
4. Verify validation works

### 8. Test with Recording Link
1. Record audio during shadowing practice
2. Save the recording
3. Mark exercise as complete
4. Verify completion history shows recording link
5. Click recording link to verify it plays/downloads

## Database Verification

### Check ShadowingCompletion Records
```sql
SELECT * FROM shadowing_completions WHERE user_id = [your_user_id];
```
Expected: Records with user_id, shadowing_exercise_id, duration_seconds, completed_at

### Check StudyActivity Logs
```sql
SELECT * FROM study_activities 
WHERE user_id = [your_user_id] 
AND activity_type = 'shadowing';
```
Expected: Activity records with xp_earned = 50

### Check UserProgress Updates
```sql
SELECT * FROM user_progress 
WHERE user_id = [your_user_id] 
AND lesson_id = [lesson_id];
```
Expected: shadowing_completed count incremented, completion_percentage updated

### Check XP Awards
```sql
SELECT total_xp, level FROM user_profiles WHERE user_id = [your_user_id];
```
Expected: total_xp increased by 50 per completion, level updated if threshold reached

## API Testing

### Test Completion Endpoint
```bash
curl -X POST http://localhost:8000/shadowing/1/complete \
  -H "Content-Type: application/json" \
  -H "X-CSRF-TOKEN: your-csrf-token" \
  -d '{"duration_seconds": 900, "recording_id": null}'
```

Expected Response:
```json
{
  "success": true,
  "message": "Shadowing exercise completed!",
  "xp_earned": 50,
  "level_up": null,
  "completion": {
    "id": 1,
    "user_id": 1,
    "shadowing_exercise_id": 1,
    "duration_seconds": 900,
    "recording_id": null,
    "completed_at": "2025-01-10T12:00:00.000000Z"
  }
}
```

## Edge Cases to Test

1. **Complete without recording**: Verify recording_id is null
2. **Complete with recording**: Verify recording_id is set and link works
3. **Very short duration**: Test with 1 minute
4. **Very long duration**: Test with 120 minutes
5. **Rapid completions**: Complete multiple times quickly
6. **Level up threshold**: Complete enough to trigger level up
7. **First completion**: Verify UI changes from no history to showing history
8. **Browser refresh**: Verify data persists after page reload

## Expected Behavior Summary

### UI Elements
- ✅ Completion count badge (green gradient)
- ✅ Last completion timestamp
- ✅ Mark as Complete form with duration input
- ✅ Submit button with loading state
- ✅ Success/error messages
- ✅ Completion history list
- ✅ XP badges on completions
- ✅ Recording links (when available)

### Backend Operations
- ✅ ShadowingCompletion record created
- ✅ StudyActivity logged
- ✅ UserProgress updated
- ✅ XP awarded (50 points)
- ✅ Level checked and updated
- ✅ Lesson progress recalculated

### Data Integrity
- ✅ All timestamps accurate
- ✅ Duration stored in seconds
- ✅ Recording links valid
- ✅ XP calculations correct
- ✅ Progress percentages accurate

## Common Issues and Solutions

### Issue: Button stays disabled
**Solution**: Check that duration is > 0

### Issue: No XP awarded
**Solution**: Verify user has a profile record

### Issue: Progress not updating
**Solution**: Check that lesson_id is correct in completion

### Issue: Recording link broken
**Solution**: Verify recording exists and route is correct

### Issue: Level up not showing
**Solution**: Check XP thresholds in GamificationService

## Success Criteria

All tests pass when:
1. Completions are recorded correctly
2. XP is awarded consistently
3. Progress updates accurately
4. History displays properly
5. Level ups trigger correctly
6. UI is responsive and clear
7. Error handling works
8. Data persists correctly
