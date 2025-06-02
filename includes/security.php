<?php
// Two-factor authentication functions
function generateTwoFactorSecret() {
    $secret = random_bytes(32);
    return base32_encode($secret);
}

function verifyTwoFactorCode($secret, $code) {
    $timeSlice = floor(time() / 30);
    $codeLength = 6;
    
    for ($i = -1; $i <= 1; $i++) {
        $calculatedCode = getCode($secret, $timeSlice + $i);
        if ($calculatedCode == $code) {
            return true;
        }
    }
    
    return false;
}

function getCode($secret, $timeSlice) {
    $secretKey = base32_decode($secret);
    $time = chr(0).chr(0).chr(0).chr(0).pack('N*', $timeSlice);
    $hm = hash_hmac('SHA1', $time, $secretKey, true);
    $offset = ord(substr($hm, -1)) & 0x0F;
    $hashpart = substr($hm, $offset, 4);
    $value = unpack('N', $hashpart);
    $value = $value[1];
    $value = $value & 0x7FFFFFFF;
    $modulo = pow(10, 6);
    return str_pad($value % $modulo, 6, '0', STR_PAD_LEFT);
}

// Rate limiting functions
function checkRateLimit($ip, $action, $limit = 60, $period = 3600) {
    global $conn;
    
    $query = "SELECT COUNT(*) as count 
              FROM rate_limits 
              WHERE ip = ? AND action = ? AND created_at > DATE_SUB(NOW(), INTERVAL ? SECOND)";
    $stmt = $conn->prepare($query);
    $stmt->execute([$ip, $action, $period]);
    
    $count = $stmt->fetch(PDO::FETCH_OBJ)->count;
    
    if ($count >= $limit) {
        return false;
    }
    
    $query = "INSERT INTO rate_limits (ip, action) VALUES (?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->execute([$ip, $action]);
    
    return true;
}

// CAPTCHA functions
function generateCaptcha() {
    $chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $captchaString = '';
    
    for ($i = 0; $i < 6; $i++) {
        $captchaString .= $chars[rand(0, strlen($chars) - 1)];
    }
    
    $_SESSION['captcha'] = $captchaString;
    
    return $captchaString;
}

function verifyCaptcha($input) {
    if (!isset($_SESSION['captcha'])) {
        return false;
    }
    
    $result = strtolower($input) === strtolower($_SESSION['captcha']);
    unset($_SESSION['captcha']);
    
    return $result;
}

// Password security functions
function hashPassword($password) {
    return password_hash($password, PASSWORD_BCRYPT, ['cost' => 12]);
}

function verifyPassword($password, $hash) {
    return password_verify($password, $hash);
}

function generatePasswordResetToken() {
    return bin2hex(random_bytes(32));
}

function validatePasswordResetToken($token) {
    global $conn;
    
    $query = "SELECT * FROM password_resets 
              WHERE token = ? AND created_at > DATE_SUB(NOW(), INTERVAL 1 HOUR)";
    $stmt = $conn->prepare($query);
    $stmt->execute([$token]);
    
    return $stmt->fetch(PDO::FETCH_OBJ);
}

// Session security functions
function regenerateSession() {
    if (session_status() === PHP_SESSION_ACTIVE) {
        session_regenerate_id(true);
    }
}

function validateSession() {
    if (!isset($_SESSION['last_activity'])) {
        $_SESSION['last_activity'] = time();
    }
    
    if (time() - $_SESSION['last_activity'] > 1800) {
        session_unset();
        session_destroy();
        return false;
    }
    
    $_SESSION['last_activity'] = time();
    return true;
}

// Input sanitization functions
function sanitizeInput($input) {
    if (is_array($input)) {
        return array_map('sanitizeInput', $input);
    }
    
    return htmlspecialchars(trim($input), ENT_QUOTES, 'UTF-8');
}

function validateEmail($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}

function validatePhone($phone) {
    return preg_match('/^[0-9]{10,15}$/', $phone);
}

// Logging functions
function logSecurityEvent($event, $details = '') {
    global $conn;
    
    $query = "INSERT INTO security_logs (event, details, ip_address) 
              VALUES (?, ?, ?)";
    $stmt = $conn->prepare($query);
    return $stmt->execute([$event, $details, $_SERVER['REMOTE_ADDR']]);
}

// CSRF protection
function generateCSRFToken() {
    if (!isset($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

function validateCSRFToken($token) {
    if (!isset($_SESSION['csrf_token']) || $token !== $_SESSION['csrf_token']) {
        return false;
    }
    return true;
}

// XSS protection
function escapeOutput($output) {
    if (is_array($output)) {
        return array_map('escapeOutput', $output);
    }
    return htmlspecialchars($output, ENT_QUOTES, 'UTF-8');
}

// SQL injection protection
function sanitizeSQL($input) {
    if (is_array($input)) {
        return array_map('sanitizeSQL', $input);
    }
    return str_replace([';', '"', "'", '\\', '/', '*', '=', '>', '<', '(', ')', ' '], '', $input);
}
?> 