<?php
function sanitizeInput($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

// Function to create a notification
function createNotification($user_id, $title, $message, $type = 'info') {
    global $conn;
    try {
        $stmt = $conn->prepare("INSERT INTO notifications (user_id, title, message, type) VALUES (?, ?, ?, ?)");
        return $stmt->execute([$user_id, $title, $message, $type]);
    } catch (Exception $e) {
        return false;
    }
}

// Function to get user's notifications
function getUserNotifications($user_id, $limit = 10) {
    global $conn;
    try {
        $stmt = $conn->prepare("SELECT * FROM notifications WHERE user_id = ? ORDER BY created_at DESC LIMIT ?");
        $stmt->execute([$user_id, $limit]);
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    } catch (Exception $e) {
        return [];
    }
}

// Function to mark notification as read
function markNotificationAsRead($notification_id) {
    global $conn;
    try {
        $stmt = $conn->prepare("UPDATE notifications SET is_read = 1 WHERE id = ?");
        return $stmt->execute([$notification_id]);
    } catch (Exception $e) {
        return false;
    }
}

// Function to get unread notification count
function getUnreadNotificationCount($user_id) {
    global $conn;
    try {
        $stmt = $conn->prepare("SELECT COUNT(*) as count FROM notifications WHERE user_id = ? AND is_read = 0");
        $stmt->execute([$user_id]);
        $result = $stmt->fetch(PDO::FETCH_OBJ);
        return $result->count;
    } catch (Exception $e) {
        return 0;
    }
}

// Function to delete a notification
function deleteNotification($notification_id) {
    global $conn;
    try {
        $stmt = $conn->prepare("DELETE FROM notifications WHERE id = ?");
        return $stmt->execute([$notification_id]);
    } catch (Exception $e) {
        return false;
    }
} 