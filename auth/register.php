<?php require "../includes/header.php"?>
<?php require "../config/config.php"?>
<?php
if(isset($_POST['submit'])){
if(empty($_POST['username']) or empty($_POST['email']) or empty($_POST['password'])){
echo "<script>alert ('Fill in all fields')</script>";
}else{
  $username = $_POST['username'];
  $email= $_POST['email'];
  $password=$_POST['password'];

  $insert = $conn->prepare("INSERT into users(username,email,mypassword) values (:username,:email,:mypassword)");
  $insert->execute([
  ':username'=>$username,
  ':email'=>$email,
  ':mypassword'=>password_hash($password, PASSWORD_DEFAULT)
  ]);

  header("location: login.php");
}
}

?>

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

  <div class="site-blocks-cover inner-page-cover overlay" style="background-image: url(<?php echo APPURL ; ?>images/hero_bg_2.jpg);"
    data-aos="fade" data-stellar-background-ratio="0.5">
    <div class="container">
      <div class="row align-items-center justify-content-center text-center">
        <div class="col-md-10">
          <h1 class="mb-2">Register</h1>
        </div>
      </div>
    </div>
  </div>


  <div class="site-section">
    <div class="container">
      <div class="row">
        <div class="col-md-12" data-aos="fade-up" data-aos-delay="100">
          <h3 class="h4 text-black widget-title mb-3">Register</h3>
          <form action="register.php" method = "POST" class="form-contact-agent">

            <div class="form-group">
              <label for="email">Username</label>
              <input type="username" id="username" name="username" class="form-control">
            </div>
            <div class="form-group">
              <label for="email">Email</label>
              <input type="email" id="email" name="email" class="form-control">
            </div>
            <div class="form-group">
              <label for="password">Password</label>
              <input type="password" id="password" name="password" class="form-control">
            </div>
            <div class="form-group">
              <input type="submit" id="phone" name="submit" class="btn btn-primary" value="Register">
            </div>
          </form>
        </div>

      </div>
    </div>
  </div>

  <?php require "../includes/footer.php"?>