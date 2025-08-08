<?php
/**
 * Template Name: Lead Generation Form
 *
 * Custom page template for lead generation form
 *
 * @package SS Enterprises B2B
 * @since 1.0.0
 */

if (!is_front_page()) {
    get_header();
} else {
    echo '<style>
        .ast-container #primary #main .lead-form-container {
            max-width: 100%;
            background: #ffffff;
            margin: 40px 0;
        }
        .services-form {
            background-color: #ECEFF1;
            width: 100vw;
            margin-left: calc(-50vw + 50%);
        }
        .services-form .services-container {
            padding: 0px;
        }
        @media (max-width: 480px) {
            .lead-form input[type="text"], .lead-form input[type="email"], .lead-form input[type="tel"], .lead-form input[type="url"], .lead-form textarea, .lead-form select {
                padding: 6px 10px;
            }
        }
    </style>';
}

?>

<div class="ast-container">
    <div id="primary" class="content-area">
        <main id="main" class="site-main">

            <?php while ( have_posts() ) : the_post(); ?>

                <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

                    <header class="entry-header">
                        <?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>
                    </header><!-- .entry-header -->

                    <div class="entry-content">
                        <?php the_content(); ?>

                        <!-- Lead Generation Form -->
                        <div id="lead-form" class="lead-form-container">
                            <?php
                            // Display success message if form was submitted
                            if (isset($_GET['lead_form_sent']) && $_GET['lead_form_sent'] === '1') {
                                echo '<div class="lead-form-success">Thank you! Your message has been sent successfully. We will get back to you soon.</div>';
                            }

                            // Display error message if there was an issue
                            if (isset($_GET['lead_form_error']) && $_GET['lead_form_error'] === '1') {
                                echo '<div class="lead-form-error">Sorry, there was an error sending your message. Please try again or contact us directly.</div>';
                            }

                            // Get user's location data for auto-filling
                            $location_data = SSB2B_Geolocation::get_location_by_ip();
                            ?>

                            <form id="lead-generation-form" class="lead-form" method="post" novalidate>
                                <div id="form-response-container"></div>
                                <input type="hidden" name="lead_form_nonce" value="<?php echo esc_attr(wp_create_nonce('lead_form_nonce')); ?>" />

                                <!-- Personal Information Section -->
                                <div class="form-section">
                                    <h3 class="form-section-title">Personal Information</h3>

                                    <div class="form-row">
                                        <div class="form-group half-width">
                                            <label for="lead_first_name">First Name *</label>
                                            <input type="text" id="lead_first_name" name="lead_first_name" required>
                                        </div>
                                        <div class="form-group half-width">
                                            <label for="lead_last_name">Last Name *</label>
                                            <input type="text" id="lead_last_name" name="lead_last_name" required>
                                        </div>
                                    </div>

                                    <div class="form-row">
                                        <div class="form-group half-width">
                                            <label for="lead_email">Email Address *</label>
                                            <input type="email" id="lead_email" name="lead_email" required>
                                        </div>
                                        <div class="form-group half-width">
                                            <label for="lead_phone">Phone Number</label>
                                            <input type="tel" id="lead_phone" name="lead_phone">
                                        </div>
                                    </div>
                                </div>

                                <!-- Location Information Section (Auto-filled) -->
                                <div class="form-section">
                                    <h3 class="form-section-title">Location Information</h3>
                                    <p class="form-section-note">Location information has been automatically detected. Please verify and update if needed.</p>

                                    <div class="form-row">
                                        <div class="form-group half-width">
                                            <label for="lead_city">City</label>
                                            <input type="text" id="lead_city" name="lead_city" value="<?php echo esc_attr($location_data['city']); ?>">
                                        </div>
                                        <div class="form-group half-width">
                                            <label for="lead_state">State/Region</label>
                                            <input type="text" id="lead_state" name="lead_state" value="<?php echo esc_attr($location_data['state']); ?>">
                                        </div>
                                    </div>

                                    <div class="form-row">
                                        <div class="form-group half-width">
                                            <label for="lead_country">Country</label>
                                            <input type="text" id="lead_country" name="lead_country" value="<?php echo esc_attr($location_data['country']); ?>">
                                        </div>
                                        <div class="form-group half-width">
                                            <label for="lead_postal_code">ZIP/Postal Code</label>
                                            <input type="text" id="lead_postal_code" name="lead_postal_code" value="<?php echo esc_attr($location_data['postal_code']); ?>">
                                        </div>
                                    </div>

                                    <!-- Hidden fields for additional location data -->
                                    <input type="hidden" id="lead_latitude" name="lead_latitude" value="<?php echo esc_attr($location_data['latitude']); ?>">
                                    <input type="hidden" id="lead_longitude" name="lead_longitude" value="<?php echo esc_attr($location_data['longitude']); ?>">
                                    <input type="hidden" id="lead_timezone" name="lead_timezone" value="<?php echo esc_attr($location_data['timezone']); ?>">
                                    <input type="hidden" id="lead_ip_address" name="lead_ip_address" value="<?php echo esc_attr($location_data['ip']); ?>">
                                    <input type="hidden" id="lead_location_source" name="lead_location_source" value="<?php echo esc_attr($location_data['source']); ?>">
                                </div>

                                <!-- Consent and Submit Section -->
                                <div class="form-section">
                                    <div class="form-row">
                                        <div class="form-group">
                                            <input type="checkbox" id="lead_consent" name="lead_consent" required>
                                            <label for="lead_consent">I agree to receive communications and understand that my information will be handled according to the privacy policy. *</label>
                                        </div>
                                    </div>

                                    <div class="form-row">
                                        <div class="form-group">
                                            <input type="checkbox" id="lead_marketing_consent" name="lead_marketing_consent">
                                            <label for="lead_marketing_consent">I would like to receive updates about products, services, and industry insights.</label>
                                        </div>
                                    </div>

                                    <div class="form-row">
                                        <div class="form-group">
                                            <button type="submit" class="lead-form-submit">Send Message</button>
                                        </div>
                                    </div>
                                </div>

                            </form>
                        </div>

                    </div><!-- .entry-content -->

                </article><!-- #post-## -->

            <?php endwhile; ?>

        </main><!-- #main -->
    </div><!-- #primary -->
</div><!-- .ast-container -->

<?php if (!is_front_page()) {
    get_footer();
} ?>