<?php
require "../../config/config.php";
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../../vendor/autoload.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

error_reporting(E_ALL);
ini_set('display_errors', 1);

if (!isset($_SESSION['adminname'])) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized access. Please log in.']);
    exit;
}

if (!isset($_POST['requestId']) || !isset($_POST['subject']) || !isset($_POST['message'])) {
    echo json_encode(['success' => false, 'message' => 'All fields are required']);
    exit;
}

$request_id = $_POST['requestId'];
$subject = $_POST['subject'];
$message = $_POST['message'];

try {
    $query = $conn->prepare("SELECT r.*, u.id as user_id, u.email as user_email, u.username, p.name as property_name 
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

    $conn->beginTransaction();

    $notification = $conn->prepare("INSERT INTO notifications (user_id, title, message, created_at) 
                                  VALUES (:user_id, :title, :message, NOW())");

    $notification_message = "You have received a message from an administrator regarding your request for property '{$request->property_name}': {$message}";

    $notification->execute([
        'user_id' => $request->user_id,
        'title' => $subject,
        'message' => $notification_message
    ]);

    // Email content
    $email_message = "
    <html>
    <head>
        <title>{$subject}</title>
        <style>
            body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
            .container { max-width: 600px; margin: 0 auto; padding: 20px; }
            .header { background: #f8f9fa; padding: 20px; border-radius: 5px; margin-bottom: 20px; }
            .content { padding: 20px; }
            .footer { text-align: center; margin-top: 20px; padding-top: 20px; border-top: 1px solid #eee; }
        </style>
    </head>
    <body>
        <div class='container'>
            <div class='header'>
                <h2>{$subject}</h2>
            </div>
            <div class='content'>
                <p>Dear {$request->username},</p>
                <p>{$message}</p>
                <p>This message is regarding your request for the property: <strong>{$request->property_name}</strong></p>
            </div>
            <div class='footer'>
                <p>Best regards,<br>QejaniConnect Administration</p>
            </div>
        </div>
    </body>
    </html>";

    // PHPMailer setup
    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com'; 
        $mail->SMTPAuth = true;
        $mail->Username = ADMINEMAIL; // Use admin email from config
        $mail->Password = 'your_app_password';    // Use App Password for the admin email
        $mail->SMTPSecure = 'tls';
        $mail->Port = 587;

        $mail->setFrom(ADMINEMAIL, 'QejaniConnect Admin'); // Use admin email from config
        $mail->addAddress($request->user_email, $request->username); // Send to user's email
        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body = $email_message;

        $mail->send();
    } catch (Exception $e) {
        throw new Exception("Mail error: " . $mail->ErrorInfo);
    }

    $conn->commit();
    echo json_encode(['success' => true, 'message' => 'Message sent successfully']);

} catch (Exception $e) {
    if ($conn->inTransaction()) {
        $conn->rollBack();
    }
    echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
}
?>
 