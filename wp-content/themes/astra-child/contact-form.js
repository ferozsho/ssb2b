(function($) {
    'use strict';

    $(document).ready(function() {
        const contactForm = $('#contact-form');
        const formFields = {
            firstName: $('#first-name'),
            lastName: $('#last-name'),
            phone: $('#phone'),
            email: $('#email'),
            message: $('#message')
        };

        const errorMessages = {
            firstName: $('#first-name-error'),
            lastName: $('#last-name-error'),
            phone: $('#phone-error'),
            email: $('#email-error'),
            message: $('#message-error')
        };

        const submitButton = $('#submit-button');
        const loadingSpinner = $('.loading-spinner');
        const successMessage = $('.contact-form-success');

        // Form validation
        function validateForm() {
            let isValid = true;

            // Reset error messages
            Object.values(errorMessages).forEach(error => error.text(''));

            // First Name validation
            if (formFields.firstName.val().trim() === '') {
                errorMessages.firstName.text('First name is required');
                isValid = false;
            }

            // Last Name validation
            if (formFields.lastName.val().trim() === '') {
                errorMessages.lastName.text('Last name is required');
                isValid = false;
            }

            // Phone validation
            const phoneValue = formFields.phone.val().trim();
            if (phoneValue === '') {
                errorMessages.phone.text('Phone number is required');
                isValid = false;
            } else if (!/^[0-9+\-\s()]{10,15}$/.test(phoneValue)) {
                errorMessages.phone.text('Please enter a valid phone number');
                isValid = false;
            }

            // Email validation
            const emailValue = formFields.email.val().trim();
            if (emailValue === '') {
                errorMessages.email.text('Email address is required');
                isValid = false;
            } else if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(emailValue)) {
                errorMessages.email.text('Please enter a valid email address');
                isValid = false;
            }

            // Message validation
            if (formFields.message.val().trim() === '') {
                errorMessages.message.text('Message is required');
                isValid = false;
            }

            return isValid;
        }

        // Reset form
        function resetForm() {
            contactForm[0].reset();
            Object.values(errorMessages).forEach(error => error.text(''));
        }

        // Form submission
        contactForm.on('submit', function(e) {
            e.preventDefault();

            if (!validateForm()) {
                return;
            }

            submitButton.prop('disabled', true);
            loadingSpinner.show();

            const formData = {
                action: 'contact_form_submit',
                firstName: formFields.firstName.val().trim(),
                lastName: formFields.lastName.val().trim(),
                phone: formFields.phone.val().trim(),
                email: formFields.email.val().trim(),
                message: formFields.message.val().trim(),
                nonce: $('#contact_form_nonce').val()
            };

            $.ajax({
                url: contactFormData.ajaxurl,
                type: 'POST',
                data: formData,
                success: function(response) {
                    submitButton.prop('disabled', false);
                    loadingSpinner.hide();

                    if (response.success) {
                        contactForm.hide();
                        successMessage.fadeIn();
                        resetForm();

                        // Reset form after 5 seconds
                        setTimeout(function() {
                            successMessage.fadeOut(function() {
                                contactForm.fadeIn();
                            });
                        }, 5000);
                    } else {
                        alert(response.data.message || 'Something went wrong. Please try again.');
                    }
                },
                error: function() {
                    submitButton.prop('disabled', false);
                    loadingSpinner.hide();
                    alert('Something went wrong. Please try again later.');
                }
            });
        });
    });
})(jQuery);
