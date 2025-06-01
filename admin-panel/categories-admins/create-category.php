<?php require "../layout/header.php" ?>
<?php require "../../config/config.php" ?>

<?php
if (!isset($_SESSION['adminname'])) {
  echo "<script>window.location.href='" . ADMINURL . "/admins/login-admins.php'</script>";
}

if (isset($_POST['submit'])) {
  if (empty($_POST['categoryname'])) {
    echo "<script>alert ('Category name cannot be empty')</script>";
  } else {
    $categoryname = $_POST['categoryname'];

    $insert = $conn->prepare("INSERT into categories(name) values (:categoryname)");
    $insert->execute([
      ':categoryname' => $categoryname,
    ]);

    // header("location: login.php");
    echo "<script>window.location.href='" . ADMINURL . "/categories-admins/show-categories.php'</script>";
  }
}

?>
<div class="container-fluid">
  <div class="row">
    <div class="col">
      <div class="card">
        <div class="card-body">
          <h5 class="card-title mb-5 d-inline">Create Categories</h5>
          <form method="POST" action="" enctype="multipart/form-data">
            <!-- Email input -->
            <div class="form-outline mb-4 mt-4">
              <input type="text" name="categoryname" id="form2Example1" class="form-control" placeholder="name" />
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