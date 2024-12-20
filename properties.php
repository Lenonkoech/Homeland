<?php require "includes/header.php"?>

  <div class="slide-one-item home-slider owl-carousel">

    <div class="site-blocks-cover overlay" style="background-image: url(images/hero_bg_1.jpg);" data-aos="fade"
      data-stellar-background-ratio="0.5">
      <div class="container">
        <div class="row align-items-center justify-content-center text-center">
          <div class="col-md-10">
            <span class="d-inline-block bg-success text-white px-3 mb-3 property-offer-type rounded">For Rent</span>
            <h1 class="mb-2">871 Crenshaw Blvd</h1>
            <p class="mb-5"><strong class="h2 text-success font-weight-bold">$2,250,500</strong></p>
            <p><a href="#" class="btn btn-white btn-outline-white py-3 px-5 rounded-0 btn-2">See Details</a></p>
          </div>
        </div>
      </div>
    </div>

    <div class="site-blocks-cover overlay" style="background-image: url(images/hero_bg_2.jpg);" data-aos="fade"
      data-stellar-background-ratio="0.5">
      <div class="container">
        <div class="row align-items-center justify-content-center text-center">
          <div class="col-md-10">
            <span class="d-inline-block bg-danger text-white px-3 mb-3 property-offer-type rounded">For Sale</span>
            <h1 class="mb-2">625 S. Berendo St</h1>
            <p class="mb-5"><strong class="h2 text-success font-weight-bold">$1,000,500</strong></p>
            <p><a href="#" class="btn btn-white btn-outline-white py-3 px-5 rounded-0 btn-2">See Details</a></p>
          </div>
        </div>
      </div>
    </div>

  </div>


  <div class="site-section site-section-sm pb-0">
    <div class="container">
      <div class="row">
        <form class="form-search col-md-12" style="margin-top: -100px;">
          <div class="row  align-items-end">
            <div class="col-md-3">
              <label for="list-types">Listing Types</label>
              <div class="select-wrap">
                <span class="icon icon-arrow_drop_down"></span>
                <select name="list-types" id="list-types" class="form-control d-block rounded-0">
                  <option value="">Condo</option>
                  <option value="">Commercial Building</option>
                  <option value="">Land Property</option>
                </select>
              </div>
            </div>
            <div class="col-md-3">
              <label for="offer-types">Offer Type</label>
              <div class="select-wrap">
                <span class="icon icon-arrow_drop_down"></span>
                <select name="offer-types" id="offer-types" class="form-control d-block rounded-0">
                  <option value="">For Sale</option>
                  <option value="">For Rent</option>
                  <option value="">For Lease</option>
                </select>
              </div>
            </div>
            <div class="col-md-3">
              <label for="select-city">Select City</label>
              <div class="select-wrap">
                <span class="icon icon-arrow_drop_down"></span>
                <select name="select-city" id="select-city" class="form-control d-block rounded-0">
                  <option value="">New York</option>
                  <option value="">Brooklyn</option>
                  <option value="">London</option>
                  <option value="">Japan</option>
                  <option value="">Philippines</option>
                </select>
              </div>
            </div>
            <div class="col-md-3">
              <input type="submit" class="btn btn-success text-white btn-block rounded-0" value="Search">
            </div>
          </div>
        </form>
      </div>

      <div class="row">
        <div class="col-md-12">
          <div class="view-options bg-white py-3 px-3 d-md-flex align-items-center">
            <div class="mr-auto">
              <a href="index.html" class="icon-view view-module active"><span class="icon-view_module"></span></a>
              <a href="view-list.html" class="icon-view view-list"><span class="icon-view_list"></span></a>

            </div>
            <div class="ml-auto d-flex align-items-center">
              <div>
                <a href="#" class="view-list px-3 border-right active">All</a>
                <a href="#" class="view-list px-3 border-right">Rent</a>
                <a href="#" class="view-list px-3">Sale</a>
              </div>


              <div class="select-wrap">
                <span class="icon icon-arrow_drop_down"></span>
                <select class="form-control form-control-sm d-block rounded-0">
                  <option value="">Sort by</option>
                  <option value="">Price Ascending</option>
                  <option value="">Price Descending</option>
                </select>
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
        <div class="col-md-6 col-lg-4 mb-4">
          <div class="property-entry h-100">
            <a href="property-details.html" class="property-thumbnail">
              <div class="offer-type-wrap">
                <span class="offer-type bg-danger">Sale</span>
                <span class="offer-type bg-success">Rent</span>
              </div>
              <img src="images/img_1.jpg" alt="Image" class="img-fluid">
            </a>
            <div class="p-4 property-body">
              <a href="#" class="property-favorite"><span class="icon-heart-o"></span></a>
              <h2 class="property-title"><a href="property-details.html">625 S. Berendo St</a></h2>
              <span class="property-location d-block mb-3"><span class="property-icon icon-room"></span> 625 S. Berendo
                St Unit 607 Los Angeles, CA 90005</span>
              <strong class="property-price text-primary mb-3 d-block text-success">$2,265,500</strong>
              <ul class="property-specs-wrap mb-3 mb-lg-0">
                <li>
                  <span class="property-specs">Beds</span>
                  <span class="property-specs-number">2 <sup>+</sup></span>

                </li>
                <li>
                  <span class="property-specs">Baths</span>
                  <span class="property-specs-number">2</span>

                </li>
                <li>
                  <span class="property-specs">SQ FT</span>
                  <span class="property-specs-number">7,000</span>

                </li>
              </ul>

            </div>
          </div>
        </div>

        <div class="col-md-6 col-lg-4 mb-4">
          <div class="property-entry h-100">
            <a href="property-details.html" class="property-thumbnail">
              <div class="offer-type-wrap">
                <span class="offer-type bg-danger">Sale</span>
                <span class="offer-type bg-success">Rent</span>
              </div>
              <img src="images/img_2.jpg" alt="Image" class="img-fluid">
            </a>
            <div class="p-4 property-body">
              <a href="#" class="property-favorite active"><span class="icon-heart-o"></span></a>
              <h2 class="property-title"><a href="property-details.html">871 Crenshaw Blvd</a></h2>
              <span class="property-location d-block mb-3"><span class="property-icon icon-room"></span> 1 New York Ave,
                Warners Bay, NSW 2282</span>
              <strong class="property-price text-primary mb-3 d-block text-success">$2,265,500</strong>
              <ul class="property-specs-wrap mb-3 mb-lg-0">
                <li>
                  <span class="property-specs">Beds</span>
                  <span class="property-specs-number">2 <sup>+</sup></span>

                </li>
                <li>
                  <span class="property-specs">Baths</span>
                  <span class="property-specs-number">2</span>

                </li>
                <li>
                  <span class="property-specs">SQ FT</span>
                  <span class="property-specs-number">1,620</span>

                </li>
              </ul>

            </div>
          </div>
        </div>

        <div class="col-md-6 col-lg-4 mb-4">
          <div class="property-entry h-100">
            <a href="property-details.html" class="property-thumbnail">
              <div class="offer-type-wrap">
                <span class="offer-type bg-info">Lease</span>
              </div>
              <img src="images/img_3.jpg" alt="Image" class="img-fluid">
            </a>
            <div class="p-4 property-body">
              <a href="#" class="property-favorite"><span class="icon-heart-o"></span></a>
              <h2 class="property-title"><a href="property-details.html">853 S Lucerne Blvd</a></h2>
              <span class="property-location d-block mb-3"><span class="property-icon icon-room"></span> 853 S Lucerne
                Blvd Unit 101 Los Angeles, CA 90005</span>
              <strong class="property-price text-primary mb-3 d-block text-success">$2,265,500</strong>
              <ul class="property-specs-wrap mb-3 mb-lg-0">
                <li>
                  <span class="property-specs">Beds</span>
                  <span class="property-specs-number">2 <sup>+</sup></span>

                </li>
                <li>
                  <span class="property-specs">Baths</span>
                  <span class="property-specs-number">2</span>

                </li>
                <li>
                  <span class="property-specs">SQ FT</span>
                  <span class="property-specs-number">5,500</span>

                </li>
              </ul>

            </div>
          </div>
        </div>

        <div class="col-md-6 col-lg-4 mb-4">
          <div class="property-entry h-100">
            <a href="property-details.html" class="property-thumbnail">
              <div class="offer-type-wrap">
                <span class="offer-type bg-danger">Sale</span>
                <span class="offer-type bg-success">Rent</span>
              </div>
              <img src="images/img_4.jpg" alt="Image" class="img-fluid">
            </a>
            <div class="p-4 property-body">
              <a href="#" class="property-favorite"><span class="icon-heart-o"></span></a>
              <h2 class="property-title"><a href="property-details.html">625 S. Berendo St</a></h2>
              <span class="property-location d-block mb-3"><span class="property-icon icon-room"></span> 625 S. Berendo
                St Unit 607 Los Angeles, CA 90005</span>
              <strong class="property-price text-primary mb-3 d-block text-success">$2,265,500</strong>
              <ul class="property-specs-wrap mb-3 mb-lg-0">
                <li>
                  <span class="property-specs">Beds</span>
                  <span class="property-specs-number">2 <sup>+</sup></span>

                </li>
                <li>
                  <span class="property-specs">Baths</span>
                  <span class="property-specs-number">2</span>

                </li>
                <li>
                  <span class="property-specs">SQ FT</span>
                  <span class="property-specs-number">7,000</span>

                </li>
              </ul>

            </div>
          </div>
        </div>

        <div class="col-md-6 col-lg-4 mb-4">
          <div class="property-entry h-100">
            <a href="property-details.html" class="property-thumbnail">
              <div class="offer-type-wrap">
                <span class="offer-type bg-danger">Sale</span>
                <span class="offer-type bg-success">Rent</span>
              </div>
              <img src="images/img_5.jpg" alt="Image" class="img-fluid">
            </a>
            <div class="p-4 property-body">
              <a href="#" class="property-favorite"><span class="icon-heart-o"></span></a>
              <h2 class="property-title"><a href="property-details.html">871 Crenshaw Blvd</a></h2>
              <span class="property-location d-block mb-3"><span class="property-icon icon-room"></span> 1 New York Ave,
                Warners Bay, NSW 2282</span>
              <strong class="property-price text-primary mb-3 d-block text-success">$2,265,500</strong>
              <ul class="property-specs-wrap mb-3 mb-lg-0">
                <li>
                  <span class="property-specs">Beds</span>
                  <span class="property-specs-number">2 <sup>+</sup></span>

                </li>
                <li>
                  <span class="property-specs">Baths</span>
                  <span class="property-specs-number">2</span>

                </li>
                <li>
                  <span class="property-specs">SQ FT</span>
                  <span class="property-specs-number">1,620</span>

                </li>
              </ul>

            </div>
          </div>
        </div>

        <div class="col-md-6 col-lg-4 mb-4">
          <div class="property-entry h-100">
            <a href="property-details.html" class="property-thumbnail">
              <div class="offer-type-wrap">
                <span class="offer-type bg-info">Lease</span>
              </div>
              <img src="images/img_6.jpg" alt="Image" class="img-fluid">
            </a>
            <div class="p-4 property-body">
              <a href="#" class="property-favorite"><span class="icon-heart-o"></span></a>
              <h2 class="property-title"><a href="property-details.html">853 S Lucerne Blvd</a></h2>
              <span class="property-location d-block mb-3"><span class="property-icon icon-room"></span> 853 S Lucerne
                Blvd Unit 101 Los Angeles, CA 90005</span>
              <strong class="property-price text-primary mb-3 d-block text-success">$2,265,500</strong>
              <ul class="property-specs-wrap mb-3 mb-lg-0">
                <li>
                  <span class="property-specs">Beds</span>
                  <span class="property-specs-number">2 <sup>+</sup></span>

                </li>
                <li>
                  <span class="property-specs">Baths</span>
                  <span class="property-specs-number">2</span>

                </li>
                <li>
                  <span class="property-specs">SQ FT</span>
                  <span class="property-specs-number">5,500</span>

                </li>
              </ul>

            </div>
          </div>
        </div>

        <div class="col-md-6 col-lg-4 mb-4">
          <div class="property-entry h-100">
            <a href="property-details.html" class="property-thumbnail">
              <div class="offer-type-wrap">
                <span class="offer-type bg-danger">Sale</span>
                <span class="offer-type bg-success">Rent</span>
              </div>
              <img src="images/img_7.jpg" alt="Image" class="img-fluid">
            </a>
            <div class="p-4 property-body">
              <a href="#" class="property-favorite"><span class="icon-heart-o"></span></a>
              <h2 class="property-title"><a href="property-details.html">625 S. Berendo St</a></h2>
              <span class="property-location d-block mb-3"><span class="property-icon icon-room"></span> 625 S. Berendo
                St Unit 607 Los Angeles, CA 90005</span>
              <strong class="property-price text-primary mb-3 d-block text-success">$2,265,500</strong>
              <ul class="property-specs-wrap mb-3 mb-lg-0">
                <li>
                  <span class="property-specs">Beds</span>
                  <span class="property-specs-number">2 <sup>+</sup></span>

                </li>
                <li>
                  <span class="property-specs">Baths</span>
                  <span class="property-specs-number">2</span>

                </li>
                <li>
                  <span class="property-specs">SQ FT</span>
                  <span class="property-specs-number">7,000</span>

                </li>
              </ul>

            </div>
          </div>
        </div>

        <div class="col-md-6 col-lg-4 mb-4">
          <div class="property-entry h-100">
            <a href="property-details.html" class="property-thumbnail">
              <div class="offer-type-wrap">
                <span class="offer-type bg-danger">Sale</span>
                <span class="offer-type bg-success">Rent</span>
              </div>
              <img src="images/img_8.jpg" alt="Image" class="img-fluid">
            </a>
            <div class="p-4 property-body">
              <a href="#" class="property-favorite"><span class="icon-heart-o"></span></a>
              <h2 class="property-title"><a href="property-details.html">871 Crenshaw Blvd</a></h2>
              <span class="property-location d-block mb-3"><span class="property-icon icon-room"></span> 1 New York Ave,
                Warners Bay, NSW 2282</span>
              <strong class="property-price text-primary mb-3 d-block text-success">$2,265,500</strong>
              <ul class="property-specs-wrap mb-3 mb-lg-0">
                <li>
                  <span class="property-specs">Beds</span>
                  <span class="property-specs-number">2 <sup>+</sup></span>

                </li>
                <li>
                  <span class="property-specs">Baths</span>
                  <span class="property-specs-number">2</span>

                </li>
                <li>
                  <span class="property-specs">SQ FT</span>
                  <span class="property-specs-number">1,620</span>

                </li>
              </ul>

            </div>
          </div>
        </div>

        <div class="col-md-6 col-lg-4 mb-4">
          <div class="property-entry h-100">
            <a href="property-details.html" class="property-thumbnail">
              <div class="offer-type-wrap">
                <span class="offer-type bg-info">Lease</span>
              </div>
              <img src="images/img_1.jpg" alt="Image" class="img-fluid">
            </a>
            <div class="p-4 property-body">
              <a href="#" class="property-favorite"><span class="icon-heart-o"></span></a>
              <h2 class="property-title"><a href="property-details.html">853 S Lucerne Blvd</a></h2>
              <span class="property-location d-block mb-3"><span class="property-icon icon-room"></span> 853 S Lucerne
                Blvd Unit 101 Los Angeles, CA 90005</span>
              <strong class="property-price text-primary mb-3 d-block text-success">$2,265,500</strong>
              <ul class="property-specs-wrap mb-3 mb-lg-0">
                <li>
                  <span class="property-specs">Beds</span>
                  <span class="property-specs-number">2 <sup>+</sup></span>

                </li>
                <li>
                  <span class="property-specs">Baths</span>
                  <span class="property-specs-number">2</span>

                </li>
                <li>
                  <span class="property-specs">SQ FT</span>
                  <span class="property-specs-number">5,500</span>

                </li>
              </ul>

            </div>
          </div>
        </div>
      </div>


    </div>
  </div>

  <footer class="site-footer">
    <div class="container">
      <div class="row">
        <div class="col-lg-4">
          <div class="mb-5">
            <h3 class="footer-heading mb-4">About Homeland</h3>
            <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Saepe pariatur reprehenderit vero atque,
              consequatur id ratione, et non dignissimos culpa? Ut veritatis, quos illum totam quis blanditiis, minima
              minus odio!</p>
          </div>



        </div>
        <div class="col-lg-4 mb-5 mb-lg-0">
          <div class="row mb-5">
            <div class="col-md-12">
              <h3 class="footer-heading mb-4">Navigations</h3>
            </div>
            <div class="col-md-6 col-lg-6">
              <ul class="list-unstyled">
                <li><a href="#">Home</a></li>
                <li><a href="#">Buy</a></li>
                <li><a href="#">Rent</a></li>
                <li><a href="#">Properties</a></li>
              </ul>
            </div>
            <div class="col-md-6 col-lg-6">
              <ul class="list-unstyled">
                <li><a href="#">About Us</a></li>
                <li><a href="#">Privacy Policy</a></li>
                <li><a href="#">Contact Us</a></li>
                <li><a href="#">Terms</a></li>
              </ul>
            </div>
          </div>


        </div>

        <div class="col-lg-4 mb-5 mb-lg-0">
          <h3 class="footer-heading mb-4">Follow Us</h3>

          <div>
            <a href="#" class="pl-0 pr-3"><span class="icon-facebook"></span></a>
            <a href="#" class="pl-3 pr-3"><span class="icon-twitter"></span></a>
            <a href="#" class="pl-3 pr-3"><span class="icon-instagram"></span></a>
            <a href="#" class="pl-3 pr-3"><span class="icon-linkedin"></span></a>
          </div>



        </div>

      </div>
      <div class="row pt-5 mt-5 text-center">
        <div class="col-md-12">
          <p>

            Copyright &copy;
            <script data-cfasync="false" src="/cdn-cgi/scripts/5c5dd728/cloudflare-static/email-decode.min.js"></script>
            <script>document.write(new Date().getFullYear());</script> All rights reserved

          </p>
        </div>

      </div>
    </div>
  </footer>

  </div>

  <script src="js/jquery-3.3.1.min.js"></script>
  <script src="js/jquery-migrate-3.0.1.min.js"></script>
  <script src="js/jquery-ui.js"></script>
  <script src="js/popper.min.js"></script>
  <script src="js/bootstrap.min.js"></script>
  <script src="js/owl.carousel.min.js"></script>
  <script src="js/mediaelement-and-player.min.js"></script>
  <script src="js/jquery.stellar.min.js"></script>
  <script src="js/jquery.countdown.min.js"></script>
  <script src="js/jquery.magnific-popup.min.js"></script>
  <script src="js/bootstrap-datepicker.min.js"></script>
  <script src="js/aos.js"></script>

  <script src="js/main.js"></script>

</body>

</html>