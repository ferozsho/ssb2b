<?php
/**
 * SS Enterprises B2B Theme functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package SS Enterprises B2B
 * @since 1.0.0
 */

/**
 * Define Constants
 */
define( 'CHILD_THEME_SS_ENTERPRISES_B2B_VERSION', '1.0.0' );

/**
 * Enqueue styles
 */
function child_enqueue_styles() {

	wp_enqueue_style( 'ss-enterprises-b2b-theme-css', get_stylesheet_directory_uri() . '/style.css', array('astra-theme-css'), CHILD_THEME_SS_ENTERPRISES_B2B_VERSION, 'all' );

}

/**
 * Enqueue Lead Form JavaScript
 */
function child_enqueue_scripts() {
    // Only load on pages using the lead form template
    if (is_page_template('page-lead-form.php')) {
        wp_enqueue_script(
            'lead-form-js',
            get_stylesheet_directory_uri() . '/js/lead-form.js',
            array(),
            CHILD_THEME_SS_ENTERPRISES_B2B_VERSION,
            true
        );
    }
}

add_action('wp_enqueue_scripts', 'child_enqueue_scripts');

add_action( 'wp_enqueue_scripts', 'child_enqueue_styles', 15 );

/**
 * Lead Generation Form Handler
 */
function handle_lead_generation_form() {
    // Check if form was submitted
    if (isset($_POST['submit_lead_form'])) {
        
        // Verify nonce for security
        if (!wp_verify_nonce($_POST['lead_form_nonce_field'], 'lead_form_nonce')) {
            wp_redirect(add_query_arg('lead_form_error', '1', get_permalink()));
            exit;
        }
        
        // Sanitize and validate form data
        $first_name = sanitize_text_field($_POST['lead_first_name']);
        $last_name = sanitize_text_field($_POST['lead_last_name']);
        $email = sanitize_email($_POST['lead_email']);
        $phone = sanitize_text_field($_POST['lead_phone']);
        $company = sanitize_text_field($_POST['lead_company']);
        $subject = sanitize_text_field($_POST['lead_subject']);
        $message = sanitize_textarea_field($_POST['lead_message']);
        $interest = sanitize_text_field($_POST['lead_interest']);
        $consent = isset($_POST['lead_consent']) ? 1 : 0;
        
        // Basic validation
        if (empty($first_name) || empty($last_name) || empty($email) || empty($subject) || empty($message) || !$consent) {
            wp_redirect(add_query_arg('lead_form_error', '1', get_permalink()));
            exit;
        }
        
        if (!is_email($email)) {
            wp_redirect(add_query_arg('lead_form_error', '1', get_permalink()));
            exit;
        }
        
        // Get admin email or use default
        $admin_email = get_option('admin_email');
        $site_name = get_bloginfo('name');
        
        // Prepare email content
        $email_subject = '[' . $site_name . '] New Lead: ' . $subject;
        
        $email_body = "New lead submission from " . $site_name . "\n\n";
        $email_body .= "Name: " . $first_name . " " . $last_name . "\n";
        $email_body .= "Email: " . $email . "\n";
        $email_body .= "Phone: " . $phone . "\n";
        $email_body .= "Company: " . $company . "\n";
        $email_body .= "Subject: " . $subject . "\n";
        $email_body .= "Area of Interest: " . $interest . "\n\n";
        $email_body .= "Message:\n" . $message . "\n\n";
        $email_body .= "Submitted on: " . date('Y-m-d H:i:s') . "\n";
        $email_body .= "IP Address: " . $_SERVER['REMOTE_ADDR'] . "\n";
        
        // Email headers
        $headers = array();
        $headers[] = 'From: ' . $site_name . ' <' . $admin_email . '>';
        $headers[] = 'Reply-To: ' . $first_name . ' ' . $last_name . ' <' . $email . '>';
        $headers[] = 'Content-Type: text/plain; charset=UTF-8';
        
        // Send email using wp_mail (WP Mail SMTP will handle the actual sending)
        $sent = wp_mail($admin_email, $email_subject, $email_body, $headers);
        
        // Send auto-reply to customer
        $auto_reply_subject = 'Thank you for contacting ' . $site_name;
        $auto_reply_body = "Dear " . $first_name . ",\n\n";
        $auto_reply_body .= "Thank you for contacting us. We have received your message and will get back to you as soon as possible.\n\n";
        $auto_reply_body .= "Your message details:\n";
        $auto_reply_body .= "Subject: " . $subject . "\n";
        $auto_reply_body .= "Message: " . $message . "\n\n";
        $auto_reply_body .= "Best regards,\n";
        $auto_reply_body .= $site_name . " Team\n";
        
        $auto_reply_headers = array();
        $auto_reply_headers[] = 'From: ' . $site_name . ' <' . $admin_email . '>';
        $auto_reply_headers[] = 'Content-Type: text/plain; charset=UTF-8';
        
        wp_mail($email, $auto_reply_subject, $auto_reply_body, $auto_reply_headers);
        
        // Store lead in database (optional)
        $lead_data = array(
            'first_name' => $first_name,
            'last_name' => $last_name,
            'email' => $email,
            'phone' => $phone,
            'company' => $company,
            'subject' => $subject,
            'message' => $message,
            'interest' => $interest,
            'submission_date' => current_time('mysql'),
            'ip_address' => $_SERVER['REMOTE_ADDR']
        );
        
        // Save to custom table or post meta (for now, we'll save as custom post type)
        $lead_post = array(
            'post_title' => 'Lead: ' . $first_name . ' ' . $last_name,
            'post_content' => $message,
            'post_status' => 'private',
            'post_type' => 'lead_submission',
            'meta_input' => $lead_data
        );
        
        wp_insert_post($lead_post);
        
        // Redirect with success message
        if ($sent) {
            wp_redirect(add_query_arg('lead_form_sent', '1', get_permalink()));
        } else {
            wp_redirect(add_query_arg('lead_form_error', '1', get_permalink()));
        }
        exit;
    }
}

add_action('template_redirect', 'handle_lead_generation_form');

/**
 * Register Lead Submission Custom Post Type
 */
function register_lead_submission_post_type() {
    $args = array(
        'label' => 'Lead Submissions',
        'public' => false,
        'show_ui' => true,
        'show_in_menu' => true,
        'capability_type' => 'post',
        'hierarchical' => false,
        'rewrite' => false,
        'query_var' => false,
        'menu_icon' => 'dashicons-email-alt',
        'supports' => array('title', 'editor', 'custom-fields'),
        'labels' => array(
            'name' => 'Lead Submissions',
            'singular_name' => 'Lead Submission',
            'menu_name' => 'Leads',
            'add_new' => 'Add New Lead',
            'add_new_item' => 'Add New Lead',
            'edit' => 'Edit',
            'edit_item' => 'Edit Lead',
            'new_item' => 'New Lead',
            'view' => 'View Lead',
            'view_item' => 'View Lead',
            'search_items' => 'Search Leads',
            'not_found' => 'No Leads Found',
            'not_found_in_trash' => 'No Leads Found in Trash',
            'parent' => 'Parent Lead',
        ),
    );
    register_post_type('lead_submission', $args);
}

add_action('init', 'register_lead_submission_post_type');

/**
 * Add custom columns to Lead Submissions list
 */
function lead_submission_columns($columns) {
    $columns = array(
        'cb' => $columns['cb'],
        'title' => 'Name',
        'email' => 'Email',
        'phone' => 'Phone',
        'company' => 'Company',
        'subject' => 'Subject',
        'date' => 'Date'
    );
    return $columns;
}

add_filter('manage_lead_submission_posts_columns', 'lead_submission_columns');

/**
 * Populate custom columns
 */
function lead_submission_column_content($column, $post_id) {
    switch ($column) {
        case 'email':
            echo get_post_meta($post_id, 'email', true);
            break;
        case 'phone':
            echo get_post_meta($post_id, 'phone', true);
            break;
        case 'company':
            echo get_post_meta($post_id, 'company', true);
            break;
        case 'subject':
            echo get_post_meta($post_id, 'subject', true);
            break;
    }
}

add_action('manage_lead_submission_posts_custom_column', 'lead_submission_column_content', 10, 2);