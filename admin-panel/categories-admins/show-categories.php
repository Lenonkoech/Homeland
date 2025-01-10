<?php include "../layout/header.php" ?>
<div class="container-fluid">

  <div class="row">
    <div class="col">
      <div class="card">
        <div class="card-body">
          <h5 class="card-title mb-4 d-inline">Categories</h5>
          <a href="create-category.html" class="btn btn-primary mb-4 text-center float-right">Create Categories</a>
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
              <tr>
                <th scope="row">1</th>
                <td>Condo</td>
                <td><a href="update-category.html" class="btn btn-warning text-white text-center ">Update Categories</a></td>
                <td><a href="delete-category.html" class="btn btn-danger  text-center ">Delete Categories</a></td>
              </tr>
              <tr>
                <th scope="row">2</th>
                <td>Condo</td>
                <td><a href="update-category.html" class="btn btn-warning text-white text-center">Update Categories</a></td>
                <td><a href="delete-category.html" class="btn btn-danger  text-center ">Delete Categories</a></td>
              </tr>
              <tr>
                <th scope="row">3</th>
                <td>Condo</td>
                <td><a href="update-category.html" class="btn btn-warning text-white text-center ">Update Categories</a></td>
                <td><a href="delete-category.html" class="btn btn-danger text-center">Delete Categories</a></td>
              </tr>
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