<?php
// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Allow CORS for local development
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET');
header('Access-Control-Allow-Headers: Content-Type');

session_start();
require_once '../includes/functions.php';

// Generate a CSRF token
$token = generate_csrf_token();

// Log token generation for debugging
error_log('Generated CSRF token: ' . $token);

// Return the token as JSON
send_response(true, 'CSRF token generated', ['token' => $token]);
?>
