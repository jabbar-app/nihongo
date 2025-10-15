/**
 * Form Validation Enhancement
 * Provides inline validation feedback for authentication forms
 */

document.addEventListener('DOMContentLoaded', function() {
    // Add real-time validation for email fields
    const emailInputs = document.querySelectorAll('input[type="email"]');
    emailInputs.forEach(input => {
        input.addEventListener('blur', function() {
            validateEmail(this);
        });
        
        input.addEventListener('input', function() {
            // Clear error state when user starts typing
            if (this.classList.contains('border-red-300')) {
                this.classList.remove('border-red-300', 'focus:border-red-500', 'focus:ring-red-500');
                this.classList.add('border-gray-300', 'focus:border-indigo-500', 'focus:ring-indigo-500');
                
                const errorElement = document.getElementById(this.id + '-error');
                if (errorElement) {
                    errorElement.style.display = 'none';
                }
            }
        });
    });

    // Add real-time validation for password fields
    const passwordInputs = document.querySelectorAll('input[type="password"]');
    passwordInputs.forEach(input => {
        input.addEventListener('input', function() {
            // Clear error state when user starts typing
            if (this.classList.contains('border-red-300')) {
                this.classList.remove('border-red-300', 'focus:border-red-500', 'focus:ring-red-500');
                this.classList.add('border-gray-300', 'focus:border-indigo-500', 'focus:ring-indigo-500');
                
                const errorElement = document.getElementById(this.id + '-error');
                if (errorElement) {
                    errorElement.style.display = 'none';
                }
            }
        });
    });

    // Password confirmation matching
    const passwordConfirmation = document.getElementById('password_confirmation');
    const password = document.getElementById('password');
    
    if (passwordConfirmation && password) {
        passwordConfirmation.addEventListener('blur', function() {
            if (this.value && password.value && this.value !== password.value) {
                showInlineError(this, 'The passwords don\'t match. Please try again.');
            }
        });
    }

    // Radio button group validation for speaking goals
    const radioGroups = document.querySelectorAll('input[type="radio"][name="study_goal_minutes"]');
    radioGroups.forEach(radio => {
        radio.addEventListener('change', function() {
            // Update visual state of all radio options
            const allLabels = document.querySelectorAll('label:has(input[name="study_goal_minutes"])');
            allLabels.forEach(label => {
                const input = label.querySelector('input');
                if (input.checked) {
                    label.classList.remove('border-gray-200', 'hover:border-gray-300');
                    label.classList.add('border-indigo-600', 'bg-indigo-50');
                    label.querySelector('span').classList.remove('text-gray-700');
                    label.querySelector('span').classList.add('text-indigo-700');
                } else {
                    label.classList.remove('border-indigo-600', 'bg-indigo-50');
                    label.classList.add('border-gray-200', 'hover:border-gray-300');
                    label.querySelector('span').classList.remove('text-indigo-700');
                    label.querySelector('span').classList.add('text-gray-700');
                }
            });
        });
    });
});

function validateEmail(input) {
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    const value = input.value.trim();
    
    if (value && !emailRegex.test(value)) {
        showInlineError(input, 'Please enter a valid email address.');
    }
}

function showInlineError(input, message) {
    // Add error styling to input
    input.classList.remove('border-gray-300', 'focus:border-indigo-500', 'focus:ring-indigo-500');
    input.classList.add('border-red-300', 'focus:border-red-500', 'focus:ring-red-500');
    
    // Show or create error message
    let errorElement = document.getElementById(input.id + '-error');
    if (errorElement) {
        errorElement.style.display = 'block';
        const errorText = errorElement.querySelector('span');
        if (errorText) {
            errorText.textContent = message;
        }
    }
}
