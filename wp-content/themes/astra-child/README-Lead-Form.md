# Lead Generation Form - Usage Instructions

## Overview
This custom lead generation form has been added to the SS Enterprises B2B Astra child theme. It integrates with the existing WP Mail SMTP plugin for reliable email delivery.

## How to Use

### 1. Create a New Page with Lead Form Template
1. Go to WordPress Admin → Pages → Add New
2. Give your page a title (e.g., "Contact Us", "Get a Quote", "Lead Form")
3. Add any content you want above the form
4. In the Page Attributes box, select "Lead Generation Form" as the template
5. Publish the page

### 2. Form Features
- **Required Fields**: First Name, Last Name, Email, Subject, Message, and Consent checkbox
- **Optional Fields**: Phone, Company, Area of Interest
- **Auto-reply**: Customers receive an automatic confirmation email
- **Admin Notification**: Site admin receives email notification of new leads
- **Lead Storage**: All submissions are stored in WordPress admin under "Leads" menu

### 3. Form Fields Explained
- **Name Fields**: First and Last name (required)
- **Email**: Customer's email address (required) 
- **Phone**: Contact number (optional)
- **Company**: Company name (optional)
- **Subject**: Brief description of inquiry (required)
- **Message**: Detailed message (required)
- **Area of Interest**: Dropdown with predefined options
- **Consent**: Required checkbox for GDPR compliance

### 4. Email Configuration
The form uses the existing WP Mail SMTP plugin configuration:
- Admin receives notifications at the email set in WordPress Settings → General
- Auto-reply emails are sent from the same address
- All emails are sent via your configured SMTP settings

### 5. Managing Leads
- Access leads via WordPress Admin → Leads
- View all submissions with contact details
- Leads are stored as private custom posts
- Export functionality can be added if needed

### 6. Customization
- **Styling**: Modify `/wp-content/themes/astra-child/style.css`
- **Form Fields**: Edit `/wp-content/themes/astra-child/page-lead-form.php`
- **Email Templates**: Customize in `/wp-content/themes/astra-child/functions.php`
- **Validation**: Additional validation can be added in functions.php

### 7. Security Features
- CSRF protection with WordPress nonces
- Input sanitization and validation
- Email validation
- Required field validation
- IP address logging for security

## Technical Details
- **Template File**: `page-lead-form.php`
- **Handler Function**: `handle_lead_generation_form()`
- **Custom Post Type**: `lead_submission`
- **Dependencies**: WP Mail SMTP plugin (already installed)

## Troubleshooting
- If emails aren't sending, check WP Mail SMTP configuration
- If form isn't appearing, ensure you selected the correct page template
- For styling issues, check browser developer tools and modify CSS accordingly