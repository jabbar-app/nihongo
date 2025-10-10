# Transformation Drill Exercises

## Overview

Transformation drill exercises help learners practice converting Japanese sentences from one form to another, such as:
- Polite (丁寧) → Casual (カジュアル)
- Statement → Question
- Affirmative → Negative
- Present → Past tense

## Features

### 1. Source Sentence Display
Each question displays the original sentence that needs to be transformed, along with clear instructions about the type of transformation required.

### 2. Transformation Instructions
The exercise clearly indicates the transformation type (e.g., "丁寧 → カジュアル") so learners understand what changes they need to make.

### 3. Text Input
Learners type their transformed sentence in a text input field, allowing for free-form answers rather than multiple choice.

### 4. Answer Validation
The system validates answers against the correct transformation and provides:
- **Exact Match**: Full credit for perfect answers
- **Partial Credit**: Credit for close answers (70%+ similarity)
- **Incorrect**: No credit for answers below 70% similarity

### 5. Similarity Algorithm
The system uses the `similar_text()` function to calculate similarity between the user's answer and the correct answer. This works well with multibyte Japanese characters and provides a percentage match.

Partial credit thresholds:
- **90%+ similarity**: "Very close! Minor differences."
- **70-89% similarity**: "Partially correct. Check the transformation."
- **Below 70%**: "Incorrect. Review the correct answer."

### 6. Feedback Display
After submission, learners see:
- Whether their answer was correct (green), partially correct (yellow), or incorrect (red)
- Their submitted answer
- The correct answer
- Partial credit percentage (if applicable)
- A helpful message about their performance

## Content Structure

Transformation drills are stored in the `drills` table with the following JSON structure:

```json
{
  "content": [
    {
      "from": "丁寧",
      "to": "カジュアル",
      "examples": [
        {
          "source": "ありがとうございます",
          "target": "ありがとう"
        },
        {
          "source": "すみません",
          "target": "ごめん"
        }
      ]
    }
  ]
}
```

## Usage

1. Navigate to a lesson with transformation drills
2. Click on the "Drills" tab
3. Select a transformation drill exercise
4. Read the source sentence and transformation instruction
5. Type your transformed sentence
6. Submit your answers
7. Review feedback and correct answers

## Implementation Details

### TransformationExercise Service
- **generate()**: Converts drill content into exercise questions
- **validate()**: Checks user answers against correct answers
- **getScore()**: Calculates overall score including partial credit
- **calculateSimilarity()**: Determines how close an answer is to the correct one

### View Template
The exercise view (`resources/views/exercises/attempt.blade.php`) includes special handling for transformation exercises:
- Displays source sentence in a highlighted box
- Shows transformation instruction in a blue info box
- Uses Japanese font styling for proper character display
- Shows partial credit percentage in feedback

### Scoring
- Correct answers: 100% (1.0 points)
- Partial credit: 70-99% (0.7-0.99 points)
- Incorrect: 0% (0.0 points)

Final score is calculated as: `(total points / total questions) * 100`

## Example

**Transformation Type**: 丁寧 → カジュアル

**Source Sentence**: ありがとうございます

**Correct Answer**: ありがとう

**User Answers**:
- "ありがとう" → ✓ Correct! (100%)
- "ありがと" → ⚠ Very close! Minor differences. (93% partial credit)
- "こんにちは" → ✗ Incorrect. Review the correct answer. (0%)
