<?php require "../layout/header.php" ?>
<?php require "../../config/config.php" ?>
<?php
if (isset($_SESSION['adminname'])) {
  echo "<script>window.location.href='" . ADMINURL . "'</script>";
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
    
    //query
    $login = $conn->prepare("SELECT * FROM admins where email = :email");
    $login->execute([':email' => $sanitized_email]);
    //fetch details
    $fetch = $login->fetch(PDO::FETCH_ASSOC);

    if ($login->rowCount() > 0) {
      if (password_verify($password, $fetch['mypassword'])) {
        $_SESSION['adminname'] = $fetch['name'];
        $_SESSION['email'] = $fetch['email'];
        $_SESSION['admin_id'] = $fetch['id'];

        echo "<script>window.location.href='" . ADMINURL . "'</script>";
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
    body {
        margin: 0;
        padding: 0;
        min-height: 100vh;
        overflow-x: hidden;
    }

    .site-blocks-cover {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        width: 100%;
        height: 100vh;
        background-size: cover;
        background-position: center;
        background-repeat: no-repeat;
        z-index: 0;
    }

    .admin-login-wrapper {
        position: fixed;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        z-index: 10;
        width: 100%;
        max-width: 400px;
        padding: 0 15px;
        box-sizing: border-box;
    }

    /* Add dark overlay for better contrast */
    .site-blocks-cover::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0, 0, 0, 0.5);
        z-index: 1;
    }

    .admin-login-wrapper {
        z-index: 2;
    }

    .glassmorphic-card {
        background: rgba(255, 255, 255, 0.1);
        backdrop-filter: blur(10px);
        -webkit-backdrop-filter: blur(10px);
        border-radius: 15px;
        border: 1px solid rgba(255, 255, 255, 0.2);
        padding: 30px;
        box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.37);
        color: #fff;
        width: 100%;
    }

    .glassmorphic-card h3 {
        color: #fff;
        margin-bottom: 20px;
        text-align: center;
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
        opacity: 1;
    }

    .form-group label {
        color: rgba(255, 255, 255, 0.8);
        margin-bottom: 5px;
        display: block;
        font-size: 0.9rem;
    }

    .btn-admin-login {
        background: #2f89fc;
        border: none;
        padding: 12px;
        border-radius: 8px;
        font-weight: 600;
        letter-spacing: 0.5px;
        transition: all 0.3s ease;
        width: 100%;
    }

    .btn-admin-login:hover {
        background: #1a6cd1;
        transform: translateY(-2px);
    }

    .error-message {
        color: #dc3545;
        background: rgba(220, 53, 69, 0.1);
        border: 1px solid rgba(220, 53, 69, 0.3);
        padding: 10px;
        border-radius: 8px;
        margin-bottom: 20px;
        text-align: center;
    }

    @media (max-width: 768px) {
        .glassmorphic-card {
            padding: 25px;
        }
        .glassmorphic-card h3 {
            font-size: 1.8rem;
        }
        .admin-login-wrapper {
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
        .admin-login-wrapper {
            max-width: 90%;
            padding: 0 10px;
        }
    }
</style>

<div class="site-blocks-cover inner-page-cover overlay" style="background-image: url(<?php echo IMAGESURL; ?>/hero_bg_2.jpg);" data-aos="fade" data-stellar-background-ratio="0.5">
  <div class="container">
    <div class="row align-items-center justify-content-center text-center">
      <div class="col-md-10">
        <!-- <h1 class="mb-2">Admin Login</h1> -->
      </div>
    </div>
  </div>
  <div class="admin-login-wrapper">
      <div class="glassmorphic-card">
          <h3 class="h4 widget-title">Admin Login</h3>

          <?php if (!empty($error)): ?>
              <div class="error-message">
                  <i class="fas fa-exclamation-circle me-2"></i>
                  <?php echo htmlspecialchars($error); ?>
              </div>
          <?php endif; ?>

          <form action="<?php echo get_relative_url($_SERVER['PHP_SELF']); ?>" method="POST" class="form-contact-agent">
            <div class="form-group">
              <label for="email">Email address</label>
              <input type="email" id="email" name="email" class="form-control form-control-glass" value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>" required>
            </div>
            <div class="form-group">
              <label for="password">Password</label>
              <input type="password" id="password" name="password" class="form-control form-control-glass" required>
            </div>
            <div class="form-group mt-4">
              <button type="submit" name="submit" class="btn btn-primary btn-admin-login">
                  <i class="fas fa-sign-in-alt me-2"></i> Sign In
              </button>
            </div>
          </form>
      </div>
  </div>
</div>

<?php include "../layout/footer.php" ?>