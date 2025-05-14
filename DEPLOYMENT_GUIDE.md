# ANAYD Website Deployment Guide for Truehost

This guide outlines the steps required to deploy the ANAYD website to Truehost hosting.

## Pre-Deployment Checklist

- [ ] Ensure all HTML, CSS, and JavaScript files are finalized
- [ ] Verify all links are working correctly
- [ ] Test contact form and other interactive elements locally
- [ ] Optimize images for web

## Deployment Steps

### 1. Upload Files to Truehost

1. Log in to your Truehost cPanel account
2. Navigate to the File Manager or use FTP to upload files
3. Upload all website files to the public_html directory

### 2. Database Setup

1. Log in to your Truehost cPanel
2. Create a new MySQL database with the name specified in `backend/config/database.php`
3. Create a database user and assign it to the database with all privileges
4. Update the database credentials in `backend/config/database.php` if needed:
   ```php
   define('DB_HOST', 'localhost'); // Usually 'localhost' on shared hosting
   define('DB_USER', 'ptknavfj_anayd_admin'); // Your Truehost database username
   define('DB_PASS', '@Anayd.Africa.2020..'); // Your Truehost database password
   define('DB_NAME', 'ptknavfj_anayd_forms'); // Your database name
   ```

### 3. Install Dependencies

1. Connect to your Truehost hosting via SSH or use the Terminal in cPanel
2. Navigate to your website directory
3. Run the following command to install PHP dependencies:
   ```
   composer install
   ```

### 4. Run Installation Script

1. After uploading all files, navigate to the installation script in your browser:
   ```
   https://anayd.org/backend/install.php
   ```
2. This script will create the necessary database tables
3. If successful, you'll see a confirmation message

### 5. Test the Contact Form

1. Navigate to the contact form on your website
2. Submit a test message
3. Verify that you receive the confirmation email
4. Check the database to ensure the submission was recorded

### 6. Email Configuration

The website is configured to use Truehost's SMTP server for sending emails. If you encounter any issues with email delivery, verify these settings in `backend/includes/smtp_mailer.php`:

```php
$mail->Host       = 'mail.anayd.org';        // Truehost SMTP server
$mail->SMTPAuth   = true;                    // Enable SMTP authentication
$mail->Username   = 'info@anayd.org';        // SMTP username
$mail->Password   = '@Anayd.Africa.2020..';  // SMTP password
$mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; // Enable TLS encryption
$mail->Port       = 587;                     // TCP port to connect to
```

### 7. Troubleshooting

- **Database Connection Issues**: Check the database credentials in `backend/config/database.php`
- **Email Sending Problems**: Check the email logs in `backend/logs/email_log.txt`
- **PHP Errors**: Check the server error logs in cPanel

## Security Considerations

1. Set proper file permissions:
   - PHP files: 644
   - Directories: 755
   - Configuration files with sensitive information: 600

2. Regularly backup your database and files

3. Keep all dependencies updated

## Post-Deployment

1. Set up SSL certificate for HTTPS (available through Truehost cPanel)
2. Configure caching for better performance
3. Set up regular backups

## Contact

If you encounter any issues during deployment, contact the development team at [your-email@example.com].
