<?php
require "../../config/config.php";

// Check if admin is logged in
if (!isset($_SESSION['adminname'])) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized access']);
    exit;
}

// Check if request ID is provided
if (!isset($_GET['id'])) {
    echo json_encode(['success' => false, 'message' => 'Request ID is required']);
    exit;
}

$request_id = $_GET['id'];

try {
    // Get request details with related information
    $query = $conn->prepare("SELECT r.*, p.name as property_name, p.price, p.type as property_type,
                            p.description as property_description, p.location,
                            u.username, u.email as user_email,
                            DATE_FORMAT(r.timestamp, '%Y-%m-%d %H:%i') as formatted_date
                            FROM requests r
                            INNER JOIN props p ON r.prop_id = p.id
                            INNER JOIN users u ON r.user_id = u.id
                            WHERE r.id = :id");
    
    $query->execute(['id' => $request_id]);
    $request = $query->fetch(PDO::FETCH_OBJ);

    if (!$request) {
        echo json_encode(['success' => false, 'message' => 'Request not found']);
        exit;
    }

    // Generate HTML for the modal
    $html = "
    <div class='request-details'>
        <div class='row mb-3'>
            <div class='col-md-6'>
                <h6>User Information</h6>
                <p><strong>Name:</strong> {$request->username}</p>
                <p><strong>Email:</strong> {$request->user_email}</p>
                <p><strong>Phone:</strong> {$request->phone}</p>
            </div>
            <div class='col-md-6'>
                <h6>Request Information</h6>
                <p><strong>Date:</strong> {$request->formatted_date}</p>
                <p><strong>Status:</strong> <span class='badge badge-primary'>Pending</span></p>
            </div>
        </div>
        
        <div class='row mb-3'>
            <div class='col-12'>
                <h6>Property Details</h6>
                <p><strong>Name:</strong> {$request->property_name}</p>
                <p><strong>Type:</strong> " . ucfirst($request->property_type) . "</p>
                <p><strong>Price:</strong> $" . number_format($request->price) . "</p>
                <p><strong>Location:</strong> {$request->location}</p>
                <p><strong>Description:</strong> {$request->property_description}</p>
            </div>
        </div>
        
        <div class='row'>
            <div class='col-12'>
                <h6>Additional Information</h6>
                <p><strong>Message:</strong> {$request->message}</p>
            </div>
        </div>
    </div>";

    echo json_encode(['success' => true, 'html' => $html]);

} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
}
?> 