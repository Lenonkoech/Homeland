<?php require "../includes/header.php" ?>
<?php require "../config/config.php" ?>
<?php
if (isset($_SESSION['username'])) {
  echo "<script>window.location.href='" . APPURL . "'</script>";
}
if (isset($_POST["submit"])) {
  if (empty($_POST['email']) or empty($_POST['password'])) {
    echo "<script>alert('Fill in all fields')</script";
  } else {
    $email = $_POST["email"];
    $password = $_POST["password"];

    $login = $conn->query("SELECT * FROM users where email = '$email'");
    $login->execute();

    $fetch = $login->fetch(PDO::FETCH_ASSOC);

    if ($login->rowCount() > 0) {
      if (password_verify($password, $fetch['mypassword'])) {
        $_SESSION['username'] = $fetch['username'];
        $_SESSION['email'] = $fetch['email'];
        $_SESSION['user_id'] = $fetch['id'];
        // header("location:" . APPURL . "");
        echo "<script>window.location.href='" . APPURL . "'</script>";
      } else {
        echo "<script>alert('Wrong email or password')</script";
      }
    } else {
      echo "<script>alert('Wrong email or password')</script";
    }
  }
}

?>
<div class="site-blocks-cover inner-page-cover overlay" style="background-image: url(<?php echo APPURL; ?>images/hero_bg_2.jpg);" data-aos="fade" data-stellar-background-ratio="0.5">
  <div class="container">
    <div class="row align-items-center justify-content-center text-center">
      <div class="col-md-10">
        <h1 class="mb-2">Log In</h1>
      </div>
    </div>
  </div>
</div>


<div class="site-section">
  <div class="container">
    <div class="row">
      <div class="col-md-12" data-aos="fade-up" data-aos-delay="100">
        <h3 class="h4 text-black widget-title mb-3">Login</h3>
        <form action="login.php" method="POST" class="form-contact-agent">
          <div class="form-group">
            <label for="email">Email</label>
            <input type="email" id="email" name="email" class="form-control">
          </div>
          <div class="form-group">
            <label for="password">Password</label>
            <input type="password" id="password" name="password" class="form-control">
          </div>
          <div class="form-group">
            <input type="submit" id="phone" name="submit" class="btn btn-primary" value="Login">
          </div>
        </form>
      </div>

    </div>
  </div>
</div>
</body>

<?php require "../includes/footer.php" ?>