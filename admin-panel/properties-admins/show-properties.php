<?php require "../layout/header.php" ?>
<?php require "../../config/config.php" 
?>
<?php
if (!isset($_SESSION['adminname'])) {
    echo "<script>window.location.href='" . ADMINURL . "/admins/login-admins.php'</script>";
}

// Get search query
$search = isset($_GET['search']) ? $_GET['search'] : '';

// Pagination settings
$items_per_page = 15;
$current_page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($current_page - 1) * $items_per_page;

// Build the query
$query = "SELECT * FROM props";
$count_query = "SELECT COUNT(*) as total FROM props";
$params = [];

if (!empty($search)) {
    $query .= " WHERE name LIKE :search OR location LIKE :search OR type LIKE :search OR description LIKE :search";
    $count_query .= " WHERE name LIKE :search OR location LIKE :search OR type LIKE :search OR description LIKE :search";
    $params[':search'] = "%$search%";
}

// Add ordering
$query .= " ORDER BY id DESC LIMIT :limit OFFSET :offset";

// Get total count
$count_stmt = $conn->prepare($count_query);
foreach ($params as $key => $value) {
    $count_stmt->bindValue($key, $value);
}
$count_stmt->execute();
$total_items = $count_stmt->fetch(PDO::FETCH_OBJ)->total;
$total_pages = ceil($total_items / $items_per_page);

// Execute the main query
$stmt = $conn->prepare($query);
foreach ($params as $key => $value) {
    $stmt->bindValue($key, $value);
}
$stmt->bindValue(':limit', $items_per_page, PDO::PARAM_INT);
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
$stmt->execute();
$allProps = $stmt->fetchAll(PDO::FETCH_OBJ);
$counter = $offset + 1;
?>

<div class="container-fluid">
    <div class="row">
        <div class="col">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h5 class="card-title mb-0">Properties (<?php echo $total_items; ?>)</h5>
                        <a href="create-properties.php" class="btn btn-primary">
                            <i class="fas fa-plus"></i> Create Property
                        </a>
                    </div>

                    <!-- Search Form -->
                    <form action="" method="GET" class="mb-4">
                        <div class="input-group">
                            <input type="text" class="form-control" name="search" 
                                   placeholder="Search properties..." 
                                   value="<?php echo htmlspecialchars($search); ?>">
                            <button class="btn btn-outline-secondary" type="submit">
                                <i class="fas fa-search"></i> Search
                            </button>
                            <?php if (!empty($search)): ?>
                                <a href="show-properties.php" class="btn btn-outline-secondary">
                                    <i class="fas fa-times"></i> Clear
                                </a>
                            <?php endif; ?>
                        </div>
                    </form>

                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Name</th>
                                    <th scope="col">Price</th>
                                    <th scope="col">Type</th>
                                    <th scope="col">Location</th>
                                    <th scope="col">Status</th>
                                    <th scope="col">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (count($allProps) > 0): ?>
                                    <?php foreach ($allProps as $prop): ?>
                                        <tr>
                                            <th scope="row"><?php echo $counter++; ?></th>
                                            <td><?php echo $prop->name; ?></td>
                                            <td>
                                                <strong>Ksh <?php echo $prop->price; ?></strong>
                                            </td>
                                            <td>
                                                <span class="">
                                                    <?php echo $prop->type; ?>
                                                </span>
                                            </td>
                                            <td>
                                                <i class="fas fa-map-marker-alt text-danger"></i>
                                                <?php echo $prop->location; ?>
                                            </td>
                                            <td>
                                                <span class="badge bg-<?php 
                                                    echo $prop->status == 'available' ? 'success' : 
                                                        ($prop->status == 'sold' ? 'danger' : 'warning'); 
                                                ?>">
                                                    <?php echo ucfirst($prop->status); ?>
                                                </span>
                                            </td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <button type="button" class="btn btn-info btn-sm" data-toggle="modal" data-target="#viewModal<?php echo $prop->id; ?>">
                                                        <i class="fas fa-eye">View</i>
                                                    </button>
                                                    <a href="edit-properties.php?id=<?php echo $prop->id; ?>" class="btn btn-primary btn-sm">
                                                        <i class="fas fa-edit">Edit</i>
                                                    </a>
                                                    <a href="<?php echo ADMINURL; ?>/properties-admins/delete-properties.php?id=<?php echo $prop->id; ?>" 
                                                       class="btn btn-danger btn-sm"
                                                       onclick="return confirm('Are you sure you want to delete this property?')">
                                                        <i class="fas fa-trash">Delete</i>
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>

                                        <!-- View Modal for each property -->
                                        <div class="modal fade" id="viewModal<?php echo $prop->id; ?>" tabindex="-1" role="dialog" aria-labelledby="viewModalLabel<?php echo $prop->id; ?>" aria-hidden="true">
                                            <div class="modal-dialog modal-lg" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="viewModalLabel<?php echo $prop->id; ?>"><?php echo $prop->name; ?></h5>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="row">
                                                            <div class="col-md-6">
                                                                <?php if ($prop->image): ?>
                                                                    <img src="<?php echo IMAGESURL; ?>/thumbnails/<?php echo $prop->image; ?>"
                                                                         alt="<?php echo $prop->name; ?>"
                                                                         class="img-fluid rounded mb-3">
                                                                <?php endif; ?>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="property-details">
                                                                    <div class="detail-item">
                                                                        <strong>Price:</strong>
                                                                        <p>Ksh <?php echo $prop->price; ?></p>
                                                                    </div>
                                                                    <div class="detail-item">
                                                                        <strong>Type:</strong>
                                                                        <p><?php echo $prop->type; ?></p>
                                                                    </div>
                                                                    <div class="detail-item">
                                                                        <strong>Location:</strong>
                                                                        <p><?php echo $prop->location; ?></p>
                                                                    </div>
                                                                    <div class="detail-item">
                                                                        <strong>Status:</strong>
                                                                        <p>
                                                                            <span class="badge bg-<?php 
                                                                                echo $prop->status == 'available' ? 'success' : 
                                                                                    ($prop->status == 'sold' ? 'danger' : 'warning'); 
                                                                            ?>">
                                                                                <?php echo ucfirst($prop->status); ?>
                                                                            </span>
                                                                        </p>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row mt-4">
                                                            <div class="col-12">
                                                                <div class="detail-item">
                                                                    <strong>Description:</strong>
                                                                    <p><?php echo $prop->description; ?></p>
                                                                </div>
                                                                <div class="detail-item">
                                                                    <strong>Features:</strong>
                                                                    <div class="row mt-2">
                                                                        <div class="col-md-4">
                                                                            <div class="feature-item">
                                                                                <i class="fas fa-bed text-primary"></i>
                                                                                <span class="ml-2"><?php echo $prop->beds; ?> Beds</span>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-4">
                                                                            <div class="feature-item">
                                                                                <i class="fas fa-bath text-primary"></i>
                                                                                <span class="ml-2"><?php echo $prop->baths; ?> Baths</span>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-4">
                                                                            <div class="feature-item">
                                                                                <i class="fas fa-ruler-combined text-primary"></i>
                                                                                <span class="ml-2"><?php echo $prop->sqft; ?> sqft</span>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                        <a href="edit-properties.php?id=<?php echo $prop->id; ?>" class="btn btn-primary">
                                                            <i class="fas fa-edit"></i> Edit Property
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="9" class="text-center">No properties found</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <?php if ($total_pages > 1): ?>
                    <nav aria-label="Page navigation" class="mt-4">
                        <ul class="pagination justify-content-center">
                            <?php if ($current_page > 1): ?>
                                <li class="page-item">
                                    <a class="page-link" href="?page=<?php echo $current_page - 1; ?><?php echo !empty($search) ? '&search=' . urlencode($search) : ''; ?>" aria-label="Previous">
                                        <span aria-hidden="true">&laquo;</span>
                                    </a>
                                </li>
                            <?php endif; ?>

                            <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                                <li class="page-item <?php echo $i === $current_page ? 'active' : ''; ?>">
                                    <a class="page-link" href="?page=<?php echo $i; ?><?php echo !empty($search) ? '&search=' . urlencode($search) : ''; ?>">
                                        <?php echo $i; ?>
                                    </a>
                                </li>
                            <?php endfor; ?>

                            <?php if ($current_page < $total_pages): ?>
                                <li class="page-item">
                                    <a class="page-link" href="?page=<?php echo $current_page + 1; ?><?php echo !empty($search) ? '&search=' . urlencode($search) : ''; ?>" aria-label="Next">
                                        <span aria-hidden="true">&raquo;</span>
                                    </a>
                                </li>
                            <?php endif; ?>
                        </ul>
                    </nav>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.btn-group {
    display: flex;
    gap: 5px;
}

.btn-group .btn {
    padding: 0.25rem 0.5rem;
    font-size: 0.875rem;
}

.table td {
    vertical-align: middle;
}

.badge {
    font-size: 0.875rem;
    padding: 0.5em 0.75em;
}

#propertyImage img {
    width: 100%;
    max-height: 300px;
    object-fit: cover;
    border-radius: 8px;
}

.property-details p {
    margin-bottom: 0.5rem;
}

.modal-body {
    padding: 2rem;
}

.text-muted {
    color: #6c757d !important;
}

.input-group {
    max-width: 500px;
}

.table-responsive {
    max-height: 800px;
    overflow-y: auto;
}

.table thead th {
    position: sticky;
    top: 0;
    background: white;
    z-index: 1;
    box-shadow: 0 2px 2px rgba(0,0,0,0.1);
}

.description-preview {
    max-width: 200px;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}

.table img {
    border-radius: 4px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.table a {
    color: #2c3e50;
    text-decoration: none;
}

.table a:hover {
    color: #3498db;
    text-decoration: underline;
}

.btn-group .btn {
    transition: all 0.3s ease;
}

.btn-group .btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.feature-item {
    display: flex;
    align-items: center;
    padding: 10px;
    background: #f8f9fa;
    border-radius: 8px;
    margin-bottom: 10px;
}

.feature-item i {
    font-size: 1.2rem;
    margin-right: 10px;
}

.feature-item span {
    font-size: 1rem;
    color: #333;
}

/* Pagination Styles */
.pagination {
    margin-bottom: 0;
}

.pagination .page-link {
    color: #2c3e50;
    border: 1px solid #dee2e6;
    padding: 0.5rem 0.75rem;
    margin: 0 2px;
    border-radius: 4px;
}

.pagination .page-item.active .page-link {
    background-color: #2c3e50;
    border-color: #2c3e50;
    color: white;
}

.pagination .page-link:hover {
    background-color: #f8f9fa;
    color: #2c3e50;
}

.pagination .page-item.disabled .page-link {
    color: #6c757d;
    pointer-events: none;
    background-color: #fff;
    border-color: #dee2e6;
}
</style>

<script>
function openModal(id) {
    $('#viewModal' + id).modal('show');
}

$(document).ready(function() {
    // Initialize all modals
    $('.modal').modal({
        show: false
    });
});
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<?php require "../layout/footer.php" ?>