<?php require "includes/header.php"?>

<body>

  <div class="site-loader"></div>

  <div class="site-wrap">

    <div class="site-mobile-menu">
      <div class="site-mobile-menu-header">
        <div class="site-mobile-menu-close mt-3">
          <span class="icon-close2 js-menu-toggle"></span>
        </div>
      </div>
      <div class="site-mobile-menu-body"></div>
    </div> <!-- .site-mobile-menu -->

    <div class="site-navbar mt-4">
      <div class="container py-1">
        <div class="row align-items-center">
          <div class="col-8 col-md-8 col-lg-4">
            <h1 class="mb-0"><a href="index.php" class="text-white h2 mb-0"><strong>Homeland<span
                    class="text-danger">.</span></strong></a></h1>
          </div>
          <div class="col-4 col-md-4 col-lg-8">
            <nav class="site-navigation text-right text-md-right" role="navigation">

              <div class="d-inline-block d-lg-none ml-md-0 mr-auto py-3"><a href="#"
                  class="site-menu-toggle js-menu-toggle text-white"><span class="icon-menu h3"></span></a></div>

              <ul class="site-menu js-clone-nav d-none d-lg-block">
                <li class="active">
                  <a href="index.php">Home</a>
                </li>
                <li><a href="buy.php">Buy</a></li>
                <li><a href="rent.php">Rent</a></li>
                <li class="has-children">
                  <a href="properties.php">Properties</a>
                  <ul class="dropdown arrow-top">
                    <li><a href="#">Condo</a></li>
                    <li><a href="#">Property Land</a></li>
                    <li><a href="#">Commercial Building</a></li>

                  </ul>
                </li>
                <li><a href="about.php">About</a></li>
                <li><a href="contact.php">Contact</a></li>
                <li><a href="login.php">Login</a></li>
                <li><a href="register.php">Register</a></li>
              </ul>
            </nav>
          </div>


        </div>
      </div>
    </div>
  </div>

  <div class="site-blocks-cover inner-page-cover overlay" style="background-image: url(images/hero_bg_2.jpg);"
    data-aos="fade" data-stellar-background-ratio="0.5">
    <div class="container">
      <div class="row align-items-center justify-content-center text-center">
        <div class="col-md-10">
          <h1 class="mb-2">Contact Us</h1>
        </div>
      </div>
    </div>
  </div>


  <div class="site-section">
    <div class="container">
      <div class="row">

        <div class="col-md-12 col-lg-8 mb-5">



          <form action="#" class="p-5 bg-white border">

            <div class="row form-group">
              <div class="col-md-12 mb-3 mb-md-0">
                <label class="font-weight-bold" for="fullname">Full Name</label>
                <input type="text" id="fullname" class="form-control" placeholder="Full Name">
              </div>
            </div>
            <div class="row form-group">
              <div class="col-md-12">
                <label class="font-weight-bold" for="email">Email</label>
                <input type="email" id="email" class="form-control" placeholder="Email Address">
              </div>
            </div>
            <div class="row form-group">
              <div class="col-md-12">
                <label class="font-weight-bold" for="email">Subject</label>
                <input type="text" id="subject" class="form-control" placeholder="Enter Subject">
              </div>
            </div>


            <div class="row form-group">
              <div class="col-md-12">
                <label class="font-weight-bold" for="message">Message</label>
                <textarea name="message" id="message" cols="30" rows="5" class="form-control"
                  placeholder="Say hello to us"></textarea>
              </div>
            </div>

            <div class="row form-group">
              <div class="col-md-12">
                <input type="submit" value="Send Message" class="btn btn-primary  py-2 px-4 rounded-0">
              </div>
            </div>


          </form>
        </div>

        <div class="col-lg-4">
          <div class="p-4 mb-3 bg-white">
            <h3 class="h6 text-black mb-3 text-uppercase">Contact Info</h3>
            <p class="mb-0 font-weight-bold">Address</p>
            <p class="mb-4">203 Fake St. Mountain View, San Francisco, California, USA</p>

            <p class="mb-0 font-weight-bold">Phone</p>
            <p class="mb-4"><a href="#">+1 232 3235 324</a></p>

            <p class="mb-0 font-weight-bold">Email Address</p>
            <p class="mb-0"><a href="#">youremail@domain.com</a></p>

          </div>

        </div>
      </div>
    </div>
  </div>

  <div class="site-section bg-light">
    <div class="container">
      <div class="row mb-5 justify-content-center">
        <div class="col-md-7">
          <div class="site-section-title text-center">
            <h2>Our Agents</h2>
            <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Vero magnam officiis ipsa eum pariatur labore
              fugit amet eaque iure vitae, repellendus laborum in modi reiciendis quis! Optio minima quibusdam,
              laboriosam.</p>
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-md-6 col-lg-4 mb-5 mb-lg-5">
          <div class="team-member">

            <img src="images/person_1.jpg" alt="Image" class="img-fluid rounded mb-4">

            <div class="text">

              <h2 class="mb-2 font-weight-light text-black h4">Megan Smith</h2>
              <span class="d-block mb-3 text-white-opacity-05">Real Estate Agent</span>
              <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Modi dolorem totam non quis facere blanditiis
                praesentium est. Totam atque corporis nisi, veniam non. Tempore cupiditate, vitae minus obcaecati
                provident beatae!</p>
              <p>
                <a href="#" class="text-black p-2"><span class="icon-facebook"></span></a>
                <a href="#" class="text-black p-2"><span class="icon-twitter"></span></a>
                <a href="#" class="text-black p-2"><span class="icon-linkedin"></span></a>
              </p>
            </div>

          </div>
        </div>

        <div class="col-md-6 col-lg-4 mb-5 mb-lg-5">
          <div class="team-member">

            <img src="images/person_2.jpg" alt="Image" class="img-fluid rounded mb-4">

            <div class="text">

              <h2 class="mb-2 font-weight-light text-black h4">Brooke Cagle</h2>
              <span class="d-block mb-3 text-white-opacity-05">Real Estate Agent</span>
              <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Omnis, cumque vitae voluptates culpa earum
                similique corrupti itaque veniam doloribus amet perspiciatis recusandae sequi nihil tenetur ad, modi
                quos id magni!</p>
              <p>
                <a href="#" class="text-black p-2"><span class="icon-facebook"></span></a>
                <a href="#" class="text-black p-2"><span class="icon-twitter"></span></a>
                <a href="#" class="text-black p-2"><span class="icon-linkedin"></span></a>
              </p>
            </div>

          </div>
        </div>

        <div class="col-md-6 col-lg-4 mb-5 mb-lg-5">
          <div class="team-member">

            <img src="images/person_3.jpg" alt="Image" class="img-fluid rounded mb-4">

            <div class="text">

              <h2 class="mb-2 font-weight-light text-black h4">Philip Martin</h2>
              <span class="d-block mb-3 text-white-opacity-05">Real Estate Agent</span>
              <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Maiores illo iusto, inventore, iure dolorum
                officiis modi repellat nobis, praesentium perspiciatis, explicabo. Atque cupiditate, voluptates pariatur
                odit officia libero veniam quo.</p>
              <p>
                <a href="#" class="text-black p-2"><span class="icon-facebook"></span></a>
                <a href="#" class="text-black p-2"><span class="icon-twitter"></span></a>
                <a href="#" class="text-black p-2"><span class="icon-linkedin"></span></a>
              </p>
            </div>

          </div>
        </div>



      </div>
    </div>
  </div>



  <?php require "includes/footer.php"?>