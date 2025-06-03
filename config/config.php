<?php
// Include URL configuration
require_once __DIR__ . '/urls.php';

// // Define base URLs - using absolute paths
// define("APPURL", "http://localhost/homeland");
// define("ADMINURL", "http://localhost/homeland/admin-panel/");
// define("IMAGESURL", "http://localhost/homeland/admin-panel/properties-admins");
// define("IMAGES", __DIR__ . "/../admin-panel/properties-admins/images");

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
    define('ADMINEMAIL', 'cuea1049074@gmail.com'); // Change this to your admin email address

    $conn = new PDO("mysql:host=" . HOSTNAME . ";dbname=" . DBNAME . ";", USER, PASS);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Set timezone for the connection
    $conn->exec("SET time_zone = '+03:00'"); // Set to East Africa Time (EAT)
} catch (PDOException $e) {
    //cancel DB connection and display error message
    die("Database connection failed :" . $e->getMessage());
}
