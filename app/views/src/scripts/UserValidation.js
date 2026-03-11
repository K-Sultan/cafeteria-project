document.addEventListener('DOMContentLoaded', () => {
    const form = document.querySelector('form');
    if (!form) return;

    const showError = (input, message) => {
        const container = input.closest('div');
        const errorSpan = container.querySelector('.error-msg');
        if (errorSpan) {
            errorSpan.textContent = message;
            errorSpan.classList.remove('hidden');
        }
    };

    const clearError = (input) => {
        const container = input.closest('div');
        const errorSpan = container.querySelector('.error-msg');
        if (errorSpan) {
            errorSpan.classList.add('hidden');
            errorSpan.textContent = '';
        }
    };

    const validateField = (input) => {
        const name = input.name;
        const value = input.value.trim();
        clearError(input);

        // Name Validation
        if (name === 'name' && value.length < 3) {
            showError(input, "Name must be at least 3 characters.");
        }

        // Email Validation
        if (name === 'email') {
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailRegex.test(value)) showError(input, "Invalid email address.");
        }

        // Password Validation
        if (name === 'password' && value.length > 0 && value.length < 6) {
            showError(input, "Password must be at least 6 characters.");
        }

        // Confirm Password Validation
        if (name === 'confirm_password') {
            const pwd = form.querySelector('input[name="password"]').value;
            if (value !== pwd) showError(input, "Passwords do not match.");
        }


        if (name === 'room_no') {
            if (value === "") {
                showError(input, "Room number is required.");
            } else if (isNaN(value)) {
                showError(input, "Room number must be a valid number.");
            }
        }

        if (name === 'extension' && value === "") {
            showError(input, "Extension cannot be empty.");
        }


        // Profile Picture Validation
        if (name === 'profile_pic' && input.files.length > 0) {
            const ext = input.value.split('.').pop().toLowerCase();
            if (!['jpg', 'jpeg', 'png', 'gif'].includes(ext)) {
                showError(input, "Allowed: JPG, PNG, GIF.");
            }
        }
    };

    form.querySelectorAll('input').forEach(input => {
        input.addEventListener('blur', () => validateField(input));
    });

    // Final check on submit
    form.addEventListener('submit', (e) => {
        form.querySelectorAll('input').forEach(input => validateField(input));
        const visibleErrors = form.querySelectorAll('.error-msg:not(.hidden)');
        if (visibleErrors.length > 0) {
            e.preventDefault();
        }
    });
});