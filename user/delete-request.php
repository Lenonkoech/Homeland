<?php
session_start();
require "../config/config.php";
require_once "../includes/functions.php";

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Please login to delete requests']);
    exit;
}

// Check if request ID is provided
if (!isset($_POST['request_id'])) {
    echo json_encode(['success' => false, 'message' => 'Request ID is required']);
    exit;
}

$request_id = $_POST['request_id'];
$user_id = $_SESSION['user_id'];

try {
    // First verify that the request belongs to the user
    $check = $conn->prepare("SELECT * FROM requests WHERE id = :request_id AND user_id = :user_id");
    $check->execute([
        ':request_id' => $request_id,
        ':user_id' => $user_id
    ]);
    
    if ($check->rowCount() === 0) {
        echo json_encode(['success' => false, 'message' => 'Request not found or unauthorized']);
        exit;
    }
    
    // Delete the request
    $delete = $conn->prepare("DELETE FROM requests WHERE id = :request_id AND user_id = :user_id");
    $result = $delete->execute([
        ':request_id' => $request_id,
        ':user_id' => $user_id
    ]);
    
    if ($result) {
        // Create notification for request deletion
        createNotification(
            $user_id,
            "Request Deleted",
            "Your property request has been successfully deleted."
        );
        
        echo json_encode(['success' => true, 'message' => 'Request deleted successfully']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to delete request']);
    }
} catch (PDOException $e) {
    error_log("Error deleting request: " . $e->getMessage());
    echo json_encode(['success' => false, 'message' => 'An error occurred while deleting the request']);
} 