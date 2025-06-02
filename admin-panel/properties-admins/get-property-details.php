<?php
require "../../config/config.php";

// Check if admin is logged in
if (!isset($_SESSION['adminname'])) {
    http_response_code(401);
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

// Check if property ID is provided
if (!isset($_GET['id'])) {
    http_response_code(400);
    echo json_encode(['error' => 'Property ID is required']);
    exit;
}

$propertyId = $_GET['id'];

// Function to get single property details
function getPropertyDetails($conn, $propertyId) {
    $query = "SELECT * FROM props WHERE id = :id";
    $stmt = $conn->prepare($query);
    $stmt->bindValue(':id', $propertyId);
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_OBJ);
}

try {
    $property = getPropertyDetails($conn, $propertyId);
    
    if (!$property) {
        http_response_code(404);
        echo json_encode(['error' => 'Property not found']);
        exit;
    }

    // Return property details as JSON
    header('Content-Type: application/json');
    echo json_encode($property);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Server error']);
}
?> 