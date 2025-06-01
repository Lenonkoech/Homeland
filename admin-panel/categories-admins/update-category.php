<?php require "../layout/header.php" ?>
<?php require "../../config/config.php" ?>

<?php
if (!isset($_SESSION['adminname'])) {
  echo "<script>window.location.href='" . ADMINURL . "/admins/login-admins.php'</script>";
}

if (isset($_GET['id'])) {
  $id = $_GET['id'];

  $category = $conn->query("SELECT * From categories where id = '$id'");
  $category->execute();
  $allCategories = $category->fetch(PDO::FETCH_OBJ);
}

if (isset($_POST['submit'])) {
  if (empty($_POST['categoryname'])) {
    echo "<script>alert ('Category name cannot be empty')</script>";
  } else {
    $categoryname = $_POST['categoryname'];

    $update = $conn->prepare("UPDATE categories SET name = '$categoryname' WHERE id ='$id'");
    $update->execute();

    echo "<script>window.location.href='" . ADMINURL . "/categories-admins/show-categories.php'</script>";
  }
} else {
  // echo "<script>window.location.href='" . ADMINURL . "/admins/404.php'</script>";
}

?>
<div class="container-fluid">
  <div class="row">
    <div class="col">
      <div class="card">
        <div class="card-body">
          <h5 class="card-title mb-5 d-inline">Update Categories</h5>
          <form method="POST" action="update-category.php?id=<?php echo $allCategories->id ?>">
            <!-- category name input -->
            <div class="form-outline mb-4 mt-4">
              <input type="text" name="categoryname" id="form2Example1" class="form-control" placeholder="<?php echo $allCategories->name ?>" />
            </div>
            <!-- Submit button -->
            <button type="submit" name="submit" class="btn btn-primary  mb-4 text-center">update</button>
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