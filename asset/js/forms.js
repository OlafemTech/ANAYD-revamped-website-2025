// Function to handle form submissions
function handleFormSubmit(formId, successMessage) {
    const form = document.getElementById(formId);
    if (!form) return;

    form.addEventListener('submit', async (e) => {
        e.preventDefault();

        try {
            const formData = new FormData(form);
            const response = await fetch(form.action, {
                method: 'POST',
                body: formData
            });

            const result = await response.json();

            if (result.success) {
                alert(successMessage);
                form.reset();
            } else {
                alert(result.message || 'An error occurred. Please try again.');
            }
        } catch (error) {
            console.error('Error:', error);
        }
    });
}

// Initialize form handlers
document.addEventListener('DOMContentLoaded', function() {
    const contactForm = document.getElementById('contact-form');
    const newsletterForm = document.querySelector('form[action="backend/handlers/newsletter.php"]');
    const formMessages = document.getElementById('form-messages');

    if (contactForm) {
        handleFormSubmit(contactForm, 'Thank you for your message. We will get back to you soon!');
    }

    if (newsletterForm) {
        handleFormSubmit(newsletterForm, 'Thank you for subscribing to our newsletter!');
    }
});
