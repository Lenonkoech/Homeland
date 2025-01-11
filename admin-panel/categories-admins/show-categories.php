<?php require "../layout/header.php" ?>
<?php require "../../config/config.php" ?>
<?php
if (!isset($_SESSION['adminname'])) {
  echo "<script>window.location.href='" . ADMINURL . "/admins/login-admins.php'</script>";
}
$showCategories = $conn->query("SELECT * From categories");
$showCategories->execute();
$allCategories = $showCategories->fetchAll(PDO::FETCH_OBJ);
$counter = 1;
?>
<div class="container-fluid">

  <div class="row">
    <div class="col">
      <div class="card">
        <div class="card-body">
          <h5 class="card-title mb-4 d-inline">Categories</h5>
          <a href="create-category.php" class="btn btn-primary mb-4 text-center float-right">Create Categories</a>
          <table class="table">
            <thead>
              <tr>
                <th scope="col">#</th>
                <th scope="col">name</th>
                <th scope="col">update</th>
                <th scope="col">delete</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($allCategories as $category): ?>
                <tr>
                  <th scope="row"><?php echo $counter++; ?></th>
                  <td><?php echo $category->name; ?></td>
                  <td><a href="<?php echo ADMINURL ?>/categories-admins/update-category.php?id=<?php echo $category->id; ?>" class="btn btn-warning text-white text-center ">Update</a></td>
                  <td><a href="<?php echo ADMINURL ?>/categories-admins/delete-category.php?id=<?php echo $category->id; ?>" class="btn btn-danger  text-center ">Delete</a></td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>



</div>
<script type="text/javascript">

</script>
</body>

</html>