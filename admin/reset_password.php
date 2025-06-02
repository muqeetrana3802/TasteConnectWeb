<?php
session_start();
require_once '../config/db.php';

if (!isset($_SESSION['reset_admin_id'])) {
  header("Location: forgot_password.php");
  exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
  $admin_id = $_SESSION['reset_admin_id'];

  $query = "UPDATE admin SET password = ? WHERE id = ?";
  $stmt = $conn->prepare($query);
  $stmt->bind_param("si", $password, $admin_id);
  $stmt->execute();

  session_unset();
  session_destroy();
  header("Location: login.php?success=Password reset successfully");
  exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="shortcut icon" href="../assets/img/logostaste.png" type="image/x-png">
  <title>TasteConnect - Reset Password</title>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
  <style>
    :root {
      --primary-color: #ff6b35;
      --secondary-color: #f8f9fa;
      --dark-color: #2c3e50;
      --light-orange: #fff5f2;
      --success-color: #28a745;
      --danger-color: #dc3545;
      --light-gray: #f8f9fa;
      --medium-gray: #6c757d;
      --dark-gray: #495057;
      --white: #ffffff;
      --shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
      --border-radius: 15px;
      --transition: all 0.3s ease;
    }

    body {
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      background-color: var(--light-gray);
      margin: 0;
      min-height: 100vh;
      display: flex;
      flex-direction: column;
    }

    .container-fluid {
      flex: 1;
      display: flex;
      padding: 0;
    }

    .sidebar {
      width: 250px;
      background: var(--dark-color);
      color: var(--white);
      padding: 2rem;
      height: 100vh;
      position: fixed;
    }

    .sidebar h2 {
      color: var(--primary-color);
      font-size: 1.5rem;
      margin-bottom: 2rem;
    }

    .sidebar p {
      font-size: 0.9rem;
      line-height: 1.6;
    }

    .main-content {
      margin-left: 250px;
      flex: 1;
      display: flex;
      justify-content: center;
      align-items: center;
      padding: 2rem;
    }

    .reset-container {
      background: var(--white);
      border-radius: var(--border-radius);
      box-shadow: var(--shadow);
      padding: 3rem;
      width: 100%;
      max-width: 500px;
    }

    .reset-header {
      text-align: center;
      margin-bottom: 2rem;
    }

    .reset-header h1 {
      color: var(--dark-color);
      font-size: 2rem;
      font-weight: bold;
    }

    .form-control {
      border: 2px solid var(--light-gray);
      border-radius: 25px;
      padding: 0.75rem 2.5rem 0.75rem 1rem;
      transition: var(--transition);
    }

    .form-control:focus {
      border-color: var(--primary-color);
      box-shadow: 0 0 0 0.2rem rgba(255, 107, 53, 0.25);
      outline: none;
    }

    .btn-primary {
      background: var(--primary-color);
      border: none;
      border-radius: 25px;
      padding: 0.75rem;
      width: 100%;
      font-weight: 600;
      transition: var(--transition);
    }

    .btn-primary:hover {
      background: #e55a2e;
      transform: translateY(-2px);
    }

    .back-to-login {
      text-align: center;
      margin-top: 1.5rem;
    }

    .back-to-login a {
      color: var(--primary-color);
      text-decoration: none;
      font-size: 0.95rem;
    }

    .back-to-login a:hover {
      text-decoration: underline;
    }

    .password-toggle {
      position: relative;
    }

    .password-toggle .toggle-icon {
      position: absolute;
      right: 10px;
      top: 50%;
      transform: translateY(-50%);
      cursor: pointer;
      color: var(--medium-gray);
    }

    .password-strength {
      margin-top: 0.5rem;
      font-size: 0.85rem;
      color: var(--medium-gray);
    }

    .progress {
      height: 5px;
      margin-top: 0.5rem;
    }

    footer {
      background: var(--dark-color);
      color: var(--white);
      text-align: center;
      padding: 1rem;
      font-size: 0.9rem;
    }

    .requirements {
      font-size: 0.85rem;
      color: var(--medium-gray);
      margin-top: 0.5rem;
    }

    .requirements i {
      margin-right: 5px;
    }

    .requirements .met {
      color: var(--success-color);
    }

    .requirements .not-met {
      color: var(--danger-color);
    }
  </style>
</head>

<body>
  <div class="container-fluid">
    <div class="sidebar">
      <h2>TasteConnect</h2>
      <p>Securely reset your password to regain access to your FoodHub admin account. Ensure your new password is strong and unique.</p>
    </div>
    <div class="main-content">
      <div class="reset-container">
        <div class="reset-header">
          <h1>Reset Your Password</h1>
        </div>
        <form method="post" action="">
          <div class="mb-3 password-toggle">
            <label for="password" class="form-label">New Password</label>
            <input type="password" name="password" id="password" class="form-control" required>
            <i class="fas fa-eye toggle-icon" id="togglePassword"></i>
          </div>
          <div class="password-strength" id="strengthText">Password Strength: Weak</div>
          <div class="progress">
            <div id="strengthBar" class="progress-bar" role="progressbar" style="width: 0%;"></div>
          </div>
          <div class="requirements">
            <div id="lengthReq"><i class="fas fa-circle not-met"></i> At least 8 characters</div>
            <div id="upperReq"><i class="fas fa-circle not-met"></i> At least one uppercase letter</div>
            <div id="numberReq"><i class="fas fa-circle not-met"></i> At least one number</div>
            <div id="specialReq"><i class="fas fa-circle not-met"></i> At least one special character</div>
          </div>
          <div class="mb-3 password-toggle">
            <label for="confirm_password" class="form-label">Confirm Password</label>
            <input type="password" name="confirm_password" id="confirm_password" class="form-control" required>
            <i class="fas fa-eye toggle-icon" id="toggleConfirmPassword"></i>
          </div>
          <button type="submit" class="btn btn-primary">Reset Password</button>
          <div class="back-to-login">
            <a href="login.php">Back to Login</a>
          </div>
        </form>
      </div>
    </div>
  </div>
  <footer>
    &copy; 2025 FoodHub. All rights reserved. | <a href="#" style="color: var(--primary-color); text-decoration: none;">Privacy Policy</a> | <a href="#" style="color: var(--primary-color); text-decoration: none;">Terms of Service</a>
  </footer>

  <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script>
    document.querySelector('form').addEventListener('submit', function(e) {
      const password = document.getElementById('password').value;
      const confirmPassword = document.getElementById('confirm_password').value;
      if (password !== confirmPassword) {
        e.preventDefault();
        Swal.fire({
          icon: 'error',
          title: 'Error',
          text: 'Passwords do not match.',
          confirmButtonColor: '#ff6b35'
        });
      }
    });

    // Toggle password visibility
    function togglePasswordVisibility(inputId, toggleId) {
      const input = document.getElementById(inputId);
      const toggle = document.getElementById(toggleId);
      toggle.addEventListener('click', function() {
        const type = input.getAttribute('type') === 'password' ? 'text' : 'password';
        input.setAttribute('type', type);
        this.classList.toggle('fa-eye');
        this.classList.toggle('fa-eye-slash');
      });
    }

    togglePasswordVisibility('password', 'togglePassword');
    togglePasswordVisibility('confirm_password', 'toggleConfirmPassword');

    // Password strength checker
    document.getElementById('password').addEventListener('input', function() {
      const password = this.value;
      const strengthBar = document.getElementById('strengthBar');
      const strengthText = document.getElementById('strengthText');
      const lengthReq = document.getElementById('lengthReq');
      const upperReq = document.getElementById('upperReq');
      const numberReq = document.getElementById('numberReq');
      const specialReq = document.getElementById('specialReq');

      let strength = 0;

      // Check requirements
      if (password.length >= 8) {
        strength += 25;
        lengthReq.classList.remove('not-met');
        lengthReq.classList.add('met');
        lengthReq.querySelector('i').classList.replace('fa-circle', 'fa-check-circle');
      } else {
        lengthReq.classList.remove('met');
        lengthReq.classList.add('not-met');
        lengthReq.querySelector('i').classList.replace('fa-check-circle', 'fa-circle');
      }

      if (/[A-Z]/.test(password)) {
        strength += 25;
        upperReq.classList.remove('not-met');
        upperReq.classList.add('met');
        upperReq.querySelector('i').classList.replace('fa-circle', 'fa-check-circle');
      } else {
        upperReq.classList.remove('met');
        upperReq.classList.add('not-met');
        upperReq.querySelector('i').classList.replace('fa-check-circle', 'fa-circle');
      }

      if (/[0-9]/.test(password)) {
        strength += 25;
        numberReq.classList.remove('not-met');
        numberReq.classList.add('met');
        numberReq.querySelector('i').classList.replace('fa-circle', 'fa-check-circle');
      } else {
        numberReq.classList.remove('met');
        numberReq.classList.add('not-met');
        numberReq.querySelector('i').classList.replace('fa-check-circle', 'fa-circle');
      }

      if (/[^A-Za-z0-9]/.test(password)) {
        strength += 25;
        specialReq.classList.remove('not-met');
        specialReq.classList.add('met');
        specialReq.querySelector('i').classList.replace('fa-circle', 'fa-check-circle');
      } else {
        specialReq.classList.remove('met');
        specialReq.classList.add('not-met');
        specialReq.querySelector('i').classList.replace('fa-check-circle', 'fa-circle');
      }

      // Update strength bar and text
      strengthBar.style.width = strength + '%';
      if (strength <= 25) {
        strengthBar.classList.remove('bg-warning', 'bg-success');
        strengthBar.classList.add('bg-danger');
        strengthText.textContent = 'Password Strength: Weak';
      } else if (strength <= 50) {
        strengthBar.classList.remove('bg-danger', 'bg-success');
        strengthBar.classList.add('bg-warning');
        strengthText.textContent = 'Password Strength: Fair';
      } else if (strength <= 75) {
        strengthBar.classList.remove('bg-danger', 'bg-success');
        strengthBar.classList.add('bg-warning');
        strengthText.textContent = 'Password Strength: Good';
      } else {
        strengthBar.classList.remove('bg-danger', 'bg-warning');
        strengthBar.classList.add('bg-success');
        strengthText.textContent = 'Password Strength: Strong';
      }
    });
  </script>
</body>

</html>
<?php $conn->close(); ?>