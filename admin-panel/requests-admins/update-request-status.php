<?php
// Prevent any output before JSON response
ob_start();

// Enable detailed error logging
error_reporting(E_ALL);
ini_set('display_errors', 0);
ini_set('log_errors', 1);
ini_set('error_log', '/tmp/php_errors.log');

// Set error handler to catch all errors
set_error_handler(function($errno, $errstr, $errfile, $errline) {
    error_log("PHP Error [$errno]: $errstr in $errfile on line $errline");
    return false;
});

// Set exception handler
set_exception_handler(function($e) {
    error_log("Uncaught Exception: " . $e->getMessage() . "\n" . $e->getTraceAsString());
    header('Content-Type: application/json');
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'An unexpected error occurred',
        'error' => $e->getMessage()
    ]);
    exit;
});

// Log the start of the script
error_log("Starting update-request-status.php script");

try {
    require "../../config/config.php";

    // Start the session if not already started
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    // Set JSON header
    header('Content-Type: application/json');

    // Function to send JSON response and exit
    function sendJsonResponse($success, $message, $statusCode = 200) {
        error_log("Sending JSON response: " . json_encode(['success' => $success, 'message' => $message]));
        http_response_code($statusCode);
        echo json_encode(['success' => $success, 'message' => $message]);
        exit;
    }

    // Check if admin is logged in
    if (!isset($_SESSION['adminname']) || empty($_SESSION['adminname'])) {
        error_log("Unauthorized access attempt");
        sendJsonResponse(false, 'Unauthorized access', 401);
    }

    // Get JSON data from request body
    $raw_data = file_get_contents('php://input');
    error_log("Raw input data: " . $raw_data);

    $data = json_decode($raw_data, true);
    if (json_last_error() !== JSON_ERROR_NONE) {
        error_log("JSON decode error: " . json_last_error_msg());
        sendJsonResponse(false, 'Invalid JSON data: ' . json_last_error_msg(), 400);
    }

    // Log the received data
    error_log("Decoded data: " . print_r($data, true));

    if (!isset($data['request_id']) || !isset($data['status'])) {
        error_log("Missing required fields: request_id or status");
        sendJsonResponse(false, 'Request ID and status are required', 400);
    }

    $request_id = $data['request_id'];
    $status = $data['status'];
    $note = isset($data['note']) ? $data['note'] : '';

    // Validate status
    $valid_statuses = ['pending', 'approved', 'rejected'];
    if (!in_array($status, $valid_statuses)) {
        error_log("Invalid status provided: " . $status);
        sendJsonResponse(false, 'Invalid status', 400);
    }

    error_log("Starting database operations for request_id: " . $request_id);

    // Check and add required columns if they don't exist
    $checkStatusColumn = $conn->query("SHOW COLUMNS FROM requests LIKE 'status'");
    if ($checkStatusColumn->rowCount() == 0) {
        error_log("Adding status column to requests table");
        $conn->exec("ALTER TABLE requests ADD COLUMN status VARCHAR(20) DEFAULT 'pending'");
    }

    $checkUpdatedAtColumn = $conn->query("SHOW COLUMNS FROM requests LIKE 'updated_at'");
    if ($checkUpdatedAtColumn->rowCount() == 0) {
        error_log("Adding updated_at column to requests table");
        $conn->exec("ALTER TABLE requests ADD COLUMN updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP");
    }

    // Get the request details to create a notification
    error_log("Fetching request details for ID: " . $request_id);
    $query = $conn->prepare("SELECT r.*, u.id as user_id, u.email as user_email, u.username, p.name as property_name, p.type as property_type, p.price 
                            FROM requests r
                            INNER JOIN users u ON r.user_id = u.id
                            INNER JOIN props p ON r.prop_id = p.id
                            WHERE r.id = :id");
    $query->execute(['id' => $request_id]);
    $request = $query->fetch(PDO::FETCH_OBJ);

    if (!$request) {
        error_log("Request not found for ID: " . $request_id);
        sendJsonResponse(false, 'Request not found', 404);
    }

    error_log("Request found: " . print_r($request, true));

    // Begin transaction
    $conn->beginTransaction();
    error_log("Transaction started");

    // Update request status
    $update = $conn->prepare("UPDATE requests SET status = :status WHERE id = :id");
    $update->execute([
        'status' => $status,
        'id' => $request_id
    ]);
    error_log("Request status updated to: " . $status);

    // Create notification for the user
    $notification = $conn->prepare("INSERT INTO notifications (user_id, title, message, created_at) 
                                  VALUES (:user_id, :title, :message, NOW())");
    
    $status_message = ucfirst($status);
    $message = "Your request for property '{$request->property_name}' has been {$status}.";
    if (!empty($note)) {
        $message .= "\n\nNote from admin:\n" . $note;
    }
    
    $notification->execute([
        'user_id' => $request->user_id,
        'title' => "Request {$status_message}",
        'message' => $message
    ]);
    error_log("Notification created for user_id: " . $request->user_id);

    // Commit transaction
    $conn->commit();
    error_log("Transaction committed successfully");

    // Clear any output buffer
    ob_end_clean();

    // Return success response
    sendJsonResponse(true, 'Request status updated successfully');

} catch (PDOException $e) {
    // Rollback transaction on error
    if ($conn->inTransaction()) {
        $conn->rollBack();
        error_log("Transaction rolled back due to PDO error");
    }
    error_log("Database error in update-request-status.php: " . $e->getMessage());
    error_log("SQL State: " . $e->getCode());
    error_log("Error Info: " . print_r($e->errorInfo, true));
    sendJsonResponse(false, 'Database error: ' . $e->getMessage(), 500);
} catch (Exception $e) {
    // Rollback transaction on error
    if ($conn->inTransaction()) {
        $conn->rollBack();
        error_log("Transaction rolled back due to general error");
    }
    error_log("General error in update-request-status.php: " . $e->getMessage());
    error_log("Error trace: " . $e->getTraceAsString());
    sendJsonResponse(false, 'An error occurred: ' . $e->getMessage(), 500);
}
?> 