<?php
/**
 * Template Name: Contact Us Page
 * A custom page template for the Contact Us form with AJAX submission
 */

get_header();
?>

<div id="primary" class="content-area">
    <main id="main" class="site-main">
        <div class="contact-form-container">
            <div class="contact-form-wrapper">
                <h1 class="entry-title"><?php the_title(); ?></h1>

                <div class="contact-form-success" style="display: none;">
                    <div class="success-message">
                        <h3>Thank you for contacting us!</h3>
                        <p>We have received your message and will get back to you shortly.</p>
                    </div>
                </div>

                <form id="contact-form" class="contact-form">
                    <div class="form-row">
                        <div class="form-group">
                            <label for="first-name">First Name <span class="required">*</span></label>
                            <input type="text" id="first-name" name="first-name" class="form-control" required>
                            <span class="error-message" id="first-name-error"></span>
                        </div>
                        <div class="form-group">
                            <label for="last-name">Last Name <span class="required">*</span></label>
                            <input type="text" id="last-name" name="last-name" class="form-control" required>
                            <span class="error-message" id="last-name-error"></span>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="phone">Phone <span class="required">*</span></label>
                            <input type="tel" id="phone" name="phone" class="form-control" required>
                            <span class="error-message" id="phone-error"></span>
                        </div>
                        <div class="form-group">
                            <label for="email">E-mail <span class="required">*</span></label>
                            <input type="email" id="email" name="email" class="form-control" required>
                            <span class="error-message" id="email-error"></span>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="message">Message <span class="required">*</span></label>
                        <textarea id="message" name="message" class="form-control" rows="5" required></textarea>
                        <span class="error-message" id="message-error"></span>
                    </div>

                    <div class="form-group">
                        <?php wp_nonce_field('contact_form_submit', 'contact_form_nonce'); ?>
                        <button type="submit" id="submit-button" class="submit-button">Submit</button>
                        <div class="loading-spinner" style="display: none;">
                            <span class="spinner"></span>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </main>
</div>

<?php get_footer(); ?>
