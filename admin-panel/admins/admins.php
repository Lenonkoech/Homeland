<?php require "../layout/header.php" ?>
<?php require "../../config/config.php" ?>
<?php
$query_admins = $conn->query("SELECT * from admins");
$query_admins->execute();

$alladmins = $query_admins->fetchAll(PDO::FETCH_OBJ);
?>

<div class="container-fluid">
  <div class="row">
    <div class="col">
      <div class="card">
        <div class="card-body">
          <h5 class="card-title mb-4 d-inline">Admins</h5>
          <a href="<?php echo ADMINURL; ?>/admins/create-admins.php" class="btn btn-primary mb-4 text-center float-right">Create Admins</a>
          <table class="table">
            <thead>
              <tr>
                <th scope="col">#</th>
                <th scope="col">username</th>
                <th scope="col">email</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <?php foreach ($alladmins as $admin): ?>
                  <th scope="row"><?php echo $admin->id; ?></th>
                  <td><?php echo $admin->name; ?></td>
                  <td><?php echo $admin->email; ?></td>
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