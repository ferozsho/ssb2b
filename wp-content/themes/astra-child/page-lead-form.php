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
                        <div class="lead-form-container">
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

                                    <div class="form-row">
                                        <div class="form-group half-width">
                                            <label for="lead_job_title">Job Title</label>
                                            <input type="text" id="lead_job_title" name="lead_job_title" placeholder="e.g. Marketing Manager">
                                        </div>
                                        <div class="form-group half-width">
                                            <label for="lead_department">Department</label>
                                            <select id="lead_department" name="lead_department">
                                                <option value="">Select Department</option>
                                                <option value="executive">Executive/Management</option>
                                                <option value="sales">Sales</option>
                                                <option value="marketing">Marketing</option>
                                                <option value="operations">Operations</option>
                                                <option value="finance">Finance</option>
                                                <option value="hr">Human Resources</option>
                                                <option value="it">IT/Technology</option>
                                                <option value="purchasing">Purchasing/Procurement</option>
                                                <option value="other">Other</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <!-- Company Information Section -->
                                <div class="form-section">
                                    <h3 class="form-section-title">Company Information</h3>

                                    <div class="form-row">
                                        <div class="form-group half-width">
                                            <label for="lead_company">Company Name *</label>
                                            <input type="text" id="lead_company" name="lead_company" required>
                                        </div>
                                        <div class="form-group half-width">
                                            <label for="lead_website">Company Website</label>
                                            <input type="url" id="lead_website" name="lead_website" placeholder="https://example.com">
                                        </div>
                                    </div>

                                    <div class="form-row">
                                        <div class="form-group half-width">
                                            <label for="lead_industry">Industry</label>
                                            <select id="lead_industry" name="lead_industry">
                                                <option value="">Select Industry</option>
                                                <option value="manufacturing">Manufacturing</option>
                                                <option value="technology">Technology</option>
                                                <option value="healthcare">Healthcare</option>
                                                <option value="finance">Finance</option>
                                                <option value="retail">Retail</option>
                                                <option value="education">Education</option>
                                                <option value="construction">Construction</option>
                                                <option value="hospitality">Hospitality</option>
                                                <option value="transportation">Transportation</option>
                                                <option value="consulting">Consulting</option>
                                                <option value="other">Other</option>
                                            </select>
                                        </div>
                                        <div class="form-group half-width">
                                            <label for="lead_company_size">Company Size</label>
                                            <select id="lead_company_size" name="lead_company_size">
                                                <option value="">Select Size</option>
                                                <option value="1-10">1-10 employees</option>
                                                <option value="11-50">11-50 employees</option>
                                                <option value="51-200">51-200 employees</option>
                                                <option value="201-500">201-500 employees</option>
                                                <option value="501-1000">501-1000 employees</option>
                                                <option value="1000+">1000+ employees</option>
                                            </select>
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

                                <!-- Inquiry Information Section -->
                                <div class="form-section">
                                    <h3 class="form-section-title">Inquiry Details</h3>

                                    <div class="form-row">
                                        <div class="form-group">
                                            <label for="lead_subject">Subject *</label>
                                            <input type="text" id="lead_subject" name="lead_subject" required>
                                        </div>
                                    </div>

                                    <div class="form-row">
                                        <div class="form-group half-width">
                                            <label for="lead_interest">Area of Interest</label>
                                            <select id="lead_interest" name="lead_interest">
                                                <option value="">Select an option</option>
                                                <option value="general_inquiry">General Inquiry</option>
                                                <option value="partnership">Partnership Opportunities</option>
                                                <option value="product_info">Product Information</option>
                                                <option value="pricing">Pricing Information</option>
                                                <option value="support">Technical Support</option>
                                                <option value="demo">Request Demo</option>
                                                <option value="consultation">Consultation</option>
                                                <option value="other">Other</option>
                                            </select>
                                        </div>
                                        <div class="form-group half-width">
                                            <label for="lead_budget">Estimated Budget</label>
                                            <select id="lead_budget" name="lead_budget">
                                                <option value="">Select Budget Range</option>
                                                <option value="under_10k">Under $10,000</option>
                                                <option value="10k_25k">$10,000 - $25,000</option>
                                                <option value="25k_50k">$25,000 - $50,000</option>
                                                <option value="50k_100k">$50,000 - $100,000</option>
                                                <option value="100k_250k">$100,000 - $250,000</option>
                                                <option value="250k_plus">$250,000+</option>
                                                <option value="not_disclosed">Prefer not to disclose</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="form-row">
                                        <div class="form-group half-width">
                                            <label for="lead_timeline">Timeline</label>
                                            <select id="lead_timeline" name="lead_timeline">
                                                <option value="">Select Timeline</option>
                                                <option value="immediate">Immediate (within 1 month)</option>
                                                <option value="short_term">Short-term (1-3 months)</option>
                                                <option value="medium_term">Medium-term (3-6 months)</option>
                                                <option value="long_term">Long-term (6+ months)</option>
                                                <option value="exploring">Just exploring options</option>
                                            </select>
                                        </div>
                                        <div class="form-group half-width">
                                            <label for="lead_how_heard">How did you hear about us?</label>
                                            <select id="lead_how_heard" name="lead_how_heard">
                                                <option value="">Select source</option>
                                                <option value="search_engine">Search Engine</option>
                                                <option value="social_media">Social Media</option>
                                                <option value="referral">Referral</option>
                                                <option value="advertisement">Advertisement</option>
                                                <option value="trade_show">Trade Show/Event</option>
                                                <option value="website">Company Website</option>
                                                <option value="email">Email Campaign</option>
                                                <option value="other">Other</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="form-row">
                                        <div class="form-group">
                                            <label for="lead_message">Message *</label>
                                            <textarea id="lead_message" name="lead_message" rows="5" required placeholder="Please provide details about your inquiry, requirements, or any specific questions you have."></textarea>
                                        </div>
                                    </div>
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