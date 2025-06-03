<?php
require "../../config/config.php";
require "../../includes/functions.php";

// if (!isset($_SESSION['adminname']) || !isset($_SESSION['admin_id'])) {
//     echo json_encode(['success' => false, 'message' => 'Please login first']);
//     exit();
// }

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    try {
        // Validate required fields
        $required_fields = ['id', 'name', 'price', 'type', 'description', 'location', 'status'];
        foreach ($required_fields as $field) {
            if (!isset($_POST[$field]) || empty($_POST[$field])) {
                echo json_encode(['success' => false, 'message' => ucfirst($field) . ' is required']);
                exit();
            }
        }

        $id = filter_var($_POST['id'], FILTER_SANITIZE_NUMBER_INT);
        $name = filter_var($_POST['name'], FILTER_SANITIZE_STRING);
        $price = filter_var($_POST['price'], FILTER_SANITIZE_NUMBER_INT);
        $type = filter_var($_POST['type'], FILTER_SANITIZE_STRING);
        $description = filter_var($_POST['description'], FILTER_SANITIZE_STRING);
        $location = filter_var($_POST['location'], FILTER_SANITIZE_STRING);
        $status = filter_var($_POST['status'], FILTER_SANITIZE_STRING);

        // Validate property exists
        $checkProp = $conn->prepare("SELECT * FROM props WHERE id = :id");
        $checkProp->execute([':id' => $id]);
        if (!$checkProp->fetch()) {
            echo json_encode(['success' => false, 'message' => 'Property not found']);
            exit();
        }

        // Handle image upload
        $image = $_FILES['image']['name'];
        $image_tmp = $_FILES['image']['tmp_name'];
        $image_size = $_FILES['image']['size'];
        $image_error = $_FILES['image']['error'];

        // Get current image
        $getCurrentImage = $conn->prepare("SELECT image FROM props WHERE id = :id");
        $getCurrentImage->execute([':id' => $id]);
        $currentImage = $getCurrentImage->fetch(PDO::FETCH_OBJ);

        if ($image_error === 0) {
            // New image uploaded
            $allowed = ['jpg', 'jpeg', 'png', 'webp'];
            $image_ext = strtolower(pathinfo($image, PATHINFO_EXTENSION));

            if (in_array($image_ext, $allowed)) {
                if ($image_size < 5000000) { // 5MB max
                    $image_name = uniqid('property_') . '.' . $image_ext;
                    
                    // Delete old image if exists
                    if ($currentImage->image && file_exists(IMAGES . '/' . $currentImage->image)) {
                        unlink(IMAGES . '/' . $currentImage->image);
                    }
                    
                    // Upload new image
                    if (!move_uploaded_file($image_tmp, IMAGES . '/' . $image_name)) {
                        echo json_encode(['success' => false, 'message' => 'Failed to upload image']);
                        exit();
                    }
                    
                    // Update with new image
                    $update = $conn->prepare("UPDATE props SET 
                        name = :name,
                        price = :price,
                        type = :type,
                        description = :description,
                        location = :location,
                        status = :status,
                        image = :image
                        WHERE id = :id");

                    $update->execute([
                        ':name' => $name,
                        ':price' => $price,
                        ':type' => $type,
                        ':description' => $description,
                        ':location' => $location,
                        ':status' => $status,
                        ':image' => $image_name,
                        ':id' => $id
                    ]);

                    if ($update->rowCount() > 0) {
                        // Create notification
                        createNotification(
                            $_SESSION['admin_id'],
                            "Property Updated",
                            "Property '{$name}' has been successfully updated with a new image."
                        );

                        echo json_encode(['success' => true, 'message' => 'Property updated successfully']);
                        exit();
                    } else {
                        echo json_encode(['success' => false, 'message' => 'No changes were made to the property']);
                        exit();
                    }
                } else {
                    echo json_encode(['success' => false, 'message' => 'Image size is too large. Maximum size is 5MB.']);
                    exit();
                }
            } else {
                echo json_encode(['success' => false, 'message' => 'Invalid image format. Allowed formats: jpg, jpeg, png, webp']);
                exit();
            }
        } else {
            // No new image uploaded, keep current image
            $update = $conn->prepare("UPDATE props SET 
                name = :name,
                price = :price,
                type = :type,
                description = :description,
                location = :location,
                status = :status
                WHERE id = :id");

            $update->execute([
                ':name' => $name,
                ':price' => $price,
                ':type' => $type,
                ':description' => $description,
                ':location' => $location,
                ':status' => $status,
                ':id' => $id
            ]);

            if ($update->rowCount() > 0) {
                // Create notification
                createNotification(
                    $_SESSION['admin_id'],
                    "Property Updated",
                    "Property '{$name}' has been successfully updated."
                );

                echo json_encode(['success' => true, 'message' => 'Property updated successfully']);
                exit();
            } else {
                echo json_encode(['success' => false, 'message' => 'No changes were made to the property']);
                exit();
            }
        }
    } catch (PDOException $e) {
        error_log("Error updating property: " . $e->getMessage());
        echo json_encode(['success' => false, 'message' => 'Database error occurred: ' . $e->getMessage()]);
        exit();
    } catch (Exception $e) {
        error_log("Error updating property: " . $e->getMessage());
        echo json_encode(['success' => false, 'message' => 'An error occurred: ' . $e->getMessage()]);
        exit();
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
    exit();
}
?>