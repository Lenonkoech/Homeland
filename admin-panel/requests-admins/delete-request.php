<?php
require "../../config/config.php";

// Check if admin is logged in
if (!isset($_SESSION['adminname'])) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized access']);
    exit;
}

// Get JSON data from request body
$data = json_decode(file_get_contents('php://input'), true);

if (!isset($data['request_id'])) {
    echo json_encode(['success' => false, 'message' => 'Request ID is required']);
    exit;
}

$request_id = $data['request_id'];

try {
    // First, get the request details to create a notification
    $query = $conn->prepare("SELECT r.*, u.id as user_id, p.name as property_name 
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

    // Begin transaction
    $conn->beginTransaction();

    // Delete the request
    $delete = $conn->prepare("DELETE FROM requests WHERE id = :id");
    $delete->execute(['id' => $request_id]);

    // Create notification for the user
    $notification = $conn->prepare("INSERT INTO notifications (user_id, title, message, created_at) 
                                  VALUES (:user_id, :title, :message, NOW())");
    
    $message = "Your request for property '{$request->property_name}' has been deleted by an administrator.";
    $notification->execute([
        'user_id' => $request->user_id,
        'title' => 'Request Deleted',
        'message' => $message
    ]);

    // Commit transaction
    $conn->commit();

    echo json_encode(['success' => true, 'message' => 'Request deleted successfully']);

} catch (PDOException $e) {
    // Rollback transaction on error
    if ($conn->inTransaction()) {
        $conn->rollBack();
    }
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
}
?> 