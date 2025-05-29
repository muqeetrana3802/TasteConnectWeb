<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>FoodHub - Sign In</title>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
  <style>
    :root {
      --primary-color: #ff6b35;
      --secondary-color: #f8f9fa;
      --dark-color: #2c3e50;
      --light-orange: #fff5f2;
      --success-color: #28a745;
      --danger-color: #dc3545;
      --warning-color: #ffc107;
      --info-color: #17a2b8;
      --light-gray: #f8f9fa;
      --medium-gray: #6c757d;
      --dark-gray: #495057;
      --white: #ffffff;
      --shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
      --border-radius: 15px;
      --transition: all 0.3s ease;
    }

    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    body {
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      background: linear-gradient(135deg, var(--light-orange), var(--secondary-color));
      min-height: 100vh;
      display: flex;
      align-items: center;
      padding: 2rem 0;
    }

    .login-container {
      background: var(--white);
      border-radius: var(--border-radius);
      box-shadow: var(--shadow);
      overflow: hidden;
      width: 100%;
      max-width: 900px;
      margin: 0 auto;
    }

    .login-left {
      background: linear-gradient(135deg, var(--primary-color), #ff8c42);
      color: var(--white);
      padding: 3rem;
      display: flex;
      flex-direction: column;
      justify-content: center;
      align-items: center;
      text-align: center;
      position: relative;
      overflow: hidden;
      min-height: 100%;
    }

    .login-left::before {
      content: '';
      position: absolute;
      top: -50%;
      left: -50%;
      width: 200%;
      height: 200%;
      background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><circle cx="20" cy="20" r="2" fill="rgba(255,255,255,0.1)"/><circle cx="80" cy="80" r="3" fill="rgba(255,255,255,0.1)"/><circle cx="40" cy="60" r="1.5" fill="rgba(255,255,255,0.1)"/><circle cx="60" cy="30" r="2.5" fill="rgba(255,255,255,0.1)"/></svg>');
      animation: float 20s infinite linear;
    }

    @keyframes float {
      0% {
        transform: rotate(0deg);
      }

      100% {
        transform: rotate(360deg);
      }
    }

    .brand-logo {
      font-size: 3.5rem;
      margin-bottom: 1rem;
      z-index: 1;
      animation: pulse 2s infinite;
    }

    @keyframes pulse {

      0%,
      100% {
        transform: scale(1);
      }

      50% {
        transform: scale(1.05);
      }
    }

    .brand-name {
      font-size: 2.5rem;
      font-weight: bold;
      margin-bottom: 1rem;
      z-index: 1;
    }

    .welcome-back {
      font-size: 1.3rem;
      opacity: 0.9;
      z-index: 1;
      margin-bottom: 1.5rem;
    }

    .stats-container {
      z-index: 1;
      margin-top: 2rem;
    }

    .stat-item {
      margin-bottom: 1rem;
      display: flex;
      align-items: center;
      justify-content: center;
    }

    .stat-item i {
      margin-right: 0.8rem;
      font-size: 1.2rem;
    }

    .login-right {
      padding: 3rem;
    }

    .login-title {
      color: var(--dark-color);
      font-weight: bold;
      margin-bottom: 0.5rem;
      font-size: 2rem;
    }

    .login-subtitle {
      color: var(--medium-gray);
      margin-bottom: 2rem;
    }

    .form-group {
      margin-bottom: 1.5rem;
    }

    .form-label {
      color: var(--dark-gray);
      font-weight: 600;
      margin-bottom: 0.5rem;
    }

    .form-control {
      border: 2px solid var(--light-gray);
      border-radius: 10px;
      padding: 12px 16px;
      font-size: 1rem;
      transition: var(--transition);
      background-color: var(--light-gray);
    }

    .form-control:focus {
      border-color: var(--primary-color);
      box-shadow: 0 0 0 0.2rem rgba(255, 107, 53, 0.25);
      background-color: var(--white);
    }

    .form-control.is-invalid {
      border-color: var(--danger-color);
    }

    .form-control.is-valid {
      border-color: var(--success-color);
    }

    .input-group {
      position: relative;
    }

    .input-group-text {
      background-color: var(--light-gray);
      border: 2px solid var(--light-gray);
      border-right: none;
      color: var(--medium-gray);
      border-radius: 10px 0 0 10px;
    }

    .input-group .form-control {
      border-left: none;
      border-radius: 0 10px 10px 0;
    }

    .input-group:focus-within .input-group-text {
      border-color: var(--primary-color);
      background-color: var(--white);
    }

    .password-toggle {
      position: absolute;
      right: 15px;
      top: 50%;
      transform: translateY(-50%);
      cursor: pointer;
      color: var(--medium-gray);
      z-index: 10;
    }

    .btn-primary {
      background: linear-gradient(135deg, var(--primary-color), #ff8c42);
      border: none;
      border-radius: 10px;
      padding: 12px;
      font-weight: 600;
      font-size: 1.1rem;
      transition: var(--transition);
      width: 100%;
    }

    .btn-primary:hover {
      transform: translateY(-2px);
      box-shadow: 0 5px 15px rgba(255, 107, 53, 0.4);
    }

    .form-options {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 1.5rem;
    }

    .form-check-input:checked {
      background-color: var(--primary-color);
      border-color: var(--primary-color);
    }

    .forgot-password {
      color: var(--primary-color);
      text-decoration: none;
      font-size: 0.9rem;
      font-weight: 500;
    }

    .forgot-password:hover {
      text-decoration: underline;
      color: var(--primary-color);
    }

    .divider {
      text-align: center;
      margin: 1.5rem 0;
      position: relative;
      color: var(--medium-gray);
    }

    .divider::before {
      content: '';
      position: absolute;
      top: 50%;
      left: 0;
      right: 0;
      height: 1px;
      background-color: var(--light-gray);
    }

    .divider span {
      background-color: var(--white);
      padding: 0 1rem;
    }

    .social-login {
      display: flex;
      gap: 1rem;
    }

    .btn-social {
      flex: 1;
      padding: 10px;
      border-radius: 10px;
      border: 2px solid var(--light-gray);
      background-color: var(--white);
      transition: var(--transition);
      font-weight: 500;
    }

    .btn-google:hover {
      border-color: #db4437;
      color: #db4437;
      transform: translateY(-1px);
    }

    .btn-facebook:hover {
      border-color: #4267B2;
      color: #4267B2;
      transform: translateY(-1px);
    }

    .signup-link {
      text-align: center;
      margin-top: 2rem;
      color: var(--medium-gray);
    }

    .signup-link a {
      color: var(--primary-color);
      text-decoration: none;
      font-weight: 600;
    }

    .signup-link a:hover {
      text-decoration: underline;
    }

    .invalid-feedback {
      display: block;
      color: var(--danger-color);
      font-size: 0.875rem;
      margin-top: 0.25rem;
    }

    .alert {
      border-radius: 10px;
      border: none;
      padding: 12px 16px;
      margin-bottom: 1.5rem;
    }

    .alert-success {
      background-color: rgba(40, 167, 69, 0.1);
      color: var(--success-color);
    }

    .alert-danger {
      background-color: rgba(220, 53, 69, 0.1);
      color: var(--danger-color);
    }

    @media (max-width: 768px) {
      body {
        padding: 1rem 0;
      }

      .login-container {
        margin: 0 1rem;
      }

      .login-left {
        display: none;
      }

      .login-right {
        padding: 2rem;
      }

      .login-title {
        font-size: 1.5rem;
      }
    }

    @media (max-width: 576px) {
      .social-login {
        flex-direction: column;
      }

      .form-options {
        flex-direction: column;
        gap: 1rem;
        align-items: flex-start;
      }
    }

    .loading-spinner {
      display: none;
      width: 20px;
      height: 20px;
      border: 2px solid transparent;
      border-top: 2px solid var(--white);
      border-radius: 50%;
      animation: spin 1s linear infinite;
      margin-right: 0.5rem;
    }

    @keyframes spin {
      0% {
        transform: rotate(0deg);
      }

      100% {
        transform: rotate(360deg);
      }
    }
  </style>
</head>

<body>
<a href="index.php" class="btn btn-outline-secondary position-absolute top-0 start-0 m-3 z-3">
    <i class="fas fa-arrow-left me-1"></i> Back
  </a>
  <div class="container-fluid">
    <div class="row justify-content-center">
      <div class="col-12 col-lg-10 col-xl-9">
        <div class="login-container row g-0">
          <!-- Left Side - Branding & Welcome -->
          <div class="col-md-5 login-left">
            <div>
              <div class="brand-logo">
                <i class="fas fa-utensils"></i>
              </div>
              <h1 class="brand-name">FoodHub</h1>
              <p class="welcome-back">Welcome back!</p>

              <div class="stats-container">
                <div class="stat-item">
                  <i class="fas fa-users"></i>
                  <span>50,000+ Happy Customers</span>
                </div>
                <div class="stat-item">
                  <i class="fas fa-star"></i>
                  <span>4.8★ Average Rating</span>
                </div>
                <div class="stat-item">
                  <i class="fas fa-clock"></i>
                  <span>30 Min Average Delivery</span>
                </div>
              </div>
            </div>
          </div>

          <!-- Right Side - Login Form -->
          <div class="col-md-7 login-right">
            <h2 class="login-title">Sign In</h2>
            <p class="login-subtitle">Welcome back! Please sign in to your account</p>

            <!-- Alert Messages -->
            <div id="alertContainer"></div>

            <form id="loginForm" novalidate>
              <div class="form-group">
                <label class="form-label">Email Address</label>
                <div class="input-group">
                  <span class="input-group-text">
                    <i class="fas fa-envelope"></i>
                  </span>
                  <input type="email" class="form-control" id="email" name="email" placeholder="Enter your email" required>
                </div>
                <div class="invalid-feedback"></div>
              </div>

              <div class="form-group">
                <label class="form-label">Password</label>
                <div class="input-group position-relative">
                  <span class="input-group-text">
                    <i class="fas fa-lock"></i>
                  </span>
                  <input type="password" class="form-control" id="password" name="password" placeholder="Enter your password" required>
                  <span class="password-toggle" onclick="togglePassword('password', 'toggleIcon')">
                    <i class="fas fa-eye" id="toggleIcon"></i>
                  </span>
                </div>
                <div class="invalid-feedback"></div>
              </div>

              <div class="form-options">
                <div class="form-check">
                  <input class="form-check-input" type="checkbox" id="rememberMe" name="remember_me">
                  <label class="form-check-label" for="rememberMe">
                    Remember me
                  </label>
                </div>
                <a href="#" class="forgot-password" onclick="showForgotPassword()">Forgot password?</a>
              </div>

              <button type="submit" class="btn btn-primary" id="loginBtn">
                <span class="loading-spinner" id="loadingSpinner"></span>
                <i class="fas fa-sign-in-alt me-2" id="loginIcon"></i>
                <span id="loginText">Sign In</span>
              </button>
            </form>

            <div class="divider">
              <span>or sign in with</span>
            </div>

            <div class="social-login">
              <button class="btn btn-social btn-google" onclick="socialLogin('google')">
                <i class="fab fa-google me-2"></i>Google
              </button>
              <button class="btn btn-social btn-facebook" onclick="socialLogin('facebook')">
                <i class="fab fa-facebook-f me-2"></i>Facebook
              </button>
            </div>

            <div class="signup-link">
              Don't have an account? <a href="#" onclick="window.location.href = 'register.php';">Create account</a>

            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Forgot Password Modal -->
  <div class="modal fade" id="forgotPasswordModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header border-0">
          <h5 class="modal-title">Reset Password</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <p class="text-muted">Enter your email address and we'll send you a link to reset your password.</p>
          <form id="forgotPasswordForm">
            <div class="form-group">
              <label class="form-label">Email Address</label>
              <div class="input-group">
                <span class="input-group-text">
                  <i class="fas fa-envelope"></i>
                </span>
                <input type="email" class="form-control" id="resetEmail" placeholder="Enter your email" required>
              </div>
              <div class="invalid-feedback"></div>
            </div>
            <button type="submit" class="btn btn-primary">
              <i class="fas fa-paper-plane me-2"></i>Send Reset Link
            </button>
          </form>
        </div>
      </div>
    </div>
  </div>

  <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>

</body>

</html>