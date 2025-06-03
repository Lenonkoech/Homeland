<?php
session_start();
define("APPURL", 'http://localhost/homeland');
define("ADMINURL", 'http://localhost/homeland/admin-panel');
define("IMAGESURL", 'http://localhost/homeland/admin-panel/properties-admins');
require dirname(dirname(__FILE__)) . '../../config/config.php';

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Admin Panel</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="http://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" rel="stylesheet">
    <link href="<?php echo ADMINURL; ?>/styles/style.css" rel="stylesheet">
    <script src="http://code.jquery.com/jquery-1.11.1.min.js"></script>
    <script src="http://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <style>
        /* Add styles for the logo text */
        .navbar-brand span.qejani { color: white; }
        .navbar-brand span.connect { color: red; }

        /* Keep existing or add other necessary styles */
    </style>
</head>

<body>
    <div id="wrapper">
        <nav class="navbar header-top fixed-top navbar-expand-lg  navbar-dark bg-dark">
            <div class="container">
                <a class="navbar-brand" href="<?php echo ADMINURL; ?>"><span class="qejani">Qejani</span><span class="connect">Connect</span></a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarText" aria-controls="navbarText"
                    aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <?php if (isset($_SESSION['adminname'])): ?>
                    <div class="collapse navbar-collapse" id="navbarText">

                        <ul class="navbar-nav side-nav">
                            <li class="nav-item">
                                <a class="nav-link text-white" style="margin-left: 20px;" href="<?php echo ADMINURL; ?>"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link text-white" href="<?php echo ADMINURL; ?>/admins/admins.php" style="margin-left: 20px;"><i class="fas fa-users"></i> Admins</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link text-white" href="<?php echo ADMINURL; ?>/categories-admins/show-categories.php" style="margin-left: 20px;"><i class="fas fa-tags"></i> Categories</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link text-white" href="<?php echo ADMINURL; ?>/properties-admins/show-properties.php" style="margin-left: 20px;"><i class="fas fa-home"></i> Properties</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link text-white" href="<?php echo ADMINURL; ?>/requests-admins/show-requests.php" style="margin-left: 20px;"><i class="fas fa-envelope"></i> Requests</a>
                            </li>
                        </ul>

                        <ul class="navbar-nav ml-md-auto d-md-flex">
                            <li class="nav-item">
                                <a class="nav-link" href="<?php echo APPURL; ?>" target="_blank"><i class="fas fa-external-link-alt me-2"></i> View Site
                                    <span class="sr-only">(current)</span>
                                </a>
                            </li>
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="fas fa-user me-2"></i> <?php echo $_SESSION['adminname']; ?>
                                </a>
                                <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" href="<?php echo ADMINURL; ?>/admins/logout.php"><i class="fas fa-sign-out-alt me-2"></i> Logout</a>
                                </div>
                            </li>
                        </ul>
                    </div>
                <?php endif; ?>
            </div>
        </nav>