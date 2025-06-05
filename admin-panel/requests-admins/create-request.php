<?php
// Prevent any output before JSON response
ob_start();

// Enable detailed error logging
error_reporting(E_ALL);
ini_set('display_errors', 0);
ini_set('log_errors', 1);
ini_set('error_log', '/tmp/php_errors.log');

try {
    require "../../config/config.php";
    require "../../includes/queue-email.php";

    // Start the session if not already started
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    // Set JSON header
    header('Content-Type: application/json');

    // Check if user is logged in
    if (!isset($_SESSION['user_id']) || empty($_SESSION['user_id'])) {
        http_response_code(401);
        echo json_encode(['success' => false, 'message' => 'Please login to submit a request']);
        exit;
    }

    // Get JSON data from request body
    $raw_data = file_get_contents('php://input');
    $data = json_decode($raw_data, true);

    if (!isset($data['prop_id'])) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Property ID is required']);
        exit;
    }

    $user_id = $_SESSION['user_id'];
    $prop_id = $data['prop_id'];

    // Begin transaction
    $conn->beginTransaction();

    // Get property and user details
    $query = $conn->prepare("SELECT p.*, u.email as user_email, u.username 
                            FROM props p 
                            INNER JOIN users u ON u.id = :user_id 
                            WHERE p.id = :prop_id");
    $query->execute([
        'user_id' => $user_id,
        'prop_id' => $prop_id
    ]);
    $details = $query->fetch(PDO::FETCH_OBJ);

    if (!$details) {
        throw new Exception('Property not found');
    }

    // Create the request
    $stmt = $conn->prepare("INSERT INTO requests (user_id, prop_id, status, created_at) 
                           VALUES (:user_id, :prop_id, 'pending', NOW())");
    $stmt->execute([
        'user_id' => $user_id,
        'prop_id' => $prop_id
    ]);
    $request_id = $conn->lastInsertId();

    // Create notification
    $notification = $conn->prepare("INSERT INTO notifications (user_id, title, message, created_at) 
                                  VALUES (:user_id, :title, :message, NOW())");
    $notification->execute([
        'user_id' => $user_id,
        'title' => 'Property Request Submitted',
        'message' => "Your request for property '{$details->name}' has been submitted and is pending review."
    ]);

    // Queue email to user
    $email_subject = "Property Request Submitted - {$details->name}";
    $email_message = "
        <h2>Property Request Confirmation</h2>
        <p>Dear {$details->username},</p>
        <p>Your request for the property '{$details->name}' has been submitted successfully.</p>
        <p><strong>Property Details:</strong></p>
        <ul>
            <li>Type: {$details->type}</li>
            <li>Price: $" . number_format($details->price, 2) . "</li>
        </ul>
        <p>We will review your request and get back to you soon.</p>
        <p>Thank you for using our service.</p>
        <p>Best regards,<br>Homeland Team</p>";

    // Queue the email
    $email_id = queueEmail($details->user_email, $email_subject, $email_message);
    if ($email_id) {
        error_log("Email queued successfully with ID: " . $email_id);
    } else {
        error_log("Failed to queue email for user: " . $details->user_email);
    }

    // Commit transaction
    $conn->commit();

    // Return success response
    echo json_encode([
        'success' => true,
        'message' => 'Request submitted successfully',
        'request_id' => $request_id
    ]);

} catch (Exception $e) {
    // Rollback transaction on error
    if ($conn->inTransaction()) {
        $conn->rollBack();
    }
    error_log("Error in create-request.php: " . $e->getMessage());
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'An error occurred while processing your request'
    ]);
}
?> 