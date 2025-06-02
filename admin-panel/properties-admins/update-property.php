<?php
require "../../config/config.php";

if (!isset($_SESSION['adminname'])) {
    echo "<script>window.location.href='" . ADMINURL . "/admins/login-admins.php'</script>";
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id'];
    $name = $_POST['name'];
    $price = $_POST['price'];
    $type = $_POST['type'];
    $description = $_POST['description'];
    $location = $_POST['location'];
    $status = $_POST['status'];

    // Handle image upload
    $image = $_FILES['image']['name'];
    $image_tmp = $_FILES['image']['tmp_name'];
    $image_size = $_FILES['image']['size'];
    $image_error = $_FILES['image']['error'];

    // Get current image if exists
    $getCurrentImage = $conn->query("SELECT image FROM props WHERE id = '$id'");
    $getCurrentImage->execute();
    $currentImage = $getCurrentImage->fetch(PDO::FETCH_OBJ);

    // If new image is uploaded
    if ($image_error === 0) {
        // Validate image size (max 5MB)
        if ($image_size > 5000000) {
            echo "<script>alert('Image size should be less than 5MB');</script>";
            echo "<script>window.location.href='" . ADMINURL . "/properties-admins/edit-properties.php?id=$id'</script>";
            exit;
        }

        // Generate unique image name
        $image_extension = pathinfo($image, PATHINFO_EXTENSION);
        $image_name = uniqid() . '.' . $image_extension;
        $image_path = '../../images/' . $image_name;

        // Upload new image
        if (move_uploaded_file($image_tmp, $image_path)) {
            // Delete old image if exists
            if ($currentImage && $currentImage->image) {
                $old_image_path = '../../images/' . $currentImage->image;
                if (file_exists($old_image_path)) {
                    unlink($old_image_path);
                }
            }
            $image = $image_name;
        } else {
            echo "<script>alert('Failed to upload image');</script>";
            echo "<script>window.location.href='" . ADMINURL . "/properties-admins/edit-properties.php?id=$id'</script>";
            exit;
        }
    } else {
        // Keep existing image
        $image = $currentImage->image;
    }

    // Update property in database
    $update = $conn->prepare("UPDATE props SET 
        name = :name,
        price = :price,
        type = :type,
        description = :description,
        location = :location,
        image = :image,
        status = :status
        WHERE id = :id");

    $update->execute([
        ':name' => $name,
        ':price' => $price,
        ':type' => $type,
        ':description' => $description,
        ':location' => $location,
        ':image' => $image,
        ':status' => $status,
        ':id' => $id
    ]);

    echo "<script>alert('Property updated successfully');</script>";
    echo "<script>window.location.href='" . ADMINURL . "/properties-admins/show-properties.php'</script>";
} else {
    echo "<script>window.location.href='" . ADMINURL . "/properties-admins/show-properties.php'</script>";
}
?> 