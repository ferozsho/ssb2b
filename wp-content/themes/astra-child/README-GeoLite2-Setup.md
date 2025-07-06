# GeoLite2 Database Setup Instructions

This enhanced lead form uses the MaxMind GeoLite2-City database for automatic location detection. Follow these steps to set up the geolocation functionality.

## Requirements

1. GeoLite2-City.mmdb database file from MaxMind
2. GeoIP2 PHP library (installed via Composer)

## Installation Steps

### Step 1: Download GeoLite2-City Database

1. Create a free account at [MaxMind](https://www.maxmind.com/en/geolite2/signup)
2. Download the GeoLite2-City database in MMDB format
3. Extract the `GeoLite2-City.mmdb` file

### Step 2: Install GeoIP2 PHP Library

Navigate to your WordPress uploads directory and run:

```bash
cd wp-content/uploads/geodata/geoip2/
composer require geoip2/geoip2:~2.0
```

Alternatively, you can install it in your WordPress root directory:

```bash
cd /path/to/your/wordpress/
composer require geoip2/geoip2:~2.0
```

### Step 3: Upload Database File

Place the `GeoLite2-City.mmdb` file in:
```
wp-content/uploads/geodata/geoip2/GeoLite2-City.mmdb
```

### Step 4: Set Proper Permissions

```bash
chmod 644 wp-content/uploads/geodata/geoip2/GeoLite2-City.mmdb
chown www-data:www-data wp-content/uploads/geodata/geoip2/GeoLite2-City.mmdb
```

## Directory Structure

After setup, your directory structure should look like:

```
wp-content/
├── uploads/
│   └── geodata/
│       └── geoip2/
│           ├── GeoLite2-City.mmdb
│           └── vendor/ (if installed here)
│               └── autoload.php
```

## Fallback Options

If the GeoLite2 database is not available, the system will automatically fall back to:

1. WP Mail SMTP Geo service (if available)
2. Free IP geolocation APIs (ip-api.com)
3. Empty location fields (manual entry)

## Testing

To test the geolocation functionality:

1. Visit your lead form page
2. Check if location fields are automatically populated
3. Verify the location accuracy
4. Check the admin email to see the location source information

## Updating the Database

The GeoLite2 database should be updated monthly for accuracy:

1. Download the latest version from MaxMind
2. Replace the existing `GeoLite2-City.mmdb` file
3. No code changes required

## Troubleshooting

### Common Issues:

1. **Location fields are empty**: Check if the database file exists and has proper permissions
2. **Composer autoloader not found**: Verify the vendor directory location
3. **Permission denied errors**: Ensure proper file permissions (644 for files, 755 for directories)

### Debug Information:

The system logs geolocation errors to the WordPress error log. Check your WordPress debug log for messages starting with "GeoIP2 Error:" or "WP Mail SMTP Geo Error:".

### Testing Local Development:

When testing locally (localhost), the geolocation will not work as expected since local IPs cannot be geolocated. The system will handle this gracefully and show empty location fields.

## Performance Considerations

- The GeoLite2 database lookup is fast (typically < 1ms)
- Location data is only fetched when the form loads
- Failed lookups gracefully fall back to alternative methods
- No impact on form submission performance

## Privacy Notes

- IP addresses are logged with form submissions for security purposes
- Location detection is performed server-side only
- Users can manually override detected location information
- Consider adding privacy policy information about IP-based geolocation

## License

The GeoLite2 database is provided by MaxMind and subject to their license terms. Please review MaxMind's license agreement for usage restrictions and attribution requirements.