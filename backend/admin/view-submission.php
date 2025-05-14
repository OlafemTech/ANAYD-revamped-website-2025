<?php
session_start();
require_once '../config/database.php';
require_once '../includes/functions.php';

// Basic authentication (in a real-world scenario, use proper admin authentication)
if (!isset($_SERVER['PHP_AUTH_USER']) || !isset($_SERVER['PHP_AUTH_PW']) ||
    $_SERVER['PHP_AUTH_USER'] !== 'admin' || $_SERVER['PHP_AUTH_PW'] !== 'anayd_admin_password') {
    header('WWW-Authenticate: Basic realm="ANAYD Admin Area"');
    header('HTTP/1.0 401 Unauthorized');
    echo 'Authentication required';
    exit;
}

// Check if ID is provided
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header('Location: index.php');
    exit;
}

$id = (int)$_GET['id'];

// Query to get the specific submission
$query = "SELECT id, name, email, subject, message, created_at FROM contact_submissions WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    header('Location: index.php');
    exit;
}

$submission = $result->fetch_assoc();
$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ANAYD Admin - View Submission</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
    <div class="container mx-auto px-4 py-8">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-gray-800">View Contact Submission</h1>
            <div>
                <a href="index.php" class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded mr-2">
                    Back to List
                </a>
                <a href="export-contacts.php" class="bg-green-500 hover:bg-green-600 text-white font-bold py-2 px-4 rounded">
                    Export to Excel
                </a>
            </div>
        </div>
        
        <div class="bg-white shadow-md rounded-lg overflow-hidden p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <h2 class="text-lg font-semibold text-gray-700 mb-2">Submission Details</h2>
                    <div class="mb-4">
                        <p class="text-sm text-gray-500">ID</p>
                        <p class="text-base font-medium"><?php echo $submission['id']; ?></p>
                    </div>
                    <div class="mb-4">
                        <p class="text-sm text-gray-500">Date</p>
                        <p class="text-base font-medium"><?php echo $submission['created_at']; ?></p>
                    </div>
                </div>
                <div>
                    <h2 class="text-lg font-semibold text-gray-700 mb-2">Contact Information</h2>
                    <div class="mb-4">
                        <p class="text-sm text-gray-500">Name</p>
                        <p class="text-base font-medium"><?php echo htmlspecialchars($submission['name']); ?></p>
                    </div>
                    <div class="mb-4">
                        <p class="text-sm text-gray-500">Email</p>
                        <p class="text-base font-medium"><?php echo htmlspecialchars($submission['email']); ?></p>
                    </div>
                </div>
            </div>
            
            <div class="mt-6">
                <h2 class="text-lg font-semibold text-gray-700 mb-2">Message</h2>
                <div class="mb-4">
                    <p class="text-sm text-gray-500">Subject</p>
                    <p class="text-base font-medium"><?php echo htmlspecialchars($submission['subject']); ?></p>
                </div>
                <div class="mb-4">
                    <p class="text-sm text-gray-500">Message Content</p>
                    <div class="p-4 bg-gray-50 rounded-lg mt-2">
                        <p class="text-base whitespace-pre-wrap"><?php echo nl2br(htmlspecialchars($submission['message'])); ?></p>
                    </div>
                </div>
            </div>
            
            <div class="mt-6 flex justify-between">
                <a href="mailto:<?php echo htmlspecialchars($submission['email']); ?>?subject=Re: <?php echo htmlspecialchars($submission['subject']); ?>" 
                   class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded">
                    Reply via Email
                </a>
            </div>
        </div>
    </div>
</body>
</html>

<?php $conn->close(); ?>
