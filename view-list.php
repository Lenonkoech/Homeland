<?php require "includes/header.php" ?>
<?php require "config/config.php" ?>
<?php
$select = $conn->query("SELECT * from props");
$select->execute();
$props = $select->fetchAll(PDO::FETCH_OBJ);

?>
<div class="slide-one-item home-slider owl-carousel">
  <?php foreach ($props as $prop) : ?>
    <div class="site-blocks-cover overlay" style="background-image: url(<?php echo IMAGESURL; ?>/thumbnails/<?php echo $prop->image; ?>);" data-aos="fade"
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
    <div class="row mt-5">
      <div class="col-md-12 text-center">
        <div class="site-pagination">
          <a href="#" class="active">1</a>
          <a href="#">2</a>
          <a href="#">3</a>
          <a href="#">4</a>
          <a href="#">5</a>
          <span>...</span>
          <a href="#">10</a>
        </div>
      </div>
    </div>

  </div>
</div>

<div class="site-section">
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-md-7 text-center">
        <div class="site-section-title">
          <h2>Why Choose Us?</h2>
        </div>
        <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Debitis maiores quisquam saepe architecto error
          corporis aliquam. Cum ipsam a consectetur aut sunt sint animi, pariatur corporis, eaque, deleniti cupiditate
          officia.</p>
      </div>
    </div>

    <div class="row">
      <div class="col-md-6 col-lg-4">
        <a href="#" class="service text-center">
          <span class="icon flaticon-house"></span>
          <h2 class="service-heading">Research Subburbs</h2>
          <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Incidunt iure qui natus perspiciatis ex odio
            molestia.</p>
          <p><span class="read-more">Read More</span></p>
        </a>
      </div>
      <div class="col-md-6 col-lg-4">
        <a href="#" class="service text-center">
          <span class="icon flaticon-sold"></span>
          <h2 class="service-heading">Sold Houses</h2>
          <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Incidunt iure qui natus perspiciatis ex odio
            molestia.</p>
          <p><span class="read-more">Read More</span></p>
        </a>
      </div>
      <div class="col-md-6 col-lg-4">
        <a href="#" class="service text-center">
          <span class="icon flaticon-camera"></span>
          <h2 class="service-heading">Security Priority</h2>
          <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Incidunt iure qui natus perspiciatis ex odio
            molestia.</p>
          <p><span class="read-more">Read More</span></p>
        </a>
      </div>
    </div>
  </div>
</div>

<div class="site-section bg-light">
  <div class="container">
    <div class="row justify-content-center mb-5">
      <div class="col-md-7 text-center">
        <div class="site-section-title">
          <h2>Recent Blog</h2>
        </div>
        <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Debitis maiores quisquam saepe architecto error
          corporis aliquam. Cum ipsam a consectetur aut sunt sint animi, pariatur corporis, eaque, deleniti cupiditate
          officia.</p>
      </div>
    </div>
    <div class="row">
      <div class="col-md-6 col-lg-4 mb-5" data-aos="fade-up" data-aos-delay="100">
        <a href="#"><img src="images/img_4.jpg" alt="Image" class="img-fluid"></a>
        <div class="p-4 bg-white">
          <span class="d-block text-secondary small text-uppercase">Jan 20th, 2019</span>
          <h2 class="h5 text-black mb-3"><a href="#">Mtaa Living</a></h2>
          <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Alias enim, ipsa exercitationem veniam quae
            sunt.</p>
        </div>
      </div>
      <div class="col-md-6 col-lg-4 mb-5" data-aos="fade-up" data-aos-delay="200">
        <a href="#"><img src="images/img_2.jpg" alt="Image" class="img-fluid"></a>
        <div class="p-4 bg-white">
          <span class="d-block text-secondary small text-uppercase">Jan 20th, 2019</span>
          <h2 class="h5 text-black mb-3"><a href="#">Roofs over KE</a></h2>
          <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Alias enim, ipsa exercitationem veniam quae
            sunt.</p>
        </div>
      </div>
      <div class="col-md-6 col-lg-4 mb-5" data-aos="fade-up" data-aos-delay="300">
        <a href="#"><img src="images/img_3.jpg" alt="Image" class="img-fluid"></a>
        <div class="p-4 bg-white">
          <span class="d-block text-secondary small text-uppercase">Jan 20th, 2019</span>
          <h2 class="h5 text-black mb-3"><a href="#">The Boma Blog</a></h2>
          <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Alias enim, ipsa exercitationem veniam quae
            sunt.</p>
        </div>
      </div>
    </div>

  </div>
</div>

<div class="site-section">
  <div class="container">

    <div class="row justify-content-center mb-5">
      <div class="col-md-7 text-center">
        <div class="site-section-title">
          <h2>Frequently Ask Questions</h2>
        </div>
        <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Debitis maiores quisquam saepe architecto error
          corporis aliquam. Cum ipsam a consectetur aut sunt sint animi, pariatur corporis, eaque, deleniti cupiditate
          officia.</p>
      </div>
    </div>

    <div class="row justify-content-center" data-aos="fade" data-aos-delay="100">
      <div class="col-md-8">
        <div class="accordion unit-8" id="accordion">
          <div class="accordion-item">
            <h3 class="mb-0 heading">
              <a class="btn-block" data-toggle="collapse" href="#collapseOne" role="button" aria-expanded="true"
                aria-controls="collapseOne">What is the name of your company<span class="icon"></span></a>
            </h3>
            <div id="collapseOne" class="collapse show" aria-labelledby="headingOne" data-parent="#accordion">
              <div class="body-text">
                <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Consequatur quae cumque perspiciatis
                  aperiam accusantium facilis provident aspernatur nisi optio debitis dolorum, est eum eligendi vero
                  aut ad necessitatibus nulla sit labore doloremque magnam! Ex molestiae, dolor tempora, ad fuga
                  minima enim mollitia consequuntur, necessitatibus praesentium eligendi officia recusandae culpa
                  tempore eaque quasi ullam magnam modi quidem in amet. Quod debitis error placeat, tempore quasi
                  aliquid eaque vel facilis culpa voluptate.</p>
              </div>
            </div>
          </div> <!-- .accordion-item -->

          <div class="accordion-item">
            <h3 class="mb-0 heading">
              <a class="btn-block" data-toggle="collapse" href="#collapseTwo" role="button" aria-expanded="false"
                aria-controls="collapseTwo">How much pay for 3 months?<span class="icon"></span></a>
            </h3>
            <div id="collapseTwo" class="collapse" aria-labelledby="headingOne" data-parent="#accordion">
              <div class="body-text">
                <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Vel ad laborum expedita. Nostrum iure
                  atque enim quisquam minima distinctio omnis, consequatur aliquam suscipit, quidem, esse aspernatur!
                  Libero, excepturi animi repellendus porro impedit nihil in doloremque a quaerat enim voluptatum,
                  perspiciatis, quas dignissimos maxime ut cum reiciendis eius dolorum voluptatem aliquam!</p>
              </div>
            </div>
          </div> <!-- .accordion-item -->

          <div class="accordion-item">
            <h3 class="mb-0 heading">
              <a class="btn-block" data-toggle="collapse" href="#collapseThree" role="button" aria-expanded="false"
                aria-controls="collapseThree">Do I need to register? <span class="icon"></span></a>
            </h3>
            <div id="collapseThree" class="collapse" aria-labelledby="headingOne" data-parent="#accordion">
              <div class="body-text">
                <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Vel ad laborum expedita. Nostrum iure
                  atque enim quisquam minima distinctio omnis, consequatur aliquam suscipit, quidem, esse aspernatur!
                  Libero, excepturi animi repellendus porro impedit nihil in doloremque a quaerat enim voluptatum,
                  perspiciatis, quas dignissimos maxime ut cum reiciendis eius dolorum voluptatem aliquam!</p>
              </div>
            </div>
          </div> <!-- .accordion-item -->

          <div class="accordion-item">
            <h3 class="mb-0 heading">
              <a class="btn-block" data-toggle="collapse" href="#collapseFour" role="button" aria-expanded="false"
                aria-controls="collapseFour">Who should I contact in case of support.<span class="icon"></span></a>
            </h3>
            <div id="collapseFour" class="collapse" aria-labelledby="headingOne" data-parent="#accordion">
              <div class="body-text">
                <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Vel ad laborum expedita. Nostrum iure
                  atque enim quisquam minima distinctio omnis, consequatur aliquam suscipit, quidem, esse aspernatur!
                  Libero, excepturi animi repellendus porro impedit nihil in doloremque a quaerat enim voluptatum,
                  perspiciatis, quas dignissimos maxime ut cum reiciendis eius dolorum voluptatem aliquam!</p>
              </div>
            </div>
          </div> <!-- .accordion-item -->

        </div>
      </div>
    </div>

  </div>
</div>

<?php require "includes/footer.php" ?>