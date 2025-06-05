<?php require "../layout/header.php" ?>
<?php require "../../config/config.php" ?>
<?php
if (!isset($_SESSION['adminname'])) {
    echo "<script>window.location.href='" . ADMINURL . "/admins/login-admins.php'</script>";
}

// Get all requests with property and user details
$query = $conn->query("SELECT r.*, p.name as property_name, p.price, p.type as property_type, 
                       u.username, u.email as user_email,
                       DATE_FORMAT(r.timestamp, '%Y-%m-%d %H:%i') as formatted_date
                       FROM requests r
                       INNER JOIN props p ON r.prop_id = p.id
                       INNER JOIN users u ON r.user_id = u.id
                       ORDER BY r.timestamp DESC");
$query->execute();
$requests = $query->fetchAll(PDO::FETCH_OBJ);

// Get request statistics
$stats = $conn->query("SELECT 
    COUNT(*) as total_requests,
    COUNT(CASE WHEN DATE(timestamp) = CURDATE() THEN 1 END) as today_requests,
    COUNT(CASE WHEN DATE(timestamp) = DATE_SUB(CURDATE(), INTERVAL 7 DAY) THEN 1 END) as week_requests
    FROM requests");
$stats->execute();
$request_stats = $stats->fetch(PDO::FETCH_OBJ);
?>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

<style>
.btn-group {
    display: flex;
    gap: 5px;
    flex-wrap: wrap;
}

.btn-group .btn {
    padding: 0.25rem 0.5rem;
    font-size: 0.875rem;
    line-height: 1.5;
    border-radius: 0.2rem;
    margin: 2px;
}

.btn-group .btn i {
    margin-right: 3px;
}

.btn-group .btn-secondary {
    background-color: #6c757d;
    border-color: #6c757d;
    color: white;
}

.btn-group .btn-secondary:hover {
    background-color: #5a6268;
    border-color: #545b62;
}

.table td {
    vertical-align: middle;
}

.text-muted {
    color: #6c757d !important;
}

#notificationContainer {
    position: fixed;
    top: 20px;
    left: 50%;
    transform: translateX(-50%);
    z-index: 9999;
    width: 100%;
    max-width: 400px;
    animation: slideDown 0.3s ease-out;
}

.alert {
    margin-bottom: 0;
    box-shadow: 0 4px 20px rgba(0,0,0,0.15);
    border-radius: 8px;
    text-align: center;
    padding: 15px 20px;
}

#sendingStatusModal {
    display: block !important; /* Force display */
    opacity: 1 !important; /* Force full opacity */
    visibility: visible !important; /* Force visibility */
    z-index: 40000 !important; /* Ensure it's on top of everything */
}

/* Ensure backdrop is also visible if needed, though this might cause issues if the modal is hidden */
/*
.modal-backdrop {
    opacity: 0.5 !important;
    z-index: 39999 !important; 
}
*/

/* Add new styles for the notification */
#sendingStatusNotification {
    position: fixed;
    top: 20px;
    left: 50%;
    transform: translateX(-50%);
    z-index: 9999;
    background-color: #e8f5e9;
    border: 1px solid #81c784;
    border-radius: 8px;
    box-shadow: 0 4px 20px rgba(0,0,0,0.15);
    padding: 20px 30px;
    min-width: 320px;
    max-width: 500px;
    transition: all 0.3s ease;
    animation: slideDown 0.3s ease-out;
}

@keyframes slideDown {
    from {
        opacity: 0;
        transform: translate(-50%, -20px);
    }
    to {
        opacity: 1;
        transform: translate(-50%, 0);
    }
}

.notification-content {
    display: flex;
    flex-direction: column;
    gap: 12px;
}

.notification-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 12px;
}

.notification-title {
    margin: 0;
    color: #2e7d32;
    font-size: 1.2rem;
    font-weight: 600;
}

.notification-body {
    background-color: white;
    border-radius: 6px;
    padding: 15px;
    box-shadow: inset 0 1px 3px rgba(0,0,0,0.05);
}

.progress {
    height: 10px;
    margin-bottom: 12px;
    background-color: #e8f5e9;
    border-radius: 5px;
    overflow: hidden;
    display: block !important;
    border: 1px solid #81c784;
    box-shadow: inset 0 1px 3px rgba(0,0,0,0.1);
}

.progress-bar {
    background: linear-gradient(45deg, #4caf50, #81c784);
    transition: width 0.3s ease;
    display: block !important;
    width: 0% !important;
    height: 100%;
    border-radius: 4px;
    position: relative;
    overflow: hidden;
}

.progress-bar::after {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: linear-gradient(
        45deg,
        rgba(255,255,255,0.2) 25%,
        transparent 25%,
        transparent 50%,
        rgba(255,255,255,0.2) 50%,
        rgba(255,255,255,0.2) 75%,
        transparent 75%,
        transparent
    );
    background-size: 30px 30px;
    animation: progress-bar-stripes 1s linear infinite;
}

@keyframes progress-bar-stripes {
    from {
        background-position: 0 0;
    }
    to {
        background-position: 30px 0;
    }
}

.status-text {
    color: #2e7d32;
    font-size: 0.95rem;
    margin: 0;
    font-weight: 500;
}

.progress-percentage {
    color: #2e7d32;
    font-weight: 600;
    font-size: 0.95rem;
    background-color: #e8f5e9;
    padding: 2px 8px;
    border-radius: 12px;
    border: 1px solid #81c784;
}

.close {
    color: #2e7d32;
    opacity: 0.7;
    transition: all 0.2s;
    padding: 4px;
    margin-left: 8px;
    background: none;
    border: none;
    cursor: pointer;
}

.close:hover {
    opacity: 1;
    transform: scale(1.1);
}
</style>

    <div class="container-fluid">
    <!-- Notification Container -->
    <div class="row mb-4">
        <div class="col-md-6 mx-auto">
            <div id="notificationContainer" style="display: none;">
                <div class="alert alert-success alert-dismissible fade show text-center" role="alert">
                    <span id="notificationMessage"></span>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <h5 class="card-title">Total Requests</h5>
                    <h2 class="card-text"><?php echo $request_stats->total_requests; ?></h2>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <h5 class="card-title">Today's Requests</h5>
                    <h2 class="card-text"><?php echo $request_stats->today_requests; ?></h2>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card bg-info text-white">
                <div class="card-body">
                    <h5 class="card-title">This Week's Requests</h5>
                    <h2 class="card-text"><?php echo $request_stats->week_requests; ?></h2>
                </div>
            </div>
        </div>
    </div>

    <!-- Requests Table -->
          <div class="row">
        <div class="col">
          <div class="card">
            <div class="card-body">
                    <h5 class="card-title mb-4 d-inline">Property Requests</h5>
            
                    <div id="requestsTableWrapper">
                        <div class="table-responsive">
                            <table class="table table-hover mt-3">
                <thead>
                  <tr>
                    <th scope="col">#</th>
                                        <th scope="col">Date</th>
                                        <th scope="col">User</th>
                                        <th scope="col">Property</th>
                                        <th scope="col">Contact Info</th>
                                        <th scope="col">Status</th>
                                        <th scope="col">Actions</th>
                  </tr>
                </thead>
                <tbody>
                  <?php if(count($requests) > 0): ?>
                    <?php 
                    $counter = 1;
                    foreach($requests as $request): 
                                            // Determine status class and text
                                            $statusClass = '';
                                            $statusText = ucfirst($request->status ?? 'pending');
                                            
                                            switch(strtolower($statusText)) {
                                                case 'approved':
                                                    $statusClass = 'success';
                                                    break;
                                                case 'rejected':
                                                    $statusClass = 'danger';
                                                    break;
                                                case 'pending':
                                                    $statusClass = 'warning';
                                                    break;
                                                default:
                                                    $statusClass = 'secondary';
                                                    $statusText = 'Not Read';
                                            }
                    ?>
                    <tr>
                        <th scope="row"><?php echo $counter++; ?></th>
                                            <td><?php echo $request->formatted_date; ?></td>
                                            <td>
                                                <strong><?php echo $request->username; ?></strong><br>
                                                <small class="text-muted"><?php echo $request->user_email; ?></small>
                                            </td>
                                            <td>
                                                <strong><?php echo $request->property_name; ?></strong><br>
                                                <small class="text-muted">
                                                    <?php echo ucfirst($request->property_type); ?> - 
                                                    Ksh <?php echo $request->price; ?>
                                                </small>
                                            </td>
                                            <td>
                                                <strong>Phone:</strong> <?php echo $request->phone; ?><br>
                                                <strong>Email:</strong> <?php echo $request->email; ?>
                                            </td>
                                            <td>
                                                <span class="badge bg-<?php echo $statusClass; ?>">
                                                    <?php echo $statusText; ?>
                                                </span>
                                            </td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <button type="button" class="btn btn-sm btn-success" 
                                                            onclick="updateRequestStatus(<?php echo $request->id; ?>)"
                                                            title="Update Status">
                                                        <i class="fas fa-edit"></i> Update Status
                                                    </button>
                                                </div>
                                            </td>
                    </tr>
                    <?php endforeach; ?>
                  <?php else: ?>
                    <tr>
                                            <td colspan="6" class="text-center">No requests found</td>
                    </tr>
                  <?php endif; ?>
                </tbody>
              </table> 
            </div>
          </div>
        </div>
      </div>
    </div>
    </div>
</div>

<!-- Request Details Modal -->
<div class="modal fade" id="requestDetailsModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Request Details</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="requestDetailsContent">
                <!-- Content will be loaded dynamically -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" onclick="showSendMessageModal()">
                    <i class="fas fa-envelope"></i> Send Message
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Send Message Modal -->
<div class="modal fade" id="sendMessageModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Send Message</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="sendMessageForm">
                    <input type="hidden" id="messageRequestId" name="requestId">
                    <div class="form-group">
                        <label for="subject">Subject</label>
                        <input type="text" class="form-control" id="subject" name="subject" required>
                    </div>
                    <div class="form-group">
                        <label for="message">Message</label>
                        <textarea class="form-control" id="message" name="message" rows="5" required></textarea>
                    </div>
                    <div class="progress mb-3" style="display: none;">
                        <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" style="width: 0%"></div>
                    </div>
                    <button type="submit" class="btn btn-primary" id="sendMessageBtn">
                        <i class="fas fa-paper-plane"></i> Send Message
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Update Status Modal -->
<div class="modal fade" id="updateStatusModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Update Request Status</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="updateStatusForm">
                    <input type="hidden" id="statusRequestId" name="requestId">
                    <div class="form-group">
                        <label for="status">Status</label>
                        <select class="form-control" id="status" name="status" required>
                            <option value="pending">Pending</option>
                            <option value="approved">Approved</option>
                            <option value="rejected">Rejected</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="statusNote">Note (Optional)</label>
                        <textarea class="form-control" id="statusNote" name="note" rows="3" 
                                placeholder="Add a note to explain the status update..."></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">Update Status</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Sending Status Modal -->
<div id="sendingStatusNotification" style="display: none;" aria-hidden="true">
    <div class="notification-content">
        <div class="notification-header">
            <h5 class="notification-title" id="sendingStatusTitle">Sending Email...</h5>
            <div>
                <button type="button" class="close minimize-notification" aria-label="Minimize">
                    <span aria-hidden="true">−</span>
                </button>
                <button type="button" class="close ml-2 close-notification" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        </div>
        <div class="notification-body" id="sendingStatusBody">
            <div class="progress">
                <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" style="width: 0%" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
            </div>
            <div class="d-flex justify-content-between align-items-center">
                <p class="status-text mb-0">Preparing...</p>
                <span class="progress-percentage">0%</span>
            </div>
        </div>
    </div>
</div>

<script>
function viewRequestDetails(requestId) {
    // Load request details via AJAX
    fetch('get-request-details.php?id=' + requestId)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                document.getElementById('requestDetailsContent').innerHTML = data.html;
                $('#requestDetailsModal').modal('show');
            }
        })
        .catch(error => console.error('Error:', error));
}

function updateRequestStatus(requestId) {
    document.getElementById('statusRequestId').value = requestId;
    $('#updateStatusModal').modal('show');
}

function showSendMessageModal() {
    const requestId = document.getElementById('requestDetailsContent').getAttribute('data-request-id');
    document.getElementById('messageRequestId').value = requestId;
    $('#requestDetailsModal').modal('hide');
    $('#sendMessageModal').modal('show');
}

document.getElementById('updateStatusForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    // Get form values
    const requestId = document.getElementById('statusRequestId').value;
    const status = document.getElementById('status').value;
    const note = document.getElementById('statusNote').value;
    
    // Get references to the status notification elements
    const statusNotification = document.getElementById('sendingStatusNotification');
    const progressBarInner = statusNotification.querySelector('.progress-bar');
    const statusText = statusNotification.querySelector('.status-text');
    const progressPercentage = statusNotification.querySelector('.progress-percentage');
    
    // Show status notification and reset progress
    statusText.textContent = 'Updating Status...';
    progressBarInner.style.width = '0%';
    progressBarInner.setAttribute('aria-valuenow', 0);
    progressPercentage.textContent = '0%';
    
    // Show the notification
    statusNotification.style.display = 'block';
    statusNotification.setAttribute('aria-hidden', 'false');
    
    // Disable submit button
    const submitButton = this.querySelector('button[type="submit"]');
    submitButton.disabled = true;
    
    // Create JSON data
    const jsonData = {
        request_id: requestId,
        status: status,
        note: note
    };
    
    // Close modal immediately after initiating fetch
    $('#updateStatusModal').modal('hide');

    // Simulate progress updates
    let progress = 0;
    const progressInterval = setInterval(() => {
        progress += 5;
        if (progress <= 90) {
            progressBarInner.style.width = progress + '%';
            progressBarInner.setAttribute('aria-valuenow', progress);
            progressPercentage.textContent = progress + '%';
            statusText.textContent = 'Updating Status...';
        }
    }, 100);

    fetch('update-request-status.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(jsonData)
    })
    .then(response => response.json())
    .then(data => {
        clearInterval(progressInterval);
        
        // Complete the progress bar
        progressBarInner.style.width = '100%';
        progressBarInner.setAttribute('aria-valuenow', 100);
        progressPercentage.textContent = '100%';
        statusText.textContent = 'Status Updated Successfully!';
        
        // Re-enable button
        submitButton.disabled = false;
        
        if (data.success) {
            // Show success notification
            const notificationContainer = document.getElementById('notificationContainer');
            const notificationMessage = document.getElementById('notificationMessage');
            notificationMessage.textContent = data.message;
            notificationContainer.style.display = 'block';
            
            // Hide progress notification after a delay
            setTimeout(() => {
                statusNotification.style.display = 'none';
                statusNotification.setAttribute('aria-hidden', 'true');
                
                // Reset the form
                this.reset();
                
                // Reload the page after a short delay
                setTimeout(() => {
                    window.location.reload();
                }, 1000);
            }, 1000);
        } else {
            // Show error message in status notification and as an alert
            progressBarInner.style.width = '0%';
            progressBarInner.setAttribute('aria-valuenow', 0);
            progressPercentage.textContent = '0%';
            statusText.textContent = 'Error: ' + data.message;
            alert('Error: ' + data.message);
            
            // Hide progress notification after a delay
            setTimeout(() => {
                statusNotification.style.display = 'none';
                statusNotification.setAttribute('aria-hidden', 'true');
            }, 2000);
        }
    })
    .catch(error => {
        clearInterval(progressInterval);
        console.error('Error:', error);
        
        // Show error message in status notification and as an alert
        progressBarInner.style.width = '0%';
        progressBarInner.setAttribute('aria-valuenow', 0);
        progressPercentage.textContent = '0%';
        statusText.textContent = 'An error occurred';
        alert('An error occurred while updating the status');
        
        // Re-enable button
        submitButton.disabled = false;
        
        // Hide progress notification after a delay
        setTimeout(() => {
            statusNotification.style.display = 'none';
            statusNotification.setAttribute('aria-hidden', 'true');
        }, 2000);
    });
});

document.getElementById('sendMessageForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    // Get form values
    const requestId = document.getElementById('messageRequestId').value;
    const subject = document.getElementById('subject').value;
    const message = document.getElementById('message').value;
    
    // Hide send message modal
    $('#sendMessageModal').modal('hide');

    // Get references to the new status modal elements
    const statusNotification = document.getElementById('sendingStatusNotification');
    const progressBarInner = statusNotification.querySelector('.progress-bar');
    const statusText = statusNotification.querySelector('.status-text');
    const progressPercentage = statusNotification.querySelector('.progress-percentage');
    const sendButton = document.getElementById('sendMessageBtn');
    
    // Show status modal and reset progress
    statusText.textContent = 'Preparing...';
    progressBarInner.style.width = '0%';
    progressBarInner.setAttribute('aria-valuenow', 0);
    progressPercentage.textContent = '0%';
    
    // Show the notification
    statusNotification.style.display = 'block';
    statusNotification.setAttribute('aria-hidden', 'false');

    // Disable send button
    sendButton.disabled = true;
    
    // Create form data
    const formData = new FormData();
    formData.append('requestId', requestId);
    formData.append('subject', subject);
    formData.append('message', message);
    
    // Send the request
    fetch('send-message.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        // Re-enable button
        sendButton.disabled = false;
        
        if (data.success) {
            // Update progress bar and status text to complete
            progressBarInner.style.width = '100%';
            progressBarInner.setAttribute('aria-valuenow', 100);
            progressPercentage.textContent = '100%';
            statusText.textContent = 'Message sent successfully!';
            
            // Show success notification in the main area
            const notificationContainer = document.getElementById('notificationContainer');
            const notificationMessage = document.getElementById('notificationMessage');
            notificationMessage.textContent = data.message;
            notificationContainer.style.display = 'block';
            
            // Close the status modal after a short delay
            setTimeout(() => {
                statusNotification.style.display = 'none';
                statusNotification.setAttribute('aria-hidden', 'true');
                // Reset send message form after successful send and status modal closes
                document.getElementById('sendMessageForm').reset();
            }, 2000);

        } else {
            // Show error message in status modal and as an alert
            progressBarInner.style.width = '0%';
            progressBarInner.setAttribute('aria-valuenow', 0);
            progressPercentage.textContent = '0%';
            statusText.textContent = 'Error: ' + data.message;
            alert('Error: ' + data.message);
            // Close status modal immediately on error
            statusNotification.style.display = 'none';
            statusNotification.setAttribute('aria-hidden', 'true');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        // Show error message in status modal and as an alert
        progressBarInner.style.width = '0%';
        progressBarInner.setAttribute('aria-valuenow', 0);
        progressPercentage.textContent = '0%';
        statusText.textContent = 'An error occurred';
        alert('An error occurred while sending the message');
        
        // Re-enable button
        sendButton.disabled = false;
        // Close status modal immediately on error
        statusNotification.style.display = 'none';
        statusNotification.setAttribute('aria-hidden', 'true');
    });
});

// Minimize functionality for the status modal
document.querySelector('#sendingStatusNotification .minimize-notification').addEventListener('click', function() {
    document.getElementById('sendingStatusBody').style.display = 
        document.getElementById('sendingStatusBody').style.display === 'none' ? 'block' : 'none';
});

document.querySelector('#sendingStatusNotification .close-notification').addEventListener('click', function() {
    document.getElementById('sendingStatusNotification').style.display = 'none';
    document.getElementById('sendingStatusNotification').setAttribute('aria-hidden', 'true');
});
</script>

<?php require "../layout/footer.php" ?>
