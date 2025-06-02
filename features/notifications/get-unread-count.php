<?php
require_once '../../config/config.php';
require_once '../../includes/functions.php';

session_start();

// Ensure no whitespace or output before headers
ob_start();

if (!isset($_SESSION['user_id'])) {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => 'Not authenticated']);
    exit();
}

$count = getUnreadNotificationCount($_SESSION['user_id']);
header('Content-Type: application/json');
echo json_encode(['success' => true, 'count' => $count]);

ob_end_flush();
?> 