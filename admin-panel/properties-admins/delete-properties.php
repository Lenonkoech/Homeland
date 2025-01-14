<?php require "../layout/header.php" ?>
<?php require "../../config/config.php" ?>
<?php
if (!isset($_SESSION['adminname'])) {
    echo "<script>window.location.href='" . ADMINURL . "/admins/login-admins.php'</script>";
}

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    //delete image(thumbnail)
    $query = $conn->query("SELECT * from props where id = '$id'");
    $query->execute();
    $fetch_image = $query->fetch(PDO::FETCH_OBJ);

    unlink("thumbnails/" . $fetch_image->image);

    //delete prop
    $delete = $conn->query("DELETE from props where id = '$id'");
    $delete->execute();

    //delete gallery images

    $images = $conn->query("SELECT * from props_gallery where prop_id = '$id'");
    $images->execute();
    $delete_images = $images->fetchAll(PDO::FETCH_OBJ);

    foreach ($delete_images as $delete_image) {
        unlink("images/" . $delete_image->image);
    }

    echo "<script>window.location.href='" . ADMINURL . "/properties-admins/show-properties.php'</script>";
} else {
    echo "<script>window.location.href='" . ADMINURL . "/admins/404.php'</script>";
}
?>