<?php
// Database installation script for ANAYD website
// Run this script once after deploying to Truehost

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Include database configuration
require_once 'config/database.php';

// Function to execute SQL file
function execute_sql_file($conn, $file_path) {
    $success = true;
    $error_message = '';
    
    if (!file_exists($file_path)) {
        return [false, "SQL file not found: $file_path"];
    }
    
    $sql = file_get_contents($file_path);
    $statements = explode(';', $sql);
    
    foreach ($statements as $statement) {
        $statement = trim($statement);
        if (!empty($statement)) {
            if (!$conn->query($statement)) {
                $success = false;
                $error_message .= "Error executing statement: {$conn->error}\n";
            }
        }
    }
    
    return [$success, $error_message];
}

// Check if the installation has already been completed
$installation_status = 'pending';
$status_file = __DIR__ . '/install_status.txt';

if (file_exists($status_file)) {
    $installation_status = trim(file_get_contents($status_file));
}

// If already installed, show message and exit
if ($installation_status === 'completed') {
    echo '<h1>Installation Already Completed</h1>';
    echo '<p>The database has already been set up. If you need to reinstall, delete the install_status.txt file.</p>';
    echo '<p><a href="../index.html">Return to Homepage</a></p>';
    exit;
}

// Execute the SQL setup file
$sql_file = __DIR__ . '/sql/setup.sql';
list($success, $error_message) = execute_sql_file($conn, $sql_file);

// Display results
echo '<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ANAYD Database Installation</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
        }
        .success {
            color: green;
            padding: 10px;
            background-color: #f0fff0;
            border-left: 5px solid green;
        }
        .error {
            color: red;
            padding: 10px;
            background-color: #fff0f0;
            border-left: 5px solid red;
        }
        pre {
            background-color: #f5f5f5;
            padding: 10px;
            overflow: auto;
        }
    </style>
</head>
<body>
    <h1>ANAYD Database Installation</h1>
';

if ($success) {
    // Mark installation as completed
    file_put_contents($status_file, 'completed');
    
    echo '<div class="success">';
    echo '<h2>Installation Successful!</h2>';
    echo '<p>The database has been set up successfully.</p>';
    echo '</div>';
    
    // Create test tables to verify connection
    $test_query = "SHOW TABLES";
    $result = $conn->query($test_query);
    
    echo '<h3>Database Tables:</h3>';
    echo '<ul>';
    while ($row = $result->fetch_row()) {
        echo '<li>' . $row[0] . '</li>';
    }
    echo '</ul>';
} else {
    echo '<div class="error">';
    echo '<h2>Installation Failed</h2>';
    echo '<p>There was an error setting up the database:</p>';
    echo '<pre>' . $error_message . '</pre>';
    echo '</div>';
}

echo '<p><a href="../index.html">Return to Homepage</a></p>';
echo '</body>
</html>';

// Close the database connection
$conn->close();
