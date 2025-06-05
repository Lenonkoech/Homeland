<?php
/**
 * Queue an email to be sent
 * 
 * @param string $to Recipient email address
 * @param string $subject Email subject
 * @param string $message Email message (HTML)
 * @return int|false Returns the email queue ID if successful, false otherwise
 */
function queueEmail($to_email, $subject, $message) {
    global $conn;
    try {
        $stmt = $conn->prepare("INSERT INTO email_queue (to_email, subject, message, status, created_at) VALUES (?, ?, ?, 'pending', NOW())");
        return $stmt->execute([$to_email, $subject, $message]);
    } catch (Exception $e) {
        return false;
    }
}
?> 