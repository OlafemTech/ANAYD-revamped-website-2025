<?php
require_once 'config/database.php';

// Test database connection
if ($conn->ping()) {
    echo "Database connection successful!\n";
    
    // Check if tables exist
    $tables = ['contact_submissions', 'volunteer_submissions', 'partnership_submissions', 'donations', 'newsletter_subscribers'];
    
    foreach ($tables as $table) {
        $result = $conn->query("SHOW TABLES LIKE '$table'");
        if ($result->num_rows > 0) {
            echo "Table '$table' exists\n";
        } else {
            echo "Table '$table' does not exist\n";
        }
    }
} else {
    echo "Database connection failed: " . $conn->error . "\n";
}

$conn->close();
?>
