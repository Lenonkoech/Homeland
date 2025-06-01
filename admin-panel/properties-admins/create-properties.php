<?php
require "../layout/header.php";
require "../../config/config.php";

// Ensure only logged-in admins can access this page
if (!isset($_SESSION['adminname'])) {
    echo "<script>window.location.href='" . ADMINURL . "/admins/login-admins.php'</script>";
    exit;
}

// Fetch all categories
try {
    $categories = $conn->query("SELECT * FROM categories");
    $categories->execute();
    $allCategories = $categories->fetchAll(PDO::FETCH_OBJ);
} catch (PDOException $e) {
    echo "Error fetching categories: " . $e->getMessage();
    exit;
}

if (isset($_POST['submit'])) {
    // Check if required fields are filled
    if (
        empty($_POST['name']) || empty($_POST['location']) || empty($_POST['price'])
        || empty($_POST['beds']) || empty($_POST['baths']) || empty($_POST['sq_ft'])
        || empty($_POST['home_type']) || empty($_POST['year_built']) || empty($_POST['type'])
        || empty($_POST['description']) || empty($_POST['price_sqft'])
    ) {
        echo "<script>alert('Please fill all required fields');</script>";
    } else {
        // Collect form data
        $name = $_POST['name'];
        $location = $_POST['location'];
        $price = $_POST['price'];
        $beds = $_POST['beds'];
        $baths = $_POST['baths'];
        $sq_ft = $_POST['sq_ft'];
        $home_type = $_POST['home_type'];
        $year_built = $_POST['year_built'];
        $type = $_POST['type'];
        $description = $_POST['description'];
        $price_sqft = $_POST['price_sqft'];
        $adminname = $_SESSION['adminname'];
        $image = $_FILES['thumbnail']['name'];

        // Validate and upload thumbnail image
        $thumbnailDir = "thumbnails/";
        $thumbnailPath = $thumbnailDir . basename($image);
        $allowedImageTypes = ['image/jpeg', 'image/png', 'image/jpg'];
        $imageType = $_FILES['thumbnail']['type'];

        if (!in_array($imageType, $allowedImageTypes)) {
            echo "<script>alert('Only JPG, JPEG, and PNG images are allowed for the thumbnail');</script>";
        } else {
            // Insert property data into database
            try {
                $insert = $conn->prepare("INSERT INTO props(name, location, price, beds, baths, sqft, home_type, year_built, type, description, price_sqft, admin_name, image) 
                                          VALUES (:name, :location, :price, :beds, :baths, :sq_ft, :home_type, :year_built, :type, :description, :price_sqft, :adminname, :image)");

                $insert->execute([
                    ':name' => $name,
                    ':location' => $location,
                    ':price' => $price,
                    ':beds' => $beds,
                    ':baths' => $baths,
                    ':sq_ft' => $sq_ft,
                    ':home_type' => $home_type,
                    ':year_built' => $year_built,
                    ':type' => $type,
                    ':description' => $description,
                    ':price_sqft' => $price_sqft,
                    ':adminname' => $adminname,
                    ':image' => $image,
                ]);

                // Move the uploaded thumbnail file
                if (move_uploaded_file($_FILES['thumbnail']['tmp_name'], $thumbnailPath)) {
                    // Get the last inserted ID
                    $id = $conn->lastInsertId();
                    // Upload gallery images
                    if (!empty($_FILES['image']['name'][0])) {
                        foreach ($_FILES['image']['tmp_name'] as $key => $value) {
                            $filename = $_FILES['image']['name'][$key];
                            $filenameTmp = $_FILES['image']['tmp_name'][$key];
                            $ext = pathinfo($filename, PATHINFO_EXTENSION);
                            $finalimg = str_replace('.', '-', basename($filename, $ext)) . time() . "." . $ext;

                            // Validate file type for gallery images
                            if (!in_array($ext, ['jpg', 'jpeg', 'png'])) {
                                echo "<script>alert('Invalid file type for gallery image. Only JPG, JPEG, PNG are allowed.');</script>";
                                exit;
                            }

                            // Move the gallery image
                            move_uploaded_file($filenameTmp, 'images/' . $finalimg);

                            // Insert gallery image into the database
                            $insertqry = $conn->prepare("INSERT INTO `props_gallery`( `image`, prop_id) VALUES (:image, :prop_id)");
                            $insertqry->execute([
                                ':image' => $finalimg,
                                ':prop_id' => $id
                            ]);
                        }
                    }
                    // Redirect to properties page after successful insertion
                    echo "<script>window.location.href='" . ADMINURL . "/properties-admins/show-properties.php'</script>";
                } else {
                    echo "<script>alert('Failed to upload thumbnail image');</script>";
                }
            } catch (PDOException $e) {
                echo "Error inserting property: " . $e->getMessage();
                exit;
            }
        }
    }
}
?>

<div class="row">
    <div class="col">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title mb-5 d-inline">Create Property</h5>
                <form method="POST" action="create-properties.php" enctype="multipart/form-data">
                    <div class="form-outline mb-4 mt-4">
                        <input type="text" name="name" class="form-control" placeholder="Property Name" required />
                    </div>
                    <div class="form-outline mb-4 mt-4">
                        <input type="text" name="location" class="form-control" placeholder="Location" required />
                    </div>
                    <div class="form-outline mb-4 mt-4">
                        <input type="text" name="price" class="form-control" placeholder="Price" required />
                    </div>
                    <div class="form-outline mb-4 mt-4">
                        <input type="text" name="beds" class="form-control" placeholder="Beds" required />
                    </div>
                    <div class="form-outline mb-4 mt-4">
                        <input type="text" name="baths" class="form-control" placeholder="Baths" required />
                    </div>
                    <div class="form-outline mb-4 mt-4">
                        <input type="text" name="sq_ft" class="form-control" placeholder="Square Feet" required />
                    </div>
                    <div class="form-outline mb-4 mt-4">
                        <input type="text" name="year_built" class="form-control" placeholder="Year Built" required />
                    </div>
                    <div class="form-outline mb-4 mt-4">
                        <input type="text" name="price_sqft" class="form-control" placeholder="Price Per Sq Ft" required />
                    </div>

                    <select name="home_type" class="form-control form-select mb-4" required>
                        <option selected disabled>Select Home Type</option>
                        <?php foreach ($allCategories as $category) : ?>
                            <option value="<?php echo htmlspecialchars($category->name); ?>"><?php echo htmlspecialchars($category->name); ?></option>
                        <?php endforeach; ?>
                    </select>

                    <select name="type" class="form-control mt-3 mb-4 form-select" required>
                        <option selected disabled>Select Type</option>
                        <option value="rent">Rent</option>
                        <option value="sale">Sale</option>
                        <option value="lease">Lease</option>
                    </select>

                    <div class="form-group">
                        <label for="description">Description</label>
                        <textarea name="description" class="form-control" id="description" rows="3" required></textarea>
                    </div>

                    <div class="mb-3">
                        <label for="thumbnail" class="form-label">Property Thumbnail</label>
                        <input name="thumbnail" class="form-control" type="file" id="thumbnail" required />
                    </div>

                    <div class="mb-3">
                        <label for="gallery" class="form-label">Gallery Images</label>
                        <input name="image[]" class="form-control" type="file" id="gallery" multiple />
                    </div>

                    <button type="submit" name="submit" class="btn btn-primary mb-4">Create Property</button>
                </form>
            </div>
        </div>
    </div>
</div>

<?php require "../layout/footer.php"; ?>