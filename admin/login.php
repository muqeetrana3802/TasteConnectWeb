<?php
session_start();
require_once '../config/db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $username = $_POST['username'];
  $password = $_POST['password'];

  $query = "SELECT id, password FROM admin WHERE username = ?";
  $stmt = $conn->prepare($query);
  $stmt->bind_param("s", $username);
  $stmt->execute();
  $result = $stmt->get_result();

  if ($result->num_rows === 1) {
    $admin = $result->fetch_assoc();
    if (password_verify($password, $admin['password'])) {
      $_SESSION['admin_id'] = $admin['id'];
      header("Location: index.php");
      exit;
    } else {
      $error = "Invalid password.";
    }
  } else {
    $error = "Username not found.";
  }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="shortcut icon" href="../assets/img/logostaste.png" type="image/x-png">
  <title>TasteConnect - Admin Login</title>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="assets/login.css">
</head>

<body>
  <!-- Animated Background -->
  <div class="bg-animation">
    <div class="floating-shape shape-1"></div>
    <div class="floating-shape shape-2"></div>
    <div class="floating-shape shape-3"></div>
    <div class="floating-shape shape-4"></div>
  </div>

  <div class="main-container">
    <div class="login-wrapper animate__animated animate__fadeIn">
      <!-- Left Side - Branding -->
      <div class="brand-section">
        <div class="brand-content">
          <div class="brand-logo">
            <i class="fas fa-utensils"></i>
          </div>
          <h1 class="brand-title">TasteConnect</h1>
          <p class="brand-subtitle">Complete Restaurant Management System</p>

          <ul class="feature-list">
            <li><i class="fas fa-chart-line"></i> Advanced Analytics</li>
            <li><i class="fas fa-users"></i> Vendor Management</li>
            <li><i class="fas fa-shopping-cart"></i> Order Tracking</li>
            <li><i class="fas fa-star"></i> Review System</li>
            <li><i class="fas fa-mobile-alt"></i> Mobile Responsive</li>
          </ul>

          <div class="stats-section">
            <div class="stats-grid">
              <div class="stat-item">
                <h3>500+</h3>
                <p>Vendors</p>
              </div>
              <div class="stat-item">
                <h3>10K+</h3>
                <p>Orders</p>
              </div>
              <div class="stat-item">
                <h3>98%</h3>
                <p>Uptime</p>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Right Side - Login Form -->
      <div class="login-section">
        <div class="login-header">
          <h1>Admin Login</h1>
          <p>Access your dashboard to manage the platform</p>
        </div>

        <form method="post" action="" id="loginForm">
          <div class="form-floating">
            <input type="text" name="username" id="username" class="form-control" placeholder="Username" required>
            <label for="username"><i class="fas fa-user me-2"></i>Username</label>
          </div>

          <div class="form-floating">
            <input type="password" name="password" id="password" class="form-control" placeholder="Password" required>
            <label for="password"><i class="fas fa-lock me-2"></i>Password</label>
          </div>

          <button type="submit" class="btn btn-login" id="loginBtn">
            <span class="btn-text">Login to Dashboard</span>
            <div class="loading">
              <div class="spinner"></div>
            </div>
          </button>

          <div class="forgot-password">
            <a href="forgot_password.php"><i class="fas fa-key me-1"></i>Forgot Password?</a>
          </div>
        </form>

        <div class="divider">
          <span>Quick Actions</span>
        </div>

        <!-- <div class="social-login">
          <a href="#" class="social-btn">
            <i class="fas fa-chart-bar me-2"></i>Reports
          </a>
          <a href="#" class="social-btn">
            <i class="fas fa-cog me-2"></i>Settings
          </a>
        </div> -->
      </div>
    </div>
  </div>

  <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

  <script>
    // Form submission with loading animation
    document.getElementById('loginForm').addEventListener('submit', function(e) {
      const btn = document.getElementById('loginBtn');
      const btnText = btn.querySelector('.btn-text');
      const loading = btn.querySelector('.loading');

      btnText.style.opacity = '0';
      loading.style.display = 'block';
      btn.disabled = true;
    });

    // Password visibility toggle
    const passwordInput = document.getElementById('password');
    passwordInput.addEventListener('input', function() {
      if (this.value.length > 0) {
        this.parentElement.classList.add('has-value');
      } else {
        this.parentElement.classList.remove('has-value');
      }
    });

    // Show error message if exists
    <?php if (isset($error)): ?>
      Swal.fire({
        icon: 'error',
        title: 'Login Failed',
        text: '<?php echo htmlspecialchars($error); ?>',
        confirmButtonColor: '#ff6b35',
        background: '#fff',
        color: '#2c3e50',
        showClass: {
          popup: 'animate__animated animate__shakeX'
        }
      }).then(() => {
        // Reset form button
        const btn = document.getElementById('loginBtn');
        const btnText = btn.querySelector('.btn-text');
        const loading = btn.querySelector('.loading');

        btnText.style.opacity = '1';
        loading.style.display = 'none';
        btn.disabled = false;
      });
    <?php endif; ?>

    // Add focus effects
    document.querySelectorAll('.form-control').forEach(input => {
      input.addEventListener('focus', function() {
        this.parentElement.classList.add('focused');
      });

      input.addEventListener('blur', function() {
        if (this.value === '') {
          this.parentElement.classList.remove('focused');
        }
      });
    });

    // Keyboard shortcuts
    document.addEventListener('keydown', function(e) {
      if (e.ctrlKey && e.key === 'Enter') {
        document.getElementById('loginForm').submit();
      }
    });

    // Welcome animation on load
    window.addEventListener('load', function() {
      setTimeout(() => {
        document.querySelector('.login-wrapper').classList.add('animate__pulse');
      }, 500);
    });
  </script>
</body>

</html>
<?php
if (isset($conn)) {
  $conn->close();
}
?>