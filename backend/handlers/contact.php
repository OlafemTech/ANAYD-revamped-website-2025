<?php
session_start();
require_once '../config/database.php';
require_once '../includes/functions.php';
require_once '../includes/email_templates.php';
require_once '../includes/smtp_mailer.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    send_response(false, 'Invalid request method');
}

if (!verify_csrf_token($_POST['csrf_token'])) {
    send_response(false, 'Invalid security token');
}

$name = sanitize_input($_POST['name']);
$email = sanitize_input($_POST['email']);
$subject = sanitize_input($_POST['subject']);
$message = sanitize_input($_POST['message']);

if (empty($name) || empty($email) || empty($message)) {
    send_response(false, 'Please fill all required fields');
}

if (!validate_email($email)) {
    send_response(false, 'Invalid email address');
}

$stmt = $conn->prepare("INSERT INTO contact_submissions (name, email, subject, message) VALUES (?, ?, ?, ?)");
$stmt->bind_param("ssss", $name, $email, $subject, $message);

if ($stmt->execute()) {
    // Send confirmation email to the user
    $email_template = get_contact_confirmation_email($name, $email, $subject);
    $email_sent = send_smtp_email($email, $email_template['subject'], $email_template['body']);
    
    // Log email status
    $email_status = $email_sent ? 'Email sent successfully' : 'Failed to send email';
    error_log("Contact form submission: {$email_status} to {$email}");
    
    send_response(true, 'Message sent successfully');
} else {
    send_response(false, 'Error sending message');
}

$stmt->close();
$conn->close();
?>
