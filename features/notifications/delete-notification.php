<?php
require_once '../../config/config.php';
require_once '../../includes/functions.php';

session_start();

// Ensure no whitespace or output before headers
ob_start();

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Not logged in']);
    exit();
}

$data = json_decode(file_get_contents('php://input'), true);
$notification_id = $data['notification_id'] ?? null;

if (!$notification_id) {
    echo json_encode(['success' => false, 'message' => 'Notification ID is required']);
    exit();
}

try {
    $delete = $conn->prepare("DELETE FROM notifications WHERE id = :id AND user_id = :user_id");
    $result = $delete->execute([
        ':id' => $notification_id,
        ':user_id' => $_SESSION['user_id']
    ]);

    if ($result) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to delete notification']);
    }
} catch (PDOException $e) {
    error_log("Error deleting notification: " . $e->getMessage());
    echo json_encode(['success' => false, 'message' => 'Database error']);
}

ob_end_flush();
?> 