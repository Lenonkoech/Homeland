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
    define('APPURL', $base_url . '/');
}

if (!defined('ADMINURL')) {
    define('ADMINURL', $base_url . '/admin-panel');
}

// Define image URLs
if (!defined('IMAGESURL')) {
    define('IMAGESURL', $base_url . '/images');
}

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