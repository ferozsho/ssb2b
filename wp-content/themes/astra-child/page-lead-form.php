<?php
/**
 * Template Name: Lead Generation Form
 * 
 * Custom page template for lead generation form
 *
 * @package SS Enterprises B2B
 * @since 1.0.0
 */

get_header(); ?>

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
                            ?>
                            
                            <form id="lead-generation-form" class="lead-form" method="post" action="">
                                <?php wp_nonce_field('lead_form_nonce', 'lead_form_nonce_field'); ?>
                                
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
                                    <div class="form-group">
                                        <label for="lead_company">Company Name</label>
                                        <input type="text" id="lead_company" name="lead_company">
                                    </div>
                                </div>
                                
                                <div class="form-row">
                                    <div class="form-group">
                                        <label for="lead_subject">Subject *</label>
                                        <input type="text" id="lead_subject" name="lead_subject" required>
                                    </div>
                                </div>
                                
                                <div class="form-row">
                                    <div class="form-group">
                                        <label for="lead_message">Message *</label>
                                        <textarea id="lead_message" name="lead_message" rows="5" required></textarea>
                                    </div>
                                </div>
                                
                                <div class="form-row">
                                    <div class="form-group">
                                        <label for="lead_interest">Area of Interest</label>
                                        <select id="lead_interest" name="lead_interest">
                                            <option value="">Select an option</option>
                                            <option value="general_inquiry">General Inquiry</option>
                                            <option value="partnership">Partnership Opportunities</option>
                                            <option value="product_info">Product Information</option>
                                            <option value="pricing">Pricing Information</option>
                                            <option value="support">Technical Support</option>
                                            <option value="other">Other</option>
                                        </select>
                                    </div>
                                </div>
                                
                                <div class="form-row">
                                    <div class="form-group">
                                        <input type="checkbox" id="lead_consent" name="lead_consent" required>
                                        <label for="lead_consent">I agree to receive communications and understand that my information will be handled according to the privacy policy. *</label>
                                    </div>
                                </div>
                                
                                <div class="form-row">
                                    <div class="form-group">
                                        <button type="submit" name="submit_lead_form" class="lead-form-submit">Send Message</button>
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

<?php get_footer(); ?>