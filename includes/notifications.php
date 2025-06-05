<?php
require_once __DIR__ . '/../config/config.php';

function createNotification($userId, $title, $message) {
    global $conn;
    
    $query = "INSERT INTO notifications (user_id, title, message) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($query);
    return $stmt->execute([$userId, $title, $message]);
}

function getUnreadNotifications($userId) {
    global $conn;
    
    $query = "SELECT * FROM notifications 
              WHERE user_id = ? AND is_read = 0 
              ORDER BY created_at DESC";
    $stmt = $conn->prepare($query);
    $stmt->execute([$userId]);
    
    return $stmt->fetchAll(PDO::FETCH_OBJ);
}

function markNotificationAsRead($notificationId) {
    global $conn;
    
    $query = "UPDATE notifications SET is_read = 1 WHERE id = ?";
    $stmt = $conn->prepare($query);
    return $stmt->execute([$notificationId]);
}

function markAllNotificationsAsRead($userId) {
    global $conn;
    
    $query = "UPDATE notifications SET is_read = 1 WHERE user_id = ?";
    $stmt = $conn->prepare($query);
    return $stmt->execute([$userId]);
}

function deleteNotification($notificationId) {
    global $conn;
    
    $query = "DELETE FROM notifications WHERE id = ?";
    $stmt = $conn->prepare($query);
    return $stmt->execute([$notificationId]);
}

function getNotificationCount($userId) {
    global $conn;
    
    $query = "SELECT COUNT(*) as count FROM notifications 
              WHERE user_id = ? AND is_read = 0";
    $stmt = $conn->prepare($query);
    $stmt->execute([$userId]);
    
    return $stmt->fetch(PDO::FETCH_OBJ)->count;
}

// Function to handle price change notifications
function checkPriceChanges() {
    global $conn;
    
    $query = "SELECT p.*, u.email 
              FROM properties p 
              INNER JOIN saved_properties sp ON p.id = sp.property_id 
              INNER JOIN users u ON sp.user_id = u.id 
              WHERE p.last_price_update > DATE_SUB(NOW(), INTERVAL 1 DAY)";
    
    $stmt = $conn->prepare($query);
    $stmt->execute();
    $priceChanges = $stmt->fetchAll(PDO::FETCH_OBJ);
    
    foreach ($priceChanges as $change) {
        $message = "The price of {$change->name} has been updated to {$change->price}";
        createNotification($change->user_id, "Price Update", $message);
    }
}

// Function to handle new property notifications
function notifyNewProperties() {
    global $conn;
    
    $query = "SELECT u.id as user_id, u.email, p.* 
              FROM users u 
              INNER JOIN saved_searches ss ON u.id = ss.user_id 
              INNER JOIN properties p ON p.created_at > DATE_SUB(NOW(), INTERVAL 1 DAY)";
    
    $stmt = $conn->prepare($query);
    $stmt->execute();
    $newProperties = $stmt->fetchAll(PDO::FETCH_OBJ);
    
    foreach ($newProperties as $property) {
        $message = "A new property matching your saved search has been listed: {$property->name}";
        createNotification($property->user_id, "New Property", $message);
    }
}
?> 