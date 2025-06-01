<?php require "../includes/header.php" ?>
<?php require "../config/config.php" ?>
<?php
if (isset($_POST['submit'])) {
    if (empty($_POST['name']) or empty($_POST['email']) or empty($_POST['phone'])) {
        echo "<script>alert ('Fill in all fields')</script>";
        //redirect back to page
        echo "<script>window.location.href='" . APPURL . "'</script>";
    } else {
        $name = $_POST['name'];
        $email = $_POST['email'];
        $phone = $_POST['phone'];
        $prop_id = $_POST['prop_id'];
        $user_id = $_POST['user_id'];
        $agent_name = $_POST['agent-name'];

        $insert = $conn->prepare("INSERT into requests(name,email,phone,prop_id,user_id,agent_name) values
                 (:name,:email,:phone,:prop_id,:user_id,:agent_name)");
        $insert->execute([
            ':name' => $name,
            ':email' => $email,
            ':phone' => $phone,
            ':prop_id' => $prop_id,
            ':user_id' => $user_id,
            ':agent_name' => $agent_name
        ]);
        echo "<script>alert ('Request submitted successfully')</script>";
        echo "<script>window.location.href='" . APPURL . "property-details.php?id=$prop_id'</script>";
    }
}
?>