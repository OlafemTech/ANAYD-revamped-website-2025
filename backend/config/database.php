<?php
// Database configuration - update these with your Truehost credentials
define('DB_HOST', 'localhost'); // Usually 'localhost' on shared hosting
define('DB_USER', 'ptknavfj_anayd_admin'); // Your Truehost database username
define('DB_PASS', '@Anayd.Africa.2020..'); // Your Truehost database password
define('DB_NAME', 'ptknavfj_anayd_forms'); // Your database name

// Create connection
$conn = new mysqli(DB_HOST, DB_USER, DB_PASS);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Create database if not exists
$sql = "CREATE DATABASE IF NOT EXISTS " . DB_NAME;
if ($conn->query($sql) === TRUE) {
    $conn->select_db(DB_NAME);
} else {
    die("Error creating database: " . $conn->error);
}
?>
