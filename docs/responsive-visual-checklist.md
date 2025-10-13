# Responsive Design Visual Checklist

## Quick Visual Verification Guide

Use this checklist to quickly verify responsive design implementation by visual inspection.

## Mobile View (375px - iPhone)

### Navigation
- [ ] Logo visible on left
- [ ] Hamburger menu icon on right (3 horizontal lines)
- [ ] Hamburger button is large enough to tap easily
- [ ] Menu opens from top when tapped
- [ ] Search bar appears in mobile menu
- [ ] All menu items stack vertically

### Dashboard
- [ ] Welcome message displays on one line or wraps nicely
- [ ] Stats display in 2 columns (Cards Due, Level on top row)
- [ ] Stats display in 2 columns (Streak, Today on bottom row)
- [ ] Numbers are large and readable (2xl-3xl)
- [ ] Quick action buttons stack vertically
- [ ] Quick action buttons are full width
- [ ] Charts adapt to mobile width

### Flashcard Review
- [ ] Japanese text is large (3xl)
- [ ] Romaji text is readable (xl)
- [ ] "Show Answer" button is full width
- [ ] Rating buttons display in 2x2 grid
- [ ] Rating buttons are large and tappable
- [ ] Audio buttons stack vertically
- [ ] Progress bar is visible at top

### Lessons List
- [ ] Lesson cards stack vertically (1 column)
- [ ] Cards have adequate spacing
- [ ] Text is readable
- [ ] Buttons are tappable

### Lesson Detail
- [ ] Header wraps nicely
- [ ] Lesson number badge is visible
- [ ] Bookmark button shows icon only
- [ ] Tabs scroll horizontally
- [ ] Tab labels are shortened ("Dialog" not "Dialogues")
- [ ] Phrase tables display as cards
- [ ] Each phrase card shows all info clearly

### Progress Page
- [ ] Overall progress bar is visible
- [ ] Stats display in 3 columns
- [ ] Labels are shortened ("Completed" not "Lessons Completed")
- [ ] Charts adapt to mobile width
- [ ] All text is readable

## Tablet View (768px - iPad)

### Navigation
- [ ] Full navigation menu visible
- [ ] Search bar in header
- [ ] All menu items in horizontal row
- [ ] User dropdown on right

### Dashboard
- [ ] Stats display in 4 columns
- [ ] Quick action buttons in 3 columns
- [ ] Charts use available width
- [ ] Spacing is comfortable

### Flashcard Review
- [ ] Card is centered
- [ ] Rating buttons in 4 columns
- [ ] Audio buttons side by side
- [ ] Keyboard shortcuts visible

### Lessons
- [ ] Lesson cards in 2 columns
- [ ] Adequate spacing between cards
- [ ] All content visible

### Lesson Detail
- [ ] All tabs visible without scrolling
- [ ] Full tab labels shown
- [ ] Tables display properly
- [ ] Good use of space

## Desktop View (1280px+)

### Navigation
- [ ] Full navigation menu
- [ ] Search bar centered
- [ ] User dropdown on right
- [ ] All items have hover states

### Dashboard
- [ ] Stats in 4 columns
- [ ] Quick actions in 3 columns
- [ ] Charts use optimal width
- [ ] Content centered with max-width

### Flashcard Review
- [ ] Card centered with good margins
- [ ] Rating buttons in 4 columns
- [ ] All text clearly visible
- [ ] Keyboard shortcuts shown

### Lessons
- [ ] Lesson cards in 3 columns
- [ ] Grid layout balanced
- [ ] Hover effects work

### Lesson Detail
- [ ] All tabs visible
- [ ] Tables display fully
- [ ] Content well-spaced
- [ ] Optimal reading width

## Touch Target Verification

### Visual Check
Look for these characteristics:
- [ ] Buttons appear substantial (not tiny)
- [ ] Adequate spacing between interactive elements
- [ ] No cramped layouts
- [ ] Easy to distinguish tap targets

### Size Check (use browser inspector)
- [ ] Navigation hamburger: 44x44px minimum
- [ ] All buttons: 44x44px minimum
- [ ] Primary actions: 48x48px or larger
- [ ] Links: 44px height minimum

## Typography Check

### Mobile
- [ ] Headings: 20-24px (text-xl to text-2xl)
- [ ] Body text: 14-16px (text-sm to text-base)
- [ ] Labels: 12-14px (text-xs to text-sm)
- [ ] All text readable without zooming

### Desktop
- [ ] Headings: 24-30px (text-2xl to text-3xl)
- [ ] Body text: 16px (text-base)
- [ ] Labels: 14px (text-sm)
- [ ] Comfortable reading experience

## Spacing Check

### Mobile
- [ ] Padding: 16px (p-4)
- [ ] Gaps: 12px (gap-3)
- [ ] Margins: Adequate for readability
- [ ] No cramped feeling

### Desktop
- [ ] Padding: 24px (p-6)
- [ ] Gaps: 24px (gap-6)
- [ ] Margins: Generous spacing
- [ ] Comfortable layout

## Common Issues to Spot

### Layout Problems
- [ ] No horizontal scrolling (except tabs)
- [ ] No overlapping elements
- [ ] No cut-off text
- [ ] No awkward wrapping

### Touch Issues
- [ ] No tiny buttons
- [ ] No cramped tap targets
- [ ] No accidental taps
- [ ] Clear active states

### Text Issues
- [ ] No unreadable text
- [ ] No excessive line length
- [ ] No poor contrast
- [ ] No awkward breaks

## Quick Test Procedure

1. **Open in Chrome DevTools**
   - Press F12
   - Click device toolbar icon (Ctrl+Shift+M)

2. **Test Mobile (375px)**
   - Select "iPhone 12 Pro"
   - Navigate through all pages
   - Check all items in mobile checklist

3. **Test Tablet (768px)**
   - Select "iPad"
   - Navigate through all pages
   - Check all items in tablet checklist

4. **Test Desktop (1280px)**
   - Select "Responsive" and set to 1280px
   - Navigate through all pages
   - Check all items in desktop checklist

5. **Test Touch Interactions**
   - Enable touch simulation in DevTools
   - Tap all interactive elements
   - Verify touch targets are adequate

## Pass/Fail Criteria

### Pass
- All checklist items verified
- No layout issues observed
- All touch targets adequate
- Text readable at all sizes
- Smooth interactions

### Fail (needs fixing)
- Any checklist item fails
- Layout breaks at any size
- Touch targets too small
- Text unreadable
- Janky interactions

## Quick Fixes

If you spot issues:

1. **Too small touch targets**
   - Add `min-h-[44px]` class
   - Add `touch-manipulation` class

2. **Text too small**
   - Use responsive text classes (text-sm sm:text-base)
   - Increase base font size

3. **Layout breaks**
   - Check grid columns (grid-cols-1 sm:grid-cols-2)
   - Verify padding/margin classes
   - Check for fixed widths

4. **Horizontal scrolling**
   - Add `overflow-x-hidden` to parent
   - Check for elements wider than viewport
   - Use `max-w-full` on images

## Browser Testing

Test in these browsers:
- [ ] Chrome (desktop)
- [ ] Chrome (mobile)
- [ ] Safari (desktop)
- [ ] Safari (iOS)
- [ ] Firefox (desktop)
- [ ] Firefox (mobile)
- [ ] Edge (desktop)

## Final Verification

Before marking complete:
- [ ] All pages tested at all breakpoints
- [ ] All touch targets verified
- [ ] All text readable
- [ ] No layout issues
- [ ] Smooth interactions
- [ ] Good performance
- [ ] Accessible to all users
