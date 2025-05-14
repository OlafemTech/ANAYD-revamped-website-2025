<?php
session_start();
require_once '../config/database.php';
require_once '../includes/functions.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    send_response(false, 'Invalid request method');
}

// Skip CSRF check for now as we're using AJAX
// if (!verify_csrf_token($_POST['csrf_token'])) {
//     send_response(false, 'Invalid security token');
// }

// Collect and sanitize all form fields
$name = sanitize_input($_POST['name'] ?? '');
$last_name = sanitize_input($_POST['last_name'] ?? '');
$email = sanitize_input($_POST['email'] ?? '');
$phone = sanitize_input($_POST['phone'] ?? '');
$dob = sanitize_input($_POST['dob'] ?? '');
$location = sanitize_input($_POST['location'] ?? '');
$volunteer_type = sanitize_input($_POST['volunteer_type'] ?? '');
$availability = sanitize_input($_POST['availability'] ?? '');
$areas_of_interest = sanitize_input($_POST['areas_of_interest'] ?? '');
$skills = sanitize_input($_POST['skills'] ?? '');
$experience = sanitize_input($_POST['experience'] ?? '');
$motivation = sanitize_input($_POST['motivation'] ?? '');
$hear_about = sanitize_input($_POST['hear_about'] ?? '');
$newsletter = isset($_POST['newsletter']) ? 1 : 0;
$terms = isset($_POST['terms']) ? 1 : 0;

// Validate required fields
if (empty($name) || empty($last_name) || empty($email) || empty($motivation) || 
    empty($volunteer_type) || empty($availability) || empty($areas_of_interest) || 
    empty($hear_about) || $terms !== 1) {
    send_response(false, 'Please fill all required fields');
}

// Validate email
if (!validate_email($email)) {
    send_response(false, 'Invalid email address');
}

// Prepare SQL statement
$stmt = $conn->prepare("INSERT INTO volunteer_submissions 
    (name, last_name, email, phone, dob, location, volunteer_type, availability, 
    areas_of_interest, skills, experience, motivation, hear_about, newsletter, terms) 
    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

$stmt->bind_param("sssssssssssssii", 
    $name, $last_name, $email, $phone, $dob, $location, $volunteer_type, 
    $availability, $areas_of_interest, $skills, $experience, $motivation, 
    $hear_about, $newsletter, $terms
);

// Execute the query
if ($stmt->execute()) {
    // Send notification email to admin
    $admin_email = 'info@anayd.org';
    $subject = 'New Volunteer Application';
    $message = "<html><body>
        <h2>New Volunteer Application</h2>
        <p><strong>Name:</strong> $name $last_name</p>
        <p><strong>Email:</strong> $email</p>
        <p><strong>Phone:</strong> $phone</p>
        <p><strong>Area of Interest:</strong> $areas_of_interest</p>
        <p><strong>Volunteer Type:</strong> $volunteer_type</p>
        <p><strong>Availability:</strong> $availability</p>
        <p><strong>Motivation:</strong> $motivation</p>
        <p>Please log in to the admin dashboard to review the full application.</p>
    </body></html>";
    
    send_email($admin_email, $subject, $message);
    
    // Send confirmation email to applicant with WhatsApp group link
    $whatsapp_group_link = 'https://chat.whatsapp.com/DL5lw6owSrLKl9sa2eGQvL'; // Replace with your actual WhatsApp group link
    $applicant_subject = 'Thank You for Your ANAYD Volunteer Application';
    $applicant_message = "<html><body>
        <h2>Thank You for Your Application!</h2>
        <p>Dear $name,</p>
        <p>Thank you for your interest in volunteering with ANAYD. We have received your application and will review it shortly.</p>
        <p>Our team will contact you within the next 5-7 business days regarding the next steps.</p>
        <p><strong>Join our Volunteer WhatsApp Group:</strong> <a href='$whatsapp_group_link' target='_blank'>Click here to join</a></p>
        <p>This group will help you connect with other volunteers and stay updated on opportunities.</p>
        <p>If you have any questions in the meantime, please feel free to contact us at info@anayd.org.</p>
        <p>Best regards,<br>The ANAYD Team</p>
    </body></html>";
    
    send_email($email, $applicant_subject, $applicant_message);
    
    send_response(true, 'Volunteer application submitted successfully');
} else {
    send_response(false, 'Error submitting application: ' . $stmt->error);
}

$stmt->close();
$conn->close();
?>
