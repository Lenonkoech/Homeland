<?php
require "../includes/header.php";
require "../config/config.php";

if (isset($_SESSION['username'])) {
  echo "<script>window.location.href='" . APPURL . "'</script>";
  exit();
}

$error = ''; // Initialize error variable

if (isset($_POST['submit'])) {

    if (empty($_POST['email']) or empty($_POST['password'])) {
      $error = 'Fill in all fields';
    } else {
      $email = $_POST["email"];
      $password = $_POST["password"];

      // Sanitize email input
      $sanitized_email = filter_var($email, FILTER_SANITIZE_EMAIL);

      $login = $conn->prepare("SELECT * FROM users where email = :email");
      $login->execute([':email' => $sanitized_email]);

      $fetch = $login->fetch(PDO::FETCH_ASSOC);

      if ($login->rowCount() > 0) {
        if (password_verify($password, $fetch['mypassword'])) {
          $_SESSION['username'] = $fetch['username'];
          $_SESSION['email'] = $fetch['email'];
          $_SESSION['user_id'] = $fetch['id'];
          echo "<script>window.location.href='" . APPURL . "'</script>";
          exit();
        } else {
          $error = 'Wrong email or password';
        }
      } else {
        $error = 'Wrong email or password';
      }
    }
}

?>

<style>
    /* Custom styles for the glassmorphic effect and centering */
    .login-form-wrapper {
        position: absolute; /* Position over other content */
        top: 50%; /* Center vertically relative to positioned parent */
        left: 50%; /* Center horizontally relative to positioned parent */
        transform: translate(-50%, -50%); /* Adjust for the element's own size */
        z-index: 10; /* Ensure it's above the background and hero overlay */
        width: 100%; /* Allow max-width to work */
        max-width: 400px; /* Max width for the form card */
        padding: 0 15px; /* Add some horizontal padding for small screens */
        box-sizing: border-box; /* Include padding in width */
    }

    .glassmorphic-card {
        background: rgba(255, 255, 255, 0.1);
        backdrop-filter: blur(10px);
        -webkit-backdrop-filter: blur(10px); /* Safari support */
        border-radius: 15px;
        border: 1px solid rgba(255, 255, 255, 0.2);
        padding: 30px;
        box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.37);
        color: #fff; /* Text color for contrast */
        width: 100%; /* Make it fill its container wrapper */
    }

    .glassmorphic-card h3 {
        color: #fff; /* Make heading white */
        margin-bottom: 20px; /* Add space below heading */
        text-align: center; /* Center the heading */
    }

    .form-control-glass {
        background: rgba(255, 255, 255, 0.1);
        border: 1px solid rgba(255, 255, 255, 0.3);
        color: #fff;
        padding: 12px 15px;
        border-radius: 8px;
        margin-bottom: 15px;
        transition: all 0.3s ease;
    }

    .form-control-glass:focus {
        background: rgba(255, 255, 255, 0.2);
        border-color: rgba(255, 255, 255, 0.5);
        box-shadow: none;
        color: #fff;
    }

    .form-control-glass::placeholder {
        color: rgba(255, 255, 255, 0.6);
        opacity: 1; /* Ensure placeholder is visible */
    }

     /* Style for form labels to be visible */
    .form-group label {
        color: rgba(255, 255, 255, 0.8);
        margin-bottom: 5px;
        display: block; /* Make label a block element */
        font-size: 0.9rem;
    }

    .btn-login {
        background: #2f89fc; /* Use primary site color */
        border: none;
        padding: 12px;
        border-radius: 8px;
        font-weight: 600;
        letter-spacing: 0.5px;
        transition: all 0.3s ease;
        width: 100%; /* Full width button */
    }

    .btn-login:hover {
        background: #1a6cd1;
        transform: translateY(-2px);
    }

    .register-link {
        text-align: center;
        margin-top: 15px;
        font-size: 0.95rem;
        color: rgba(255, 255, 255, 0.8);
    }

    .register-link a {
        color: #2f89fc; /* Use primary site color */
        text-decoration: none;
        font-weight: 600;
    }

    .register-link a:hover {
        text-decoration: underline;
    }

    .error-message {
        color: #dc3545; /* Bootstrap danger color */
        background: rgba(220, 53, 69, 0.1);
        border: 1px solid rgba(220, 53, 69, 0.3);
        padding: 10px;
        border-radius: 8px;
        margin-bottom: 20px;
        text-align: center;
    }

    /* The hero section needs relative positioning for the absolute positioning of the form wrapper to work correctly */
    .site-blocks-cover {
       position: relative; /* Ensure this is set */
    }

    /* Responsive adjustments */
     @media (max-width: 768px) {
          .glassmorphic-card {
             padding: 25px;
          }
          .glassmorphic-card h3 {
              font-size: 1.8rem;
          }
          /* Adjust positioning for smaller screens if needed */
           .login-form-wrapper {
               max-width: 350px;
           }
     }

     @media (max-width: 576px) {
         .glassmorphic-card {
             padding: 20px;
         }
         .glassmorphic-card h3 {
             font-size: 1.5rem;
         }
         /* Adjust positioning for very small screens */
          .login-form-wrapper {
              max-width: 90%; /* Allow it to take more width */
              padding: 0 10px; /* Ensure padding is sufficient */
          }
     }

</style>

<div class="site-blocks-cover inner-page-cover overlay" style="background-image: url(<?php echo APPURL; ?>images/hero_bg_2.jpg);" data-aos="fade" data-stellar-background-ratio="0.5">
  <div class="container">
    <div class="row align-items-center justify-content-center text-center">
      <div class="col-md-10">
        <!-- <h1 class="mb-2">Log In</h1> -->
      </div>
    </div>
  </div>
  <!-- Login form wrapper positioned absolutely over the hero section -->
  <div class="login-form-wrapper">
      <div class="glassmorphic-card"> <!-- Apply glassmorphic styles here -->

          <h3 class="h4 widget-title">Login</h3>

          <?php if (!empty($error)): ?>
              <div class="error-message">
                  <i class="fas fa-exclamation-circle me-2"></i>
                  <?php echo htmlspecialchars($error); // Sanitize error output ?>
              </div>
          <?php endif; ?>

          <form action="<?php echo APPURL; ?>auth/login.php" method="POST" class="form-contact-agent">
            <div class="form-group">
              <label for="email">Email address</label>
              <input type="email" id="email" name="email" class="form-control form-control-glass" value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>" required>
            </div>
            <div class="form-group">
              <label for="password">Password</label>
              <input type="password" id="password" name="password" class="form-control form-control-glass" required>
            </div>
            <div class="form-group mt-4"> <!-- Added margin top for button -->
              <button type="submit" name="submit" class="btn btn-success btn-login" value="Login">
                  <i class="fas fa-sign-in-alt me-2"></i> Sign In
              </button>
            </div>
             <div class="register-link">
                  Don't have an account? <a href="<?php echo APPURL; ?>auth/register.php">Register here</a>
             </div>
          </form>
      </div>
  </div>
</div>

<?php require "../includes/footer.php"; ?>