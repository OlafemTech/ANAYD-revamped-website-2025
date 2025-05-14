<?php
session_start();
require_once '../config/database.php';
require_once '../includes/functions.php';

// Flutterwave API configuration
define('FLW_PUBLIC_KEY', 'YOUR_FLUTTERWAVE_PUBLIC_KEY');
define('FLW_SECRET_KEY', 'YOUR_FLUTTERWAVE_SECRET_KEY');
define('FLW_ENCRYPTION_KEY', 'YOUR_FLUTTERWAVE_ENCRYPTION_KEY');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verify_csrf_token($_POST['csrf_token'])) {
        send_response(false, 'Invalid security token');
    }

    $name = sanitize_input($_POST['name']);
    $email = sanitize_input($_POST['email']);
    $amount = floatval(sanitize_input($_POST['amount']));
    $tx_ref = 'ANAYD-'.time();

    if (empty($name) || empty($email) || $amount <= 0) {
        send_response(false, 'Please fill all required fields');
    }

    if (!validate_email($email)) {
        send_response(false, 'Invalid email address');
    }

    // Insert initial donation record
    $stmt = $conn->prepare("INSERT INTO donations (name, email, amount, transaction_ref, status) VALUES (?, ?, ?, ?, 'pending')");
    $stmt->bind_param("ssds", $name, $email, $amount, $tx_ref);
    
    if (!$stmt->execute()) {
        send_response(false, 'Error processing donation');
    }

    // Prepare Flutterwave payment data
    $payment_data = [
        'tx_ref' => $tx_ref,
        'amount' => $amount,
        'currency' => 'NGN',
        'payment_options' => 'card,banktransfer',
        'redirect_url' => 'https://anayd.org/backend/handlers/verify-payment.php',
        'customer' => [
            'email' => $email,
            'name' => $name
        ],
        'meta' => [
            'source' => 'website',
            'donation_for' => 'ANAYD'
        ],
        'customizations' => [
            'title' => 'ANAYD Donation',
            'description' => 'Support ANAYD\'s Mission',
            'logo' => 'https://anayd.org/assets/images/logo.png'
        ]
    ];

    send_response(true, 'Payment initialized', $payment_data);
}

$stmt->close();
$conn->close();
?>
