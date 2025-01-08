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
  $queryGallery = $conn->query("SELECT * from props_gallery where prop_id = '$id'");
  $queryGallery->execute();
  $gallery = $queryGallery->fetchAll(PDO::FETCH_OBJ);
  $relatedProps = $conn->query("SELECT * from props where home_type='$allDetails->home_type' and id != '$id'");
  $relatedProps->execute();
  $RelatedProp = $relatedProps->fetchAll(PDO::FETCH_OBJ);
}
?>
<div class="slide-one-item home-slider owl-carousel">
  <?php foreach ($props as $prop) : ?>
    <div class="site-blocks-cover overlay" style="background-image: url(images/<?php echo $prop->image; ?>);" data-aos="fade"
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

<div class="site-section site-section-sm">
  <div class="container">
    <div class="row">
      <div class="col-lg-8">
        <div>

          <div class="slide-one-item home-slider owl-carousel">
            <?php foreach ($gallery as $image): ?>
              <div><img src="<?php echo APPURL ?>images/<?php echo $image->image; ?>" alt="Image" class="img-fluid"></div>
            <?php endforeach; ?>
          </div>
        </div>
        <div class="bg-white property-body border-bottom border-left border-right">
          <div class="row mb-5">
            <div class="col-md-6">
              <strong class="text-success h1 mb-3">$<?php echo $allDetails->price; ?></strong>
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
              <strong class="d-block">$<?php echo $allDetails->price_sqft ?></strong>
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
                <a href="images/img_1.jpg" class="image-popup gal-item"><img src="images/<?php echo $image->image; ?>" alt="Image"
                    class="img-fluid"></a>
              </div>
            <?php endforeach; ?>
          </div>
        </div>
      </div>
      <div class="col-lg-4">

        <div class="bg-white widget border rounded">

          <h3 class="h4 text-black widget-title mb-3">Contact Agent</h3>
          <form action="" class="form-contact-agent">
            <div class="form-group">
              <label for="name">Name</label>
              <input type="text" id="name" class="form-control">
            </div>
            <div class="form-group">
              <label for="email">Email</label>
              <input type="email" id="email" class="form-control">
            </div>
            <div class="form-group">
              <label for="phone">Phone</label>
              <input type="text" id="phone" class="form-control">
            </div>
            <div class="form-group">
              <input type="submit" id="phone" class="btn btn-primary" value="Send Message">
            </div>
          </form>
        </div>

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
      <?php if(count($RelatedProp)>0):?>
      <?php foreach ($RelatedProp as $relatedproperty): ?>
        <div class="col-md-6 col-lg-4 mb-4">
          <div class="property-entry h-100">
            <a href="property-details.php?id=<?php echo $relatedproperty->id;?>" class="property-thumbnail">
              <div class="offer-type-wrap">
                <span class="offer-type bg-<?php if ($relatedproperty->type == "rent") {
                                              echo "success";
                                            } else if ($relatedproperty->type == "sale") {
                                              echo "danger";
                                            } else {
                                              echo "info";
                                            } ?>"><?php echo $relatedproperty->type;?></span>
              </div>
              <img src="images/<?php echo $relatedproperty->image;?>" alt="Image" class="img-fluid">
            </a>
            <div class="p-4 property-body">
              <a href="#" class="property-favorite"><span class="icon-heart-o"></span></a>
              <h2 class="property-title"><a href="property-details.php?id=<?php echo $relatedproperty->id;?>"><?php echo $relatedproperty->name;?></a></h2>
              <span class="property-location d-block mb-3"><span class="property-icon icon-room"></span><?php echo $relatedproperty->location;?></span>
              <strong class="property-price text-primary mb-3 d-block text-success">$2,265,500</strong>
              <ul class="property-specs-wrap mb-3 mb-lg-0">
                <li>
                  <span class="property-specs">Beds</span>
                  <span class="property-specs-number"><?php echo $relatedproperty->beds;?><sup>+</sup></span>

                </li>
                <li>
                  <span class="property-specs">Baths</span>
                  <span class="property-specs-number"><?php echo $relatedproperty->baths;?></span>

                </li>
                <li>
                  <span class="property-specs">SQ FT</span>
                  <span class="property-specs-number"><?php echo $relatedproperty->sqft;?></span>

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