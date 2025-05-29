<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Vendor Login - FoodieHub</title>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
  <style>
    :root {
      --primary-color: #ff6b35;
      --secondary-color: #ffa726;
      --accent-color: #4caf50;
      --dark-color: #2c3e50;
      --light-color: #ecf0f1;
      --success-color: #27ae60;
      --warning-color: #f39c12;
      --danger-color: #e74c3c;
      --info-color: #3498db;
      --white: #ffffff;
      --gray-100: #f8f9fa;
      --gray-200: #e9ecef;
      --gray-300: #dee2e6;
      --gray-400: #ced4da;
      --gray-500: #adb5bd;
      --gray-800: #495057;
      --shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
      --shadow-hover: 0 15px 35px rgba(0, 0, 0, 0.15);
      --border-radius: 12px;
      --transition: all 0.3s ease;
    }

    body {
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      line-height: 1.6;
      color: var(--dark-color);
      background: var(--gray-100);
      height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      margin: 0;
    }

    /* Navbar Styles */
    .navbar {
      background: var(--white) !important;
      box-shadow: var(--shadow);
      padding: 1rem 0;
      transition: var(--transition);
    }

    .navbar-brand {
      font-weight: 700;
      font-size: 1.8rem;
      color: var(--primary-color) !important;
    }

    /* Login Card */
    .login-card {
      background: var(--white);
      border-radius: var(--border-radius);
      box-shadow: var(--shadow);
      padding: 2rem;
      width: 100%;
      max-width: 400px;
      transition: var(--transition);
    }

    .login-card:hover {
      transform: translateY(-5px);
      box-shadow: var(--shadow-hover);
    }

    .login-card h2 {
      font-size: 1.8rem;
      font-weight: 700;
      color: var(--dark-color);
      margin-bottom: 1.5rem;
      text-align: center;
    }

    .form-control {
      border-radius: var(--border-radius);
      border: 1px solid var(--gray-300);
      padding: 0.75rem;
      transition: var(--transition);
    }

    .form-control:focus {
      border-color: var(--primary-color);
      box-shadow: 0 0 0 0.2rem rgba(255, 107, 53, 0.25);
    }

    .btn-login {
      background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
      border: none;
      color: var(--white);
      padding: 0.75rem;
      border-radius: 25px;
      font-weight: 500;
      width: 100%;
      transition: var(--transition);
    }

    .btn-login:hover {
      transform: translateY(-2px);
      box-shadow: var(--shadow);
      color: var(--white);
    }

    .error-message {
      color: var(--danger-color);
      font-size: 0.9rem;
      margin-top: 0.5rem;
      display: none;
      text-align: center;
    }

    /* Links */
    .register-link,
    .home-link {
      color: var(--primary-color);
      text-decoration: none;
      font-weight: 500;
      transition: var(--transition);
    }

    .register-link:hover,
    .home-link:hover {
      color: var(--secondary-color);
      text-decoration: underline;
    }

    /* Fade in animation */
    .fade-in {
      opacity: 0;
      transform: translateY(30px);
      transition: all 0.6s ease;
    }

    .fade-in.visible {
      opacity: 1;
      transform: translateY(0);
    }

    /* Responsive */
    @media (max-width: 576px) {
      .login-card {
        margin: 1rem;
        padding: 1.5rem;
      }
    }
  </style>
</head>

<body>
  <!-- Login Form -->
  <div class="login-card fade-in">
    <h2><i class="fas fa-utensils me-2"></i>Vendor Login</h2>
    <div id="errorMessage" class="error-message">Invalid email or password</div>
    <div class="mb-3">
      <label for="email" class="form-label">Email</label>
      <input type="email" class="form-control" id="email" placeholder="Enter your email" required>
    </div>
    <div class="mb-3">
      <label for="password" class="form-label">Password</label>
      <input type="password" class="form-control" id="password" placeholder="Enter your password" required>
    </div>
    <button class="btn btn-login" onclick="handleLogin()">Login</button>
    <div class="text-center mt-3">
      <p>Don't have an account? <a href="add-vendor.php" class="register-link">Register here</a></p>
      <p><a href="index.php" class="home-link">Back to Home</a></p>
    </div>
  </div>

  <!-- Bootstrap JS -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/js/bootstrap.bundle.min.js"></script>

  <!-- Custom JavaScript -->
  <script>
    // Sample credentials (in real app, this would be handled by a backend)
    const validCredentials = {
      email: "vendor@foodiehub.com",
      password: "password123"
    };

    // Handle login
    window.handleLogin = function() {
      const email = document.getElementById('email').value.trim();
      const password = document.getElementById('password').value.trim();
      const errorMessage = document.getElementById('errorMessage');

      if (email === validCredentials.email && password === validCredentials.password) {
        // In real app, set session or token here
        localStorage.setItem('vendorLoggedIn', 'true');
        window.location.href = 'vendor.php';
      } else {
        errorMessage.style.display = 'block';
        setTimeout(() => {
          errorMessage.style.display = 'none';
        }, 3000);
      }
    };

    // Initialize page
    document.addEventListener('DOMContentLoaded', function() {
      // Fade-in animation
      const loginCard = document.querySelector('.login-card');
      loginCard.classList.add('visible');

      // Allow login on Enter key
      document.getElementById('password').addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
          handleLogin();
        }
      });
    });
  </script>
</body>

</html>