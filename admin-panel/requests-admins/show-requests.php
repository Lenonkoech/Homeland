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
                        <textarea class="form-control" id="statusNote" name="note" rows="3"></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">Update Status</button>
                </form>
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

document.getElementById('updateStatusForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    // Get form values
    const requestId = document.getElementById('statusRequestId').value;
    const status = document.getElementById('status').value;
    const note = document.getElementById('statusNote').value;
    
    // Create JSON data
    const jsonData = {
        request_id: requestId,
        status: status,
        note: note
    };
    
    // Close modal immediately after initiating fetch
    $('#updateStatusModal').modal('hide');

    // Send the request
    fetch('update-request-status.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(jsonData)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Show success notification
            const notificationContainer = document.getElementById('notificationContainer');
            const notificationMessage = document.getElementById('notificationMessage');
            notificationMessage.textContent = data.message;
            notificationContainer.style.display = 'block';
            
            // Reload the page after a short delay
            setTimeout(() => {
                window.location.reload();
            }, 2000);
        } else {
            alert('Error: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred while updating the status');
    });
});
</script>

<?php require "../layout/footer.php" ?>
