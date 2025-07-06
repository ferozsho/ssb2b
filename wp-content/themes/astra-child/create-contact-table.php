<?php
/**
 * Script to create the contact form submissions table in the WordPress database
 * This file should be accessed directly from the browser to create the table
 */

// Define WordPress content directory
$wp_load_path = dirname(dirname(dirname(dirname(__FILE__)))) . '/wp-load.php';
require_once($wp_load_path);

// Security check - make sure the user is an admin
if (!current_user_can('manage_options')) {
    die('Access denied');
}

// Create the table
global $wpdb;
$charset_collate = $wpdb->get_charset_collate();

// Contact form submissions table
$contact_table = $wpdb->prefix . 'contact_form_submissions';    $sql_contact = "CREATE TABLE IF NOT EXISTS $contact_table (
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

require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
$result = dbDelta($sql_contact);

// Display the results
echo '<h1>Contact Form Table Creation Result</h1>';
echo '<p>Attempted to create the contact form submissions table.</p>';

if ($wpdb->get_var("SHOW TABLES LIKE '$contact_table'") == $contact_table) {
    echo '<p style="color: green;">Success! The contact form submissions table has been created successfully.</p>';
} else {
    echo '<p style="color: red;">Error! The contact form submissions table could not be created. Please check your database permissions.</p>';
}

echo '<p><a href="' . admin_url() . '">Return to Dashboard</a></p>';
