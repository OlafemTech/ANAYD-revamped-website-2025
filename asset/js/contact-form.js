/**
 * Contact Form Handler
 * Handles CSRF token fetching, form submission, and response messages
 */
document.addEventListener('DOMContentLoaded', function() {
    const contactForm = document.getElementById('contact-form');
    const formMessages = document.getElementById('form-messages');
    const csrfToken = document.getElementById('csrf-token');

    // Fetch CSRF token when page loads
    fetchCsrfToken();

    // Add event listener to form
    if (contactForm) {
        contactForm.addEventListener('submit', handleFormSubmit);
    }

    /**
     * Fetch CSRF token from server
     */
    function fetchCsrfToken() {
        fetch('backend/handlers/get-csrf-token.php')
            .then(response => response.json())
            .then(data => {
                if (data.success && csrfToken) {
                    csrfToken.value = data.token;
                }
            })
            .catch(error => {
                console.error('Error fetching CSRF token:', error);
            });
    }

    /**
     * Handle form submission
     * @param {Event} event - Form submit event
     */
    function handleFormSubmit(event) {
        event.preventDefault();

        // Get form data
        const formData = new FormData(contactForm);

        // Show loading state
        const submitButton = contactForm.querySelector('button[type="submit"]');
        const originalButtonText = submitButton.textContent;
        submitButton.textContent = 'Sending...';
        submitButton.disabled = true;

        // Send form data via fetch
        fetch(contactForm.action, {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            // Display success or error message
            formMessages.classList.remove('hidden', 'bg-red-100', 'text-red-700', 'bg-green-100', 'text-green-700');
            
            if (data.success) {
                // Success message
                formMessages.classList.add('bg-green-100', 'text-green-700');
                formMessages.textContent = data.message;
                
                // Reset form
                contactForm.reset();
                
                // Refresh CSRF token
                fetchCsrfToken();
            } else {
                // Error message
                formMessages.classList.add('bg-red-100', 'text-red-700');
                formMessages.textContent = data.message || 'An error occurred. Please try again.';
            }
            
            // Restore button state
            submitButton.textContent = originalButtonText;
            submitButton.disabled = false;
            
            // Scroll to message
            formMessages.scrollIntoView({ behavior: 'smooth', block: 'center' });
        })
        .catch(error => {
            // Handle network errors
            console.error('Error:', error);
            formMessages.classList.remove('hidden');
            formMessages.classList.add('bg-red-100', 'text-red-700');
            formMessages.textContent = 'A network error occurred. Please try again.';
            
            // Restore button state
            submitButton.textContent = originalButtonText;
            submitButton.disabled = false;
        });
    }
});
