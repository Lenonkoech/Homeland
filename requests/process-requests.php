<?php
session_start();
define("APPURL", 'http://localhost/homeland/');
require "../config/config.php";
require_once "../includes/functions.php";

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

try {
    if (isset($_POST['submit'])) {
        if (empty($_POST['name']) or empty($_POST['email']) or empty($_POST['phone'])) {
            header("Location: " . APPURL . "index.php");
            exit;
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

            // Get property name for notification
            $prop_query = $conn->prepare("SELECT name FROM props WHERE id = :prop_id");
            $prop_query->execute([':prop_id' => $prop_id]);
            $property = $prop_query->fetch(PDO::FETCH_OBJ);

            if ($property) {
                // Create notification
                $notification_title = "Property Request Sent";
                $notification_message = "You have sent a request for the property: " . $property->name;
                createNotification($user_id, $notification_title, $notification_message, 'info');

                header("Location: " . APPURL . "property-details.php?id=$prop_id");
                exit;
            } else {
                throw new Exception("Property not found");
            }
        }
    }
} catch (Exception $e) {
    error_log("Error in process-requests.php: " . $e->getMessage());
    header("Location: " . APPURL . "index.php");
    exit;
}
?>