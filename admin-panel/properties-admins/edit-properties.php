<?php require "../layout/header.php" ?>
<?php require "../../config/config.php" ?>

<?php
if (!isset($_SESSION['adminname'])) {
    echo "<script>window.location.href='" . ADMINURL . "/admins/login-admins.php'</script>";
}

if (!isset($_GET['id'])) {
    echo "<script>window.location.href='" . ADMINURL . "/properties-admins/show-properties.php'</script>";
}

try {
    $id = $_GET['id'];
    
    // Get property details
    $getProp = $conn->prepare("SELECT * FROM props WHERE id = :id");
    $getProp->execute([':id' => $id]);
    $prop = $getProp->fetch(PDO::FETCH_OBJ);

    if (!$prop) {
        echo "<script>window.location.href='" . ADMINURL . "/properties-admins/show-properties.php'</script>";
    }

    // Fetch home types from categories table
    $getTypes = $conn->prepare("SELECT * FROM categories");
    $getTypes->execute();
    $homeTypes = $getTypes->fetchAll(PDO::FETCH_OBJ);

} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
    exit();
}
?>

<div class="container-fluid">
    <div class="row">
        <div class="col-md-8 mx-auto">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title mb-4">Edit Property</h5>
                    
                    <form id="updateForm" action="update-property.php" method="POST" enctype="multipart/form-data">
                        <input type="hidden" name="id" value="<?php echo $prop->id; ?>">
                        
                        <div class="form-group mb-3">
                            <label for="name">Property Name</label>
                            <input type="text" class="form-control" id="name" name="name" 
                                   value="<?php echo $prop->name; ?>" required>
                        </div>

                        <div class="form-group mb-3">
                            <label for="price">Price (Ksh)</label>
                            <input type="text" class="form-control" id="price" name="price" 
                                   value="<?php echo $prop->price; ?>" required>
                        </div>

                        <div class="form-group mb-3">
                            <label for="type">Property Type</label>
                            <select class="form-control" id="type" name="type" required>
                                <option value="rent" <?php echo $prop->type == 'rent' ? 'selected' : ''; ?>>For Rent</option>
                                <option value="sale" <?php echo $prop->type == 'sale' ? 'selected' : ''; ?>>For Sale</option>
                                <option value="lease" <?php echo $prop->type == 'lease' ? 'selected' : ''; ?>>For Lease</option>
                            </select>
                        </div>

                        <div class="form-group mb-3">
                            <label for="home_type">Home Type</label>
                            <select class="form-control" id="home_type" name="home_type" required>
                                <?php if($homeTypes && count($homeTypes) > 0): ?>
                                    <?php foreach($homeTypes as $homeType): ?>
                                        <option value="<?php echo $homeType->name; ?>" 
                                                <?php echo $prop->home_type == $homeType->name ? 'selected' : ''; ?>>
                                            <?php echo ucfirst($homeType->name); ?>
                                        </option>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <option value="">No home types found</option>
                                <?php endif; ?>
                            </select>
                        </div>

                        <div class="form-group mb-3">
                            <label for="description">Description</label>
                            <textarea class="form-control" id="description" name="description" 
                                      rows="4" required><?php echo $prop->description; ?></textarea>
                        </div>

                        <div class="form-group mb-3">
                            <label for="location">Location</label>
                            <input type="text" class="form-control" id="location" name="location" 
                                   value="<?php echo $prop->location; ?>" required>
                        </div>

                        <div class="form-group mb-3">
                            <label for="image">Property Image</label>
                            <input type="file" class="form-control" id="image" name="image">
                            <small class="form-text text-muted">Leave empty to keep current image</small>
                            <?php if ($prop->image): ?>
                                <div class="mt-2">
                                    <img src="<?php echo IMAGESURL; ?>/thumbnails/<?php echo $prop->image; ?>" 
                                         alt="Current property image" style="max-width: 200px;">
                                </div>
                            <?php endif; ?>
                        </div>

                        <div class="form-group mb-3">
                            <label for="status">Status</label>
                            <select class="form-control" id="status" name="status" required>
                                <option value="available" <?php echo $prop->status == 'available' ? 'selected' : ''; ?>>Available</option>
                                <option value="sold" <?php echo $prop->status == 'sold' ? 'selected' : ''; ?>>Sold</option>
                                <option value="pending" <?php echo $prop->status == 'pending' ? 'selected' : ''; ?>>Pending</option>
                            </select>
                        </div>

                        <button type="submit" class="btn btn-primary">Update Property</button>
                        <a href="<?php echo ADMINURL; ?>/properties-admins/show-properties.php" 
                           class="btn btn-secondary">Cancel</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.getElementById('updateForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        
        fetch('update-property.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Show success notification
                const notification = document.createElement('div');
                notification.className = 'notification success';
                notification.innerHTML = `
                    <div class="notification-content">
                        <i class="fas fa-check-circle"></i>
                        <span>${data.message}</span>
                    </div>
                `;
                document.body.appendChild(notification);
                
                // Remove notification after 3 seconds
                setTimeout(() => {
                    notification.remove();
                    window.location.href = 'show-properties.php';
                }, 3000);
            } else {
                // Show error notification
                const notification = document.createElement('div');
                notification.className = 'notification error';
                notification.innerHTML = `
                    <div class="notification-content">
                        <i class="fas fa-exclamation-circle"></i>
                        <span>${data.message}</span>
                    </div>
                `;
                document.body.appendChild(notification);
                
                // Remove notification after 3 seconds
                setTimeout(() => {
                    notification.remove();
                }, 3000);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            // Show error notification
            const notification = document.createElement('div');
            notification.className = 'notification error';
            notification.innerHTML = `
                <div class="notification-content">
                    <i class="fas fa-exclamation-circle"></i>
                    <span>An error occurred while updating the property. Please try again.</span>
                </div>
            `;
            document.body.appendChild(notification);
            
            // Remove notification after 3 seconds
            setTimeout(() => {
                notification.remove();
            }, 3000);
        });
    });
</script>

<style>
.notification {
    position: fixed;
    top: 20px;
    left: 50%;
    transform: translateX(-50%);
    z-index: 9999;
    min-width: 300px;
    padding: 15px 20px;
    border-radius: 8px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    animation: slideDown 0.5s ease-out;
    font-weight: 500;
}

.notification.success {
    background-color: #d4edda;
    border: 1px solid #c3e6cb;
    color: #155724;
}

.notification.error {
    background-color: #dc3545;
    border: 1px solid #dc3545;
    color: #ffffff;
}

.notification-content {
    display: flex;
    align-items: center;
    gap: 10px;
}

.notification-content i {
    font-size: 1.2rem;
}

.notification-content span {
    font-size: 0.95rem;
    line-height: 1.4;
}

@keyframes slideDown {
    from {
        transform: translate(-50%, -100%);
        opacity: 0;
    }
    to {
        transform: translate(-50%, 0);
        opacity: 1;
    }
}
</style>

<?php require "../layout/footer.php" ?> 