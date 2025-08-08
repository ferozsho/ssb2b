<?php
/**
 * Geolocation Helper Functions
 *
 * Provides IP-based geolocation using GeoLite2-City database
 *
 * @package SS Enterprises B2B
 * @since 1.1.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

class SSB2B_Geolocation {

    /**
     * Path to GeoLite2-City database
     */
    private static $database_path = null;

    /**
     * Initialize the geolocation service
     */
    public static function init() {
        self::$database_path = wp_upload_dir()['basedir'] . '/geodata/geoip2/GeoLite2-City.mmdb';
    }

    /**
     * Get location data from IP address
     *
     * @param string $ip_address IP address to lookup
     * @return array Location data array
     */
    public static function get_location_by_ip($ip_address = null) {
        // Get the user's IP if not provided
        if (!$ip_address) {
            $ip_address = self::get_user_ip();
        }

        // Initialize default data
        $location_data = [
            'ip' => $ip_address,
            'city' => 'Hyderabad',
            'state' => 'Telangana',
            'country' => 'India',
            'country_code' => 'IN',
            'postal_code' => '500034',
            'latitude' => '17.385044',
            'longitude' => '78.486671',
            'timezone' => 'Asia/Kolkata',
            'source' => 'unknown'
        ];

        // Skip local IPs
        if (self::is_local_ip($ip_address)) {
            $location_data['source'] = 'local';
            return $location_data;
        }

        // Try GeoLite2 database first
        $geoip_data = self::get_location_from_geoip($ip_address);
        if (!empty($geoip_data)) {
            return array_merge($location_data, $geoip_data);
        }

        // Fallback to existing WP Mail SMTP Geo class if available
        if (class_exists('WPMailSMTP\Geo')) {
            $fallback_data = self::get_location_from_wpmail_smtp($ip_address);
            if (!empty($fallback_data)) {
                return array_merge($location_data, $fallback_data);
            }
        }

        // Last resort: try free IP geolocation APIs
        $api_data = self::get_location_from_api($ip_address);
        if (!empty($api_data)) {
            return array_merge($location_data, $api_data);
        }

        return $location_data;
    }

    /**
     * Get location from GeoLite2 database
     *
     * @param string $ip_address
     * @return array
     */
    private static function get_location_from_geoip($ip_address) {
        // Check if database file exists
        if (!file_exists(self::$database_path)) {
            return [];
        }

        // Check if GeoIP2 library is available
        if (!class_exists('GeoIp2\Database\Reader')) {
            // Try to include the library if it exists in vendor directory
            $vendor_paths = [
                ABSPATH . 'vendor/autoload.php',
                wp_upload_dir()['basedir'] . '/geodata/geoip2/vendor/autoload.php',
                get_stylesheet_directory() . '/vendor/autoload.php'
            ];

            foreach ($vendor_paths as $vendor_path) {
                if (file_exists($vendor_path)) {
                    require_once $vendor_path;
                    break;
                }
            }

            if (!class_exists('GeoIp2\Database\Reader')) {
                return [];
            }
        }

        try {
            $reader = new \GeoIp2\Database\Reader(self::$database_path);
            $record = $reader->city($ip_address);

            return [
                'city' => $record->city->name ?? '',
                'state' => $record->mostSpecificSubdivision->name ?? '',
                'country' => $record->country->name ?? '',
                'country_code' => $record->country->isoCode ?? '',
                'postal_code' => $record->postal->code ?? '',
                'latitude' => $record->location->latitude ?? '',
                'longitude' => $record->location->longitude ?? '',
                'timezone' => $record->location->timeZone ?? '',
                'source' => 'geoip2'
            ];

        } catch (Exception $e) {
            error_log('GeoIP2 Error: ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Get location using WP Mail SMTP Geo class
     *
     * @param string $ip_address
     * @return array
     */
    private static function get_location_from_wpmail_smtp($ip_address) {
        if (!class_exists('WPMailSMTP\Geo')) {
            return [];
        }

        try {
            $geo_data = \WPMailSMTP\Geo::get_location_by_ip($ip_address);

            if (!empty($geo_data)) {
                return [
                    'city' => $geo_data['city'] ?? '',
                    'state' => $geo_data['region'] ?? '',
                    'country' => $geo_data['country'] ?? '',
                    'country_code' => $geo_data['country'] ?? '',
                    'postal_code' => $geo_data['postal'] ?? '',
                    'latitude' => $geo_data['latitude'] ?? '',
                    'longitude' => $geo_data['longitude'] ?? '',
                    'timezone' => '',
                    'source' => 'wpmail_smtp'
                ];
            }
        } catch (Exception $e) {
            error_log('WP Mail SMTP Geo Error: ' . $e->getMessage());
        }

        return [];
    }

    /**
     * Get location from free API services
     *
     * @param string $ip_address
     * @return array
     */
    private static function get_location_from_api($ip_address) {
        // Use a simple, free IP geolocation service
        $api_url = 'http://ip-api.com/json/' . $ip_address . '?fields=status,country,countryCode,region,regionName,city,zip,lat,lon,timezone';

        $response = wp_remote_get($api_url, [
            'timeout' => 10,
            'user-agent' => 'WordPress/' . get_bloginfo('version') . '; ' . home_url()
        ]);

        if (is_wp_error($response)) {
            return [];
        }

        $body = wp_remote_retrieve_body($response);
        $data = json_decode($body, true);

        if (!$data || $data['status'] !== 'success') {
            return [];
        }

        return [
            'city' => $data['city'] ?? '',
            'state' => $data['regionName'] ?? '',
            'country' => $data['country'] ?? '',
            'country_code' => $data['countryCode'] ?? '',
            'postal_code' => $data['zip'] ?? '',
            'latitude' => $data['lat'] ?? '',
            'longitude' => $data['lon'] ?? '',
            'timezone' => $data['timezone'] ?? '',
            'source' => 'api'
        ];
    }

    /**
     * Get user's IP address
     *
     * @return string
     */
    public static function get_user_ip() {
        // Check for various headers that might contain the real IP
        $ip_headers = [
            'HTTP_CF_CONNECTING_IP',     // Cloudflare
            'HTTP_TRUE_CLIENT_IP',       // Cloudflare Enterprise
            'HTTP_X_FORWARDED_FOR',      // Load balancers/proxies
            'HTTP_X_FORWARDED',          // Proxies
            'HTTP_X_CLUSTER_CLIENT_IP',  // Cluster balancers
            'HTTP_FORWARDED_FOR',        // Proxies
            'HTTP_FORWARDED',            // Proxies
            'HTTP_CLIENT_IP',            // Proxies
            'REMOTE_ADDR'                // Default
        ];

        foreach ($ip_headers as $header) {
            if (!empty($_SERVER[$header])) {
                $ip_list = explode(',', $_SERVER[$header]);
                $ip = trim($ip_list[0]);

                if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE)) {
                    return $ip;
                }
            }
        }

        // Return REMOTE_ADDR as fallback
        return $_SERVER['REMOTE_ADDR'] ?? '127.0.0.1';
    }

    /**
     * Check if IP is local/private
     *
     * @param string $ip_address
     * @return bool
     */
    private static function is_local_ip($ip_address) {
        return !filter_var(
            $ip_address,
            FILTER_VALIDATE_IP,
            FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE
        );
    }
}

// Initialize the class
SSB2B_Geolocation::init();