# ANAYD Website

This repository contains the website for the African Network of Adolescents and Young Persons Development (ANAYD).

## Local Development Setup

### Prerequisites

- PHP 7.4 or higher
- MySQL or MariaDB
- Composer (for PHP dependencies)

### Installation

1. Clone the repository
2. Install PHP dependencies:
   ```
   composer install
   ```
3. Configure the database:
   - Create a MySQL database
   - Update the credentials in `backend/config/database.php`

4. Run the installation script to set up the database tables:
   ```
   php -S localhost:8000
   ```
   Then navigate to `http://localhost:8000/backend/install.php` in your browser

### Testing the Contact Form

To test the contact form functionality locally:

1. Start a local PHP server:
   ```
   php -S localhost:8000
   ```

2. Open the website in your browser:
   ```
   http://localhost:8000
   ```

3. Navigate to the contact form and submit a test message

4. Check the `backend/logs/email_log.txt` file to verify that the email would be sent

5. Check the database to ensure the submission was recorded

## Backend Structure

- `backend/config/` - Configuration files
- `backend/handlers/` - Form submission handlers
- `backend/includes/` - Shared PHP functions and utilities
- `backend/logs/` - Log files for debugging
- `backend/sql/` - SQL scripts for database setup

## Form Handlers

- `contact.php` - Handles contact form submissions
- `newsletter.php` - Handles newsletter subscriptions
- `get-csrf-token.php` - Generates CSRF tokens for form security

## Deployment

See the `DEPLOYMENT_GUIDE.md` file for detailed instructions on deploying to Truehost hosting.

## Security Features

- CSRF protection for all forms
- Input sanitization to prevent XSS attacks
- Prepared statements for database queries to prevent SQL injection
- Email validation

## Troubleshooting

- If the contact form is not working, check the browser console for JavaScript errors
- Verify that the CSRF token is being generated correctly
- Check the PHP error logs for any backend issues
- Ensure the database connection is configured correctly
