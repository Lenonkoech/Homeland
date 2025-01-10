<?php require "../layout/header.php" ?>
<?php require "../../config/config.php" ?>

<?php
if (!isset($_SESSION['adminname'])) {
  echo "<script>window.location.href='" . ADMINURL . "/admins/login-admins.php'</script>";
}

if (isset($_POST['submit'])) {
  if (empty($_POST['adminname']) or empty($_POST['email']) or empty($_POST['password'])) {
    echo "<script>alert ('Fill in all fields')</script>";
  } else {
    $adminname = $_POST['adminname'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    $insert = $conn->prepare("INSERT into admins(name,email,mypassword) values (:adminname,:email,:mypassword)");
    $insert->execute([
      ':adminname' => $adminname,
      ':email' => $email,
      ':mypassword' => password_hash($password, PASSWORD_DEFAULT)
    ]);

    // header("location: login.php");
    echo "<script>window.location.href='" . ADMINURL . "/admins/admins.php'</script>";
  }
}

?>
<div class="container-fluid">
  <div class="row">
    <div class="col">
      <div class="card">
        <div class="card-body">
          <h5 class="card-title mb-5 d-inline">Create Admins</h5>
          <form method="POST" action="create-admins.php" enctype="multipart/form-data">
            <!-- Email input -->
            <div class="form-outline mb-4 mt-4">
              <input type="text" name="adminname" id="form2Example1" class="form-control" placeholder="Admin name" />

            </div>

            <div class="form-outline mb-4">
              <input type="emil" name="email" id="form2Example1" class="form-control" placeholder="Email" />
            </div>
            <div class="form-outline mb-4">
              <input type="password" name="password" id="form2Example1" class="form-control" placeholder="Password" />
            </div>
            <!-- Submit button -->
            <button type="submit" name="submit" class="btn btn-primary  mb-4 text-center">create</button>


          </form>

        </div>
      </div>
    </div>
  </div>
</div>
<script type="text/javascript">

</script>
</body>

</html>