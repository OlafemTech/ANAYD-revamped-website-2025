// Form validation and submission handling
const forms = {
    contact: document.getElementById('contact-form'),
    volunteer: document.getElementById('volunteer-form'),
    partnership: document.getElementById('partnership-form'),
    donate: document.getElementById('donate-form'),
    newsletter: document.getElementById('newsletter-form')
};

// Generic form validation
function validateForm(form) {
    const requiredFields = form.querySelectorAll('[required]');
    let isValid = true;

    requiredFields.forEach(field => {
        if (!field.value.trim()) {
            isValid = false;
            field.classList.add('border-red-500');
        } else {
            field.classList.remove('border-red-500');
        }
    });

    // Email validation
    const emailField = form.querySelector('input[type="email"]');
    if (emailField && !isValidEmail(emailField.value)) {
        isValid = false;
        emailField.classList.add('border-red-500');
    }

    return isValid;
}

function isValidEmail(email) {
    return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
}

// Generic form submission handler
async function handleSubmit(event, formType) {
    event.preventDefault();
    const form = event.target;
    
    if (!validateForm(form)) {
        showMessage(form, 'Please fill all required fields correctly', 'error');
        return;
    }

    const formData = new FormData(form);
    
    try {
        const response = await fetch(`/backend/handlers/${formType}.php`, {
            method: 'POST',
            body: formData
        });

        const data = await response.json();

        if (data.success) {
            showMessage(form, data.message, 'success');
            form.reset();
            
            // Special handling for donations
            if (formType === 'donate' && data.data) {
                initializeFlutterwavePayment(data.data);
            }
        } else {
            showMessage(form, data.message, 'error');
        }
    } catch (error) {
        showMessage(form, 'An error occurred. Please try again.', 'error');
    }
}

// Message display
function showMessage(form, message, type) {
    const messageDiv = form.querySelector('.form-message');
    if (messageDiv) {
        messageDiv.textContent = message;
        messageDiv.className = 'form-message mt-4 p-4 rounded ' + 
            (type === 'success' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700');
        
        // Auto-hide after 5 seconds
        setTimeout(() => {
            messageDiv.textContent = '';
            messageDiv.className = 'form-message';
        }, 5000);
    }
}

// Flutterwave payment initialization
function initializeFlutterwavePayment(paymentData) {
    FlutterwaveCheckout({
        public_key: "YOUR_FLUTTERWAVE_PUBLIC_KEY",
        tx_ref: paymentData.tx_ref,
        amount: paymentData.amount,
        currency: paymentData.currency,
        payment_options: paymentData.payment_options,
        redirect_url: paymentData.redirect_url,
        customer: paymentData.customer,
        meta: paymentData.meta,
        customizations: paymentData.customizations,
        callback: function(response) {
            // Handle callback if needed
            console.log(response);
        },
        onclose: function() {
            // Handle modal close
        }
    });
}

// Event listeners
Object.entries(forms).forEach(([type, form]) => {
    if (form) {
        form.addEventListener('submit', (e) => handleSubmit(e, type));
    }
});
