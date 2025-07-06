<?php
// This file helps debug lead submissions display issues
require_once('../../../../wp-load.php');

global $wpdb;
$table_name = $wpdb->prefix . 'lead_forms';

echo "<h2>Lead Submissions Table Debug</h2>";

// Check if table exists
$table_exists = $wpdb->get_var("SHOW TABLES LIKE '{$table_name}'") === $table_name;

if ($table_exists) {
    echo "<p style='color:green;'>✅ The {$table_name} table exists.</p>";

    // Get table structure
    $structure = $wpdb->get_results("DESCRIBE {$table_name}");

    echo "<h3>Table Structure:</h3>";
    echo "<ul>";
    foreach ($structure as $column) {
        echo "<li><strong>{$column->Field}</strong> ({$column->Type})</li>";
    }
    echo "</ul>";

    // Count records
    $count = $wpdb->get_var("SELECT COUNT(*) FROM {$table_name}");
    echo "<p>Number of records in the table: {$count}</p>";

    if ($count > 0) {
        // Show sample data
        $submissions = $wpdb->get_results("SELECT * FROM {$table_name} LIMIT 5", ARRAY_A);

        echo "<h3>Sample Data (up to 5 records):</h3>";
        echo "<table border='1' cellpadding='5' style='border-collapse: collapse;'>";
        echo "<tr>";
        foreach (array_keys($submissions[0]) as $column) {
            echo "<th>{$column}</th>";
        }
        echo "</tr>";

        foreach ($submissions as $row) {
            echo "<tr>";
            foreach ($row as $value) {
                echo "<td>" . (strlen($value) > 50 ? substr($value, 0, 50) . "..." : $value) . "</td>";
            }
            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "<p style='color:orange;'>⚠️ The table exists but has no records.</p>";
    }
} else {
    echo "<p style='color:red;'>❌ The {$table_name} table does not exist!</p>";
}

echo "<p>Go to <a href='" . admin_url('admin.php?page=lead-submissions&debug=1') . "'>Lead Submissions admin page</a> with debug info.</p>";
?>
