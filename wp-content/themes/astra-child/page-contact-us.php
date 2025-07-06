<?php
/**
 * Template Name: Contact Us Page
 * A custom page template for the Contact Us form with AJAX submission
 */

get_header();
?>

<div id="primary" class="content-area" style="margin-top: 0;">
    <main id="main" class="site-main">
        <div class="contact-section">
            <h1 class="entry-title"><?php the_title(); ?></h1>

            <div class="contact-container">
                <div class="map-column">
                    <div class="google-map">
                        <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3806.291060361037!2d78.4350577751797!3d17.44121508290379!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3bcb90cadd3753a5%3A0x7a6aef63e7e143c6!2sMedvarsity%20Technologies%20Private%20Limited!5e0!3m2!1sen!2sin!4v1719619280901!5m2!1sen!2sin" width="100%" height="500" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                    </div>
                </div>

                <div class="form-column">
                    <div class="contact-form-wrapper">
                        <h2>GET IN TOUCH</h2>
                        <h3>Send us a message</h3>

                        <div class="contact-form-success" style="display: none;">
                            <div class="success-message">
                                <h3>Thank you for contacting us!</h3>
                                <p>We have received your message and will get back to you shortly.</p>
                                <div class="form-group">
                                    <button type="button" id="submit-another" class="submit-another">Submit Another Message</button>
                                </div>
                            </div>
                        </div>

                        <form id="contact-form" class="contact-form">
                            <div class="form-group">
                                <input type="text" id="first-name" name="first-name" class="form-control" placeholder="First Name *" required>
                                <span class="error-message" id="first-name-error"></span>
                            </div>

                            <div class="form-group">
                                <input type="text" id="last-name" name="last-name" class="form-control" placeholder="Last Name *" required>
                                <span class="error-message" id="last-name-error"></span>
                            </div>

                            <div class="form-group">
                                <input type="tel" id="phone" name="phone" class="form-control" placeholder="Phone *" required>
                                <span class="error-message" id="phone-error"></span>
                            </div>

                            <div class="form-group">
                                <input type="email" id="email" name="email" class="form-control" placeholder="E-mail *" required>
                                <span class="error-message" id="email-error"></span>
                            </div>

                            <div class="form-group">
                                <textarea id="message" name="message" class="form-control" rows="5" placeholder="Message *" required></textarea>
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
            </div>

            <div class="contact-info-container">
                <div class="contact-info">
                    <div class="info-box">
                        <h3>Head Office</h3>
                        <p>Road No. 12, NBT Colony, Banjara Hills, Hyderabad, Telangana 500034.</p>
                    </div>

                    <div class="info-box">
                        <h3>Email</h3>
                        <p><a href="mailto:info@enterprisesb2b.com">info@enterprisesb2b.com</a></p>
                    </div>
                </div>
            </div>
        </div>
    </main>
</div>

<?php get_footer(); ?>
