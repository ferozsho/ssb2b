<?php
require_once(dirname(dirname(dirname(dirname(__FILE__)))) . '/wp-load.php');

global $wpdb;
$lead_table = $wpdb->prefix . 'lead_forms';

// Check if table exists
$table_exists = $wpdb->get_var("SHOW TABLES LIKE '{$lead_table}'") === $lead_table;

echo "<h2>Table Check Results</h2>";

if ($table_exists) {
    echo "<p style='color:green;'>✅ The lead_forms table exists.</p>";

    // Count records
    $count = $wpdb->get_var("SELECT COUNT(*) FROM {$lead_table}");
    echo "<p>Number of records in the table: {$count}</p>";

    if ($count > 0) {
        echo "<p>Sample data:</p>";
        $sample = $wpdb->get_row("SELECT * FROM {$lead_table} LIMIT 1", ARRAY_A);
        echo "<pre>";
        print_r($sample);
        echo "</pre>";
    } else {
        echo "<p style='color:orange;'>⚠️ The table exists but has no records.</p>";
        echo "<p>Running sample data creation function...</p>";

        // Create sample data
        $sample_data = array(
            'first_name' => 'John',
            'last_name' => 'Doe',
            'email' => 'john.doe@example.com',
            'phone' => '+1 (555) 123-4567',
            'job_title' => 'Marketing Manager',
            'department' => 'Marketing',
            'company' => 'Example Corp',
            'website' => 'https://example.com',
            'industry' => 'Technology',
            'company_size' => '50-100',
            'city' => 'New York',
            'state' => 'NY',
            'country' => 'United States',
            'postal_code' => '10001',
            'latitude' => '40.7128',
            'longitude' => '-74.0060',
            'timezone' => 'America/New_York',
            'subject' => 'Product Inquiry',
            'message' => 'I am interested in learning more about your services. Please contact me at your earliest convenience.',
            'interest' => 'Software_Services',
            'budget' => '$5000_$10000',
            'timeline' => '1_3_months',
            'how_heard' => 'Google_Search',
            'marketing_consent' => 1,
            'ip_address' => '127.0.0.1',
            'location_source' => 'manual',
            'user_agent' => 'Sample Lead Entry',
            'created_at' => current_time('mysql')
        );

        $result = $wpdb->insert($lead_table, $sample_data);

        if ($result) {
            echo "<p style='color:green;'>✅ Sample data created successfully.</p>";
        } else {
            echo "<p style='color:red;'>❌ Failed to create sample data. Error: " . $wpdb->last_error . "</p>";
        }
    }
} else {
    echo "<p style='color:red;'>❌ The lead_forms table does not exist.</p>";
    echo "<p>Running table creation function...</p>";

    // Create the table
    $charset_collate = $wpdb->get_charset_collate();
    $sql_lead = "CREATE TABLE IF NOT EXISTS $lead_table (
        id bigint(20) NOT NULL AUTO_INCREMENT,
        first_name varchar(100) NOT NULL,
        last_name varchar(100) NOT NULL,
        email varchar(100) NOT NULL,
        phone varchar(100) NOT NULL,
        job_title varchar(100) NOT NULL,
        department varchar(100) NOT NULL,
        company varchar(100) NOT NULL,
        website varchar(255) NOT NULL,
        industry varchar(100) NOT NULL,
        company_size varchar(100) NOT NULL,
        city varchar(100) NOT NULL,
        state varchar(100) NOT NULL,
        country varchar(100) NOT NULL,
        postal_code varchar(50) NOT NULL,
        latitude varchar(50) NOT NULL,
        longitude varchar(50) NOT NULL,
        timezone varchar(100) NOT NULL,
        subject varchar(255) NOT NULL,
        message text NOT NULL,
        interest varchar(100) NOT NULL,
        budget varchar(100) NOT NULL,
        timeline varchar(100) NOT NULL,
        how_heard varchar(100) NOT NULL,
        marketing_consent tinyint(1) NOT NULL DEFAULT 0,
        ip_address varchar(100) NOT NULL,
        location_source varchar(50) NOT NULL,
        user_agent text NOT NULL,
        created_at datetime NOT NULL,
        PRIMARY KEY  (id)
    ) $charset_collate;";

    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql_lead);

    // Check if table was created
    $table_exists_now = $wpdb->get_var("SHOW TABLES LIKE '{$lead_table}'") === $lead_table;

    if ($table_exists_now) {
        echo "<p style='color:green;'>✅ The lead_forms table was created successfully.</p>";

        // Create sample data
        $sample_data = array(
            'first_name' => 'John',
            'last_name' => 'Doe',
            'email' => 'john.doe@example.com',
            'phone' => '+1 (555) 123-4567',
            'job_title' => 'Marketing Manager',
            'department' => 'Marketing',
            'company' => 'Example Corp',
            'website' => 'https://example.com',
            'industry' => 'Technology',
            'company_size' => '50-100',
            'city' => 'New York',
            'state' => 'NY',
            'country' => 'United States',
            'postal_code' => '10001',
            'latitude' => '40.7128',
            'longitude' => '-74.0060',
            'timezone' => 'America/New_York',
            'subject' => 'Product Inquiry',
            'message' => 'I am interested in learning more about your services. Please contact me at your earliest convenience.',
            'interest' => 'Software_Services',
            'budget' => '$5000_$10000',
            'timeline' => '1_3_months',
            'how_heard' => 'Google_Search',
            'marketing_consent' => 1,
            'ip_address' => '127.0.0.1',
            'location_source' => 'manual',
            'user_agent' => 'Sample Lead Entry',
            'created_at' => current_time('mysql')
        );

        $result = $wpdb->insert($lead_table, $sample_data);

        if ($result) {
            echo "<p style='color:green;'>✅ Sample data created successfully.</p>";
        } else {
            echo "<p style='color:red;'>❌ Failed to create sample data. Error: " . $wpdb->last_error . "</p>";
        }
    } else {
        echo "<p style='color:red;'>❌ Failed to create the lead_forms table. Error: " . $wpdb->last_error . "</p>";
    }
}

echo "<p>You can now go to <a href='". admin_url('admin.php?page=lead-submissions') ."'>Lead Submissions</a> in the admin area.</p>";
?>
