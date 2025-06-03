<?php require "includes/header.php" ?>
<?php require "config/config.php" ?>
<?php
if (isset($_GET["type"])) {
  $type = $_GET["type"];
  
  // Get current page number
  $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
  $offset = ($page - 1) * ITEMS_PER_PAGE;

  try {
    // Get total number of properties for this type
    $totalQuery = $conn->prepare("SELECT COUNT(*) as total FROM props WHERE type = :type");
    $totalQuery->execute([':type' => $type]);
    $total = $totalQuery->fetch(PDO::FETCH_OBJ)->total;
    $totalPages = ceil($total / ITEMS_PER_PAGE);

    // Get properties for current page
    $select = $conn->prepare("SELECT * FROM props WHERE type = :type ORDER BY id DESC LIMIT :limit OFFSET :offset");
    $select->bindValue(':type', $type, PDO::PARAM_STR);
    $select->bindValue(':limit', ITEMS_PER_PAGE, PDO::PARAM_INT);
    $select->bindValue(':offset', $offset, PDO::PARAM_INT);
    $select->execute();
    $props = $select->fetchAll(PDO::FETCH_OBJ);

    // Get categories for listing types
    $categories = $conn->query("SELECT * FROM categories");
    $categories->execute();
    $categories = $categories->fetchAll(PDO::FETCH_OBJ);

    // Get featured properties for slider
    $featuredQuery = $conn->query("SELECT * FROM props WHERE type='$type' ORDER BY id DESC LIMIT 5");
    $featuredQuery->execute();
    $featuredProps = $featuredQuery->fetchAll(PDO::FETCH_OBJ);
  } catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
  }
} else {
  echo "<script>window.location.href='" . APPURL . "404.php'</script>";
}
?>

<!-- Property Slider -->
<div class="slide-one-item home-slider owl-carousel">
  <?php if (count($props) > 0): ?>
    <?php foreach ($props as $prop) : ?>
      <div class="site-blocks-cover overlay" style="background-image: url(<?php echo IMAGESURL; ?>/thumbnails/<?php echo htmlspecialchars($prop->image); ?>);" data-aos="fade"
        data-stellar-background-ratio="0.5">
        <div class="container">
          <div class="row align-items-center justify-content-center text-center">
            <div class="col-md-10">
              <span class="d-inline-block bg-<?php if ($prop->type == "rent") {
                                                echo "success";
                                              } else if ($prop->type == "sale") {
                                                echo "danger";
                                              } else {
                                                echo "info";
                                              } ?> text-white px-3 mb-3 property-offer-type rounded">For <?php echo htmlspecialchars($prop->type); ?></span>
              <h1 class="mb-2"><?php echo htmlspecialchars($prop->name); ?></h1>
              <p class="mb-5"><strong class="h2 text-success font-weight-bold">Ksh <?php echo number_format($prop->price); ?></strong></p>
              <p><a href="property-details.php?id=<?php echo $prop->id ?>" class="btn btn-white btn-outline-white py-3 px-5 rounded-0 btn-2">See Details</a></p>
            </div>
          </div>
        </div>
      </div>
    <?php endforeach; ?>
  <?php else: ?>
    <div class="site-blocks-cover overlay" style="background-image: url(<?php echo IMAGESURL; ?>/hero_bg_2.jpg);" data-aos="fade" data-stellar-background-ratio="0.5">
      <div class="container">
        <div class="row align-items-center justify-content-center text-center">
          <div class="col-md-10">
            <span class="d-inline-block bg-danger text-white px-3 mb-3 property-offer-type rounded">For Sale</span>
            <h1 class="mb-2">No Properties Available</h1>
            <p class="mb-5">Please check back later for new properties for sale</p>
          </div>
        </div>
      </div>
    </div>
  <?php endif; ?>
</div>


<div class="site-section site-section-sm pb-0">
  <div class="container">
    <div class="row">
      <form class="form-search col-md-12" action="search.php" method="POST" style="margin-top: -100px;">
        <div class="row align-items-end">
          <div class="col-md-4">
            <label for="list-types">Listing Types</label>
            <div class="select-wrap">
              <span class="icon icon-arrow_drop_down"></span>
              <select name="types" id="list-types" class="form-control d-block rounded-0" required>
                <option value="">Select Type</option>
                <?php foreach($categories as $category): ?>
                  <option value="<?php echo $category->name; ?>"><?php echo $category->name; ?></option>
                <?php endforeach; ?>
              </select>
            </div>
          </div>
          <div class="col-md-4">
            <label for="offer-types">Offer Type</label>
            <div class="select-wrap">
              <span class="icon icon-arrow_drop_down"></span>
              <select name="offers" id="offers" class="form-control d-block rounded-0" required>
                <option value="">Select type</option>
                <option value="sale">For Sale</option>
                <option value="rent">For Rent</option>
                <option value="lease">For Lease</option>
              </select>
            </div>
          </div>
          <div class="col-md-4">
            <label for="select-city">Location (Optional)</label>
            <div class="select-wrap">
              <span class="icon icon-arrow_drop_down"></span>
              <select name="cities" id="select-city" class="form-control d-block rounded-0">
                <option value="">Any Location</option>
                <option value="nairobi">Nairobi</option>
                <option value="mombasa">Mombasa</option>
                <option value="kisumu">Kisumu</option>
                <option value="nakuru">Nakuru</option>
                <option value="eldoret">Eldoret</option>
                <option value="thika">Thika</option>
                <option value="malindi">Malindi</option>
                <option value="kakamega">Kakamega</option>
                <option value="nyeri">Nyeri</option>
                <option value="meru">Meru</option>
              </select>
            </div>
          </div>
        </div>
        <div class="row mt-3">
          <div class="col-md-12 text-center">
            <input type="submit" name="submit" class="btn btn-success text-white btn-lg rounded-0" value="Search Properties">
          </div>
        </div>
      </form>
    </div>

    <div class="row">
      <div class="col-md-12">
        <div class="view-options bg-white py-3 px-3 d-md-flex align-items-center">
          <div class="ml-auto d-flex align-items-center">
            <div>
              <a href="<?php APPURL; ?>index.php" class="view-list px-3 border-right active">All</a>
              <a href="<?php APPURL; ?>rent.php?type=rent" class="view-list px-3 border-right">Rent</a>
              <a href="<?php APPURL; ?>sale.php?type=sale" class="view-list px-3 border-right">Sale</a>
              <a href="<?php APPURL; ?>lease.php?type=lease" class="view-list px-3 border-right">Lease</a>
              <a href="<?php APPURL; ?>price.php?price=ASC" class="view-list px-3 border-right">Price Ascending</a>
              <a href="<?php APPURL; ?>price.php?price=DESC" class="view-list px-3">Price Descending</a>
            </div>
          </div>
        </div>
      </div>
    </div>

  </div>
</div>

<div class="site-section site-section-sm bg-light">
  <div class="container">

    <div class="row mb-5">
      <?php foreach ($props as $prop) : ?>
        <div class="col-md-6 col-lg-4 mb-4">
          <div class="property-entry h-100">
            <a href="property-details.php?id=<?php echo $prop->id ?>" class="property-thumbnail">
              <div class="offer-type-wrap">
                <span class="offer-type bg-<?php if ($prop->type == "rent") {
                                              echo "success";
                                            } else if ($prop->type == "sale") {
                                              echo "danger";
                                            } else {
                                              echo "info";
                                            } ?>"><?php echo $prop->type; ?></span>
              </div>
              <img src="<?php echo IMAGESURL; ?>/thumbnails/<?php echo urlencode($prop->image); ?>" alt="Image" class="img-fluid">
            </a>
            <div class="p-4 property-body">
              <h2 class="property-title"><a href="property-details.php?id=<?php echo $prop->id ?>"><?php echo $prop->name; ?></a></h2>
              <span class="property-location d-block mb-3"><span class="property-icon icon-room"></span>
                <?php echo $prop->location; ?></span>
              <strong class="property-price text-primary mb-3 d-block text-success">Ksh <?php echo $prop->price; ?></strong>
              <ul class="property-specs-wrap mb-3 mb-lg-0">
                <li>
                  <span class="property-specs">Beds</span>
                  <span class="property-specs-number"><?php echo $prop->beds; ?> <sup>+</sup></span>

                </li>
                <li>
                  <span class="property-specs">Baths</span>
                  <span class="property-specs-number"><?php echo $prop->baths ?></span>

                </li>
                <li>
                  <span class="property-specs">SQ FT</span>
                  <span class="property-specs-number"><?php echo $prop->sqft; ?></span>

                </li>
              </ul>

            </div>
          </div>
        </div>
      <?php endforeach; ?>
    </div>

    <?php if ($totalPages > 1): ?>
    <div class="row mt-5">
      <div class="col-md-12 text-center">
        <div class="site-pagination">
          <?php if ($page > 1): ?>
            <a href="?type=<?php echo $type; ?>&page=<?php echo $page-1; ?>">&laquo;</a>
          <?php endif; ?>
          
          <?php
          $startPage = max(1, $page - 2);
          $endPage = min($totalPages, $page + 2);
          
          if ($startPage > 1) {
            echo '<a href="?type=' . $type . '&page=1">1</a>';
            if ($startPage > 2) {
              echo '<span>...</span>';
            }
          }
          
          for ($i = $startPage; $i <= $endPage; $i++) {
            echo '<a href="?type=' . $type . '&page=' . $i . '"' . ($i == $page ? ' class="active"' : '') . '>' . $i . '</a>';
          }
          
          if ($endPage < $totalPages) {
            if ($endPage < $totalPages - 1) {
              echo '<span>...</span>';
            }
            echo '<a href="?type=' . $type . '&page=' . $totalPages . '">' . $totalPages . '</a>';
          }
          ?>
          
          <?php if ($page < $totalPages): ?>
            <a href="?type=<?php echo $type; ?>&page=<?php echo $page+1; ?>">&raquo;</a>
          <?php endif; ?>
        </div>
      </div>
    </div>
    <?php endif; ?>

  </div>
</div>
<?php require "includes/footer.php" ?>