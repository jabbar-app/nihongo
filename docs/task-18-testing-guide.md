# Task 18: Audio Recording - Testing Guide

## Manual Testing Checklist

### 1. Browser Compatibility Testing

#### Supported Browsers
- [ ] Chrome/Edge (latest version)
  - Navigate to shadowing exercise
  - Verify "Record Your Practice" section appears
  - Verify no compatibility warning shown
  
- [ ] Firefox (latest version)
  - Navigate to shadowing exercise
  - Verify recording functionality works
  
- [ ] Safari 14.1+ (macOS/iOS)
  - Navigate to shadowing exercise
  - Verify recording functionality works

#### Unsupported Browsers
- [ ] Internet Explorer 11
  - Verify yellow warning message appears
  - Message should say "Audio recording not supported"

### 2. Microphone Permission Testing

#### First-Time Permission Request
- [ ] Navigate to shadowing exercise page
- [ ] Click "Start Recording" button
- [ ] Verify browser shows permission prompt
- [ ] Grant permission
- [ ] Verify recording starts (red dot, timer)

#### Permission Denied
- [ ] Click "Start Recording"
- [ ] Deny microphone permission
- [ ] Verify red error message appears
- [ ] Message should say "Microphone access was denied"
- [ ] Verify helpful instructions provided

#### No Microphone Connected
- [ ] Disconnect/disable microphone
- [ ] Click "Start Recording"
- [ ] Verify error message: "No microphone found"

#### Microphone In Use
- [ ] Open another app using microphone (e.g., Zoom)
- [ ] Try to start recording
- [ ] Verify error message: "Microphone is already in use"

### 3. Recording Functionality

#### Start Recording
- [ ] Click "Start Recording" button
- [ ] Verify button changes to "Stop" button
- [ ] Verify red pulsing dot appears
- [ ] Verify "Recording" status text appears
- [ ] Verify timer starts (0:00, 0:01, 0:02...)
- [ ] Speak into microphone

#### Stop Recording
- [ ] Click "Stop" button while recording
- [ ] Verify recording stops
- [ ] Verify green dot appears
- [ ] Verify "Recording saved" status text
- [ ] Verify timer shows final duration
- [ ] Verify playback section appears

#### Recording Duration
- [ ] Record for 5 seconds
- [ ] Verify timer shows 0:05
- [ ] Record for 65 seconds
- [ ] Verify timer shows 1:05

### 4. Playback Functionality

#### Play Recording
- [ ] After recording, click play button
- [ ] Verify audio plays back
- [ ] Verify play button changes to pause icon
- [ ] Verify progress bar moves
- [ ] Verify audio quality is acceptable

#### Pause Playback
- [ ] During playback, click pause button
- [ ] Verify playback pauses
- [ ] Verify progress bar stops moving
- [ ] Click play again
- [ ] Verify playback resumes from paused position

#### Playback Progress
- [ ] Start playback
- [ ] Verify progress bar fills from 0% to 100%
- [ ] Verify progress bar resets to 0% when playback ends

#### Playback Completion
- [ ] Let recording play to end
- [ ] Verify playback stops automatically
- [ ] Verify play button reappears (not pause)
- [ ] Verify progress bar resets to 0%

### 5. Re-record Functionality

#### Re-record Button
- [ ] Complete a recording
- [ ] Verify "Re-record" button appears
- [ ] Click "Re-record" button
- [ ] Verify previous recording is cleared
- [ ] Verify recording starts immediately
- [ ] Verify timer resets to 0:00

#### Multiple Re-records
- [ ] Record audio
- [ ] Re-record
- [ ] Re-record again
- [ ] Verify each time works correctly
- [ ] Verify no memory leaks (check browser dev tools)

### 6. Download Functionality

#### Download Recording
- [ ] Complete a recording
- [ ] Click download button (down arrow icon)
- [ ] Verify file downloads
- [ ] Verify filename format: `shadowing-recording-[timestamp].webm`
- [ ] Open downloaded file
- [ ] Verify audio plays correctly

### 7. UI/UX Testing

#### Visual States
- [ ] Ready state: Gray dot, "Ready to record"
- [ ] Recording state: Red pulsing dot, "Recording"
- [ ] Saved state: Green dot, "Recording saved"

#### Button States
- [ ] Ready: "Start Recording" button visible
- [ ] Recording: "Stop" button visible
- [ ] Saved: "Re-record" button visible

#### Timer Display
- [ ] Verify monospace font for timer
- [ ] Verify format: M:SS (e.g., 0:05, 1:23)
- [ ] Verify timer only shows when recording or after recording

#### Responsive Design
- [ ] Test on mobile (320px width)
- [ ] Test on tablet (768px width)
- [ ] Test on desktop (1024px+ width)
- [ ] Verify buttons stack properly on mobile
- [ ] Verify text remains readable

### 8. Error Handling

#### Network Issues
- [ ] Start recording with poor connection
- [ ] Verify recording still works (client-side)

#### Browser Tab Switch
- [ ] Start recording
- [ ] Switch to another tab
- [ ] Switch back
- [ ] Verify recording continues

#### Page Refresh During Recording
- [ ] Start recording
- [ ] Refresh page
- [ ] Verify recording is lost (expected behavior)
- [ ] Verify no errors on page load

### 9. Integration Testing

#### With Shadowing Player
- [ ] Play shadowing audio
- [ ] Start recording
- [ ] Verify both can work independently
- [ ] Stop shadowing audio
- [ ] Verify recording continues

#### Multiple Recordings in Session
- [ ] Record first audio
- [ ] Play it back
- [ ] Re-record
- [ ] Play new recording
- [ ] Verify correct audio plays

### 10. Performance Testing

#### Short Recordings (< 5 seconds)
- [ ] Record 1-2 seconds
- [ ] Verify recording saves correctly
- [ ] Verify playback works

#### Long Recordings (> 2 minutes)
- [ ] Record 2-3 minutes
- [ ] Verify recording continues
- [ ] Verify timer updates correctly
- [ ] Verify file size is reasonable
- [ ] Verify playback works

#### Memory Management
- [ ] Record 5 different clips in succession
- [ ] Open browser dev tools
- [ ] Check memory usage
- [ ] Verify no significant memory leaks

### 11. Accessibility Testing

#### Keyboard Navigation
- [ ] Tab to "Start Recording" button
- [ ] Press Enter to start
- [ ] Tab to "Stop" button
- [ ] Press Enter to stop
- [ ] Tab through playback controls

#### Screen Reader
- [ ] Use screen reader (NVDA/VoiceOver)
- [ ] Verify button labels are announced
- [ ] Verify status messages are announced
- [ ] Verify error messages are announced

#### Color Contrast
- [ ] Verify red recording indicator is visible
- [ ] Verify green saved indicator is visible
- [ ] Verify text has sufficient contrast

### 12. Edge Cases

#### Rapid Start/Stop
- [ ] Click Start Recording
- [ ] Immediately click Stop
- [ ] Verify recording saves (even if very short)

#### Multiple Clicks
- [ ] Click Start Recording multiple times rapidly
- [ ] Verify only one recording starts
- [ ] Click Stop multiple times
- [ ] Verify no errors occur

#### Browser Back Button
- [ ] Start recording
- [ ] Click browser back button
- [ ] Verify recording stops gracefully
- [ ] Navigate forward
- [ ] Verify page loads correctly

## Expected Results Summary

### Success Criteria
✅ Recording starts when "Start Recording" clicked
✅ Microphone permission requested on first use
✅ Recording timer displays and updates every second
✅ Recording stops when "Stop" clicked
✅ Playback controls appear after recording
✅ Recorded audio plays back correctly
✅ Re-record functionality clears previous recording
✅ Download saves audio file locally
✅ Error messages display for permission/compatibility issues
✅ UI updates reflect current state (ready/recording/saved)

### Known Limitations
- Recordings are stored in browser memory only (not persisted to server yet - Task 19)
- Recordings lost on page refresh (expected until Task 19)
- MIME type varies by browser (webm, mp4, or ogg)
- Maximum recording length limited by browser memory

## Bug Reporting Template

If you find issues during testing, report them with:

```
**Bug Title**: [Brief description]

**Steps to Reproduce**:
1. 
2. 
3. 

**Expected Behavior**:
[What should happen]

**Actual Behavior**:
[What actually happens]

**Browser**: [Chrome 120, Firefox 121, etc.]
**OS**: [Windows 11, macOS 14, etc.]
**Device**: [Desktop, Mobile, etc.]

**Screenshots/Console Errors**:
[Attach if applicable]
```

## Testing Sign-off

- [ ] All manual tests passed
- [ ] No critical bugs found
- [ ] Performance is acceptable
- [ ] Accessibility requirements met
- [ ] Ready for Task 19 (server-side storage)

**Tested by**: _______________
**Date**: _______________
**Notes**: _______________
