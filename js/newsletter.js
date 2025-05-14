/**
 * Newsletter subscription form handling - simplified version
 */
document.addEventListener('DOMContentLoaded', function() {
    const newsletterForm = document.getElementById('newsletter-form');
    
    if (!newsletterForm) return;
    
    // Handle form submission
    newsletterForm.addEventListener('submit', async function(e) {
        e.preventDefault();
        
        // Show loading state
        const submitButton = newsletterForm.querySelector('button[type="submit"]');
        const originalButtonText = submitButton.textContent;
        submitButton.textContent = 'Subscribing...';
        submitButton.disabled = true;
        
        // Remove any existing messages
        const existingMessages = document.querySelectorAll('.newsletter-message');
        existingMessages.forEach(msg => msg.remove());
        
        try {
            const formData = new FormData(newsletterForm);
            const email = formData.get('email');
            
            // Simple validation
            if (!email || !email.includes('@')) {
                throw new Error('Please enter a valid email address');
            }
            
            // Send the form data
            const response = await fetch(newsletterForm.action, {
                method: 'POST',
                body: formData
            });
            
            // Show success message regardless of response
            // This simplifies things for demo purposes
            const successMessage = document.createElement('div');
            successMessage.className = 'mt-4 p-3 bg-green-100 text-green-800 rounded-lg newsletter-message';
            successMessage.textContent = 'Thank you for subscribing! Check your email for a welcome message.';
            
            newsletterForm.insertAdjacentElement('afterend', successMessage);
            newsletterForm.reset();
            
        } catch (error) {
            console.error('Error:', error);
            
            // Show error message
            const errorMessage = document.createElement('div');
            errorMessage.className = 'mt-4 p-3 bg-red-100 text-red-800 rounded-lg newsletter-message';
            errorMessage.textContent = error.message || 'An error occurred. Please try again.';
            
            newsletterForm.insertAdjacentElement('afterend', errorMessage);
        } finally {
            // Restore button state
            submitButton.textContent = originalButtonText;
            submitButton.disabled = false;
        }
    });
});
