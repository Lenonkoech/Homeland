<?php require "includes/header.php" ?>
<?php require "config/config.php" ?>
<?php
$select = $conn->query("SELECT * from props");
$select->execute();
$props = $select->fetchAll(PDO::FETCH_OBJ);
if (isset($_GET["id"])) {
  $id = $_GET["id"];
  $single = $conn->query("SELECT * from props where id = '$id'");
  $single->execute();
  $allDetails = $single->fetch(PDO::FETCH_OBJ);
  $heroImage = $single->fetchAll(PDO::FETCH_OBJ);


  // fetch propety gallery
  $queryGallery = $conn->query("SELECT * from props_gallery where prop_id = '$id'");
  $queryGallery->execute();
  $gallery = $queryGallery->fetchAll(PDO::FETCH_OBJ);


  // fetch related properties
  $relatedProps = $conn->query("SELECT * from props where home_type='$allDetails->home_type' and id != '$id'");
  $relatedProps->execute();
  $RelatedProp = $relatedProps->fetchAll(PDO::FETCH_OBJ);
} else {
  echo "<script>window.location.href='" . APPURL . "404.php'</script>";
}
if (isset($_SESSION['user_id'])) {
  //check if prop is added to favorites by user
  $user_id = $_SESSION['user_id'];
  $check = $conn->query("SELECT * FROM fav WHERE prop_id = '$id' AND user_id = '$user_id'");
  $check->execute();
  $fetch_check = $check->fetch(PDO::FETCH_OBJ);
}

if (isset($_SESSION['user_id'])) {
  //check if user has sent property request
  $check_request = $conn->query("SELECT * from requests where prop_id = '$id' AND user_id = ' $_SESSION[user_id]'");
  $check_request->execute();
}
?>
<div class="slide-one-item home-slider owl-carousel">
  <div class="site-blocks-cover overlay" style="background-image: url(<?php echo USERIMAGESURL; ?>/thumbnails/<?php echo urlencode($allDetails->image); ?>);" data-aos="fade"
    data-stellar-background-ratio="0.5">
    <div class="container">
      <div class="row align-items-center justify-content-center text-center">
        <div class="col-md-10">
          <span class="d-inline-block bg-<?php if ($allDetails->rent == "rent") {
                                            echo "success";
                                          } else if ($allDetails->type == "sale") {
                                            echo "danger";
                                          } else {
                                            echo "info";
                                          } ?> text-white px-3 mb-3 property-offer-type rounded">For <?php echo $allDetails->type; ?></span>
          <h1 class="mb-2"><?php echo $allDetails->name; ?></h1>
          <p class="mb-5"><strong class="h2 text-success font-weight-bold">Ksh <?php echo $allDetails->price; ?></strong></p>
          <p><a href="#details" class="btn btn-white btn-outline-white py-3 px-5 rounded-0 btn-2">See Details</a></p>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="site-section site-section-sm">
  <div class="container">
    <div class="row">
      <div class="col-lg-8">
        <div>

          <div class="slide-one-item home-slider owl-carousel" id="details">
            <?php foreach ($gallery as $image): ?>
              <div><img src="<?php echo USERIMAGESURL; ?>/images/<?php echo urlencode($image->image); ?>" alt="Image" class="img-fluid"></div>
            <?php endforeach; ?>
          </div>
        </div>
        <div class="bg-white property-body border-bottom border-left border-right">
          <div class="row mb-5">
            <div class="col-md-6">
              <strong class="text-success h1 mb-3">Ksh <?php echo $allDetails->price; ?></strong>
            </div>
            <div class="col-md-6">
              <ul class="property-specs-wrap mb-3 mb-lg-0  float-lg-right">
                <li>
                  <span class="property-specs">Beds</span>
                  <span class="property-specs-number"><?php echo $allDetails->beds ?> <sup>+</sup></span>

                </li>
                <li>
                  <span class="property-specs">Baths</span>
                  <span class="property-specs-number"><?php echo $allDetails->baths ?></span>

                </li>
                <li>
                  <span class="property-specs">SQ FT</span>
                  <span class="property-specs-number"><?php echo $allDetails->sqft ?></span>

                </li>
              </ul>
            </div>
          </div>
          <div class="row mb-5">
            <div class="col-md-6 col-lg-4 text-center border-bottom border-top py-3">
              <span class="d-inline-block text-black mb-0 caption-text">Home Type</span>
              <strong class="d-block"><?php echo $allDetails->home_type ?></strong>
            </div>
            <div class="col-md-6 col-lg-4 text-center border-bottom border-top py-3">
              <span class="d-inline-block text-black mb-0 caption-text">Year Built</span>
              <strong class="d-block"><?php echo $allDetails->year_built ?></strong>
            </div>
            <div class="col-md-6 col-lg-4 text-center border-bottom border-top py-3">
              <span class="d-inline-block text-black mb-0 caption-text">Price/Sqft</span>
              <strong class="d-block"><?php echo $allDetails->price_sqft ?></strong>
            </div>
          </div>
          <h2 class="h4 text-black">More Info</h2>
          <?php echo $allDetails->description ?>

          <div class="row no-gutters mt-5">
            <div class="col-12">
              <h2 class="h4 text-black mb-3">Gallery</h2>
            </div>
            <?php foreach ($gallery as $image): ?>
              <div class="col-sm-6 col-md-4 col-lg-3">
                <a href="<?php echo USERIMAGESURL; ?>/images/<?php echo urlencode($image->image); ?>" class="image-popup gal-item"><img src="<?php echo USERIMAGESURL; ?>/images/<?php echo urlencode($image->image); ?>" alt="Image"
                    class="img-fluid"></a>
              </div>
            <?php endforeach; ?>
          </div>
        </div>
      </div>
      <div class="col-lg-4">
        <div class="bg-white widget border rounded">
          <h3 class="h4 text-black widget-title mb-3">Contact Agent</h3>
          <?php if (isset($_SESSION['username'])) : ?>
            <?php if ($check_request->rowCount() > 0) : ?>
              <p>Request already sent. <br>Kindly wait for a reply from agent.</p>
            <?php else : ?>
              <form action="requests/process-requests.php" method="post" class="form-contact-agent">
                <div class="form-group">
                  <label for="name">Name</label>
                  <input type="text" name="name" id="name" class="form-control">
                </div>
                <div class="form-group">
                  <label for="email">Email</label>
                  <input type="email" name="email" id="email" class="form-control">
                </div>
                <div class="form-group">
                  <label for="email">Phone</label>
                  <input type="text" id="phone" name="phone" class="form-control">
                </div>
                <div class="form-group">
                  <input type="hidden" id="phone" name="prop_id" value="<?php echo $id ?>" class="form-control">
                </div>
                <div class="form-group">
                  <input type="hidden" id="phone" name="user_id" value="<?php echo $_SESSION['user_id']; ?>" class="form-control">
                </div>
                <div class="form-group">
                  <l>
                    <input type="hidden" id="phone" name="agent-name" value="<?php echo $allDetails->admin_name ?>" class="form-control">
                </div>
                <div class="form-group">
                  <input type="submit" name="submit" id="phone" class="btn btn-primary" value="Send Request">
                </div>
              </form>
            <?php endif; ?>
          <?php else : ?>
            Login to contact agent
        </div>
      </div>
    <?php endif; ?>
    <div class="bg-white widget border rounded">
      <h3 class="h4 text-black widget-title mb-3 ml-0">Share</h3>
      <div class="px-3" style="margin-left: -15px;">
        <a href="https://www.facebook.com/sharer/sharer.php?u=&quote=" class="pt-3 pb-3 pr-3 pl-0"><span
            class="icon-facebook"></span></a>
        <a href="https://twitter.com/intent/tweet?text=&url=" class="pt-3 pb-3 pr-3 pl-0"><span
            class="icon-twitter"></span></a>
        <a href="https://www.linkedin.com/sharing/share-offsite/?url=" class="pt-3 pb-3 pr-3 pl-0"><span
            class="icon-linkedin"></span></a>
      </div>
    </div>
    <!-- Only display section to logged in users -->
    <?php if (isset($_SESSION['username'])) : ?>
      <div class="bg-white widget border rounded">
        <h3 class="h4 text-black widget-title mb-3 ml-0">Add to Favorites</h3>
        <div class="px-3" style="margin-left: -15px;">
          <form action="favs/add-fav.php" method="POST" class="form-contact-agent">
            <div class="form-group">
              <!-- <label for="name">prop_id</label> -->
              <input type="hidden" id="name" name="prop_id" value="<?php echo $id; ?>" class="form-control">
            </div>
            <div class="form-group">
              <!-- <label for="email">user_id</label> -->
              <input type="hidden" name="user_id" id="user_id" value="<?php echo $_SESSION['user_id']; ?>" class="form-control">
            </div>
            <?php if ($check->rowCount() > 0) : ?>
              <div class="form-group">
                <a href="favs/delete-fav.php?prop_id=<?php echo $id ?>&user_id=<?php echo $_SESSION['user_id'] ?>" class="btn btn-success">Added to Favorites</a>
              </div>
            <?php else: ?>
              <div class="form-group">
                <input type="submit" class="btn btn-success" name="submit" value="Add to Favorites">
              </div>
            <?php endif ?>
          </form>
        </div>
      </div>
    <?php endif; ?>
    </div>

    <!-- Property Location Map -->
    <div class="bg-white widget border rounded">
      <h3 class="h4 text-black widget-title mb-3 ml-0">Property Location</h3>
      <div class="property-location">
        <p class="mb-3">
          <i class="icon-room mr-2"></i>
          <?php echo htmlspecialchars($allDetails->location); ?>
        </p>
        <?php
        // Format the location for maps
        $location = $allDetails->location . ', Kenya';
        $encodedLocation = urlencode($location);
        $mapUrl = "https://www.google.com/maps/search/?api=1&query=" . $encodedLocation;
        ?>
        <div class="location-preview">
          <a href="<?php echo $mapUrl; ?>" target="_blank" class="location-link">
            <div class="location-image">
              <div class="map-pattern"></div>
              <div class="location-content">
                <i class="icon-map-marker"></i>
                <span>View on Google Maps</span>
              </div>
            </div>
          </a>
        </div>
      </div>
    </div>

    <style>
      .property-location {
        padding: 15px;
      }
      
      .property-location p {
        font-size: 1.1rem;
        color: #666;
      }
      
      .location-preview {
        width: 100%;
        height: 200px;
        background: #2c3e50;
        border-radius: 4px;
        overflow: hidden;
        box-shadow: 0 4px 6px rgba(0,0,0,0.1);
      }
      
      .location-link {
        display: block;
        width: 100%;
        height: 100%;
        text-decoration: none;
        color: inherit;
      }
      
      .location-image {
        width: 100%;
        height: 100%;
        position: relative;
        overflow: hidden;
      }

      .map-pattern {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: #34495e;
        background-image: 
          linear-gradient(rgba(255, 255, 255, 0.1) 1px, transparent 1px),
          linear-gradient(90deg, rgba(255, 255, 255, 0.1) 1px, transparent 1px);
        background-size: 20px 20px;
      }

      .map-pattern::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: 
          radial-gradient(circle at 50% 50%, 
            rgba(52, 152, 219, 0.2) 0%,
            transparent 50%),
          linear-gradient(45deg,
            rgba(52, 152, 219, 0.1) 25%,
            transparent 25%,
            transparent 50%,
            rgba(52, 152, 219, 0.1) 50%,
            rgba(52, 152, 219, 0.1) 75%,
            transparent 75%,
            transparent);
        background-size: 100% 100%, 10px 10px;
      }
      
      .location-content {
        position: relative;
        z-index: 1;
        width: 100%;
        height: 100%;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        background: rgba(44, 62, 80, 0.7);
        transition: all 0.3s ease;
      }
      
      .location-image:hover .location-content {
        background: rgba(44, 62, 80, 0.8);
      }
      
      .location-content i {
        font-size: 3.5rem;
        color: #3498db;
        margin-bottom: 1rem;
        text-shadow: 0 2px 4px rgba(0,0,0,0.2);
        animation: pulse 2s infinite;
      }

      @keyframes pulse {
        0% {
          transform: scale(1);
        }
        50% {
          transform: scale(1.1);
        }
        100% {
          transform: scale(1);
        }
      }
      
      .location-content span {
        font-size: 1.2rem;
        color: #fff;
        text-align: center;
        padding: 0.5rem 1.5rem;
        font-weight: 500;
        text-shadow: 0 2px 4px rgba(0,0,0,0.3);
        letter-spacing: 0.5px;
      }
    </style>
    </div>

  </div>
</div>
</div>

<div class="site-section site-section-sm bg-light">
  <div class="container">

    <div class="row">
      <div class="col-12">
        <div class="site-section-title mb-5">
          <h2>Related Properties</h2>
        </div>
      </div>
    </div>

    <div class="row mb-5">
      <?php if (count($RelatedProp) > 0): ?>
        <?php foreach ($RelatedProp as $relatedproperty): ?>
          <div class="col-md-6 col-lg-4 mb-4">
            <div class="property-entry h-100">
              <a href="property-details.php?id=<?php echo $relatedproperty->id; ?>" class="property-thumbnail">
                <div class="offer-type-wrap">
                  <span class="offer-type bg-<?php if ($relatedproperty->type == "rent") {
                                                echo "success";
                                              } else if ($relatedproperty->type == "sale") {
                                                echo "danger";
                                              } else {
                                                echo "info";
                                              } ?>"><?php echo $relatedproperty->type; ?></span>
                </div>
                <img src="<?php echo USERIMAGESURL; ?>/thumbnails/<?php echo urlencode($relatedproperty->image); ?>" alt="Image" class="img-fluid">
              </a>
              <div class="p-4 property-body">
                <a href="#" class="property-favorite"><span class="icon-heart-o"></span></a>
                <h2 class="property-title"><a href="property-details.php?id=<?php echo $relatedproperty->id; ?>"><?php echo $relatedproperty->name; ?></a></h2>
                <span class="property-location d-block mb-3"><span class="property-icon icon-room"></span><?php echo $relatedproperty->location; ?></span>
                <strong class="property-price text-primary mb-3 d-block text-success">Ksh <?php echo $relatedproperty->price ?></strong>
                <ul class="property-specs-wrap mb-3 mb-lg-0">
                  <li>
                    <span class="property-specs">Beds</span>
                    <span class="property-specs-number"><?php echo $relatedproperty->beds; ?><sup>+</sup></span>

                  </li>
                  <li>
                    <span class="property-specs">Baths</span>
                    <span class="property-specs-number"><?php echo $relatedproperty->baths; ?></span>

                  </li>
                  <li>
                    <span class="property-specs">SQ FT</span>
                    <span class="property-specs-number"><?php echo $relatedproperty->sqft; ?></span>

                  </li>
                </ul>

              </div>
            </div>
          </div>
        <?php endforeach; ?>
      <?php else : ?>
        <div class="bg-success text-white px-3">
          No Related property found !!!
        </div>
      <?php endif; ?>
    </div>
  </div>

  <?php require "includes/footer.php" ?>