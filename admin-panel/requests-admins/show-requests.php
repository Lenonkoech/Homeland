<?php require "../layout/header.php" ?>
<?php require "../../config/config.php" ?>
<?php
$query = $conn->query("SELECT requests.*, props.name as property_name 
                       FROM requests 
                       INNER JOIN props 
                       ON requests.prop_id = props.id");
$query->execute();
$requests = $query->fetchAll(PDO::FETCH_OBJ);
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
                    <th scope="col">#</th>
                    <th scope="col">User Name</th>
                    <th scope="col">email</th>
                    <th scope="col">phone</th>
                    <th scope="col">property name</th>
                  </tr>
                </thead>
                <tbody>
                  <?php if(count($requests) > 0): ?>
                    <?php 
                    $counter = 1;
                    foreach($requests as $request): 
                    ?>
                    <tr>
                        <th scope="row"><?php echo $counter++; ?></th>
                        <td><?php echo $request->name; ?></td>
                        <td><?php echo $request->email; ?></td>
                        <td><?php echo $request->phone; ?></td>
                        <td><?php echo $request->property_name; ?></td>
                    </tr>
                    <?php endforeach; ?>
                  <?php else: ?>
                    <tr>
                        <td colspan="5" class="text-center">No requests found</td>
                    </tr>
                  <?php endif; ?>
                </tbody>
              </table> 
            </div>
          </div>
        </div>
      </div>
    </div>
<?php require "../layout/footer.php" ?>
