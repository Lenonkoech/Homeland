<?php require "../layout/header.php"; ?>
<?php require "../../config/config.php"; ?>

<?php
// session_start(); // Ensure session is started

if (!isset($_SESSION['adminname'])) {
    echo "<script>window.location.href='" . ADMINURL . "/admins/login-admins.php'</script>";
    exit; // Stop further execution
}

// Get search query
$search = isset($_GET['search']) ? $_GET['search'] : '';

// Build the query
$query = "SELECT * FROM props";
$params = [];

if (!empty($search)) {
    $query .= " WHERE name LIKE :search OR location LIKE :search OR type LIKE :search";
    $params[':search'] = "%$search%";
}

// Add ordering
$query .= " ORDER BY id DESC";

// Prepare and execute the statement
$stmt = $conn->prepare($query);
foreach ($params as $key => $value) {
    $stmt->bindValue($key, $value);
}
$stmt->execute();

// Fetch data as objects
$allProps = $stmt->fetchAll(PDO::FETCH_OBJ);

$counter = 1;
?>

<div class="container-fluid">
  <div class="row">
    <div class="col">
      <div class="card">
        <div class="card-body">
          <div class="d-flex justify-content-between align-items-center mb-4">
            <h5 class="card-title mb-0">Properties (<?php echo count($allProps); ?>)</h5>
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
                <a href="show-properties-new.php" class="btn btn-outline-secondary">
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
                  <th scope="col">Image</th>
                  <th scope="col">Property Name</th>
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
                      <td>
                        <?php if ($prop->image): ?>
                          <img src="<?php echo IMAGESURL; ?>/thumbnails/<?php echo htmlspecialchars($prop->image); ?>"
                               alt="<?php echo htmlspecialchars($prop->name); ?>"
                               style="width: 80px; height: 80px; object-fit: cover; border-radius: 4px; cursor: pointer;"
                               onclick='showPropertyDetails(<?php echo json_encode($prop); ?>)'>
                        <?php else: ?>
                          <div style="width: 80px; height: 80px; background-color: #e9ecef; border-radius: 4px; display: flex; align-items: center; justify-content: center; cursor: pointer;"
                               onclick='showPropertyDetails(<?php echo json_encode($prop); ?>)'>
                            <i class="fas fa-home fa-2x text-muted"></i>
                          </div>
                        <?php endif; ?>
                      </td>
                      <td>
                        <a href="#" onclick='showPropertyDetails(<?php echo json_encode($prop); ?>)'>
                          <?php echo htmlspecialchars($prop->name); ?>
                        </a>
                      </td>
                      <td>
                        <div style="font-weight: 600; color: #2c3e50; font-size: 1.1rem; white-space: nowrap;">
                          Ksh <?php echo number_format($prop->price); ?>
                        </div>
                      </td>
                      <td>
                        <span class="badge bg-info" style="font-size: 0.9rem; padding: 0.5em 0.75em; display: inline-block;">
                          <?php echo htmlspecialchars($prop->type); ?>
                        </span>
                      </td>
                      <td>
                        <div style="display: flex; align-items: center; color: #2c3e50; font-size: 1rem; white-space: nowrap;">
                          <i class="fas fa-map-marker-alt text-danger me-1"></i>
                          <?php echo htmlspecialchars($prop->location); ?>
                        </div>
                      </td>
                      <td>
                        <span class="badge bg-<?php 
                          echo $prop->status == 'available' ? 'success' : 
                               ($prop->status == 'sold' ? 'danger' : 'warning'); 
                        ?>" style="font-size: 0.9rem; padding: 0.5em 0.75em; display: inline-block;">
                          <?php echo htmlspecialchars($prop->status); ?>
                        </span>
                      </td>
                      <td>
                        <div class="btn-group" role="group" style="display: flex; gap: 5px;">
                          <button onclick='showPropertyDetails(<?php echo json_encode($prop); ?>)'
                                  class="btn btn-info btn-sm"
                                  title="View Details"
                                  style="padding: 0.25rem 0.5rem; font-size: 0.875rem;">
                            <i class="fas fa-eye"></i>
                          </button>
                          <a href="edit-properties.php?id=<?php echo $prop->id; ?>"
                             class="btn btn-primary btn-sm"
                             title="Edit Property"
                             style="padding: 0.25rem 0.5rem; font-size: 0.875rem;">
                            <i class="fas fa-edit"></i>
                          </a>
                          <a href="<?php echo ADMINURL; ?>/properties-admins/delete-properties.php?id=<?php echo $prop->id; ?>"
                             class="btn btn-danger btn-sm"
                             onclick="return confirm('Are you sure you want to delete this property?')"
                             title="Delete Property"
                             style="padding: 0.25rem 0.5rem; font-size: 0.875rem;">
                            <i class="fas fa-trash"></i>
                          </a>
                        </div>
                      </td>
                    </tr>
                  <?php endforeach; ?>
                <?php else: ?>
                  <tr>
                    <td colspan="8" class="text-center">No properties found</td>
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

<!-- Property Details Modal -->
<div class="modal fade" id="propertyDetailsModal" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header bg-light">
        <h5 class="modal-title">Property Details</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">×</span>
        </button>
      </div>
      <div class="modal-body">
        <!-- Content populated by JS -->
        <div class="row">
          <div class="col-md-6">
            <div id="propertyImage" class="mb-4"></div>
            <div class="property-features mt-4">
              <h6 class="mb-3">Property Features</h6>
              <div class="row">
                <div class="col-6 mb-3">
                  <div class="d-flex align-items-center">
                    <i class="fas fa-bed text-primary me-2"></i>
                    <span id="propertyBedrooms"></span>
                  </div>
                </div>
                <div class="col-6 mb-3">
                  <div class="d-flex align-items-center">
                    <i class="fas fa-bath text-info me-2"></i>
                    <span id="propertyBathrooms"></span>
                  </div>
                </div>
                <div class="col-6 mb-3">
                  <div class="d-flex align-items-center">
                    <i class="fas fa-ruler-combined text-success me-2"></i>
                    <span id="propertyArea"></span>
                  </div>
                </div>
                <div class="col-6 mb-3">
                  <div class="d-flex align-items-center">
                    <i class="fas fa-car text-warning me-2"></i>
                    <span id="propertyParking"></span>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="col-md-6">
            <h4 id="propertyName" class="mb-3"></h4>
            <div class="property-details">
              <div class="mb-3">
                <h6 class="text-muted mb-2">Price</h6>
                <h4 id="propertyPrice" class="text-primary mb-0"></h4>
              </div>
              <div class="mb-3">
                <h6 class="text-muted mb-2">Type</h6>
                <span id="propertyType" class="badge bg-info"></span>
              </div>
              <div class="mb-3">
                <h6 class="text-muted mb-2">Location</h6>
                <div class="d-flex align-items-center">
                  <i class="fas fa-map-marker-alt text-danger me-2"></i>
                  <span id="propertyLocation"></span>
                </div>
              </div>
              <div class="mb-3">
                <h6 class="text-muted mb-2">Status</h6>
                <span id="propertyStatus" class="badge"></span>
              </div>
              <div class="mb-3">
                <h6 class="text-muted mb-2">Listed Date</h6>
                <span id="propertyDate"></span>
              </div>
            </div>
          </div>
        </div>
        <div class="row mt-4">
          <div class="col-12">
            <h6 class="text-muted mb-2">Description</h6>
            <p id="propertyDescription" class="text-muted"></p>
          </div>
        </div>
        <div class="row mt-4">
          <div class="col-12">
            <h6 class="text-muted mb-2">Additional Features</h6>
            <div id="propertyFeatures" class="row"></div>
          </div>
        </div>
      </div>
      <div class="modal-footer bg-light">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <a href="#" id="editPropertyBtn" class="btn btn-primary">
          <i class="fas fa-edit"></i> Edit Property
        </a>
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
    padding: 1rem;
}

.table th {
    padding: 1rem;
    background-color: #f8f9fa;
    border-bottom: 2px solid #dee2e6;
}

.table {
    width: 100%;
    margin-bottom: 1rem;
    background-color: transparent;
    border-collapse: collapse;
}

.table td {
    border-bottom: 1px solid #dee2e6;
}

.table tbody tr:hover {
    background-color: rgba(0,0,0,.075);
}

.table a {
    color: #2c3e50;
    text-decoration: none;
    font-weight: 500;
    font-size: 1.1rem;
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

#propertyImage img {
    width: 100%;
    height: 300px;
    object-fit: cover;
    border-radius: 8px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.property-details p {
    margin-bottom: 0.75rem;
    font-size: 1rem;
}

.property-details strong {
    color: #2c3e50;
    min-width: 100px;
    display: inline-block;
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
    overflow-x: auto;
    -webkit-overflow-scrolling: touch;
}

.table thead th {
    position: sticky;
    top: 0;
    background: white;
    z-index: 1;
    box-shadow: 0 2px 2px rgba(0,0,0,0.1);
}

.badge {
    font-weight: 500;
    padding: 0.5em 0.75em;
}

.table td {
    white-space: nowrap;
    vertical-align: middle;
}

.modal-content {
    border-radius: 8px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
}

.modal-header {
    border-bottom: 1px solid #e9ecef;
    padding: 1rem 1.5rem;
}

.modal-footer {
    border-top: 1px solid #e9ecef;
    padding: 1rem 1.5rem;
}

.property-details h6 {
    font-size: 0.875rem;
    font-weight: 600;
}

.property-details .badge {
    font-size: 0.875rem;
    padding: 0.5em 0.75em;
}

.property-features i {
    font-size: 1.25rem;
    width: 24px;
}

.property-features span {
    font-size: 0.875rem;
    color: #2c3e50;
}

#propertyFeatures .feature-item {
    background: #f8f9fa;
    padding: 0.5rem 1rem;
    border-radius: 4px;
    margin: 0.25rem;
    display: inline-block;
    font-size: 0.875rem;
}
</style>

<script>
function showPropertyDetails(property) {
    console.log('Property object:', property);

    // Basic property details
    document.getElementById('propertyName').textContent = property.name || 'N/A';
    document.getElementById('propertyPrice').textContent = property.price ? 'Ksh ' + new Intl.NumberFormat().format(property.price) : 'N/A';
    document.getElementById('propertyType').textContent = property.type || 'N/A';
    document.getElementById('propertyLocation').textContent = property.location || 'N/A';
    document.getElementById('propertyDescription').textContent = property.description || 'No description available.';

    // Property features
    document.getElementById('propertyBedrooms').textContent = property.beds ? property.beds + (property.beds > 1 ? ' Bedrooms' : ' Bedroom') : 'N/A';
    document.getElementById('propertyBathrooms').textContent = property.baths ? property.baths + (property.baths > 1 ? ' Bathrooms' : ' Bathroom') : 'N/A';
    document.getElementById('propertyArea').textContent = property.sqft ? property.sqft + ' sq ft' : 'N/A';
    document.getElementById('propertyParking').textContent = property.parking ? property.parking + (property.parking > 1 ? ' Parking spots' : ' Parking spot') : 'N/A';

    // Date
    document.getElementById('propertyDate').textContent = property.created_at ? new Date(property.created_at).toLocaleDateString() : 'N/A';

    // Status with color
    const statusClass = property.status === 'available' ? 'success' :
                       (property.status === 'sold' ? 'danger' : 'warning');
    const statusElement = document.getElementById('propertyStatus');
    statusElement.className = `badge bg-${statusClass}`;
    statusElement.textContent = property.status || 'N/A';

    // Image
    const imageContainer = document.getElementById('propertyImage');
    if (property.image) {
        imageContainer.innerHTML = `<img src="${IMAGESURL}/thumbnails/${property.image}" alt="${property.name}" style="width: 100%; height: 300px; object-fit: cover; border-radius: 8px;">`;
    } else {
        imageContainer.innerHTML = `
            <div class="bg-secondary text-white d-flex align-items-center justify-content-center" style="height: 300px; border-radius: 8px;">
                <i class="fas fa-home fa-3x"></i>
            </div>`;
    }

    // Features
    const featuresContainer = document.getElementById('propertyFeatures');
    if (property.features) {
        const features = property.features.split(',').map(f => f.trim()).filter(f => f !== '');
        if (features.length > 0) {
            featuresContainer.innerHTML = features.map(f =>
                `<div class="feature-item">${f}</div>`
            ).join('');
        } else {
            featuresContainer.innerHTML = '<p class="text-muted">No additional features listed</p>';
        }
    } else {
        featuresContainer.innerHTML = '<p class="text-muted">No additional features listed</p>';
    }

    // Edit button
    document.getElementById('editPropertyBtn').href = `edit-properties.php?id=${property.id}`;

    // Show modal
    $('#propertyDetailsModal').modal('show');
}
</script>

<?php require "../layout/footer.php"; ?> 