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
    // Get request details
    $query = $conn->prepare("SELECT r.*, u.username, p.name as property_name 
                            FROM requests r
                            INNER JOIN users u ON r.user_id = u.id
                            INNER JOIN props p ON r.prop_id = p.id
                            WHERE r.id = :id");
    $query->execute(['id' => $request_id]);
    $request = $query->fetch(PDO::FETCH_OBJ);

    if (!$request) {
        echo json_encode(['success' => false, 'message' => 'Request not found']);
        exit;
    }

    // Get message history
    $messages = $conn->prepare("SELECT n.*, DATE_FORMAT(n.created_at, '%Y-%m-%d %H:%i') as formatted_date
                               FROM notifications n
                               WHERE n.user_id = :user_id 
                               AND n.type = 'admin_message'
                               AND n.message LIKE :property_name
                               ORDER BY n.created_at DESC");
    
    $messages->execute([
        'user_id' => $request->user_id,
        'property_name' => '%' . $request->property_name . '%'
    ]);
    $message_history = $messages->fetchAll(PDO::FETCH_OBJ);

    // Generate HTML for the modal
    $html = "
    <div class='message-history'>
        <div class='alert alert-info mb-4'>
            <h6 class='mb-2'>Request Information</h6>
            <p class='mb-1'><strong>Property:</strong> {$request->property_name}</p>
            <p class='mb-1'><strong>User:</strong> {$request->username}</p>
        </div>
        
        <div class='message-list'>";
    
    if (count($message_history) > 0) {
        foreach ($message_history as $message) {
            $html .= "
            <div class='card mb-3'>
                <div class='card-body'>
                    <div class='d-flex justify-content-between align-items-center mb-2'>
                        <small class='text-muted'>{$message->formatted_date}</small>
                    </div>
                    <p class='card-text'>{$message->message}</p>
                </div>
            </div>";
        }
    } else {
        $html .= "
        <div class='alert alert-warning'>
            No message history found for this request.
        </div>";
    }

    $html .= "
        </div>
    </div>";

    echo json_encode(['success' => true, 'html' => $html]);

} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
}
?> 