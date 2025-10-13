export default function exerciseAttempt(exerciseData, drillId) {
  return {
    questions: exerciseData.questions || [],
    answers: {},
    results: null,
    submitted: false,
    submitting: false,
    score: 0,
    correctCount: 0,
    elapsedTime: 0,
    timerInterval: null,

    init() {
      // Initialize answers object
      this.questions.forEach(question => {
        this.answers[question.id] = '';
      });

      // Start timer
      this.startTimer();
    },

    startTimer() {
      this.timerInterval = setInterval(() => {
        this.elapsedTime++;
      }, 1000);
    },

    stopTimer() {
      if (this.timerInterval) {
        clearInterval(this.timerInterval);
        this.timerInterval = null;
      }
    },

    formatTime(seconds) {
      const mins = Math.floor(seconds / 60);
      const secs = seconds % 60;
      return `${mins.toString().padStart(2, '0')}:${secs.toString().padStart(2, '0')}`;
    },

    canSubmit() {
      // Check if all questions have been answered
      return Object.values(this.answers).every(answer => answer.trim() !== '');
    },

    async submitAnswers() {
      if (!this.canSubmit()) {
        alert('Please answer all questions before submitting.');
        return;
      }

      this.submitting = true;

      this.stopTimer();

      try {
        const response = await fetch(`/exercises/${drillId}/submit`, {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json',
          },
          body: JSON.stringify({
            answers: this.answers,
            duration_seconds: this.elapsedTime,
          }),
        });

        if (!response.ok) {
          throw new Error('Failed to submit answers');
        }

        const data = await response.json();

        this.results = data.results;
        this.score = data.score;
        this.submitted = true;

        // Calculate correct count
        this.correctCount = Object.values(this.results).filter(result => result.correct).length;

        // Scroll to top to see results
        window.scrollTo({ top: 0, behavior: 'smooth' });
      } catch (error) {
        console.error('Error submitting answers:', error);
        alert('An error occurred while submitting your answers. Please try again.');
        this.startTimer(); // Restart timer if submission failed
      } finally {
        this.submitting = false;
      }
    },

    destroy() {
      this.stopTimer();
    },
  };
}
