<?php
require "../../config/config.php";
require_once '../../vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['adminname'])) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized access. Please log in.']);
    exit;
}

if (!isset($_POST['requestId']) || !isset($_POST['subject']) || !isset($_POST['message'])) {
    echo json_encode(['success' => false, 'message' => 'All fields are required']);
    exit;
}

$request_id = $_POST['requestId'];
$subject = $_POST['subject'];
$message = $_POST['message'];

try {
    // Step 1: Fetch request details
    $query = $conn->prepare("SELECT r.*, u.id as user_id, u.email as user_email, u.username, p.name as property_name 
                            FROM requests r
                            INNER JOIN users u ON r.user_id = u.id
                            INNER JOIN props p ON r.prop_id = p.id
                            WHERE r.id = :id");
    $query->execute(['id' => $request_id]);
    $request = $query->fetch(PDO::FETCH_OBJ);

    if (!$request) {
        echo json_encode(['success' => false, 'message' => 'Request not found']);
        exit;
    }

    // Step 2: Create notification and commit transaction immediately
    $conn->beginTransaction();
    
    $notification = $conn->prepare("INSERT INTO notifications (user_id, title, message, created_at) 
                                  VALUES (:user_id, :title, :message, NOW())");

    $notification_message = "You have received a message from an administrator regarding your request for property '{$request->property_name}': {$message}";

    $notification->execute([
        'user_id' => $request->user_id,
        'title' => $subject,
        'message' => $notification_message
    ]);

    // Step 3: Queue the email
    $email_data = [
        'to_email' => $request->user_email,
        'to_name' => $request->username,
        'subject' => $subject,
        'message' => $message,
        'property_name' => $request->property_name
    ];

    $queue = $conn->prepare("INSERT INTO email_queue (data, status, created_at) VALUES (:data, 'pending', NOW())");
    $queue->execute(['data' => json_encode($email_data)]);

    $conn->commit();

    // Return success response immediately
    echo json_encode([
        'success' => true,
        'message' => 'Message sent successfully! The email will be delivered shortly.'
    ]);
    exit;

} catch (Exception $e) {
    if ($conn->inTransaction()) {
        $conn->rollBack();
    }
    echo json_encode([
        'success' => false, 
        'message' => 'Error: ' . $e->getMessage()
    ]);
}
?>
 