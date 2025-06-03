<?php
// Get the server protocol
$protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https://' : 'http://';

// Get the server name
$server_name = $_SERVER['SERVER_NAME'];

// Get the base directory
$base_dir = dirname(dirname($_SERVER['SCRIPT_NAME']));
$base_dir = $base_dir === '/' ? '' : $base_dir;

// Define the base URL
$base_url = $protocol . $server_name . $base_dir;

// Define constants for URLs
if (!defined('APPURL')) {
    define('APPURL', $base_url . '/homeland/');  // For user side
}

if (!defined('ADMINURL')) {
    define('ADMINURL', $base_url . '/admin-panel');  // For admin side
}

// Define image URLs
if (!defined('IMAGESURL')) {
    define('IMAGESURL', $base_url . '/admin-panel/properties-admins');  // Base path for admin
}

if (!defined('USERIMAGESURL')) {
    define('USERIMAGESURL', $base_url . '/homeland/admin-panel/properties-admins');  // Base path for user
}

// Define physical paths for file operations
// if (!defined('IMAGES')) {
    // define('IMAGES', dirname(dirname(__DIR__)) . '');
// }

// Define asset URLs
if (!defined('CSSURL')) {
    define('CSSURL', $base_url . '/css');
}

if (!defined('JSURL')) {
    define('JSURL', $base_url . '/js');
}

// Function to get relative URL
function get_relative_url($path) {
    return ltrim($path, '/');
}
?> 