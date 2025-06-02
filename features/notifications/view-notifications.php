<?php
require '../../includes/header.php'; 
require_once '../../config/config.php';
require_once '../../includes/functions.php';

if (!isset($_SESSION['user_id'])) {
    echo "<script>window.location.href='" . APPURL . "'</script>";
}

// Function to get relative time
function getRelativeTime($timestamp) {
    $now = new DateTime('now', new DateTimeZone('Africa/Nairobi'));
    $date = new DateTime($timestamp, new DateTimeZone('Africa/Nairobi'));
    $diff = $now->diff($date);
    
    if ($diff->y > 0) {
        return $date->format('M d, Y H:i');
    } else if ($diff->m > 0) {
        return $date->format('M d, H:i');
    } else if ($diff->d > 0) {
        if ($diff->d == 1) {
            return 'Yesterday at ' . $date->format('H:i');
        }
        return $date->format('M d, H:i');
    } else if ($diff->h > 0) {
        return $diff->h . ' hour' . ($diff->h > 1 ? 's' : '') . ' ago';
    } else if ($diff->i > 0) {
        return $diff->i . ' minute' . ($diff->i > 1 ? 's' : '') . ' ago';
    } else if ($diff->s > 30) {
        return 'Less than a minute ago';
    } else {
        return 'Just now';
    }
}

// Get notifications for the user
$notifications = $conn->query("SELECT * FROM notifications WHERE user_id = $_SESSION[user_id] ORDER BY created_at DESC");
$notifications->execute();
$allNotifications = $notifications->fetchAll(PDO::FETCH_OBJ);
?>

<div class="site-blocks-cover inner-page-cover overlay" style="background-image: url(<?php echo APPURL; ?>images/hero_bg_2.jpg);" data-aos="fade" data-stellar-background-ratio="0.5">
    <div class="container">
        <div class="row align-items-center justify-content-center text-center">
            <div class="col-md-10">
                <h1 class="mb-2">Notifications</h1>
            </div>
        </div>
    </div>
</div>

<div class="site-section">
    <div class="container">
        <div class="row mb-4">
            <div class="col">
                <?php if (count($allNotifications) == 0): ?>
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i>
                        You have no notifications at this time.
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <div class="row">
            <div class="col-md-8 mx-auto">
                <?php foreach ($allNotifications as $notification): ?>
                    <div class="notification-card <?php echo $notification->is_read ? '' : 'unread'; ?>" 
                         id="notification-<?php echo $notification->id; ?>">
                            <div class="d-flex justify-content-between align-items-start">
                            <div class="flex-grow-1">
                                <h5 class="mb-2"><?php echo htmlspecialchars($notification->title); ?></h5>
                                <?php
                                // Split message into main message and admin note
                                $messageParts = explode("\n\nNote from admin:", $notification->message);
                                $mainMessage = $messageParts[0];
                                $adminNote = isset($messageParts[1]) ? trim($messageParts[1]) : '';
                                ?>
                                <div class="notification-content">
                                    <p class="mb-2"><?php echo nl2br(htmlspecialchars($mainMessage)); ?></p>
                                    <?php if ($adminNote): ?>
                                        <div class="admin-note">
                                            <strong>Note from admin:</strong><br><br>
                                            <?php echo nl2br(htmlspecialchars($adminNote)); ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                                    <small class="notification-time">
                                        <i class="far fa-clock me-1"></i>
                                    <?php echo getRelativeTime($notification->created_at); ?>
                                    </small>
                                </div>
                                <div class="ms-3">
                                    <?php if (!$notification->is_read): ?>
                                        <button onclick="markAsRead(<?php echo $notification->id; ?>)" 
                                                class="btn btn-sm btn-outline-primary me-2">
                                            <i class="fas fa-check me-1"></i> Mark as Read
                                        </button>
                                    <?php endif; ?>
                                    <button onclick="deleteNotification(<?php echo $notification->id; ?>)" 
                                            class="btn btn-sm btn-outline-danger">
                                        <i class="fas fa-trash me-1"></i> Delete
                                    </button>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>

<style>
    .notification-card {
        transition: transform 0.2s;
        margin-bottom: 1rem;
        border-left: 4px solid #0dcaf0;
        background: #fff;
        border-radius: 4px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        padding: 1.5rem;
    }
    .notification-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    }
    .notification-card.unread {
        background-color: #f8f9fa;
    }
    .notification-time {
        font-size: 0.85rem;
        color: #6c757d;
    }
    .notification-content {
        margin-bottom: 1rem;
    }
    .admin-note {
        background-color: #f8f9fa;
        padding: 1rem;
        border-radius: 4px;
        margin-top: 1rem;
        border-left: 3px solid #6c757d;
    }
    .btn-outline-primary {
        color: #2f89fc;
        border-color: #2f89fc;
    }
    .btn-outline-primary:hover {
        background-color: #2f89fc;
        border-color: #2f89fc;
    }
    .btn-outline-danger {
        color: #dc3545;
        border-color: #dc3545;
    }
    .btn-outline-danger:hover {
        background-color: #dc3545;
        border-color: #dc3545;
    }
    .site-blocks-cover {
        padding: 7em 0;
    }
    .site-blocks-cover h1 {
        font-size: 2.5rem;
        color: #fff;
        text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
    }
</style>

<script>
    function markAsRead(notificationId) {
        fetch('mark-read.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                notification_id: notificationId
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const notification = document.getElementById(`notification-${notificationId}`);
                notification.classList.remove('unread');
                const markReadBtn = notification.querySelector('.btn-outline-primary');
                if (markReadBtn) markReadBtn.remove();
                updateNotificationCount();
            } else {
                alert('Error marking notification as read: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while marking the notification as read');
        });
    }

    function deleteNotification(notificationId) {
        if (confirm('Are you sure you want to delete this notification?')) {
            fetch('delete-notification.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    notification_id: notificationId
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const notification = document.getElementById(`notification-${notificationId}`);
                    notification.remove();
                    updateNotificationCount();
                } else {
                    alert('Error deleting notification: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred while deleting the notification');
            });
        }
    }

    function updateNotificationCount() {
        fetch('get-unread-count.php')
        .then(response => response.json())
        .then(data => {
            const countElement = document.getElementById('notification-count');
            if (countElement) {
                countElement.textContent = data.count;
                if (data.count === 0) {
                    countElement.style.display = 'none';
                } else {
                    countElement.style.display = 'inline';
                }
            }
        });
    }

    // Update notification count when page loads
    document.addEventListener('DOMContentLoaded', function() {
        if (document.getElementById('notification-count')) {
            updateNotificationCount();
        }
    });
</script>

<?php include '../../includes/footer.php'; ?> 