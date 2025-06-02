<?php require "../layout/header.php" ?>
<?php require "../../config/config.php" ?>

<?php
if (!isset($_SESSION['adminname'])) {
    echo "<script>window.location.href='" . ADMINURL . "/admins/login-admins.php'</script>";
}

if (!isset($_GET['id'])) {
    echo "<script>window.location.href='" . ADMINURL . "/properties-admins/show-properties.php'</script>";
}

$id = $_GET['id'];
$getProp = $conn->query("SELECT * FROM props WHERE id = '$id'");
$getProp->execute();
$prop = $getProp->fetch(PDO::FETCH_OBJ);

if (!$prop) {
    echo "<script>window.location.href='" . ADMINURL . "/properties-admins/show-properties.php'</script>";
}
?>

<div class="container-fluid">
    <div class="row">
        <div class="col-md-8 mx-auto">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title mb-4">Edit Property</h5>
                    
                    <form action="update-property.php" method="POST" enctype="multipart/form-data">
                        <input type="hidden" name="id" value="<?php echo $prop->id; ?>">
                        
                        <div class="form-group mb-3">
                            <label for="name">Property Name</label>
                            <input type="text" class="form-control" id="name" name="name" 
                                   value="<?php echo $prop->name; ?>" required>
                        </div>

                        <div class="form-group mb-3">
                            <label for="price">Price (Ksh)</label>
                            <input type="number" class="form-control" id="price" name="price" 
                                   value="<?php echo $prop->price; ?>" required>
                        </div>

                        <div class="form-group mb-3">
                            <label for="type">Property Type</label>
                            <select class="form-control" id="type" name="type" required>
                                <option value="house" <?php echo $prop->type == 'house' ? 'selected' : ''; ?>>House</option>
                                <option value="apartment" <?php echo $prop->type == 'apartment' ? 'selected' : ''; ?>>Apartment</option>
                                <option value="villa" <?php echo $prop->type == 'villa' ? 'selected' : ''; ?>>Villa</option>
                                <option value="land" <?php echo $prop->type == 'land' ? 'selected' : ''; ?>>Land</option>
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
                                    <img src="<?php echo APPURL; ?>/images/<?php echo $prop->image; ?>" 
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

<?php require "../layout/footer.php" ?> 