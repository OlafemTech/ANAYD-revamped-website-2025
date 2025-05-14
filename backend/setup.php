<?php
// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once 'config/database.php';

// Output as HTML
header('Content-Type: text/html; charset=utf-8');
echo '<!DOCTYPE html>
<html>
<head>
    <title>ANAYD Database Setup</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; padding: 20px; max-width: 800px; margin: 0 auto; }
        h1 { color: #3B82F6; }
        .success { color: green; background: #f0fff0; padding: 10px; border-left: 4px solid green; }
        .error { color: red; background: #fff0f0; padding: 10px; border-left: 4px solid red; }
        pre { background: #f5f5f5; padding: 10px; overflow: auto; }
    </style>
</head>
<body>
    <h1>ANAYD Database Setup</h1>';

try {
    // Check if we can connect to the database
    echo '<h2>Checking database connection</h2>';
    if ($conn->connect_error) {
        throw new Exception("Connection failed: " . $conn->connect_error);
    }
    
    echo '<p class="success">Connected to database successfully!</p>';
    
    // Read and execute SQL setup file
    echo '<h2>Creating database tables</h2>';
    $sql = file_get_contents('sql/setup.sql');
    
    // Split SQL by semicolon to execute each statement separately
    $statements = explode(';', $sql);
    $statements = array_filter($statements, 'trim'); // Remove empty statements
    
    foreach ($statements as $statement) {
        if (trim($statement) != '') {
            if ($conn->query($statement) === TRUE) {
                echo '<p>Executed: <pre>' . htmlspecialchars(substr($statement, 0, 100)) . '...</pre></p>';
            } else {
                echo '<p class="error">Error executing statement: ' . $conn->error . '</p>';
                echo '<p>Statement: <pre>' . htmlspecialchars($statement) . '</pre></p>';
            }
        }
    }
    
    echo '<h2>Result</h2>';
    echo '<p class="success">Database setup completed successfully!</p>';
    
} catch (Exception $e) {
    echo '<p class="error">Error: ' . $e->getMessage() . '</p>';
} finally {
    $conn->close();
    echo '</body>
</html>';
}
?>
