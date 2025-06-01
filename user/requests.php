<?php require "../includes/header.php" ?>
<?php require "../config/config.php" ?>

<?php
if (!isset($_SESSION['username'])) {
    echo "<script>window.location.href='" . APPURL . "'</script>";
}
$requests = $conn->query("SELECT props.id as id,props.name as name, props.location as location,props.image as image,props.beds as beds,props.price as price,props.baths as baths,props.sqft as sqft,props.type as type FROM props JOIN requests on props.id =requests.prop_id where requests.user_id = $_SESSION[user_id]");
$requests->execute();
$props = $requests->fetchAll(PDO::FETCH_OBJ);
?>

<div class="site-blocks-cover inner-page-cover overlay" style="background-image: url(<?php echo APPURL; ?>images/hero_bg_2.jpg);" data-aos="fade" data-stellar-background-ratio="0.5">
    <div class="container">
        <div class="row align-items-center justify-content-center text-center">
            <div class="col-md-10">
                <h1 class="mb-2">Requests</h1>
            </div>
        </div>
    </div>
</div>
<div class="site-section site-section-sm bg-light">
    <div class="container">
        <div class="row mb-5">
            <?php if (count($props) > 0): ?>
                <?php foreach ($props as $prop) : ?>
                    <div class="col-md-6 col-lg-4 mb-4">
                        <div class="property-entry h-100">
                            <a href="<?php echo APPURL; ?>property-details.php?id=<?php echo $prop->id ?>" class="property-thumbnail">
                                <div class="offer-type-wrap">
                                    <span class="offer-type bg-<?php if ($prop->type == "rent") {
                                                                    echo "success";
                                                                } else if ($prop->type == "sale") {
                                                                    echo "danger";
                                                                } else {
                                                                    echo "info";
                                                                } ?>"><?php echo $prop->type; ?></span>
                                </div>
                                <img src="<?php echo APPURL; ?>images/<?php echo urlencode($prop->image); ?>" alt="Image" class="img-fluid">
                            </a>
                            <div class="p-4 property-body">
                                <h2 class="property-title"><a href="<?php echo APPURL; ?>property-details.php?id=<?php echo $prop->id ?>"><?php echo $prop->name; ?></a></h2>
                                <span class="property-location d-block mb-3"><span class="property-icon icon-room"></span>
                                    <?php echo $prop->location; ?></span>
                                <strong class="property-price text-primary mb-3 d-block text-success">$<?php echo $prop->price; ?></strong>
                                <ul class="property-specs-wrap mb-3 mb-lg-0">
                                    <li>
                                        <span class="property-specs">Beds</span>
                                        <span class="property-specs-number"><?php echo $prop->beds; ?> <sup>+</sup></span>

                                    </li>
                                    <li>
                                        <span class="property-specs">Baths</span>
                                        <span class="property-specs-number"><?php echo $prop->baths ?></span>

                                    </li>
                                    <li>
                                        <span class="property-specs">SQ FT</span>
                                        <span class="property-specs-number"><?php echo $prop->sqft; ?></span>

                                    </li>
                                </ul>

                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="h5 text-black widget-title mb-3 ml-0">
                    You have not made any requests to properties yet.
                </div>
            <?php endif; ?>
        </div>

    </div>
</div>
<?php require "../includes/footer.php" ?>