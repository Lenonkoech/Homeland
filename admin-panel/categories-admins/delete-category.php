<?php require "../layout/header.php" ?>
<?php require "../../config/config.php" ?>
<?php
if (!isset($_SESSION['adminname'])) {
    echo "<script>window.location.href='" . ADMINURL . "/admins/login-admins.php'</script>";
}

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    $delete = $conn->query("DELETE from categories where id = '$id'");
    $delete->execute();
    echo "<script>window.location.href='" . ADMINURL . "/categories-admins/show-categories.php'</script>";
} else {
    echo "<script>window.location.href='" . ADMINURL . "/admins/404.php'</script>";
}
?>