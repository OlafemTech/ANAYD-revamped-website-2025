<?php
// Database setup script
require_once '../config/database.php';

// Read and execute SQL file
$sql = file_get_contents(__DIR__ . '/create_volunteer_table.sql');

// Execute multi query
if ($conn->multi_query($sql)) {
    do {
        // Store first result set
        if ($result = $conn->store_result()) {
            $result->free();
        }
        // Check for more results
    } while ($conn->more_results() && $conn->next_result());
    
    echo "Database tables created successfully!\n";
} else {
    echo "Error creating tables: " . $conn->error . "\n";
}

$conn->close();
echo "Setup complete!\n";
?>
