(function($) {
    'use strict';

    $(document).ready(function() {
        // Hide all error messages initially
        $('.error-message').hide();
        const contactForm = $('#contact-form');
        const formFields = {
            firstName: $('#first-name'),
            lastName: $('#last-name'),
            countryCode: $('#country-code'),
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
        const submitAnother = $('#submit-another');

        // Form validation
        function validateForm() {
            let isValid = true;

            // Reset error messages
            Object.values(errorMessages).forEach(error => error.text(''));

            // First Name validation
            if (formFields.firstName.val().trim() === '') {
                errorMessages.firstName.show();
                formFields.firstName.addClass('error-field');
                isValid = false;
            } else {
                errorMessages.firstName.hide();
                formFields.firstName.removeClass('error-field');
            }

            // Last Name validation
            if (formFields.lastName.val().trim() === '') {
                errorMessages.lastName.show();
                formFields.lastName.addClass('error-field');
                isValid = false;
            } else {
                errorMessages.lastName.hide();
                formFields.lastName.removeClass('error-field');
            }

            // Phone validation
            const phoneValue = formFields.phone.val().trim();
            if (phoneValue === '') {
                errorMessages.phone.show();
                formFields.phone.addClass('error-field');
                isValid = false;
            } else if (!/^[0-9\s]{6,15}$/.test(phoneValue)) {
                errorMessages.phone.show().html('<i class="error-icon">⚠</i> Please enter a valid phone number');
                formFields.phone.addClass('error-field');
                isValid = false;
            } else {
                errorMessages.phone.hide();
                formFields.phone.removeClass('error-field');
            }

            // Email validation
            const emailValue = formFields.email.val().trim();
            if (emailValue === '') {
                errorMessages.email.show().html('<i class="error-icon">⚠</i> This field is required.');
                formFields.email.addClass('error-field');
                isValid = false;
            } else if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(emailValue)) {
                errorMessages.email.show();
                formFields.email.addClass('error-field');
                isValid = false;
            } else {
                errorMessages.email.hide();
                formFields.email.removeClass('error-field');
            }

            // Message validation
            if (formFields.message.val().trim() === '') {
                errorMessages.message.show();
                formFields.message.addClass('error-field');
                isValid = false;
            } else {
                errorMessages.message.hide();
                formFields.message.removeClass('error-field');
            }

            return isValid;
        }

        // Reset form
        function resetForm() {
            contactForm[0].reset();
            $('.error-message').hide();
            $('.form-control').removeClass('error-field');
        }

        // Initialize country code dropdown functionality
        function initCountryCodeDropdown() {
            const countryCodeDropdown = $('#country-code');

            // Handle dropdown changes if needed
            countryCodeDropdown.on('change', function() {
                // You could add special handling for different countries if needed
            });
        }

        // Initialize country code dropdown
        initCountryCodeDropdown();

        // Set up real-time validation for form fields
        Object.values(formFields).forEach(field => {
            if (field.attr('id') !== 'country-code') {
                field.on('input', function() {
                    const fieldId = $(this).attr('id');
                    const errorField = $('#' + fieldId + '-error');

                    // Simple validation based on whether the field is empty
                    if ($(this).val().trim() === '') {
                        $(this).addClass('error-field');
                        errorField.show();
                    } else {
                        $(this).removeClass('error-field');
                        errorField.hide();

                        // Additional validation for specific fields
                        if (fieldId === 'email') {
                            const emailValue = $(this).val().trim();
                            if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(emailValue)) {
                                $(this).addClass('error-field');
                                errorField.show();
                            }
                        } else if (fieldId === 'phone') {
                            const phoneValue = $(this).val().trim();
                            if (!/^[0-9\s]{6,15}$/.test(phoneValue)) {
                                $(this).addClass('error-field');
                                errorField.show();
                            }
                        }
                    }
                });
            }
        });

        // Event handler for "Submit Another Message" button
        submitAnother.on('click', function() {
            successMessage.hide();
            contactForm.fadeIn();
        });

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
                countryCode: formFields.countryCode.val(),
                phone: formFields.phone.val().trim(),
                email: formFields.email.val().trim(),
                message: formFields.message.val().trim(),
                nonce: $('#contact_form_nonce').val()
            };            submitButton.text('Submitting...');

            $.ajax({
                url: contactFormData.ajaxurl,
                type: 'POST',
                data: formData,
                success: function(response) {
                    if (response.success) {
                        // Show success message
                        contactForm.hide();
                        successMessage.fadeIn();
                        resetForm();

                        // Reset button state
                        submitButton.prop('disabled', false);
                        submitButton.text('Submit');
                        loadingSpinner.hide();
                        $('.form-error-message').hide();

                        // Don't automatically hide the success message
                        // User can close it by clicking submit another message
                    } else {
                        // Show error on form
                        submitButton.prop('disabled', false);
                        submitButton.text('Submit');
                        loadingSpinner.hide();

                        // Display error message on form
                        const errorMessage = response.data.message || 'Something went wrong. Please try again.';
                        $('.form-error-message').text(errorMessage).show();
                    }
                },
                error: function() {
                    // Reset button and show error
                    submitButton.prop('disabled', false);
                    submitButton.text('Submit');
                    loadingSpinner.hide();

                    // Display error message on form
                    $('.form-error-message').text('Something went wrong. Please try again later.').show();
                }
            });
        });
    });
})(jQuery);
