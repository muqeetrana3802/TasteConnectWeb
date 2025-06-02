<?php
session_start();

// Database connection configuration
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "foodiehub";

// Initialize error and success messages
$error = '';
$success = '';

try {
  // Create database connection
  $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
  $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

  // Handle form submission
  if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $first_name = trim($_POST['first_name']);
    $last_name = trim($_POST['last_name']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $password = trim($_POST['password']);
    $confirm_password = trim($_POST['confirm_password']);
    $address = trim($_POST['address'] ?? '');
    $city = trim($_POST['city'] ?? '');
    $postal_code = trim($_POST['postal_code'] ?? '');
    $terms_accepted = isset($_POST['terms_accepted']);

    // Server-side validation
    if (empty($first_name) || empty($last_name) || empty($email) || empty($phone) || empty($password)) {
      $error = "All required fields must be filled.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
      $error = "Invalid email format.";
    } elseif ($password !== $confirm_password) {
      $error = "Passwords do not match.";
    } elseif (strlen($password) < 8) {
      $error = "Password must be at least 8 characters long.";
    } elseif (!$terms_accepted) {
      $error = "You must accept the terms and conditions.";
    } else {
      // Check if email already exists
      $stmt = $conn->prepare("SELECT id FROM users WHERE email = :email");
      $stmt->bindParam(':email', $email);
      $stmt->execute();

      if ($stmt->rowCount() > 0) {
        $error = "Email is already registered.";
      } else {
        // Hash password
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Prepare and execute insert query
        $stmt = $conn->prepare("
                    INSERT INTO users (first_name, last_name, email, phone, password, address, city, postal_code, created_at)
                    VALUES (:first_name, :last_name, :email, :phone, :password, :address, :city, :postal_code, NOW())
                ");
        $stmt->bindParam(':first_name', $first_name);
        $stmt->bindParam(':last_name', $last_name);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':phone', $phone);
        $stmt->bindParam(':password', $hashed_password);
        $stmt->bindParam(':address', $address);
        $stmt->bindParam(':city', $city);
        $stmt->bindParam(':postal_code', $postal_code);

        if ($stmt->execute()) {
          // Set session variables
          $_SESSION['user_id'] = $conn->lastInsertId();
          $_SESSION['first_name'] = $first_name;
          $_SESSION['email'] = $email;

          $success = "Registration successful! Redirecting to dashboard...";
          header("refresh:2;url=index.php"); // Redirect after 2 seconds
        } else {
          $error = "Registration failed. Please try again.";
        }
      }
    }
  }
} catch (PDOException $e) {
  $error = "Connection failed: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="shortcut icon" href="../assets/img/logostaste.png" type="image/x-png">
  <title>TasteConnect - Create Account</title>
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
      padding: 2rem 0;
    }

    .registration-container {
      background: var(--white);
      border-radius: var(--border-radius);
      box-shadow: var(--shadow);
      overflow: hidden;
      width: 100%;
      max-width: 1000px;
      margin: 0 auto;
    }

    .registration-left {
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

    .registration-left::before {
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
      font-size: 3rem;
      margin-bottom: 1rem;
      z-index: 1;
    }

    .brand-name {
      font-size: 2.5rem;
      font-weight: bold;
      margin-bottom: 1rem;
      z-index: 1;
    }

    .brand-tagline {
      font-size: 1.1rem;
      opacity: 0.9;
      z-index: 1;
      margin-bottom: 2rem;
    }

    .features-list {
      text-align: left;
      z-index: 1;
    }

    .features-list li {
      margin-bottom: 0.8rem;
      display: flex;
      align-items: center;
    }

    .features-list i {
      margin-right: 0.8rem;
      color: rgba(255, 255, 255, 0.8);
    }

    .registration-right {
      padding: 3rem;
    }

    .registration-title {
      color: var(--dark-color);
      font-weight: bold;
      margin-bottom: 0.5rem;
      font-size: 2rem;
    }

    .registration-subtitle {
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

    .btn-outline-primary {
      border: 2px solid var(--primary-color);
      color: var(--primary-color);
      border-radius: 10px;
      padding: 10px 20px;
      font-weight: 600;
      transition: var(--transition);
    }

    .btn-outline-primary:hover {
      background-color: var(--primary-color);
      border-color: var(--primary-color);
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

    .password-strength {
      margin-top: 0.5rem;
      font-size: 0.875rem;
    }

    .strength-weak {
      color: var(--danger-color);
    }

    .strength-medium {
      color: var(--warning-color);
    }

    .strength-strong {
      color: var(--success-color);
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
    }

    .btn-facebook:hover {
      border-color: #4267B2;
      color: #4267B2;
    }

    .login-link {
      text-align: center;
      margin-top: 2rem;
      color: var(--medium-gray);
    }

    .login-link a {
      color: var(--primary-color);
      text-decoration: none;
      font-weight: 600;
    }

    .login-link a:hover {
      text-decoration: underline;
    }

    .terms-checkbox {
      margin-bottom: 1.5rem;
    }

    .terms-checkbox .form-check-input:checked {
      background-color: var(--primary-color);
      border-color: var(--primary-color);
    }

    .invalid-feedback {
      display: block;
      color: var(--danger-color);
      font-size: 0.875rem;
      margin-top: 0.25rem;
    }

    .valid-feedback {
      display: block;
      color: var(--success-color);
      font-size: 0.875rem;
      margin-top: 0.25rem;
    }

    .alert {
      margin-bottom: 1.5rem;
    }

    @media (max-width: 768px) {
      body {
        padding: 1rem 0;
      }

      .registration-container {
        margin: 0 1rem;
      }

      .registration-left {
        display: none;
      }

      .registration-right {
        padding: 2rem;
      }

      .registration-title {
        font-size: 1.5rem;
      }
    }

    @media (max-width: 576px) {
      .social-login {
        flex-direction: column;
      }

      .row>.col-md-6 {
        margin-bottom: 1rem;
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
      <div class="col-12 col-lg-11 col-xl-10">
        <div class="registration-container row g-0">
          <!-- Left Side - Branding & Features -->
          <div class="col-md-5 registration-left">
            <div>
              <div class="brand-logo">
                <i class="fas fa-utensils"></i>
              </div>
              <h1 class="brand-name">TasteConnect</h1>
              <p class="brand-tagline">Join thousands of food lovers</p>

              <ul class="features-list list-unstyled">
                <li><i class="fas fa-check-circle"></i> Fast delivery in 30 minutes</li>
                <li><i class="fas fa-check-circle"></i> Wide variety of restaurants</li>
                <li><i class="fas fa-check-circle"></i> Exclusive member discounts</li>
                <li><i class="fas fa-check-circle"></i> 24/7 customer support</li>
                <li><i class="fas fa-check-circle"></i> Track your order in real-time</li>
              </ul>
            </div>
          </div>

          <!-- Right Side - Registration Form -->
          <div class="col-md-7 registration-right">
            <h2 class="registration-title">Create Account</h2>
            <p class="registration-subtitle">Start your food journey with us today</p>

            <?php if ($error): ?>
              <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>
            <?php if ($success): ?>
              <div class="alert alert-success"><?php echo htmlspecialchars($success); ?></div>
            <?php endif; ?>

            <form id="registrationForm" method="POST" novalidate>
              <div class="row">
                <div class="col-md-6">
                  <div class="form-group">
                    <label class="form-label">First Name <span class="text-danger">*</span></label>
                    <div class="input-group">
                      <span class="input-group-text">
                        <i class="fas fa-user"></i>
                      </span>
                      <input type="text" class="form-control" id="firstName" name="first_name" placeholder="Enter first name" value="<?php echo isset($_POST['first_name']) ? htmlspecialchars($_POST['first_name']) : ''; ?>" required>
                    </div>
                    <div class="invalid-feedback"></div>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label class="form-label">Last Name <span class="text-danger">*</span></label>
                    <div class="input-group">
                      <span class="input-group-text">
                        <i class="fas fa-user"></i>
                      </span>
                      <input type="text" class="form-control" id="lastName" name="last_name" placeholder="Enter last name" value="<?php echo isset($_POST['last_name']) ? htmlspecialchars($_POST['last_name']) : ''; ?>" required>
                    </div>
                    <div class="invalid-feedback"></div>
                  </div>
                </div>
              </div>

              <div class="form-group">
                <label class="form-label">Email Address <span class="text-danger">*</span></label>
                <div class="input-group">
                  <span class="input-group-text">
                    <i class="fas fa-envelope"></i>
                  </span>
                  <input type="email" class="form-control" id="email" name="email" placeholder="Enter your email" value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>" required>
                </div>
                <div class="invalid-feedback"></div>
              </div>

              <div class="form-group">
                <label class="form-label">Phone Number <span class="text-danger">*</span></label>
                <div class="input-group">
                  <span class="input-group-text">
                    <i class="fas fa-phone"></i>
                  </span>
                  <input type="tel" class="form-control" id="phone" name="phone" placeholder="Enter phone number" value="<?php echo isset($_POST['phone']) ? htmlspecialchars($_POST['phone']) : ''; ?>" required>
                </div>
                <div class="invalid-feedback"></div>
              </div>

              <div class="row">
                <div class="col-md-6">
                  <div class="form-group">
                    <label class="form-label">Password <span class="text-danger">*</span></label>
                    <div class="input-group position-relative">
                      <span class="input-group-text">
                        <i class="fas fa-lock"></i>
                      </span>
                      <input type="password" class="form-control" id="password" name="password" placeholder="Create password" required>
                      <span class="password-toggle" onclick="togglePassword('password', 'toggleIcon1')">
                        <i class="fas fa-eye" id="toggleIcon1"></i>
                      </span>
                    </div>
                    <div class="password-strength" id="passwordStrength"></div>
                    <div class="invalid-feedback"></div>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label class="form-label">Confirm Password <span class="text-danger">*</span></label>
                    <div class="input-group position-relative">
                      <span class="input-group-text">
                        <i class="fas fa-lock"></i>
                      </span>
                      <input type="password" class="form-control" id="confirmPassword" name="confirm_password" placeholder="Confirm password" required>
                      <span class="password-toggle" onclick="togglePassword('confirmPassword', 'toggleIcon2')">
                        <i class="fas fa-eye" id="toggleIcon2"></i>
                      </span>
                    </div>
                    <div class="invalid-feedback"></div>
                  </div>
                </div>
              </div>

              <div class="form-group">
                <label class="form-label">Address</label>
                <div class="input-group">
                  <span class="input-group-text">
                    <i class="fas fa-map-marker-alt"></i>
                  </span>
                  <input type="text" class="form-control" id="address" name="address" placeholder="Enter your address" value="<?php echo isset($_POST['address']) ? htmlspecialchars($_POST['address']) : ''; ?>">
                </div>
              </div>

              <div class="row">
                <div class="col-md-8">
                  <div class="form-group">
                    <label class="form-label">City</label>
                    <input type="text" class="form-control" id="city" name="city" placeholder="Enter city" value="<?php echo isset($_POST['city']) ? htmlspecialchars($_POST['city']) : ''; ?>">
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group">
                    <label class="form-label">Postal Code</label>
                    <input type="text" class="form-control" id="postalCode" name="postal_code" placeholder="Enter postal code" value="<?php echo isset($_POST['postal_code']) ? htmlspecialchars($_POST['postal_code']) : ''; ?>">
                  </div>
                </div>
              </div>

              <div class="terms-checkbox">
                <div class="form-check">
                  <input class="form-check-input" type="checkbox" id="termsAccepted" name="terms_accepted" <?php echo isset($_POST['terms_accepted']) ? 'checked' : ''; ?> required>
                  <label class="form-check-label" for="termsAccepted">
                    I agree to the <a href="#" class="text-decoration-none">Terms of Service</a> and <a href="#" class="text-decoration-none">Privacy Policy</a> <span class="text-danger">*</span>
                  </label>
                  <div class="invalid-feedback"></div>
                </div>
              </div>

              <button type="submit" class="btn btn-primary">
                <i class="fas fa-user-plus me-2"></i>Create Account
              </button>
            </form>

            <div class="divider">
              <span>or sign up with</span>
            </div>

            <div class="social-login">
              <button class="btn btn-social btn-google">
                <i class="fab fa-google me-2"></i>Google
              </button>
              <button class="btn btn-social btn-facebook">
                <i class="fab fa-facebook-f me-2"></i>Facebook
              </button>
            </div>

            <div class="login-link">
              Already have an account? <a href="login.php">Sign in here</a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
  <script>
    function togglePassword(inputId, iconId) {
      const passwordInput = document.getElementById(inputId);
      const toggleIcon = document.getElementById(iconId);

      if (passwordInput.type === 'password') {
        passwordInput.type = 'text';
        toggleIcon.classList.remove('fa-eye');
        toggleIcon.classList.add('fa-eye-slash');
      } else {
        passwordInput.type = 'password';
        toggleIcon.classList.remove('fa-eye-slash');
        toggleIcon.classList.add('fa-eye');
      }
    }

    function checkPasswordStrength(password) {
      let strength = 0;
      const strengthText = document.getElementById('passwordStrength');

      if (password.length >= 8) strength += 1;
      if (/[a-z]/.test(password)) strength += 1;
      if (/[A-Z]/.test(password)) strength += 1;
      if (/[0-9]/.test(password)) strength += 1;
      if (/[^A-Za-z0-9]/.test(password)) strength += 1;

      switch (strength) {
        case 0:
        case 1:
        case 2:
          strengthText.textContent = 'Weak password';
          strengthText.className = 'password-strength strength-weak';
          break;
        case 3:
        case 4:
          strengthText.textContent = 'Medium password';
          strengthText.className = 'password-strength strength-medium';
          break;
        case 5:
          strengthText.textContent = 'Strong password';
          strengthText.className = 'password-strength strength-strong';
          break;
      }

      return strength;
    }

    // Real-time validation
    document.getElementById('password').addEventListener('input', function() {
      checkPasswordStrength(this.value);
      validatePasswords();
    });

    document.getElementById('confirmPassword').addEventListener('input', validatePasswords);

    function validatePasswords() {
      const password = document.getElementById('password').value;
      const confirmPassword = document.getElementById('confirmPassword').value;
      const confirmInput = document.getElementById('confirmPassword');
      const feedback = confirmInput.nextElementSibling;

      if (confirmPassword && password !== confirmPassword) {
        confirmInput.classList.add('is-invalid');
        confirmInput.classList.remove('is-valid');
        feedback.textContent = 'Passwords do not match';
      } else if (confirmPassword && password === confirmPassword) {
        confirmInput.classList.remove('is-invalid');
        confirmInput.classList.add('is-valid');
        feedback.textContent = '';
      }
    }

    // Email validation
    document.getElementById('email').addEventListener('blur', function() {
      const email = this.value;
      const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
      const feedback = this.nextElementSibling;

      if (email && !emailRegex.test(email)) {
        this.classList.add('is-invalid');
        this.classList.remove('is-valid');
        feedback.textContent = 'Please enter a valid email address';
      } else if (email) {
        this.classList.remove('is-invalid');
        this.classList.add('is-valid');
        feedback.textContent = '';
      }
    });

    // Phone validation
    document.getElementById('phone').addEventListener('input', function() {
      this.value = this.value.replace(/[^\d+\-\(\)\s]/g, '');
    });

    // Form submission
    document.getElementById('registrationForm').addEventListener('submit', function(e) {
      // Allow PHP to handle form submission
      let isValid = true;

      const requiredFields = ['firstName', 'lastName', 'email', 'phone', 'password', 'confirmPassword'];
      requiredFields.forEach(fieldId => {
        const field = document.getElementById(fieldId);
        const feedback = field.closest('.form-group').querySelector('.invalid-feedback');

        if (!field.value.trim()) {
          field.classList.add('is-invalid');
          feedback.textContent = 'This field is required';
          isValid = false;
        } else {
          field.classList.remove('is-invalid');
          feedback.textContent = '';
        }
      });

      const termsCheckbox = document.getElementById('termsAccepted');
      const termsFeedback = termsCheckbox.parentElement.querySelector('.invalid-feedback');

      if (!termsCheckbox.checked) {
        termsFeedback.textContent = 'You must accept the terms and conditions';
        isValid = false;
      } else {
        termsFeedback.textContent = '';
      }

      if (!isValid) {
        e.preventDefault();
      }
    });

    // Add interactive effects
    document.querySelectorAll('.form-control').forEach(input => {
      input.addEventListener('focus', function() {
        this.parentElement.style.transform = 'translateY(-1px)';
      });

      input.addEventListener('blur', function() {
        this.parentElement.style.transform = 'translateY(0)';
      });
    });
  </script>
</body>

</html>