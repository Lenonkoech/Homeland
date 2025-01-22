<?php require "../layout/header.php" ?>
<?php require "../../config/config.php" ?>

<?php
$fetch_request = $conn->query("SELECT * from requests");
$fetch_request->execute();
$requests = $fetch_request->fetchAll(PDO::FETCH_OBJ);
$counter = 1;
?>
<div class="container-fluid">
  <div class="row">
    <div class="col">
      <div class="card">
        <div class="card-body">
          <h5 class="card-title mb-4 d-inline">Requests</h5>

          <table class="table mt-3">
            <thead>
              <tr>
                <th scope="col"></th>
                <th scope="col">name</th>
                <th scope="col">email</th>
                <th scope="col">phone</th>
                <th scope="col">go to this property</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($requests as $request): ?>
                <tr>
                  <th scope="row"><?php echo $counter; ?></th>
                  <td><?php echo $request->name; ?></td>
                  <td><?php echo $request->email; ?></td>
                  <td><?php echo $request->phone; ?></td>
                  <td>
                    <a href="<?php echo APPURL; ?>/property-details?id=<?php echo $request->prop_id;
                                                                        ?>"
                      class="btn btn-success  text-center ">go</a>
                  </td>
                </tr>
              <?php endforeach ?>
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