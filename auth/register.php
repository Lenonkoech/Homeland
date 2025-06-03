<?php require "../includes/header.php" ?>
<?php require "../config/config.php" ?>
<?php
if (isset($_SESSION['username'])) {
  echo "<script>window.location.href='" . APPURL . "'</script>";
}

$error = ''; // Initialize error variable

if (isset($_POST['submit'])) {
  if (empty($_POST['username']) or empty($_POST['email']) or empty($_POST['password'])) {
    $error = 'Fill in all fields';
  } else {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Sanitize email input
    $sanitized_email = filter_var($email, FILTER_SANITIZE_EMAIL);

    // Check if email already exists
    $check = $conn->prepare("SELECT * FROM users WHERE email = :email");
    $check->execute([':email' => $sanitized_email]);
    
    if ($check->rowCount() > 0) {
      $error = 'Email already exists';
    } else {
      $insert = $conn->prepare("INSERT into users(username,email,mypassword) values (:username,:email,:mypassword)");
      $insert->execute([
        ':username' => $username,
        ':email' => $sanitized_email,
        ':mypassword' => password_hash($password, PASSWORD_DEFAULT)
      ]);

      echo "<script>window.location.href='" . APPURL . "'</script>";
    }
  }
}
?>

<style>
    /* Custom styles for the glassmorphic effect and centering */
    .register-form-wrapper {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        z-index: 10;
        width: 100%;
        max-width: 400px;
        padding: 0 15px;
        box-sizing: border-box;
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

    .btn-register {
        background: #2f89fc;
        border: none;
        padding: 12px;
        border-radius: 8px;
        font-weight: 600;
        letter-spacing: 0.5px;
        transition: all 0.3s ease;
        width: 100%;
    }

    .btn-register:hover {
        background: #1a6cd1;
        transform: translateY(-2px);
    }

    .login-link {
        text-align: center;
        margin-top: 15px;
        font-size: 0.95rem;
        color: rgba(255, 255, 255, 0.8);
    }

    .login-link a {
        color: #2f89fc;
        text-decoration: none;
        font-weight: 600;
    }

    .login-link a:hover {
        text-decoration: underline;
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

    .site-blocks-cover {
        position: relative;
    }

    @media (max-width: 768px) {
        .glassmorphic-card {
            padding: 25px;
        }
        .glassmorphic-card h3 {
            font-size: 1.8rem;
        }
        .register-form-wrapper {
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
        .register-form-wrapper {
            max-width: 90%;
            padding: 0 10px;
        }
    }
</style>

<div class="site-blocks-cover inner-page-cover overlay" style="background-image: url(<?php echo APPURL; ?>images/hero_bg_2.jpg);" data-aos="fade" data-stellar-background-ratio="0.5">
  <div class="container">
    <div class="row align-items-center justify-content-center text-center">
      <div class="col-md-10">
        <!-- <h1 class="mb-2">Register</h1> -->
      </div>
    </div>
  </div>
  <div class="register-form-wrapper">
      <div class="glassmorphic-card">
          <h3 class="h4 widget-title">Register</h3>

          <?php if (!empty($error)): ?>
              <div class="error-message">
                  <i class="fas fa-exclamation-circle me-2"></i>
                  <?php echo htmlspecialchars($error); ?>
              </div>
          <?php endif; ?>

          <form action="register.php" method="POST" class="form-contact-agent">
            <div class="form-group">
              <label for="username">Username</label>
              <input type="text" id="username" name="username" class="form-control form-control-glass" value="<?php echo isset($_POST['username']) ? htmlspecialchars($_POST['username']) : ''; ?>" required>
            </div>
            <div class="form-group">
              <label for="email">Email address</label>
              <input type="email" id="email" name="email" class="form-control form-control-glass" value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>" required>
            </div>
            <div class="form-group">
              <label for="password">Password</label>
              <input type="password" id="password" name="password" class="form-control form-control-glass" required>
            </div>
            <div class="form-group mt-4">
              <button type="submit" name="submit" class="btn btn-success btn-register">
                  <i class="fas fa-user-plus me-2"></i> Register
              </button>
            </div>
            <div class="login-link">
                Already have an account? <a href="<?php echo APPURL; ?>auth/login.php">Login here</a>
            </div>
          </form>
      </div>
  </div>
</div>

<?php require "../includes/footer.php" ?>