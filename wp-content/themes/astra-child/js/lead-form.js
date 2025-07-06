/**
 * Lead Generation Form JavaScript
 * Enhances form functionality with client-side validation and UX improvements
 */

document.addEventListener('DOMContentLoaded', function() {
    const leadForm = document.getElementById('lead-generation-form');
    
    if (!leadForm) return;
    
    // Add loading state to form submission
    leadForm.addEventListener('submit', function(e) {
        const submitButton = leadForm.querySelector('.lead-form-submit');
        const originalText = submitButton.textContent;
        
        // Disable button and show loading state
        submitButton.disabled = true;
        submitButton.textContent = 'Sending...';
        submitButton.style.opacity = '0.7';
        
        // Basic client-side validation
        const requiredFields = leadForm.querySelectorAll('[required]');
        let hasErrors = false;
        
        requiredFields.forEach(function(field) {
            if (!field.value.trim()) {
                hasErrors = true;
                field.style.borderColor = '#dc3545';
            } else {
                field.style.borderColor = '#e1e1e1';
            }
        });
        
        // Validate email format
        const emailField = leadForm.querySelector('#lead_email');
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (emailField && !emailRegex.test(emailField.value)) {
            hasErrors = true;
            emailField.style.borderColor = '#dc3545';
        }
        
        // Check consent checkbox
        const consentField = leadForm.querySelector('#lead_consent');
        if (consentField && !consentField.checked) {
            hasErrors = true;
            consentField.parentElement.style.color = '#dc3545';
        } else if (consentField) {
            consentField.parentElement.style.color = '';
        }
        
        if (hasErrors) {
            e.preventDefault();
            submitButton.disabled = false;
            submitButton.textContent = originalText;
            submitButton.style.opacity = '1';
            
            // Show error message
            showMessage('Please fill in all required fields correctly.', 'error');
            
            // Scroll to first error field
            const firstError = leadForm.querySelector('[style*="border-color: rgb(220, 53, 69)"]');
            if (firstError) {
                firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
                firstError.focus();
            }
        }
    });
    
    // Real-time validation for email field
    const emailField = leadForm.querySelector('#lead_email');
    if (emailField) {
        emailField.addEventListener('blur', function() {
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (this.value && !emailRegex.test(this.value)) {
                this.style.borderColor = '#dc3545';
                showFieldError(this, 'Please enter a valid email address.');
            } else {
                this.style.borderColor = '#e1e1e1';
                hideFieldError(this);
            }
        });
    }
    
    // Phone number formatting (basic)
    const phoneField = leadForm.querySelector('#lead_phone');
    if (phoneField) {
        phoneField.addEventListener('input', function() {
            // Remove non-numeric characters except +, -, (, ), and spaces
            this.value = this.value.replace(/[^\d\s\-\(\)\+]/g, '');
        });
    }
    
    // Character limit for textarea
    const messageField = leadForm.querySelector('#lead_message');
    if (messageField) {
        const maxLength = 1000;
        const counterElement = document.createElement('div');
        counterElement.className = 'character-counter';
        counterElement.style.cssText = 'text-align: right; font-size: 12px; color: #666; margin-top: 5px;';
        messageField.parentNode.appendChild(counterElement);
        
        function updateCounter() {
            const remaining = maxLength - messageField.value.length;
            counterElement.textContent = remaining + ' characters remaining';
            
            if (remaining < 50) {
                counterElement.style.color = '#dc3545';
            } else if (remaining < 100) {
                counterElement.style.color = '#ffc107';
            } else {
                counterElement.style.color = '#666';
            }
        }
        
        messageField.addEventListener('input', updateCounter);
        updateCounter(); // Initial count
        
        // Prevent typing beyond limit
        messageField.addEventListener('keypress', function(e) {
            if (this.value.length >= maxLength && e.key !== 'Backspace' && e.key !== 'Delete') {
                e.preventDefault();
            }
        });
    }
    
    // Auto-hide success/error messages after 10 seconds
    const messages = document.querySelectorAll('.lead-form-success, .lead-form-error');
    messages.forEach(function(message) {
        setTimeout(function() {
            message.style.transition = 'opacity 0.5s ease';
            message.style.opacity = '0';
            setTimeout(function() {
                message.style.display = 'none';
            }, 500);
        }, 10000);
    });
    
    // Helper functions
    function showMessage(text, type) {
        const existingMessage = leadForm.querySelector('.temp-message');
        if (existingMessage) {
            existingMessage.remove();
        }
        
        const messageDiv = document.createElement('div');
        messageDiv.className = 'temp-message lead-form-' + type;
        messageDiv.textContent = text;
        messageDiv.style.cssText = 'margin-bottom: 20px;';
        
        leadForm.insertBefore(messageDiv, leadForm.firstChild);
        
        // Auto-hide after 5 seconds
        setTimeout(function() {
            messageDiv.style.transition = 'opacity 0.5s ease';
            messageDiv.style.opacity = '0';
            setTimeout(function() {
                messageDiv.remove();
            }, 500);
        }, 5000);
    }
    
    function showFieldError(field, message) {
        hideFieldError(field);
        const errorDiv = document.createElement('div');
        errorDiv.className = 'field-error';
        errorDiv.textContent = message;
        errorDiv.style.cssText = 'color: #dc3545; font-size: 12px; margin-top: 5px;';
        field.parentNode.appendChild(errorDiv);
    }
    
    function hideFieldError(field) {
        const existingError = field.parentNode.querySelector('.field-error');
        if (existingError) {
            existingError.remove();
        }
    }
});