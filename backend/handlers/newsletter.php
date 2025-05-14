<?php
// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Allow CORS for local development
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

require_once '../config/database.php';
require_once '../includes/functions.php';
require_once '../includes/email_templates.php';
require_once '../includes/smtp_mailer.php';

// Log incoming request for debugging
error_log('Newsletter subscription request received: ' . json_encode($_POST));

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    send_response(false, 'Invalid request method');
}

// CSRF check removed for simplicity
// We'll add it back later when everything else is working

$email = sanitize_input($_POST['email']);

if (empty($email)) {
    send_response(false, 'Please provide an email address');
}

if (!validate_email($email)) {
    send_response(false, 'Invalid email address');
}

// Check if email already exists
$stmt = $conn->prepare("SELECT id FROM newsletter_subscribers WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    send_response(false, 'Email already subscribed');
}

// Insert new subscriber
$stmt = $conn->prepare("INSERT INTO newsletter_subscribers (email) VALUES (?)");
$stmt->bind_param("s", $email);

if ($stmt->execute()) {
    // Send welcome email
    $email_template = get_newsletter_welcome_email($email);
    
    // Use the standard mail() function which is supported by Truehost
    $email_sent = send_email($email, $email_template['subject'], $email_template['body']);
    
    // Log email sending result
    $log_message = $email_sent ? "Welcome email sent to {$email}" : "Failed to send welcome email to {$email}";
    error_log($log_message);
    
    send_response(true, 'Successfully subscribed to newsletter');
} else {
    send_response(false, 'Error subscribing to newsletter');
}

$stmt->close();
$conn->close();
?>
