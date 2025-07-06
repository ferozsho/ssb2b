<?php
if (!defined('ABSPATH')) {
    exit;
}

// Create a custom list table class for contact form submissions
if (!class_exists('WP_List_Table')) {
    require_once(ABSPATH . 'wp-admin/includes/class-wp-list-table.php');
}

class Contact_Form_Submissions_List_Table extends WP_List_Table {

    public function __construct() {
        parent::__construct(array(
            'singular' => 'contact_submission',
            'plural' => 'contact_submissions',
            'ajax' => false
        ));
    }

    public function get_columns() {
        $columns = array(
            'cb' => '<input type="checkbox" />',
            'full_name' => 'Name',
            'email' => 'Email',
            'phone' => 'Phone',
            'message' => 'Message',
            'location' => 'Location',
            'created_at' => 'Date'
        );

        return $columns;
    }

    public function get_sortable_columns() {
        $sortable_columns = array(
            'full_name' => array('last_name', true),
            'email' => array('email', false),
            'location' => array('country', false),
            'created_at' => array('created_at', true)
        );

        return $sortable_columns;
    }

    protected function column_default($item, $column_name) {
        switch ($column_name) {
            case 'email':
                return '<a href="mailto:' . esc_attr($item[$column_name]) . '">' . esc_html($item[$column_name]) . '</a>';
            case 'message':
                return wp_trim_words(esc_html($item[$column_name]), 15, '...');
            case 'created_at':
                $date_format = get_option('date_format');
                $time_format = get_option('time_format');
                $timestamp = strtotime($item[$column_name]);
                return date_i18n($date_format . ' ' . $time_format, $timestamp);
            default:
                return esc_html($item[$column_name]);
        }
    }

    protected function column_full_name($item) {
        $actions = array(
            'view' => sprintf(
                '<a href="#" class="view-submission" data-id="%s">View</a>',
                $item['id']
            ),
            'delete' => sprintf(
                '<a href="%s" class="delete-submission" onclick="return confirm(\'Are you sure you want to delete this submission?\');">Delete</a>',
                wp_nonce_url(
                    add_query_arg(
                        array(
                            'page' => 'form-submissions',
                            'action' => 'delete',
                            'submission' => $item['id'],
                        ),
                        admin_url('admin.php')
                    ),
                    'delete_submission_' . $item['id']
                )
            )
        );

        $full_name = esc_html($item['first_name'] . ' ' . $item['last_name']);

        return sprintf('%1$s %2$s', $full_name, $this->row_actions($actions));
    }

    protected function column_cb($item) {
        return sprintf(
            '<input type="checkbox" name="submissions[]" value="%s" />',
            $item['id']
        );
    }

    protected function column_location($item) {
        $location_parts = [];

        if (!empty($item['city'])) $location_parts[] = esc_html($item['city']);
        if (!empty($item['state'])) $location_parts[] = esc_html($item['state']);
        if (!empty($item['country'])) $location_parts[] = esc_html($item['country']);

        $location = !empty($location_parts) ? implode(', ', $location_parts) : 'Not available';

        return $location;
    }

    public function prepare_items() {
        global $wpdb;

        $table_name = $wpdb->prefix . 'contact_form_submissions';

        $per_page = 20;
        $current_page = $this->get_pagenum();
        $total_items = $wpdb->get_var("SELECT COUNT(id) FROM $table_name");

        $this->set_pagination_args(array(
            'total_items' => $total_items,
            'per_page' => $per_page,
            'total_pages' => ceil($total_items / $per_page)
        ));

        $orderby = !empty($_REQUEST['orderby']) ? sanitize_sql_orderby($_REQUEST['orderby']) : 'created_at';
        $order = !empty($_REQUEST['order']) ? sanitize_text_field($_REQUEST['order']) : 'DESC';

        $search = isset($_POST['s']) ? sanitize_text_field($_POST['s']) : '';

        $where = '';
        if (!empty($search)) {
            $where = $wpdb->prepare(
                " WHERE first_name LIKE %s OR last_name LIKE %s OR email LIKE %s OR message LIKE %s OR city LIKE %s OR state LIKE %s OR country LIKE %s OR postal_code LIKE %s",
                '%' . $wpdb->esc_like($search) . '%',
                '%' . $wpdb->esc_like($search) . '%',
                '%' . $wpdb->esc_like($search) . '%',
                '%' . $wpdb->esc_like($search) . '%',
                '%' . $wpdb->esc_like($search) . '%',
                '%' . $wpdb->esc_like($search) . '%',
                '%' . $wpdb->esc_like($search) . '%',
                '%' . $wpdb->esc_like($search) . '%'
            );
        }

        $sql = "SELECT * FROM $table_name $where ORDER BY $orderby $order LIMIT $per_page";
        $sql .= ' OFFSET ' . ($current_page - 1) * $per_page;

        $this->items = $wpdb->get_results($sql, ARRAY_A);
    }

    protected function get_bulk_actions() {
        $actions = array(
            'delete' => 'Delete'
        );
        return $actions;
    }

    public function process_bulk_action() {
        global $wpdb;
        $table_name = $wpdb->prefix . 'contact_form_submissions';

        if ('delete' === $this->current_action()) {
            $submission_id = isset($_GET['submission']) ? absint($_GET['submission']) : 0;

            if ($submission_id && check_admin_referer('delete_submission_' . $submission_id)) {
                $wpdb->delete(
                    $table_name,
                    array('id' => $submission_id),
                    array('%d')
                );

                wp_safe_redirect(
                    add_query_arg(
                        array(
                            'page' => 'form-submissions',
                            'deleted' => '1',
                        ),
                        admin_url('admin.php')
                    )
                );
                exit;
            }
        }

        if (isset($_POST['action']) && $_POST['action'] === 'delete' && isset($_POST['submissions'])) {
            $submission_ids = array_map('intval', $_POST['submissions']);

            if (!empty($submission_ids) && check_admin_referer('bulk-contact_submissions')) {
                foreach ($submission_ids as $id) {
                    $wpdb->delete(
                        $table_name,
                        array('id' => $id),
                        array('%d')
                    );
                }

                wp_safe_redirect(
                    add_query_arg(
                        array(
                            'page' => 'form-submissions',
                            'bulk_deleted' => count($submission_ids),
                        ),
                        admin_url('admin.php')
                    )
                );
                exit;
            }
        }
    }
}

// Process any actions
$table = new Contact_Form_Submissions_List_Table();
$table->process_bulk_action();

// Display notifications
if (isset($_GET['deleted']) && $_GET['deleted'] == '1') {
    echo '<div class="updated"><p>Submission deleted successfully.</p></div>';
}

if (isset($_GET['bulk_deleted'])) {
    $count = intval($_GET['bulk_deleted']);
    echo '<div class="updated"><p>' . sprintf(_n('%d submission deleted successfully.', '%d submissions deleted successfully.', $count), $count) . '</p></div>';
}
?>

<style>
.wp-list-table .column-location {
    width: 15%;
}

#location-details {
    border-left: 4px solid #46b450;
}

#location-details h3 {
    color: #2c3338;
    font-size: 1.2em;
    margin-bottom: 10px;
}

#location-details p {
    margin: 5px 0;
}

@media screen and (max-width: 782px) {
    .wp-list-table .column-location {
        display: none;
    }
}
</style>

<div class="wrap">
    <h1 class="wp-heading-inline">Contact Form Submissions</h1>

    <form method="post">
        <?php
        $table->prepare_items();
        $table->search_box('Search Submissions', 'search-submissions');
        $table->display();
        ?>
    </form>
</div>

<!-- Modal for viewing submission details -->
<div id="submission-modal" style="display: none; position: fixed; z-index: 9999; left: 0; top: 0; width: 100%; height: 100%; overflow: auto; background-color: rgba(0,0,0,0.4);">
    <div style="background-color: #fefefe; margin: 10% auto; padding: 20px; border: 1px solid #888; width: 60%; max-width: 700px; border-radius: 4px;">
        <span style="float: right; font-size: 28px; font-weight: bold; cursor: pointer;" id="close-modal">&times;</span>
        <h2>Submission Details</h2>
        <div id="submission-content" style="margin-top: 20px;">
            <div id="loading" style="text-align: center;">Loading...</div>
            <div id="submission-data" style="display: none;">
                <p><strong>Name:</strong> <span id="modal-name"></span></p>
                <p><strong>Email:</strong> <span id="modal-email"></span></p>
                <p><strong>Phone:</strong> <span id="modal-phone"></span></p>
                <p><strong>Date:</strong> <span id="modal-date"></span></p>
                <p><strong>IP Address:</strong> <span id="modal-ip"></span></p>

                <div id="location-details" style="background: #f8f8f8; padding: 15px; border-radius: 4px; margin-bottom: 15px;">
                    <h3 style="margin-top: 0;">Location Information</h3>
                    <p><strong>City:</strong> <span id="modal-city">Not available</span></p>
                    <p><strong>State:</strong> <span id="modal-state">Not available</span></p>
                    <p><strong>Country:</strong> <span id="modal-country">Not available</span></p>
                    <p><strong>Postal Code:</strong> <span id="modal-postal-code">Not available</span></p>
                    <p><strong>Latitude/Longitude:</strong> <span id="modal-lat-long">Not available</span></p>
                    <p><strong>Timezone:</strong> <span id="modal-timezone">Not available</span></p>
                    <p><strong>Source:</strong> <span id="modal-location-source">Not available</span></p>
                </div>

                <p><strong>Message:</strong></p>
                <div id="modal-message" style="background: #f9f9f9; padding: 15px; border-left: 4px solid #0073aa; margin-top: 10px;"></div>
            </div>
        </div>
    </div>
</div>

<script>
jQuery(document).ready(function($) {
    // Handle view submission click
    $('.view-submission').on('click', function(e) {
        e.preventDefault();
        var submissionId = $(this).data('id');

        // Show modal
        $('#submission-modal').show();
        $('#loading').show();
        $('#submission-data').hide();

        // Fetch submission data via AJAX
        $.ajax({
            url: ajaxurl,
            type: 'POST',
            data: {
                action: 'get_submission_details',
                submission_id: submissionId,
                nonce: '<?php echo wp_create_nonce('get_submission_details'); ?>'
            },
            success: function(response) {
                if (response.success) {
                    var data = response.data;
                    $('#modal-name').text(data.first_name + ' ' + data.last_name);
                    $('#modal-email').text(data.email);
                    $('#modal-phone').text(data.phone);
                    $('#modal-date').text(data.formatted_date);
                    $('#modal-ip').text(data.ip_address);
                    $('#modal-message').html(data.message.replace(/\n/g, '<br>'));

                    // Display geolocation data
                    $('#modal-city').text(data.city || 'Not available');
                    $('#modal-state').text(data.state || 'Not available');
                    $('#modal-country').text(data.country || 'Not available');
                    $('#modal-postal-code').text(data.postal_code || 'Not available');

                    // Display latitude/longitude if available
                    if (data.latitude && data.longitude) {
                        $('#modal-lat-long').text(data.latitude + ', ' + data.longitude);
                    } else {
                        $('#modal-lat-long').text('Not available');
                    }

                    $('#modal-timezone').text(data.timezone || 'Not available');
                    $('#modal-location-source').text(data.location_source || 'Not available');

                    $('#loading').hide();
                    $('#submission-data').show();
                } else {
                    $('#loading').text('Error loading submission details.');
                }
            },
            error: function() {
                $('#loading').text('Error loading submission details.');
            }
        });
    });

    // Close modal when clicking the X
    $('#close-modal').on('click', function() {
        $('#submission-modal').hide();
    });

    // Close modal when clicking outside the content
    $(window).on('click', function(event) {
        if ($(event.target).is('#submission-modal')) {
            $('#submission-modal').hide();
        }
    });
});
</script>
