<?php
/**
 * SMTP Mailer for ANAYD website
 * Uses PHPMailer to send emails through SMTP
 */

// Include PHPMailer autoloader
require_once __DIR__ . '/../../vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

/**
 * Send an email using SMTP
 * 
 * @param string $to Recipient email address
 * @param string $subject Email subject
 * @param string $message Email body (HTML)
 * @param string $from Sender email address (default: info@anayd.org)
 * @param string $fromName Sender name (default: ANAYD)
 * @return bool True if email was sent successfully, false otherwise
 */
function send_smtp_email($to, $subject, $message, $from = 'info@anayd.org', $fromName = 'ANAYD') {
    // Log the email attempt for debugging
    $log_file = __DIR__ . '/../logs/email_log.txt';
    $log_dir = dirname($log_file);
    
    // Create logs directory if it doesn't exist
    if (!file_exists($log_dir)) {
        mkdir($log_dir, 0755, true);
    }
    
    // Log the email attempt
    $log_content = "\n\n" . str_repeat('-', 50) . "\n";
    $log_content .= "Date: " . date('Y-m-d H:i:s') . "\n";
    $log_content .= "To: $to\n";
    $log_content .= "Subject: $subject\n";
    $log_content .= "From: $fromName <$from>\n";
    
    // Create a new PHPMailer instance
    $mail = new PHPMailer(true);
    
    try {
        // Server settings
        $mail->SMTPDebug = SMTP::DEBUG_OFF;           // Disable verbose debug output
        $mail->isSMTP();                             // Send using SMTP
        $mail->Host       = 'mail.anayd.org';        // Truehost SMTP server
        $mail->SMTPAuth   = true;                    // Enable SMTP authentication
        $mail->Username   = 'info@anayd.org';        // SMTP username
        $mail->Password   = '@Anayd.Africa.2020..';  // SMTP password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; // Enable TLS encryption
        $mail->Port       = 587;                     // TCP port to connect to
        
        // Recipients
        $mail->setFrom($from, $fromName);
        $mail->addAddress($to);                      // Add a recipient
        
        // Content
        $mail->isHTML(true);                         // Set email format to HTML
        $mail->Subject = $subject;
        $mail->Body    = $message;
        
        $mail->send();
        $log_content .= "Status: Email sent successfully\n";
        file_put_contents($log_file, $log_content, FILE_APPEND);
        return true;
    } catch (Exception $e) {
        $log_content .= "Status: Failed to send email\n";
        $log_content .= "Error: {$mail->ErrorInfo}\n";
        file_put_contents($log_file, $log_content, FILE_APPEND);
        error_log("Email could not be sent. Mailer Error: {$mail->ErrorInfo}");
        return false;
    }
}
?>
