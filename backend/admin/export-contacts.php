<?php
session_start();
require_once '../config/database.php';
require_once '../includes/functions.php';

// Basic authentication (in a real-world scenario, use proper admin authentication)
// This is a simple example - in production, implement proper authentication
if (!isset($_SERVER['PHP_AUTH_USER']) || !isset($_SERVER['PHP_AUTH_PW']) ||
    $_SERVER['PHP_AUTH_USER'] !== 'admin' || $_SERVER['PHP_AUTH_PW'] !== 'anayd_admin_password') {
    header('WWW-Authenticate: Basic realm="ANAYD Admin Area"');
    header('HTTP/1.0 401 Unauthorized');
    echo 'Authentication required';
    exit;
}

// Set headers for Excel download
header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="anayd_contact_submissions.xls"');
header('Cache-Control: max-age=0');

// Query to get all contact submissions
$query = "SELECT id, name, email, subject, message, created_at FROM contact_submissions ORDER BY created_at DESC";
$result = $conn->query($query);

// Excel file header
echo "<table border='1'>\n";
echo "<tr>\n";
echo "<th>ID</th>\n";
echo "<th>Name</th>\n";
echo "<th>Email</th>\n";
echo "<th>Subject</th>\n";
echo "<th>Message</th>\n";
echo "<th>Submission Date</th>\n";
echo "</tr>\n";

// Output data rows
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo "<tr>\n";
        echo "<td>" . $row['id'] . "</td>\n";
        echo "<td>" . htmlspecialchars($row['name']) . "</td>\n";
        echo "<td>" . htmlspecialchars($row['email']) . "</td>\n";
        echo "<td>" . htmlspecialchars($row['subject']) . "</td>\n";
        echo "<td>" . htmlspecialchars($row['message']) . "</td>\n";
        echo "<td>" . $row['created_at'] . "</td>\n";
        echo "</tr>\n";
    }
} else {
    echo "<tr><td colspan='6'>No submissions found</td></tr>\n";
}

echo "</table>\n";

$conn->close();
?>
