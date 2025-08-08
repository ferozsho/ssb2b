<?php
/**
 * SS Enterprises B2B Theme functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package SS Enterprises B2B
 * @since 1.0.0
 */

ob_start();
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
    if (is_page_template('page-lead-form.php') || is_home() || is_front_page()) {
        wp_enqueue_style(
            'lead-form-css',
            get_stylesheet_directory_uri() . '/css/lead-form.css',
            array(),
            CHILD_THEME_SS_ENTERPRISES_B2B_VERSION
        );

        wp_enqueue_script(
            'lead-form-ajax-js',
            get_stylesheet_directory_uri() . '/js/lead-form-ajax.js',
            array('jquery'),
            CHILD_THEME_SS_ENTERPRISES_B2B_VERSION,
            true
        );

        wp_localize_script('lead-form-ajax-js', 'leadFormData', array(
            'ajaxurl' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('lead_form_nonce')
        ));
    }

    if (is_front_page()) {
        wp_enqueue_script(
            'faq-js',
            get_stylesheet_directory_uri() . '/js/faq.js',
            array(),
            CHILD_THEME_SS_ENTERPRISES_B2B_VERSION,
            true
        );
    }

    // Enqueue Contact Form CSS and JS
    if (is_page_template('page-contact-us.php')) {
        wp_enqueue_style(
            'contact-form-styles',
            get_stylesheet_directory_uri() . '/contact-form-styles.css',
            array(),
            CHILD_THEME_SS_ENTERPRISES_B2B_VERSION
        );

        wp_enqueue_script(
            'contact-form-js',
            get_stylesheet_directory_uri() . '/contact-form.js',
            array('jquery'),
            CHILD_THEME_SS_ENTERPRISES_B2B_VERSION,
            true
        );

        wp_localize_script('contact-form-js', 'contactFormData', array(
            'ajaxurl' => admin_url('admin-ajax.php'),
        ));
    }
}

add_action('wp_enqueue_scripts', 'child_enqueue_scripts');

add_action( 'wp_enqueue_scripts', 'child_enqueue_styles', 15 );

function fix_jquery_loading() {
    if (!is_admin()) {
        wp_deregister_script('jquery');
        wp_deregister_script('jquery-core');
        wp_deregister_script('jquery-migrate');

        wp_register_script('jquery-core', includes_url('/js/jquery/jquery.min.js'), array(), null, false);
        wp_register_script('jquery-migrate', includes_url('/js/jquery/jquery-migrate.min.js'), array('jquery-core'), null, false);
        wp_register_script('jquery', false, array('jquery-core', 'jquery-migrate'), null, false);

        wp_enqueue_script('jquery');
    }
}
add_action('wp_enqueue_scripts', 'fix_jquery_loading', 1);

function fix_astra_script_dependencies() {
    if (!is_admin()) {
        global $wp_scripts;

        if (isset($wp_scripts->registered['astra-theme-js'])) {
            $wp_scripts->registered['astra-theme-js']->deps = array('jquery');
        }
    }
}
add_action('wp_print_scripts', 'fix_astra_script_dependencies', 1);

/**
 * Include Geolocation Helper
 */
require_once get_stylesheet_directory() . '/includes/geolocation.php';

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

        // Location data
        $city = sanitize_text_field($_POST['lead_city']);
        $state = sanitize_text_field($_POST['lead_state']);
        $country = sanitize_text_field($_POST['lead_country']);
        $postal_code = sanitize_text_field($_POST['lead_postal_code']);
        $latitude = sanitize_text_field($_POST['lead_latitude']);
        $longitude = sanitize_text_field($_POST['lead_longitude']);
        $timezone = sanitize_text_field($_POST['lead_timezone']);
        $ip_address = sanitize_text_field($_POST['lead_ip_address']);
        $location_source = sanitize_text_field($_POST['lead_location_source']);

        // Basic validation
        if (empty($first_name) || empty($last_name) || empty($email) || !$consent) {
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
        $email_subject = '[' . $site_name . '] New Lead';

        $email_body = "New lead submission from " . $site_name . "\n\n";
        $email_body .= "=== PERSONAL INFORMATION ===\n";
        $email_body .= "Name: " . $first_name . " " . $last_name . "\n";
        $email_body .= "Email: " . $email . "\n";
        $email_body .= "Phone: " . $phone . "\n\n";

        $email_body .= "=== LOCATION INFORMATION ===\n";
        $email_body .= "City: " . $city . "\n";
        $email_body .= "State/Region: " . $state . "\n";
        $email_body .= "Country: " . $country . "\n";
        $email_body .= "ZIP/Postal Code: " . $postal_code . "\n";
        $email_body .= "Location Source: " . $location_source . "\n\n";

        $email_body .= "=== TECHNICAL DETAILS ===\n";
        $email_body .= "Submitted on: " . date('Y-m-d H:i:s') . "\n";
        $email_body .= "IP Address: " . $ip_address . "\n";
        if ($latitude && $longitude) {
            $email_body .= "Coordinates: " . $latitude . ", " . $longitude . "\n";
        }
        if ($timezone) {
            $email_body .= "Timezone: " . $timezone . "\n";
        }

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
        $auto_reply_body .= "Thank you for contacting us. We have received your inquiry and will get back to you as soon as possible.\n\n";
        $auto_reply_body .= "We appreciate your interest and will respond within 24 hours during business days.\n\n";
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
            'city' => $city,
            'state' => $state,
            'country' => $country,
            'postal_code' => $postal_code,
            'latitude' => $latitude,
            'longitude' => $longitude,
            'timezone' => $timezone,
            'ip_address' => $ip_address,
            'location_source' => $location_source,
            'user_agent' => isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : '',
            'created_at' => current_time('mysql')
        );

        // Save to custom lead_forms table
        $lead_data['user_agent'] = isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : '';
        $lead_data['created_at'] = current_time('mysql');

        global $wpdb;
        $table_name = $wpdb->prefix . 'lead_forms';

        $wpdb->insert($table_name, $lead_data);

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

// add_action('init', 'register_lead_submission_post_type');

/**
 * Add custom columns to Lead Submissions list
 */
function lead_submission_columns($columns) {
    $columns = array(
        'cb' => $columns['cb'],
        'title' => 'Name & Company',
        'email' => 'Email',
        'phone' => 'Phone',
        'location' => 'Location',
        'date' => 'Date'
    );
    return $columns;
}

// add_filter('manage_lead_submission_posts_columns', 'lead_submission_columns');

/**
 * Populate custom columns
 */
function lead_submission_column_content($column, $post_id) {
    switch ($column) {
        case 'email':
            echo get_post_meta($post_id, 'email', true);
            break;
        case 'phone':
            $phone = get_post_meta($post_id, 'phone', true);
            echo $phone ? $phone : '—';
            break;
        case 'location':
            $city = get_post_meta($post_id, 'city', true);
            $state = get_post_meta($post_id, 'state', true);
            $country = get_post_meta($post_id, 'country', true);
            $location_parts = array_filter([$city, $state, $country]);
            echo !empty($location_parts) ? implode(', ', $location_parts) : '—';
            break;
    }
}

// add_action('manage_lead_submission_posts_custom_column', 'lead_submission_column_content', 10, 2);

/**
 * Contact Form Functionality
 */
function enqueue_contact_form_assets() {
    if (is_page_template('page-contact-us.php')) {
        wp_enqueue_style('contact-form-css', get_stylesheet_directory_uri() . '/contact-form.css', array(), CHILD_THEME_SS_ENTERPRISES_B2B_VERSION);

        wp_enqueue_script('contact-form-js', get_stylesheet_directory_uri() . '/contact-form.js', array('jquery'), CHILD_THEME_SS_ENTERPRISES_B2B_VERSION, true);

        wp_localize_script('contact-form-js', 'contactFormData', array(
            'ajaxurl' => admin_url('admin-ajax.php')
        ));
    }
}
add_action('wp_enqueue_scripts', 'enqueue_contact_form_assets');

/**
 * Contact Form AJAX Handler
 */
function handle_contact_form_submit() {
    // Check nonce for security
    if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'contact_form_submit')) {
        wp_send_json_error(array('message' => 'Security verification failed'));
        die();
    }

    // Sanitize form data
    $first_name = isset($_POST['firstName']) ? sanitize_text_field($_POST['firstName']) : '';
    $last_name = isset($_POST['lastName']) ? sanitize_text_field($_POST['lastName']) : '';
    $country_code = isset($_POST['countryCode']) ? sanitize_text_field($_POST['countryCode']) : '';
    $phone = isset($_POST['phone']) ? sanitize_text_field($_POST['phone']) : '';
    $full_phone = $country_code . ' ' . $phone;
    $email = isset($_POST['email']) ? sanitize_email($_POST['email']) : '';
    $message = isset($_POST['message']) ? sanitize_textarea_field($_POST['message']) : '';

    // Geolocation data
    $city = isset($_POST['city']) ? sanitize_text_field($_POST['city']) : '';
    $state = isset($_POST['state']) ? sanitize_text_field($_POST['state']) : '';
    $country = isset($_POST['country']) ? sanitize_text_field($_POST['country']) : '';
    $postal_code = isset($_POST['postal_code']) ? sanitize_text_field($_POST['postal_code']) : '';
    $latitude = isset($_POST['latitude']) ? sanitize_text_field($_POST['latitude']) : '';
    $longitude = isset($_POST['longitude']) ? sanitize_text_field($_POST['longitude']) : '';
    $timezone = isset($_POST['timezone']) ? sanitize_text_field($_POST['timezone']) : '';
    $ip_address = isset($_POST['ip_address']) ? sanitize_text_field($_POST['ip_address']) : $_SERVER['REMOTE_ADDR'];
    $location_source = isset($_POST['location_source']) ? sanitize_text_field($_POST['location_source']) : 'form';

    // Validate required fields
    if (empty($first_name) || empty($last_name) || empty($email) || empty($message)) {
        wp_send_json_error(array('message' => 'Please fill all required fields'));
        die();
    }

    // Validate email
    if (!is_email($email)) {
        wp_send_json_error(array('message' => 'Please enter a valid email address'));
        die();
    }

    // Get admin email
    $admin_email = get_option('admin_email');
    $site_name = get_bloginfo('name');

    // Prepare email content
    $subject = sprintf('[%s] New Contact Form Submission from %s %s', $site_name, $first_name, $last_name);

    $email_body = "You have received a new contact form submission:\n\n";
    $email_body .= "Name: " . $first_name . " " . $last_name . "\n";
    $email_body .= "Email: " . $email . "\n";
    $email_body .= "Phone: " . $full_phone . "\n\n";
    $email_body .= "Message:\n" . $message . "\n\n";
    $email_body .= "This message was sent from the contact form on " . $site_name . " (" . get_site_url() . ")";

    // Email headers
    $headers = array();
    $headers[] = 'From: ' . $first_name . ' ' . $last_name . ' <' . $email . '>';
    $headers[] = 'Reply-To: ' . $first_name . ' ' . $last_name . ' <' . $email . '>';
    $headers[] = 'Content-Type: text/plain; charset=UTF-8';

    // Send email (WP Mail SMTP plugin will handle the actual sending)
    $sent = wp_mail($admin_email, $subject, $email_body, $headers);

    // Send auto-reply to customer
    $auto_reply_subject = 'Thank you for contacting ' . $site_name;
    $auto_reply_body = "Dear " . $first_name . ",\n\n";
    $auto_reply_body .= "Thank you for contacting us. We have received your message and will get back to you shortly.\n\n";
    $auto_reply_body .= "We received the following information:\n";
    $auto_reply_body .= "Name: " . $first_name . " " . $last_name . "\n";
    $auto_reply_body .= "Email: " . $email . "\n";
    $auto_reply_body .= "Phone: " . $full_phone . "\n\n";
    $auto_reply_body .= "Here's a copy of your message:\n";
    $auto_reply_body .= "------------------------------\n";
    $auto_reply_body .= $message . "\n";
    $auto_reply_body .= "------------------------------\n\n";
    $auto_reply_body .= "We appreciate your interest and will respond as soon as possible.\n\n";
    $auto_reply_body .= "Best regards,\n";
    $auto_reply_body .= $site_name . " Team";

    $auto_reply_headers = array();
    $auto_reply_headers[] = 'From: ' . $site_name . ' <' . $admin_email . '>';
    $auto_reply_headers[] = 'Content-Type: text/plain; charset=UTF-8';

    wp_mail($email, $auto_reply_subject, $auto_reply_body, $auto_reply_headers);

    // Store submission in database
    global $wpdb;
    $table_name = $wpdb->prefix . 'contact_form_submissions';

    // Check if table exists
    $table_exists = $wpdb->get_var("SHOW TABLES LIKE '{$table_name}'") === $table_name;

    // If table exists, store the data
    if ($table_exists) {
        $data = array(
            'first_name' => $first_name,
            'last_name' => $last_name,
            'email' => $email,
            'phone' => $full_phone, // Save the full phone number with country code
            'message' => $message,
            'city' => $city,
            'state' => $state,
            'country' => $country,
            'postal_code' => $postal_code,
            'latitude' => $latitude,
            'longitude' => $longitude,
            'timezone' => $timezone,
            'ip_address' => $ip_address,
            'location_source' => $location_source,
            'user_agent' => isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : '',
            'created_at' => current_time('mysql')
        );

        $wpdb->insert($table_name, $data);
    }

    // Send response
    if ($sent) {
        wp_send_json_success(array('message' => 'Your message has been sent successfully!'));
    } else {
        wp_send_json_error(array('message' => 'There was an error sending your message. Please try again.'));
    }

    die();
}
add_action('wp_ajax_contact_form_submit', 'handle_contact_form_submit');
add_action('wp_ajax_nopriv_contact_form_submit', 'handle_contact_form_submit');

/**
 * Lead Form AJAX Handler
 */
function handle_lead_form_ajax_submit() {
    // Check nonce for security - check both possible nonce field names
    $nonce = isset($_POST['nonce']) ? $_POST['nonce'] : '';
    $lead_form_nonce = isset($_POST['lead_form_nonce']) ? $_POST['lead_form_nonce'] : '';

    if ((!$nonce || !wp_verify_nonce($nonce, 'lead_form_nonce')) &&
        (!$lead_form_nonce || !wp_verify_nonce($lead_form_nonce, 'lead_form_nonce'))) {
        wp_send_json_error(array('message' => 'Security verification failed. Please refresh the page and try again.'));
        die();
    }

    // Sanitize and validate form data
    $first_name = isset($_POST['lead_first_name']) ? sanitize_text_field($_POST['lead_first_name']) : '';
    $last_name = isset($_POST['lead_last_name']) ? sanitize_text_field($_POST['lead_last_name']) : '';
    $email = isset($_POST['lead_email']) ? sanitize_email($_POST['lead_email']) : '';
    $phone = isset($_POST['lead_phone']) ? sanitize_text_field($_POST['lead_phone']) : '';

    // Location data
    $city = isset($_POST['lead_city']) ? sanitize_text_field($_POST['lead_city']) : '';
    $state = isset($_POST['lead_state']) ? sanitize_text_field($_POST['lead_state']) : '';
    $country = isset($_POST['lead_country']) ? sanitize_text_field($_POST['lead_country']) : '';
    $postal_code = isset($_POST['lead_postal_code']) ? sanitize_text_field($_POST['lead_postal_code']) : '';
    $latitude = isset($_POST['lead_latitude']) ? sanitize_text_field($_POST['lead_latitude']) : '';
    $longitude = isset($_POST['lead_longitude']) ? sanitize_text_field($_POST['lead_longitude']) : '';
    $timezone = isset($_POST['lead_timezone']) ? sanitize_text_field($_POST['lead_timezone']) : '';
    $ip_address = isset($_POST['lead_ip_address']) ? sanitize_text_field($_POST['lead_ip_address']) : $_SERVER['REMOTE_ADDR'];
    $location_source = isset($_POST['lead_location_source']) ? sanitize_text_field($_POST['lead_location_source']) : 'form';

    // Validate required fields
    if (empty($first_name) || empty($last_name) || empty($email) || !$consent) {
        wp_send_json_error(array('message' => 'Please fill in all required fields'));
        die();
    }

    if (!is_email($email)) {
        wp_send_json_error(array('message' => 'Please enter a valid email address'));
        die();
    }

    // Get admin email
    $admin_email = get_option('admin_email');
    $site_name = get_bloginfo('name');

    // Prepare email content
    $email_subject = '[' . $site_name . '] New Lead';

    $email_body = "New lead submission from " . $site_name . "\n\n";
    $email_body .= "=== PERSONAL INFORMATION ===\n";
    $email_body .= "Name: " . $first_name . " " . $last_name . "\n";
    $email_body .= "Email: " . $email . "\n";
    $email_body .= "Phone: " . $phone . "\n\n";

    $email_body .= "=== LOCATION INFORMATION ===\n";
    $email_body .= "City: " . $city . "\n";
    $email_body .= "State/Region: " . $state . "\n";
    $email_body .= "Country: " . $country . "\n";
    $email_body .= "ZIP/Postal Code: " . $postal_code . "\n";
    $email_body .= "Location Source: " . $location_source . "\n\n";

    $email_body .= "=== TECHNICAL DETAILS ===\n";
    $email_body .= "Submitted on: " . date('Y-m-d H:i:s') . "\n";
    $email_body .= "IP Address: " . $ip_address . "\n";
    if ($latitude && $longitude) {
        $email_body .= "Coordinates: " . $latitude . ", " . $longitude . "\n";
    }
    if ($timezone) {
        $email_body .= "Timezone: " . $timezone . "\n";
    }

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
    $auto_reply_body .= "Thank you for contacting us. We have received your inquiry and will get back to you as soon as possible.\n\n";
    $auto_reply_body .= "We appreciate your interest and will respond within 24 hours during business days.\n\n";
    $auto_reply_body .= "Best regards,\n";
    $auto_reply_body .= $site_name . " Team\n";

    $auto_reply_headers = array();
    $auto_reply_headers[] = 'From: ' . $site_name . ' <' . $admin_email . '>';
    $auto_reply_headers[] = 'Content-Type: text/plain; charset=UTF-8';

    wp_mail($email, $auto_reply_subject, $auto_reply_body, $auto_reply_headers);

    // Store lead in database
    $lead_data = array(
        'first_name' => $first_name,
        'last_name' => $last_name,
        'email' => $email,
        'phone' => $phone,
        'city' => $city,
        'state' => $state,
        'country' => $country,
        'postal_code' => $postal_code,
        'latitude' => $latitude,
        'longitude' => $longitude,
        'timezone' => $timezone,
        'ip_address' => $ip_address,
        'location_source' => $location_source,
        'user_agent' => isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : '',
        'created_at' => current_time('mysql')
    );

    global $wpdb;
    $table_name = $wpdb->prefix . 'lead_forms';

    $insert_result = $wpdb->insert($table_name, $lead_data);

    if ($sent && $insert_result) {
        wp_send_json_success(array(
            'message' => 'Thank you! Your message has been sent successfully. We will get back to you soon.',
            'lead_id' => $wpdb->insert_id
        ));
    } else {
        wp_send_json_error(array('message' => 'There was an error processing your submission. Please try again or contact us directly.'));
    }

    die();
}
add_action('wp_ajax_lead_form_submit', 'handle_lead_form_ajax_submit');
add_action('wp_ajax_nopriv_lead_form_submit', 'handle_lead_form_ajax_submit');

/**
 * Create database tables for form submissions on theme activation
 */
function create_form_submissions_tables() {
    global $wpdb;
    $charset_collate = $wpdb->get_charset_collate();

    // Contact form submissions table
    $contact_table = $wpdb->prefix . 'contact_form_submissions';

    $sql_contact = "CREATE TABLE IF NOT EXISTS $contact_table (
        id bigint(20) NOT NULL AUTO_INCREMENT,
        first_name varchar(100) NOT NULL,
        last_name varchar(100) NOT NULL,
        email varchar(100) NOT NULL,
        phone varchar(100) NOT NULL,
        message text NOT NULL,
        city varchar(100) NOT NULL,
        state varchar(100) NOT NULL,
        country varchar(100) NOT NULL,
        postal_code varchar(50) NOT NULL,
        latitude varchar(50) NOT NULL,
        longitude varchar(50) NOT NULL,
        timezone varchar(100) NOT NULL,
        location_source varchar(50) NOT NULL,
        ip_address varchar(100) NOT NULL,
        user_agent text NOT NULL,
        created_at datetime NOT NULL,
        PRIMARY KEY  (id)
    ) $charset_collate;";

    // Lead form submissions table
    $lead_table = $wpdb->prefix . 'lead_forms';

    $sql_lead = "CREATE TABLE IF NOT EXISTS $lead_table (
        id bigint(20) NOT NULL AUTO_INCREMENT,
        first_name varchar(100) NOT NULL,
        last_name varchar(100) NOT NULL,
        email varchar(100) NOT NULL,
        phone varchar(100) NOT NULL,
        city varchar(100) NOT NULL,
        state varchar(100) NOT NULL,
        country varchar(100) NOT NULL,
        postal_code varchar(50) NOT NULL,
        latitude varchar(50) NOT NULL,
        longitude varchar(50) NOT NULL,
        timezone varchar(100) NOT NULL,
        ip_address varchar(100) NOT NULL,
        location_source varchar(50) NOT NULL,
        user_agent text NOT NULL,
        created_at datetime NOT NULL,
        PRIMARY KEY  (id)
    ) $charset_collate;";

    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql_contact);
    dbDelta($sql_lead);
}
add_action('after_switch_theme', 'create_form_submissions_tables');

// Run table creation on init to ensure tables exist
add_action('init', 'create_form_submissions_tables');

/**
 * Register admin pages for form submissions
 */
function register_form_submissions_admin_pages() {
    add_menu_page(
        'Form Submissions',
        'Form Submissions',
        'manage_options',
        'form-submissions',
        'contact_form_submissions_page',
        'dashicons-feedback',
        30
    );

    add_submenu_page(
        'form-submissions',
        'Lead Submissions',
        'Lead Submissions',
        'manage_options',
        'lead-submissions',
        'lead_form_submissions_page'
    );
}
add_action('admin_menu', 'register_form_submissions_admin_pages');

/**
 * Render contact form submissions admin page
 */
function contact_form_submissions_page() {
    require_once(get_stylesheet_directory() . '/admin/contact-submissions.php');
}

/**
 * Render lead form submissions admin page
 */
function lead_form_submissions_page() {
    require_once(get_stylesheet_directory() . '/admin/lead-submissions.php');
}

/**
 * AJAX handler for getting submission details
 */
function get_submission_details() {
    // Check nonce
    if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'get_submission_details')) {
        wp_send_json_error(array('message' => 'Security verification failed'));
    }

    // Get submission ID
    $submission_id = isset($_POST['submission_id']) ? intval($_POST['submission_id']) : 0;

    if (!$submission_id) {
        wp_send_json_error(array('message' => 'Invalid submission ID'));
    }

    // Get submission details
    global $wpdb;
    $table_name = $wpdb->prefix . 'contact_form_submissions';

    $submission = $wpdb->get_row(
        $wpdb->prepare(
            "SELECT * FROM $table_name WHERE id = %d",
            $submission_id
        ),
        ARRAY_A
    );

    if (!$submission) {
        wp_send_json_error(array('message' => 'Submission not found'));
    }

    // Format date
    $date_format = get_option('date_format');
    $time_format = get_option('time_format');
    $timestamp = strtotime($submission['created_at']);
    $submission['formatted_date'] = date_i18n($date_format . ' ' . $time_format, $timestamp);

    wp_send_json_success($submission);
}
add_action('wp_ajax_get_submission_details', 'get_submission_details');

/**
 * AJAX handler for getting lead submission details
 */
function get_lead_submission_details() {
    // Check nonce
    if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'get_lead_submission_details')) {
        wp_send_json_error(array('message' => 'Security verification failed'));
    }

    // Get submission ID
    $submission_id = isset($_POST['submission_id']) ? intval($_POST['submission_id']) : 0;

    if (!$submission_id) {
        wp_send_json_error(array('message' => 'Invalid submission ID'));
    }

    // Get submission details
    global $wpdb;
    $table_name = $wpdb->prefix . 'lead_forms';

    $submission = $wpdb->get_row(
        $wpdb->prepare(
            "SELECT * FROM $table_name WHERE id = %d",
            $submission_id
        ),
        ARRAY_A
    );

    if (!$submission) {
        wp_send_json_error(array('message' => 'Submission not found'));
    }

    // Format date
    $date_format = get_option('date_format');
    $time_format = get_option('time_format');
    $timestamp = strtotime($submission['created_at']);
    $submission['formatted_date'] = date_i18n($date_format . ' ' . $time_format, $timestamp);

    wp_send_json_success($submission);
}
add_action('wp_ajax_get_lead_submission_details', 'get_lead_submission_details');
