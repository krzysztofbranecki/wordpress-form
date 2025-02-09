
document.addEventListener('DOMContentLoaded', function() {
    const forms = document.querySelectorAll('.front-it-form');
    
    forms.forEach(form => {
        form.addEventListener('submit', async function(e) {
            e.preventDefault();
            
            // Get form elements
            const nameInput = form.querySelector('input[name="name"]');
            const emailInput = form.querySelector('input[name="email"]');
            const phoneInput = form.querySelector('input[name="phone"]');
            const messageInput = form.querySelector('textarea[name="message"]');
            const submitButton = form.querySelector('button[type="submit"]');
            const responseDiv = form.querySelector('.response-message') || createResponseDiv(form);
            
            // Disable submit button and show loading state
            submitButton.disabled = true;
            submitButton.innerHTML = 'Sending...';
            responseDiv.innerHTML = '';
            
            try {
                const response = await fetch('/wp-json/front-it/v1/submit', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        name: nameInput.value,
                        email: emailInput.value,
                        phone: phoneInput.value,
                        message: messageInput.value,
                    }),
                });
                
                const data = await response.json();

                if (data.success) {
                    // Success
                    responseDiv.innerHTML = `<div class="success-message">${data.message || 'Thank you! Your message has been sent successfully.'}</div>`;
                    e.target.reset();
                } else {
                    // API error with message
                    responseDiv.innerHTML = `<div class="error-message">${data.message || 'Something went wrong. Please try again.'}</div>`;
                }
            } catch (error) {
                // Network or other error
                responseDiv.innerHTML = '<div class="error-message">Unable to send message. Please try again later.</div>';
                console.error('Form submission error:', error);
            } finally {
                // Re-enable submit button
                submitButton.disabled = false;
                submitButton.innerHTML = 'Submit';
            }
        });
    });
});

// Helper function to create response message div
function createResponseDiv(form) {
    const div = document.createElement('div');
    div.className = 'response-message';
    form.appendChild(div);
    return div;
}

// Add styles
const styles = document.createElement('style');
styles.textContent = `
    .front-it-form .response-message {
        margin: 1rem 0;
        padding: 1rem;
        border-radius: 4px;
    }
    
    .front-it-form .success-message {
        background-color: #d4edda;
        color: #155724;
        border: 1px solid #c3e6cb;
    }
    
    .front-it-form .error-message {
        background-color: #f8d7da;
        color: #721c24;
        border: 1px solid #f5c6cb;
    }
    
    .front-it-form button[type="submit"]:disabled {
        opacity: 0.7;
        cursor: not-allowed;
    }
`;
document.head.appendChild(styles);