<?php
// Include URL configuration
require_once __DIR__ . '/urls.php';

// Load environment variables from .env file
if (!function_exists('loadEnv')) {
    function loadEnv($path) {
        if (file_exists($path)) {
            $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
            foreach ($lines as $line) {
                if (strpos($line, '=') !== false && strpos($line, '#') !== 0) {
                    list($key, $value) = explode('=', $line, 2);
                    $key = trim($key);
                    $value = trim($value);
                    putenv("$key=$value");
                    $_ENV[$key] = $value;
                }
            }
        }
    }
}

// Load .env file
loadEnv(__DIR__ . '/.env');

try {
    //host
    if (!defined('HOSTNAME'))
        define("HOSTNAME", "localhost");
    //database
    if (!defined("DBNAME"))
    define("DBNAME", "homeland");
    //user
    if (!defined("USER"))
    define("USER", "root");
    //password
    if (!defined("PASS"))
    define("PASS", "");

    // Pagination settings
    if (!defined("ITEMS_PER_PAGE"))
        define("ITEMS_PER_PAGE", 8);
    if (!defined("PAGINATION_RANGE"))
        define("PAGINATION_RANGE", 2);

    // Admin email configuration
    if (!defined('ADMINEMAIL'))
        define('ADMINEMAIL', 'kipyegonlenon226s@gmail.com'); // Change this to your admin email address

    $conn = new PDO("mysql:host=" . HOSTNAME . ";dbname=" . DBNAME . ";", USER, PASS);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Set timezone for the connection
    $conn->exec("SET time_zone = '+03:00'"); // Set to East Africa Time (EAT)
} catch (PDOException $e) {
    //cancel DB connection and display error message
    die("Database connection failed :" . $e->getMessage());
}

// Remove SMTP settings since we're using Node.js email service
// define('SMTP_HOST', 'smtp.brevo.com');
// define('SMTP_PORT', 587);
// define('SMTP_USERNAME', 'your-username');
// define('SMTP_PASSWORD', 'your-password');
