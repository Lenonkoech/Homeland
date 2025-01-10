<?php require "../includes/header.php" ?>
<?php require "../config/config.php" ?>
<?php
if (!isset($_SESSION['username'])) {
    echo "<script>window.location.href='" . APPURL . "'</script>";
}
if (isset($_POST['submit'])) {
    $prop_id = $_POST['prop_id'];
    $user_id = $_POST['user_id'];

    $insert = $conn->prepare("INSERT into fav(prop_id,user_id) values
         (:prop_id,:user_id)");
    $insert->execute([
        ':prop_id' => $prop_id,
        ':user_id' => $user_id,
    ]);

    echo "<script>window.location.href='" . APPURL . "property-details.php?id=$prop_id'</script>";
}
?>