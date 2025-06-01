<?php require "../layout/header.php" ?>
<?php require "../../config/config.php" ?>
<?php
if (isset($_SESSION['adminname'])) {
  echo "<script>window.location.href='" . ADMINURL . "'</script>";
}


if (isset($_POST['submit'])) {
  if (empty($_POST['email']) or empty($_POST['password'])) {
    echo "<script>alert ('Fill in all fields')</script>";
  } else {

    $email = $_POST["email"];
    $password = $_POST["password"];
    //query
    $login = $conn->query("SELECT * FROM admins where email = '$email'");
    $login->execute();
    //fetch details
    $fetch = $login->fetch(PDO::FETCH_ASSOC);

    if ($login->rowCount() > 0) {

      //echo $login->rowCount();
      if (password_verify($password, $fetch['mypassword'])) {
        $_SESSION['adminname'] = $fetch['name'];
        $_SESSION['email'] = $fetch['email'];
        $_SESSION['admin_id'] = $fetch['id'];

        echo "<script>window.location.href='" . ADMINURL . "'</script>";
      } else {
        echo "<script>alert ('Wrong email or password')</script>";
      }
    } else {
      echo "<script>alert ('Wrong email or password')</script>";
    }
  }
}
?>
<div class="container-fluid">
  <div class="row">
    <div class="col">
      <div class="card">
        <div class="card-body">
          <h5 class="card-title mt-5">Login</h5>
          <form action="login-admins.php" method="POST" class="p-auto">
            <!-- Email input -->
            <div class="form-outline mb-4">
              <input type="email" name="email" id="form2Example1" class="form-control" placeholder="Email" />
            </div>
            <!-- Password input -->
            <div class="form-outline mb-4">
              <input type="password" name="password" id="form2Example2" placeholder="Password" class="form-control" />
            </div>
            <!-- Submit button -->
            <button type="submit" name="submit" class="btn btn-primary  mb-4 text-center">Login</button>
          </form>

        </div>
      </div>
    </div>
  </div>
</div>
<?php include "../layout/footer.php" ?>