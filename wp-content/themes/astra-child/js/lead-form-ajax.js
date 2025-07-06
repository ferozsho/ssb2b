/**
 * Lead Generation Form AJAX JavaScript
 * Enhances form functionality with AJAX submission, validation and UX improvements
 */

document.addEventListener('DOMContentLoaded', function() {
    const leadForm = document.getElementById('lead-generation-form');

    if (!leadForm) return;

    // Initialize form enhancements
    initFormValidation();
    initFieldEnhancements();
    initLocationValidation();
    initProgressiveEnhancement();

    /**
     * Form Validation and AJAX Submission
     */
    function initFormValidation() {
        // Convert to AJAX submission
        leadForm.addEventListener('submit', function(e) {
            e.preventDefault();

            const submitButton = leadForm.querySelector('.lead-form-submit');
            const originalText = submitButton.textContent;

            // Basic client-side validation
            const requiredFields = leadForm.querySelectorAll('[required]');
            let hasErrors = false;

            requiredFields.forEach(function(field) {
                if (!field.value.trim()) {
                    hasErrors = true;
                    field.style.borderColor = '#e74c3c';
                    field.classList.add('error');
                } else {
                    field.style.borderColor = '#e1e1e1';
                    field.classList.remove('error');
                }
            });

            // Validate email format
            const emailField = leadForm.querySelector('#lead_email');
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (emailField && !emailRegex.test(emailField.value)) {
                hasErrors = true;
                emailField.style.borderColor = '#e74c3c';
                emailField.classList.add('error');
            }

            // Validate website URL if provided
            const websiteField = leadForm.querySelector('#lead_website');
            if (websiteField && websiteField.value) {
                const urlRegex = /^https?:\/\/.+/;
                if (!urlRegex.test(websiteField.value)) {
                    hasErrors = true;
                    websiteField.style.borderColor = '#e74c3c';
                    websiteField.classList.add('error');
                    showFieldError(websiteField, 'Please enter a valid URL starting with http:// or https://');
                }
            }

            // Check consent checkbox
            const consentField = leadForm.querySelector('#lead_consent');
            if (consentField && !consentField.checked) {
                hasErrors = true;
                consentField.parentElement.style.color = '#e74c3c';
            } else if (consentField) {
                consentField.parentElement.style.color = '';
            }

            if (hasErrors) {
                // Show error message
                showMessage('Please fill in all required fields correctly and ensure all information is valid.', 'error');

                // Scroll to first error field
                const firstError = leadForm.querySelector('.error, [style*="border-color: rgb(231, 76, 60)"]');
                if (firstError) {
                    firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    setTimeout(() => firstError.focus(), 500);
                }
                return;
            }

            // Disable all fields and show loading state
            setFormDisabled(true);
            submitButton.disabled = true;
            submitButton.textContent = 'Submitting...';
            submitButton.classList.add('loading');
            submitButton.style.opacity = '0.7';

            // Create FormData object
            const formData = new FormData(leadForm);
            formData.append('action', 'lead_form_submit');
            formData.append('nonce', leadFormData.nonce);

            // Send AJAX request
            fetch(leadFormData.ajaxurl, {
                method: 'POST',
                body: formData,
                credentials: 'same-origin'
            })
            .then(response => response.json())
            .then(data => {
                // Re-enable the form
                setFormDisabled(false);
                submitButton.disabled = false;
                submitButton.textContent = originalText;
                submitButton.classList.remove('loading');
                submitButton.style.opacity = '1';

                if (data.success) {
                    // Show success message
                    showMessage(data.data.message, 'success');

                    // Reset the form
                    leadForm.reset();

                    // Scroll to top of form
                    leadForm.scrollIntoView({ behavior: 'smooth', block: 'start' });
                } else {
                    // Show error message
                    showMessage(data.data.message || 'An error occurred. Please try again.', 'error');
                }
            })
            .catch(error => {
                console.error('Error submitting form:', error);

                // Re-enable the form
                setFormDisabled(false);
                submitButton.disabled = false;
                submitButton.textContent = originalText;
                submitButton.classList.remove('loading');
                submitButton.style.opacity = '1';

                // Show error message
                showMessage('An error occurred while submitting the form. Please try again later.', 'error');
            });
        });
    }

    /**
     * Helper function to disable/enable all form fields
     */
    function setFormDisabled(disabled) {
        const formElements = leadForm.querySelectorAll('input, select, textarea, button');
        formElements.forEach(function(element) {
            element.disabled = disabled;
            if (disabled) {
                element.classList.add('form-disabled');
            } else {
                element.classList.remove('form-disabled');
            }
        });
    }

    /**
     * Field Enhancements
     */
    function initFieldEnhancements() {
        // Real-time validation for email field
        const emailField = leadForm.querySelector('#lead_email');
        if (emailField) {
            emailField.addEventListener('blur', function() {
                const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                if (this.value && !emailRegex.test(this.value)) {
                    this.style.borderColor = '#e74c3c';
                    this.classList.add('error');
                    showFieldError(this, 'Please enter a valid email address.');
                } else {
                    this.style.borderColor = '#e1e1e1';
                    this.classList.remove('error');
                    hideFieldError(this);
                }
            });
        }

        // Website URL validation
        const websiteField = leadForm.querySelector('#lead_website');
        if (websiteField) {
            websiteField.addEventListener('blur', function() {
                if (this.value) {
                    if (!this.value.startsWith('http://') && !this.value.startsWith('https://')) {
                        this.value = 'https://' + this.value;
                    }

                    const urlRegex = /^https?:\/\/.+/;
                    if (!urlRegex.test(this.value)) {
                        this.style.borderColor = '#e74c3c';
                        this.classList.add('error');
                        showFieldError(this, 'Please enter a valid website URL.');
                    } else {
                        this.style.borderColor = '#e1e1e1';
                        this.classList.remove('error');
                        hideFieldError(this);
                    }
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
            const maxLength = 2000;
            const counterElement = document.createElement('div');
            counterElement.className = 'character-counter';
            counterElement.style.cssText = 'text-align: right; font-size: 12px; color: #7f8c8d; margin-top: 5px;';
            messageField.parentNode.appendChild(counterElement);

            function updateCounter() {
                const remaining = maxLength - messageField.value.length;
                counterElement.textContent = remaining + ' characters remaining';

                if (remaining < 100) {
                    counterElement.style.color = '#e74c3c';
                } else if (remaining < 200) {
                    counterElement.style.color = '#f39c12';
                } else {
                    counterElement.style.color = '#7f8c8d';
                }

                // Show warning when approaching limit
                if (remaining < 50) {
                    messageField.style.borderColor = '#f39c12';
                } else {
                    messageField.style.borderColor = '#e1e1e1';
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
    }

    /**
     * Location Validation
     */
    function initLocationValidation() {
        // Add a note about auto-detected location
        const locationSection = leadForm.querySelector('.form-section').nextElementSibling?.nextElementSibling;
        if (locationSection) {
            const cityField = leadForm.querySelector('#lead_city');
            const sourceField = leadForm.querySelector('#lead_location_source');

            if (cityField && cityField.value && sourceField) {
                const source = sourceField.value;
                let sourceText = '';

                switch(source) {
                    case 'geoip2':
                        sourceText = 'GeoLite2 database';
                        break;
                    case 'wpmail_smtp':
                        sourceText = 'WP Mail SMTP service';
                        break;
                    case 'api':
                        sourceText = 'IP geolocation service';
                        break;
                    default:
                        sourceText = 'automatic detection';
                }

                // Update the note text
                const noteElement = locationSection.querySelector('.form-section-note');
                if (noteElement) {
                    noteElement.innerHTML = `Location information has been automatically detected using ${sourceText}. Please verify and update if needed.`;
                }
            }
        }
    }

    /**
     * Progressive Enhancement
     */
    function initProgressiveEnhancement() {
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

        // Add smooth focus transitions
        const inputs = leadForm.querySelectorAll('input, textarea, select');
        inputs.forEach(function(input) {
            input.addEventListener('focus', function() {
                this.parentElement.classList.add('focused');
            });

            input.addEventListener('blur', function() {
                this.parentElement.classList.remove('focused');
            });
        });

        // Company size and industry dependent fields
        const companySizeField = leadForm.querySelector('#lead_company_size');
        const budgetField = leadForm.querySelector('#lead_budget');

        if (companySizeField && budgetField) {
            companySizeField.addEventListener('change', function() {
                // Suggest budget ranges based on company size
                if (this.value === '1-10') {
                    showFieldHint(budgetField, 'Small companies typically have budgets under $25k');
                } else if (this.value === '1000+') {
                    showFieldHint(budgetField, 'Enterprise companies often have larger budgets ($100k+)');
                }
            });
        }

        // Timeline and interest correlation
        const interestField = leadForm.querySelector('#lead_interest');
        const timelineField = leadForm.querySelector('#lead_timeline');

        if (interestField && timelineField) {
            interestField.addEventListener('change', function() {
                if (this.value === 'demo' || this.value === 'pricing') {
                    showFieldHint(timelineField, 'Demo and pricing inquiries are often short-term');
                }
            });
        }
    }

    // Helper functions
    function showMessage(text, type) {
        const existingMessage = document.querySelector('.lead-form-container .temp-message, .lead-form-container .lead-form-success, .lead-form-container .lead-form-error');
        if (existingMessage) {
            existingMessage.remove();
        }

        const messageDiv = document.createElement('div');
        messageDiv.className = 'temp-message lead-form-' + type;
        messageDiv.textContent = text;
        messageDiv.style.cssText = 'margin-bottom: 20px; animation: slideIn 0.3s ease; padding: 15px; border-radius: 3px;';

        if (type === 'success') {
            messageDiv.style.backgroundColor = '#d4edda';
            messageDiv.style.color = '#155724';
            messageDiv.style.border = '1px solid #c3e6cb';
        } else {
            messageDiv.style.backgroundColor = '#f8d7da';
            messageDiv.style.color = '#721c24';
            messageDiv.style.border = '1px solid #f5c6cb';
        }

        const formContainer = document.querySelector('.lead-form-container');
        formContainer.insertBefore(messageDiv, formContainer.firstChild);

        // Auto-hide after 10 seconds for error, keep success visible
        if (type === 'error') {
            setTimeout(function() {
                messageDiv.style.transition = 'opacity 0.5s ease';
                messageDiv.style.opacity = '0';
                setTimeout(function() {
                    messageDiv.remove();
                }, 500);
            }, 10000);
        }
    }

    function showFieldError(field, message) {
        hideFieldError(field);
        const errorDiv = document.createElement('div');
        errorDiv.className = 'field-error';
        errorDiv.textContent = message;
        errorDiv.style.cssText = 'color: #e74c3c; font-size: 12px; margin-top: 5px; animation: fadeIn 0.3s ease;';
        field.parentNode.appendChild(errorDiv);
    }

    function hideFieldError(field) {
        const existingError = field.parentNode.querySelector('.field-error');
        if (existingError) {
            existingError.remove();
        }
    }

    function showFieldHint(field, message) {
        // Remove existing hint
        const existingHint = field.parentNode.querySelector('.field-hint');
        if (existingHint) {
            existingHint.remove();
        }

        const hintDiv = document.createElement('div');
        hintDiv.className = 'field-hint';
        hintDiv.textContent = message;
        hintDiv.style.cssText = 'color: #3498db; font-size: 12px; margin-top: 5px; font-style: italic;';
        field.parentNode.appendChild(hintDiv);

        // Auto-hide after 5 seconds
        setTimeout(function() {
            hintDiv.style.transition = 'opacity 0.3s ease';
            hintDiv.style.opacity = '0';
            setTimeout(function() {
                hintDiv.remove();
            }, 300);
        }, 5000);
    }

    // Add CSS animations
    const style = document.createElement('style');
    style.textContent = `
        @keyframes slideIn {
            from { transform: translateY(-20px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        @keyframes pulse {
            0% { opacity: 1; }
            50% { opacity: 0.6; }
            100% { opacity: 1; }
        }

        .form-group.focused label {
            color: #3498db;
        }

        .field-hint {
            animation: fadeIn 0.3s ease;
        }

        .form-disabled {
            background-color: #f9f9f9 !important;
            cursor: not-allowed !important;
        }

        .lead-form-submit.loading {
            animation: pulse 1.5s infinite;
            position: relative;
        }
    `;
    document.head.appendChild(style);
});
