# Accessibility Testing Checklist

## Overview
This checklist provides a comprehensive guide for testing the accessibility features of the Japanese Learning Application.

## 1. Keyboard Navigation Testing

### General Navigation
- [ ] Tab key moves focus through all interactive elements in logical order
- [ ] Shift+Tab moves focus backwards
- [ ] Enter key activates buttons and links
- [ ] Space key activates buttons
- [ ] Escape key closes modals and dropdowns
- [ ] Arrow keys navigate within components (tabs, dropdowns, etc.)
- [ ] Focus is visible on all interactive elements
- [ ] Focus is not trapped in any component
- [ ] Skip to main content link works (Tab from page load)

### Flashcard Review
- [ ] Space key flips flashcard
- [ ] Number keys 1-4 rate cards (Again, Hard, Good, Easy)
- [ ] Tab navigates to rating buttons
- [ ] Enter activates rating buttons

### Audio Controls
- [ ] Space key plays/pauses audio
- [ ] Arrow keys adjust playback speed (if implemented)
- [ ] Tab navigates to audio controls

### Search
- [ ] Cmd/Ctrl+K focuses search input
- [ ] Enter submits search
- [ ] Escape clears search (if implemented)

### Modals and Dialogs
- [ ] Focus moves to modal when opened
- [ ] Tab cycles through modal elements only
- [ ] Escape closes modal
- [ ] Focus returns to trigger element when closed

## 2. Screen Reader Testing

### NVDA (Windows)
- [ ] Install NVDA (free, open-source)
- [ ] Navigate through main pages
- [ ] Verify all headings are announced
- [ ] Verify all links are announced with purpose
- [ ] Verify all buttons are announced with labels
- [ ] Verify form labels are read correctly
- [ ] Verify table headers are announced
- [ ] Verify list items are announced
- [ ] Verify dynamic content updates are announced
- [ ] Verify images have alt text or are marked decorative

### JAWS (Windows)
- [ ] Navigate through main pages
- [ ] Test forms and form validation
- [ ] Test tables and data grids
- [ ] Test dynamic content updates
- [ ] Test modal dialogs

### VoiceOver (macOS/iOS)
- [ ] Enable VoiceOver (Cmd+F5)
- [ ] Navigate with VO+Arrow keys
- [ ] Test rotor navigation (VO+U)
- [ ] Test form controls
- [ ] Test on iPhone/iPad
- [ ] Test gestures on mobile

### Testing Checklist by Page

#### Dashboard
- [ ] Welcome message is announced
- [ ] Stats cards are announced with values
- [ ] Quick action buttons are announced
- [ ] Charts have text alternatives
- [ ] Recent items are announced

#### Lessons
- [ ] Lesson list is announced
- [ ] Lesson cards are announced with progress
- [ ] Tab navigation works correctly
- [ ] Phrase table is announced properly
- [ ] Dialogue lines are announced
- [ ] Audio buttons are announced

#### Flashcard Review
- [ ] Card front/back are announced
- [ ] Rating buttons are announced
- [ ] Progress is announced
- [ ] Session complete message is announced

#### Exercises
- [ ] Exercise instructions are announced
- [ ] Questions are announced
- [ ] Input fields have labels
- [ ] Feedback is announced
- [ ] Score is announced

## 3. Color Contrast Testing

### Automated Tools
- [ ] Run axe DevTools on all pages
- [ ] Run WAVE extension on all pages
- [ ] Run Lighthouse accessibility audit
- [ ] Check Chrome DevTools contrast ratios

### Manual Testing
- [ ] Verify text on backgrounds (4.5:1 minimum)
- [ ] Verify large text on backgrounds (3:1 minimum)
- [ ] Verify button text on button backgrounds
- [ ] Verify link colors
- [ ] Verify form input borders
- [ ] Verify focus indicators
- [ ] Verify status indicators
- [ ] Verify chart colors

### Test with Color Blindness Simulators
- [ ] Protanopia (red-blind)
- [ ] Deuteranopia (green-blind)
- [ ] Tritanopia (blue-blind)
- [ ] Achromatopsia (total color blindness)

## 4. Semantic HTML Testing

### Document Structure
- [ ] One h1 per page
- [ ] Heading hierarchy is logical (h1 → h2 → h3)
- [ ] No skipped heading levels
- [ ] Landmarks are used (<nav>, <main>, <header>, <footer>)
- [ ] Lists use <ul>, <ol>, <li>
- [ ] Tables use <table>, <thead>, <tbody>, <th>, <td>
- [ ] Forms use <form>, <label>, <input>, <button>

### Validation
- [ ] HTML validates (W3C Validator)
- [ ] No duplicate IDs
- [ ] All IDs referenced by ARIA attributes exist
- [ ] Lang attribute is set on <html>

## 5. ARIA Testing

### ARIA Labels
- [ ] All icon-only buttons have aria-label
- [ ] All images without alt text have aria-label
- [ ] All form inputs have labels or aria-label
- [ ] All custom controls have appropriate roles

### ARIA States
- [ ] aria-expanded on collapsible elements
- [ ] aria-pressed on toggle buttons
- [ ] aria-selected on tabs
- [ ] aria-current on navigation
- [ ] aria-invalid on form errors
- [ ] aria-disabled on disabled elements

### ARIA Live Regions
- [ ] Dynamic content updates are announced
- [ ] aria-live="polite" for non-urgent updates
- [ ] aria-live="assertive" for urgent updates
- [ ] Status messages use role="status"
- [ ] Alerts use role="alert"

### ARIA Relationships
- [ ] aria-labelledby links to label elements
- [ ] aria-describedby links to description elements
- [ ] aria-controls links to controlled elements
- [ ] aria-owns links to owned elements

## 6. Form Accessibility Testing

### Labels and Instructions
- [ ] All inputs have associated labels
- [ ] Labels use <label> with for attribute
- [ ] Required fields are marked (aria-required or required)
- [ ] Instructions are provided before form
- [ ] Help text is associated with aria-describedby

### Validation and Errors
- [ ] Errors are announced to screen readers
- [ ] Errors are associated with fields (aria-describedby)
- [ ] Fields with errors have aria-invalid="true"
- [ ] Error messages are clear and specific
- [ ] Success messages are announced

### Focus Management
- [ ] Focus moves to first error on submit
- [ ] Focus is not lost on validation
- [ ] Tab order is logical
- [ ] Focus indicators are visible

## 7. Mobile Accessibility Testing

### Touch Targets
- [ ] All interactive elements are at least 44x44px
- [ ] Adequate spacing between touch targets
- [ ] No overlapping touch targets

### Gestures
- [ ] All functionality available without complex gestures
- [ ] Swipe gestures have alternatives
- [ ] Pinch-to-zoom is not disabled

### Screen Reader (Mobile)
- [ ] Test with VoiceOver (iOS)
- [ ] Test with TalkBack (Android)
- [ ] Test with mobile screen reader gestures

### Orientation
- [ ] Content works in portrait and landscape
- [ ] No content is lost when rotating

## 8. Automated Testing

### Tools to Run
- [ ] axe DevTools (browser extension)
- [ ] WAVE (browser extension)
- [ ] Lighthouse (Chrome DevTools)
- [ ] Pa11y (command line)
- [ ] HTML Validator (W3C)

### CI/CD Integration
- [ ] Add automated accessibility tests to CI pipeline
- [ ] Fail builds on critical accessibility issues
- [ ] Generate accessibility reports

## 9. Manual Testing Scenarios

### User Journeys (Keyboard Only)
- [ ] Register new account
- [ ] Log in
- [ ] Browse lessons
- [ ] Create flashcards
- [ ] Review flashcards
- [ ] Complete exercise
- [ ] Practice shadowing
- [ ] View progress
- [ ] Search content
- [ ] Bookmark lesson
- [ ] Log out

### User Journeys (Screen Reader)
- [ ] Navigate to specific lesson
- [ ] Find and play audio
- [ ] Submit exercise answers
- [ ] Review flashcards
- [ ] Check progress stats

## 10. Documentation Testing

### Help Content
- [ ] Keyboard shortcuts are documented
- [ ] Accessibility features are documented
- [ ] Screen reader instructions are provided
- [ ] Alternative text is meaningful

## Testing Results Template

```markdown
## Test Date: [DATE]
## Tester: [NAME]
## Tools Used: [LIST]

### Issues Found
1. **Issue**: [Description]
   - **Severity**: Critical / Serious / Moderate / Minor
   - **WCAG Criterion**: [e.g., 1.1.1, 2.1.1]
   - **Location**: [Page/Component]
   - **Steps to Reproduce**: [Steps]
   - **Recommendation**: [Fix]

### Passed Tests
- [List of passed tests]

### Notes
- [Additional observations]
```

## Priority Levels

### Critical (Must Fix)
- Keyboard traps
- Missing form labels
- Insufficient color contrast (< 3:1)
- Missing alt text on informative images
- Broken ARIA references

### Serious (Should Fix)
- Illogical heading hierarchy
- Missing skip links
- Insufficient color contrast (3:1 - 4.5:1)
- Missing ARIA labels on custom controls
- Focus not visible

### Moderate (Nice to Fix)
- Redundant ARIA
- Missing landmark roles
- Inconsistent focus indicators
- Missing aria-live regions

### Minor (Optional)
- Decorative images with alt text
- Verbose ARIA labels
- Minor semantic HTML issues

## Resources

### Testing Tools
- **axe DevTools**: https://www.deque.com/axe/devtools/
- **WAVE**: https://wave.webaim.org/extension/
- **NVDA**: https://www.nvaccess.org/download/
- **Lighthouse**: Built into Chrome DevTools
- **Color Contrast Analyzer**: https://www.tpgi.com/color-contrast-checker/

### Guidelines
- **WCAG 2.1**: https://www.w3.org/WAI/WCAG21/quickref/
- **ARIA Authoring Practices**: https://www.w3.org/WAI/ARIA/apg/
- **WebAIM**: https://webaim.org/

### Learning Resources
- **WebAIM Screen Reader Testing**: https://webaim.org/articles/screenreader_testing/
- **Deque University**: https://dequeuniversity.com/
- **A11ycasts**: https://www.youtube.com/playlist?list=PLNYkxOF6rcICWx0C9LVWWVqvHlYJyqw7g
