<?php
session_start();
define("APPURL", 'http://localhost/homeland/');
define("IMAGESURL", 'http://localhost/homeland/admin-panel/properties-admins');
require dirname(dirname(__FILE__)) . '/config/config.php';
require_once dirname(dirname(__FILE__)) . '/includes/functions.php';

$select = $conn->query("SELECT * from categories");
$select->execute();
$categories = $select->fetchAll(PDO::FETCH_OBJ);

// Get unread notification count if user is logged in
$unread_count = 0;
if (isset($_SESSION['user_id'])) {
    $unread_count = getUnreadNotificationCount($_SESSION['user_id']);
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <title>Homeland</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

  <link rel="stylesheet"
    href="https://fonts.googleapis.com/css?family=Nunito+Sans:200,300,400,700,900|Roboto+Mono:300,400,500">
  <link rel="stylesheet" href="fonts/icomoon/style.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

  <link rel="stylesheet" href="<?php echo APPURL; ?>css/bootstrap.min.css">
  <link rel="stylesheet" href="<?php echo APPURL; ?>css/magnific-popup.css">
  <link rel="stylesheet" href="<?php echo APPURL; ?>css/jquery-ui.css">
  <link rel="stylesheet" href="<?php echo APPURL; ?>css/owl.carousel.min.css">
  <link rel="stylesheet" href="<?php echo APPURL; ?>css/owl.theme.default.min.css">
  <link rel="stylesheet" href="<?php echo APPURL; ?>css/bootstrap-datepicker.css">
  <link rel="stylesheet" href="<?php echo APPURL; ?>css/mediaelementplayer.css">
  <link rel="stylesheet" href="<?php echo APPURL; ?>css/animate.css">
  <link rel="stylesheet" href="<?php echo APPURL; ?>fonts/flaticon/font/flaticon.css">
  <link rel="stylesheet" href="<?php echo APPURL; ?>css/fl-bigmug-line.css">


  <link rel="stylesheet" href="<?php echo APPURL; ?>css/aos.css">

  <link rel="stylesheet" href="<?php echo APPURL; ?>css/style.css">

  <style>
    .notification-badge {
      position: absolute;
      top: 50%;
      right: 20px;
      transform: translateY(-50%);
      padding: 2px 6px;
      border-radius: 12px;
      background: #dc3545;
      color: white;
      font-size: 0.7rem;
      display: none;
      min-width: 18px;
      height: 18px;
      line-height: 14px;
      text-align: center;
      font-weight: bold;
    }
    .notification-badge.show {
      display: inline-block;
    }
    .dropdown-item {
      position: relative;
      padding-right: 35px;
    }
  </style>
</head>

<body>

  <div class="site-loader"></div>

  <div class="site-wrap">

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
      <div class="container">
        <a class="navbar-brand" href="<?php echo APPURL; ?>">
          <strong class=" h4 h2-lg">Qejani<span class="text-danger">Connect</span></strong>
        </a>

        <!-- Hamburger menu button -->
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavDropdown"
          aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>

        <!-- Nav links -->
        <div class="collapse navbar-collapse" id="navbarNavDropdown">
          <ul class="navbar-nav d-flex align-items-center mx-n2">
            <li class="nav-item active">
              <a class="nav-link" href="<?php echo APPURL; ?>index.php">Home</a>
            </li>
            <li class="nav-item"><a class="nav-link" href="<?php echo APPURL; ?>sale.php?type=sale">Buy</a></li>
            <li class="nav-item"><a class="nav-link" href="<?php echo APPURL; ?>rent.php?type=rent">Rent</a></li>

            <!-- Categories dropdown -->
            <li class="nav-item dropdown">
              <a class="nav-link dropdown-toggle" id="navbarDropdownMenuLink" role="button"
                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                Properties
              </a>
              <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                <?php foreach ($categories as $category): ?>
                  <a class="dropdown-item"
                    href="<?php echo APPURL; ?>properties.php?home_type=<?php echo str_replace(' ', '-', $category->name); ?>">
                    <?php echo $category->name; ?>
                  </a>
                <?php endforeach; ?>
              </div>
            </li>

            <li class="nav-item"><a class="nav-link" href="<?php echo APPURL; ?>about.php">About</a></li>
            <li class="nav-item"><a class="nav-link" href="<?php echo APPURL; ?>contact.php">Contact</a></li>

            <?php if (isset($_SESSION['username'])): ?>
              <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" id="userDropdown" role="button"
                  data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                  <?php echo $_SESSION['username']; ?>
                </a>
                <div class="dropdown-menu" aria-labelledby="userDropdown">
                  <a class="dropdown-item" href="<?php echo APPURL; ?>user/favorites.php">Favorites</a>
                  <a class="dropdown-item" href="<?php echo APPURL; ?>user/requests.php">Requests</a>
                  <a class="dropdown-item" href="<?php echo APPURL; ?>features/notifications/view-notifications.php">
                    Notifications
                    <span class="notification-badge <?php echo $unread_count > 0 ? 'show' : ''; ?>" id="notification-count">
                      <?php echo $unread_count; ?>
                    </span>
                  </a>
                  <a class="dropdown-item" href="<?php echo APPURL; ?>auth/logout.php">Logout</a>
                </div>
              </li>
            <?php else: ?>
              <li class="nav-item"><a class="nav-link" href="<?php echo APPURL; ?>auth/login.php">Login</a></li>
              <li class="nav-item"><a class="nav-link" href="<?php echo APPURL; ?>auth/register.php">Register</a></li>
            <?php endif; ?>
          </ul>
        </div>
      </div>
    </nav>
  </div>

  <script>
  function updateNotificationCount() {
    fetch('<?php echo APPURL; ?>features/notifications/get-unread-count.php')
    .then(response => response.json())
    .then(data => {
      const countElement = document.getElementById('notification-count');
      if (countElement) {
        countElement.textContent = data.count;
        if (data.count === 0) {
          countElement.classList.remove('show');
        } else {
          countElement.classList.add('show');
        }
      }
    })
    .catch(error => console.error('Error:', error));
  }

  // Update notification count every 30 seconds
  setInterval(updateNotificationCount, 30000);

  // Update notification count when page loads
  document.addEventListener('DOMContentLoaded', function() {
    updateNotificationCount();
  });
  </script>
