<?php
session_start();
require_once '../config/database.php';
require_once '../includes/functions.php';

if (isset($_GET['status']) && isset($_GET['tx_ref'])) {
    $status = sanitize_input($_GET['status']);
    $tx_ref = sanitize_input($_GET['tx_ref']);
    $transaction_id = sanitize_input($_GET['transaction_id']);

    // Verify transaction with Flutterwave
    $curl = curl_init();
    curl_setopt_array($curl, [
        CURLOPT_URL => "https://api.flutterwave.com/v3/transactions/".$transaction_id."/verify",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HTTPHEADER => [
            "Content-Type: application/json",
            "Authorization: Bearer " . FLW_SECRET_KEY
        ]
    ]);

    $response = curl_exec($curl);
    $err = curl_error($curl);
    curl_close($curl);

    if ($err) {
        // Update donation status to failed
        $stmt = $conn->prepare("UPDATE donations SET status = 'failed' WHERE transaction_ref = ?");
        $stmt->bind_param("s", $tx_ref);
        $stmt->execute();
        header("Location: /donate.html?status=failed");
        exit();
    }

    $transaction = json_decode($response);

    if ($transaction->status === "success" && $transaction->data->tx_ref === $tx_ref) {
        // Update donation status to successful
        $stmt = $conn->prepare("UPDATE donations SET status = 'successful', payment_method = ? WHERE transaction_ref = ?");
        $payment_method = $transaction->data->payment_type;
        $stmt->bind_param("ss", $payment_method, $tx_ref);
        $stmt->execute();
        header("Location: /donate.html?status=success");
        exit();
    } else {
        // Update donation status to failed
        $stmt = $conn->prepare("UPDATE donations SET status = 'failed' WHERE transaction_ref = ?");
        $stmt->bind_param("s", $tx_ref);
        $stmt->execute();
        header("Location: /donate.html?status=failed");
        exit();
    }
}

header("Location: /donate.html?status=error");
exit();
?>
