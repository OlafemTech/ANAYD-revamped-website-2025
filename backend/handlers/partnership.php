<?php
session_start();
require_once '../config/database.php';
require_once '../includes/functions.php';
require_once '../includes/email_templates.php';
require_once '../includes/smtp_mailer.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    send_response(false, 'Invalid request method');
}

if (!verify_csrf_token($_POST['csrf_token'] ?? '')) {
    send_response(false, 'Invalid security token');
}

// Get form data
$org_name = sanitize_input($_POST['organization_name'] ?? '');
$contact_person = sanitize_input($_POST['contact_person'] ?? '');
$email = sanitize_input($_POST['email'] ?? '');
$phone = sanitize_input($_POST['phone'] ?? '');
$partnership_type = sanitize_input($_POST['partnership_type'] ?? '');
$proposal = sanitize_input($_POST['proposal'] ?? '');
$position = sanitize_input($_POST['position'] ?? '');
$website = sanitize_input($_POST['website'] ?? '');
$duration = sanitize_input($_POST['duration'] ?? '');
$past_projects = sanitize_input($_POST['past-projects'] ?? '');
$hear_about = sanitize_input($_POST['hear-about'] ?? '');
$contact_consent = isset($_POST['contact-consent']) ? 1 : 0;
$terms_agreed = isset($_POST['terms']) ? 1 : 0;

// Validate required fields
if (empty($org_name) || empty($contact_person) || empty($email) || empty($proposal) || empty($partnership_type)) {
    send_response(false, 'Please fill all required fields');
}

if (!validate_email($email)) {
    send_response(false, 'Invalid email address');
}

// Current date for submission timestamp
$submission_date = date('Y-m-d H:i:s');

// Prepare SQL statement with additional fields
$stmt = $conn->prepare("INSERT INTO partnership_submissions (
    organization_name, 
    contact_person, 
    position, 
    email, 
    phone, 
    website, 
    partnership_type, 
    proposal, 
    duration, 
    past_projects, 
    hear_about, 
    contact_consent, 
    terms_agreed, 
    submission_date
) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

$stmt->bind_param(
    "sssssssssssiis", 
    $org_name, 
    $contact_person, 
    $position, 
    $email, 
    $phone, 
    $website, 
    $partnership_type, 
    $proposal, 
    $duration, 
    $past_projects, 
    $hear_about, 
    $contact_consent, 
    $terms_agreed, 
    $submission_date
);

// Execute the database insert
$db_success = $stmt->execute();
$submission_id = $db_success ? $conn->insert_id : 0;

// Send confirmation email
if ($db_success) {
    // Get email template based on partnership type
    $email_template = get_partnership_confirmation_email($org_name, $contact_person, $email, $partnership_type);
    
    // Send email to the partner
    $email_success = send_smtp_email(
        $email,
        $email_template['subject'],
        $email_template['body'],
        'partnerships@anayd.org',
        'ANAYD Partnerships'
    );
    
    // Send notification to admin
    $admin_subject = "New Partnership Request: " . $org_name;
    $admin_message = "<!DOCTYPE html>
    <html>
    <head>
        <meta charset=\"UTF-8\">
        <title>New Partnership Request</title>
    </head>
    <body style=\"font-family: Arial, sans-serif; line-height: 1.6; color: #333;\">
        <h2>New Partnership Request Received</h2>
        <p><strong>Submission ID:</strong> " . $submission_id . "</p>
        <p><strong>Date:</strong> " . $submission_date . "</p>
        <p><strong>Organization:</strong> " . htmlspecialchars($org_name) . "</p>
        <p><strong>Contact Person:</strong> " . htmlspecialchars($contact_person) . "</p>
        <p><strong>Position:</strong> " . htmlspecialchars($position) . "</p>
        <p><strong>Email:</strong> " . htmlspecialchars($email) . "</p>
        <p><strong>Phone:</strong> " . htmlspecialchars($phone) . "</p>
        <p><strong>Website:</strong> " . htmlspecialchars($website) . "</p>
        <p><strong>Partnership Type:</strong> " . htmlspecialchars($partnership_type) . "</p>
        <p><strong>Duration:</strong> " . htmlspecialchars($duration) . "</p>
        <p><strong>Proposal:</strong></p>
        <div style=\"background-color: #f5f5f5; padding: 15px; border-radius: 5px;\">
            " . nl2br(htmlspecialchars($proposal)) . "
        </div>
        <p><strong>Past Projects:</strong></p>
        <div style=\"background-color: #f5f5f5; padding: 15px; border-radius: 5px;\">
            " . nl2br(htmlspecialchars($past_projects)) . "
        </div>
        <p><strong>How they heard about us:</strong> " . htmlspecialchars($hear_about) . "</p>
        <hr>
        <p>Please log in to the admin dashboard to review this partnership request.</p>
    </body>
    </html>";
    
    $admin_email_sent = send_smtp_email(
        'info@anayd.org', // Admin email
        $admin_subject,
        $admin_message
    );
    
    // Log email status
    $log_file = __DIR__ . '/../logs/partnership_log.txt';
    $log_dir = dirname($log_file);
    
    // Create logs directory if it doesn't exist
    if (!file_exists($log_dir)) {
        mkdir($log_dir, 0755, true);
    }
    
    $log_content = "\n" . str_repeat('-', 50) . "\n";
    $log_content .= "Date: " . date('Y-m-d H:i:s') . "\n";
    $log_content .= "Submission ID: $submission_id\n";
    $log_content .= "Organization: $org_name\n";
    $log_content .= "Contact: $contact_person\n";
    $log_content .= "Email: $email\n";
    $log_content .= "Partnership Type: $partnership_type\n";
    $log_content .= "DB Insert: " . ($db_success ? "Success" : "Failed") . "\n";
    $log_content .= "Partner Email: " . ($email_success ? "Sent" : "Failed") . "\n";
    $log_content .= "Admin Email: " . ($admin_email_sent ? "Sent" : "Failed") . "\n";
    
    file_put_contents($log_file, $log_content, FILE_APPEND);
    
    // Send success response
    send_response(true, 'Partnership proposal submitted successfully');
} else {
    // Log the error
    error_log("Database error: " . $conn->error);
    send_response(false, 'Error submitting proposal');
}

$stmt->close();
$conn->close();

/**
 * Send JSON response and exit
 * 
 * @param bool $success Whether the request was successful
 * @param string $message Response message
 * @param array $data Additional data to include in the response
 */
function send_response($success, $message, $data = []) {
    header('Content-Type: application/json');
    echo json_encode([
        'success' => $success,
        'message' => $message,
        'data' => $data
    ]);
    exit;
}
?>
