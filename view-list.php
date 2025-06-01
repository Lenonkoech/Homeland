<?php require "includes/header.php" ?>
<?php require "config/config.php" ?>
<?php
// Get current page number
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$perPage = 8; // Number of properties per page
$offset = ($page - 1) * $perPage;

// Get total number of properties
$totalQuery = $conn->query("SELECT COUNT(*) as total FROM props");
$totalQuery->execute();
$total = $totalQuery->fetch(PDO::FETCH_OBJ)->total;
$totalPages = ceil($total / $perPage);

// Get properties for current page
$select = $conn->query("SELECT * FROM props LIMIT $offset, $perPage");
$select->execute();
$props = $select->fetchAll(PDO::FETCH_OBJ);

?>
<div class="slide-one-item home-slider owl-carousel">
  <?php foreach ($props as $prop) : ?>
    <div class="site-blocks-cover overlay" style="background-image: url(<?php echo IMAGESURL; ?>/thumbnails/<?php echo urlencode($prop->image); ?>);" data-aos="fade"
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
                                            } ?> text-white px-3 mb-3 property-offer-type rounded">For <?php echo $prop->type; ?></span>
            <h1 class="mb-2"><?php echo $prop->name; ?></h1>
            <p class="mb-5"><strong class="h2 text-success font-weight-bold">$<?php echo $prop->price; ?></strong></p>
            <p><a href="property-details.php?id=<?php echo $prop->id ?>" class="btn btn-white btn-outline-white py-3 px-5 rounded-0 btn-2">See Details</a></p>
          </div>
        </div>
      </div>
    </div>
  <?php endforeach; ?>
</div>


<div class="site-section site-section-sm pb-0">
  <div class="container">
    <div class="row">
      <form class="form-search col-md-12" action="search.php" method="post" style="margin-top: -100px;">
        <div class="row  align-items-end">
          <div class="col-md-3">
            <label for="list-types">Listing Types</label>
            <div class="select-wrap">
              <span class="icon icon-arrow_drop_down"></span>
              <select name="list-types" id="list-types" class="form-control d-block rounded-0">
                <?php foreach ($categories as $category) : ?>
                  <option value="<?php echo $category->name; ?>"><?php echo $category->name; ?></option>
                  <?php endforeach; ?>n>
              </select>
            </div>
          </div>
          <div class="col-md-3">
            <label for="offer-types">Offer Type</label>
            <div class="select-wrap">
              <span class="icon icon-arrow_drop_down"></span>
              <select name="offer-types" id="offer-types" class="form-control d-block rounded-0">
                <option value="sale">For Sale</option>
                <option value="rent">For Rent</option>
                <option value="lease">For Lease</option>
              </select>
            </div>
          </div>
          <div class="col-md-3">
            <label for="select-city">Select City</label>
            <div class="select-wrap">
              <span class="icon icon-arrow_drop_down"></span>
              <select name="select-city" id="select-city" class="form-control d-block rounded-0">
                <option value="new york">New York</option>
                <option value="brooklyn">Brooklyn</option>
                <option value="london">London</option>
                <option value="japan">Japan</option>
                <option value="philippines">Philippines</option>
              </select>
            </div>
          </div>
          <div class="col-md-3">
            <input type="submit" name="submit" class="btn btn-success text-white btn-block rounded-0" value="Search">
          </div>
        </div>
      </form>
    </div>

    <div class="row">
      <div class="col-md-12">
        <div class="view-options bg-white py-3 px-3 d-md-flex align-items-center">
          <div class="mr-auto">
            <a href="index.php" class="icon-view view-module"><span class="icon-view_module"></span></a>
            <a href="view-list.php" class="icon-view view-list active"><span class="icon-view_list"></span></a>

          </div>
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

    <?php foreach ($props as $prop) : ?>
      <div class="row mb-4">
        <div class="col-md-12">
          <div class="property-entry horizontal d-lg-flex">

            <a href="<?php APPURL ?>property-details.php?id=<?php echo $prop->id; ?>" class="property-thumbnail h-100">
              <div class="offer-type-wrap">
                <span class="offer-type bg-<?php if ($prop->type == "rent") {
                                              echo "success";
                                            } else if ($prop->type == "sale") {
                                              echo "danger";
                                            } else {
                                              echo "info";
                                            } ?> "><?php echo $prop->type; ?></span>
              </div>
              <img src="images/<?php echo $prop->image; ?>" alt="Image" class="img-fluid">
            </a>

            <div class="p-4 property-body">
              <h2 class="property-title"><a href="<?php APPURL ?>property-details.php?id=<?php echo $prop->id; ?>"><?php echo $prop->name; ?></a></h2>
              <span class="property-location d-block mb-3"><span class="property-icon icon-room"></span><?php echo $prop->location ?></span>
              <strong class="property-price text-primary mb-3 d-block text-success">$<?php echo $prop->price ?></strong>
              <p><?php echo $prop->description ?></p>
              <ul class="property-specs-wrap mb-3 mb-lg-0">
                <li>
                  <span class="property-specs">Beds</span>
                  <span class="property-specs-number"><?php echo $prop->beds ?> <sup>+</sup></span>

                </li>
                <li>
                  <span class="property-specs">Baths</span>
                  <span class="property-specs-number"><?php echo $prop->baths ?></span>

                </li>
                <li>
                  <span class="property-specs">SQ FT</span>
                  <span class="property-specs-number"><?php echo $prop->sqft ?></span>

                </li>
              </ul>
            </div>

          </div>
        </div>
      </div>
    <?php endforeach ?>
    <?php if ($totalPages > 1): ?>
    <div class="row mt-5">
      <div class="col-md-12 text-center">
        <div class="site-pagination">
          <?php if ($page > 1): ?>
            <a href="?page=<?php echo $page-1; ?>">&laquo;</a>
          <?php endif; ?>
          
          <?php
          $startPage = max(1, $page - 2);
          $endPage = min($totalPages, $page + 2);
          
          if ($startPage > 1) {
            echo '<a href="?page=1">1</a>';
            if ($startPage > 2) {
              echo '<span>...</span>';
            }
          }
          
          for ($i = $startPage; $i <= $endPage; $i++) {
            echo '<a href="?page=' . $i . '"' . ($i == $page ? ' class="active"' : '') . '>' . $i . '</a>';
          }
          
          if ($endPage < $totalPages) {
            if ($endPage < $totalPages - 1) {
              echo '<span>...</span>';
            }
            echo '<a href="?page=' . $totalPages . '">' . $totalPages . '</a>';
          }
          ?>
          
          <?php if ($page < $totalPages): ?>
            <a href="?page=<?php echo $page+1; ?>">&raquo;</a>
          <?php endif; ?>
        </div>
      </div>
    </div>
    <?php endif; ?>

  </div>
</div>

<?php require "includes/footer.php" ?>