<?php
require_once '../../config/config.php';
require_once '../../includes/functions.php';

try {
    $sql = "CREATE TABLE IF NOT EXISTS user_tokens (
        id INT AUTO_INCREMENT PRIMARY KEY,
        user_id INT NOT NULL,
        token VARCHAR(255) NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        expires_at TIMESTAMP NOT NULL,
        is_active BOOLEAN DEFAULT TRUE,
        FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
        UNIQUE KEY unique_token (token)
    )";
    
    $conn->exec($sql);
    echo "Tokens table created successfully";
} catch(PDOException $e) {
    echo "Error creating table: " . $e->getMessage();
}
?> 