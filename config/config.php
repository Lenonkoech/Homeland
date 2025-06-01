<?php
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

    $conn = new PDO("mysql:host=" . HOSTNAME . ";dbname=" . DBNAME . ";", USER, PASS);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    //cancel DB connection and display error message
    die("Database connection failed :" . $e->getMessage());
}
