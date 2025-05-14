<?php
session_start();
require_once '../config/database.php';
require_once '../includes/functions.php';

// Get the email from the URL parameter
$email = isset($_GET['email']) ? sanitize_input($_GET['email']) : '';
$token = isset($_GET['token']) ? sanitize_input($_GET['token']) : '';

// Validate email and token
if (empty($email) || empty($token) || !validate_email($email)) {
    // Redirect to error page or homepage
    header('Location: ../../index.html?unsubscribe=error');
    exit;
}

// Verify the token (simple hash verification for demo)
$expected_token = md5($email . 'anayd_unsubscribe_salt');
if ($token !== $expected_token) {
    // Invalid token
    header('Location: ../../index.html?unsubscribe=invalid');
    exit;
}

// Check if the email exists in the database
$stmt = $conn->prepare("SELECT id FROM newsletter_subscribers WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    // Email not found in the database
    header('Location: ../../index.html?unsubscribe=notfound');
    exit;
}

// Delete the subscriber
$stmt = $conn->prepare("DELETE FROM newsletter_subscribers WHERE email = ?");
$stmt->bind_param("s", $email);

if ($stmt->execute()) {
    // Successfully unsubscribed
    header('Location: ../../unsubscribe-success.html');
} else {
    // Error unsubscribing
    header('Location: ../../index.html?unsubscribe=error');
}

$stmt->close();
$conn->close();
?>
