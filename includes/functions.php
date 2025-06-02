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
        $sql = "INSERT INTO notifications (user_id, title, message) 
                VALUES (:user_id, :title, :message)";
                
        $stmt = $conn->prepare($sql);
        return $stmt->execute([
            ':user_id' => $user_id,
            ':title' => $title,
            ':message' => $message
        ]);
    } catch (PDOException $e) {
        error_log("Error creating notification: " . $e->getMessage());
        return false;
    }
}

// Function to get user's notifications
function getUserNotifications($user_id, $limit = 10) {
    global $conn;
    try {
        $sql = "SELECT * FROM notifications WHERE user_id = :user_id ORDER BY created_at DESC";
        $stmt = $conn->prepare($sql);
        $stmt->execute(['user_id' => $user_id]);
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    } catch(PDOException $e) {
        error_log("Error fetching notifications: " . $e->getMessage());
        return [];
    }
}

// Function to mark notification as read
function markNotificationAsRead($notification_id, $user_id) {
    global $conn;
    try {
        $sql = "UPDATE notifications 
                SET is_read = TRUE 
                WHERE id = :notification_id AND user_id = :user_id";
                
        $stmt = $conn->prepare($sql);
        return $stmt->execute([
            ':notification_id' => $notification_id,
            ':user_id' => $user_id
        ]);
    } catch (PDOException $e) {
        error_log("Error marking notification as read: " . $e->getMessage());
        return false;
    }
}

// Function to get unread notification count
function getUnreadNotificationCount($user_id) {
    global $conn;
    try {
        $sql = "SELECT COUNT(*) as count 
                FROM notifications 
                WHERE user_id = :user_id AND is_read = FALSE";
                
        $stmt = $conn->prepare($sql);
        $stmt->execute([':user_id' => $user_id]);
        $result = $stmt->fetch(PDO::FETCH_OBJ);
        return $result ? $result->count : 0;
    } catch (PDOException $e) {
        error_log("Error getting unread notification count: " . $e->getMessage());
        return 0;
    }
}

// Function to delete a notification
function deleteNotification($notification_id, $user_id) {
    global $conn;
    try {
        $sql = "DELETE FROM notifications 
                WHERE id = :notification_id AND user_id = :user_id";
                
        $stmt = $conn->prepare($sql);
        return $stmt->execute([
            ':notification_id' => $notification_id,
            ':user_id' => $user_id
        ]);
    } catch (PDOException $e) {
        error_log("Error deleting notification: " . $e->getMessage());
        return false;
    }
} 