<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Vendor Registration - FoodieHub</title>
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
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      margin: 0;
      padding: 2rem 0;
    }

    /* Register Card */
    .register-card {
      background: var(--white);
      border-radius: var(--border-radius);
      box-shadow: var(--shadow);
      padding: 2rem;
      width: 100%;
      max-width: 500px;
      transition: var(--transition);
    }

    .register-card:hover {
      transform: translateY(-5px);
      box-shadow: var(--shadow-hover);
    }

    .register-card h2 {
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

    .form-select {
      border-radius: var(--border-radius);
      border: 1px solid var(--gray-300);
      padding: 0.75rem;
      transition: var(--transition);
    }

    .form-select:focus {
      border-color: var(--primary-color);
      box-shadow: 0 0 0 0.2rem rgba(255, 107, 53, 0.25);
    }

    .btn-register {
      background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
      border: none;
      color: var(--white);
      padding: 0.75rem;
      border-radius: 25px;
      font-weight: 500;
      width: 100%;
      transition: var(--transition);
    }

    .btn-register:hover {
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

    .success-message {
      color: var(--success-color);
      font-size: 0.9rem;
      margin-top: 0.5rem;
      display: none;
      text-align: center;
    }

    /* Links */
    .login-link,
    .home-link {
      color: var(--primary-color);
      text-decoration: none;
      font-weight: 500;
      transition: var(--transition);
    }

    .login-link:hover,
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
      .register-card {
        margin: 1rem;
        padding: 1.5rem;
      }
    }
  </style>
</head>

<body>
  <!-- Registration Form -->
  <div class="register-card fade-in">
    <h2><i class="fas fa-utensils me-2"></i>Vendor Registration</h2>
    <div id="errorMessage" class="error-message"></div>
    <div id="successMessage" class="success-message">Registration successful! Redirecting to login...</div>
    <div class="mb-3">
      <label for="restaurantName" class="form-label">Restaurant Name</label>
      <input type="text" class="form-control" id="restaurantName" placeholder="Enter restaurant name" required>
    </div>
    <div class="mb-3">
      <label for="email" class="form-label">Email</label>
      <input type="email" class="form-control" id="email" placeholder="Enter your email" required>
    </div>
    <div class="mb-3">
      <label for="password" class="form-label">Password</label>
      <input type="password" class="form-control" id="password" placeholder="Enter your password" required>
    </div>
    <div class="mb-3">
      <label for="confirmPassword" class="form-label">Confirm Password</label>
      <input type="password" class="form-control" id="confirmPassword" placeholder="Confirm your password" required>
    </div>
    <div class="mb-3">
      <label for="category" class="form-label">Restaurant Category</label>
      <select class="form-select" id="category" required>
        <option value="" disabled selected>Select category</option>
        <option value="Pizza">Pizza</option>
        <option value="Burgers">Burgers</option>
        <option value="Sushi">Sushi</option>
        <option value="Italian">Italian</option>
        <option value="Desserts">Desserts</option>
        <option value="Fast Food">Fast Food</option>
      </select>
    </div>
    <div class="mb-3">
      <label for="contactNumber" class="form-label">Contact Number</label>
      <input type="tel" class="form-control" id="contactNumber" placeholder="Enter contact number" required>
    </div>
    <button class="btn btn-register" onclick="handleRegister()">Register</button>
    <div class="text-center mt-3">
      <p>Already have an account? <a href="vendor-login.php" class="login-link">Login here</a></p>
      <p><a href="index.php" class="home-link">Back to Home</a></p>
    </div>
  </div>

  <!-- Bootstrap JS -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/js/bootstrap.bundle.min.js"></script>

  <!-- Custom JavaScript -->
  <script>
    // Handle registration
    window.handleRegister = function() {
      const restaurantName = document.getElementById('restaurantName').value.trim();
      const email = document.getElementById('email').value.trim();
      const password = document.getElementById('password').value.trim();
      const confirmPassword = document.getElementById('confirmPassword').value.trim();
      const category = document.getElementById('category').value;
      const contactNumber = document.getElementById('contactNumber').value.trim();
      const errorMessage = document.getElementById('errorMessage');
      const successMessage = document.getElementById('successMessage');

      // Reset messages
      errorMessage.style.display = 'none';
      successMessage.style.display = 'none';

      // Basic validation
      if (!restaurantName || !email || !password || !confirmPassword || !category || !contactNumber) {
        errorMessage.textContent = 'Please fill in all fields';
        errorMessage.style.display = 'block';
        return;
      }

      if (password !== confirmPassword) {
        errorMessage.textContent = 'Passwords do not match';
        errorMessage.style.display = 'block';
        return;
      }

      if (password.length < 6) {
        errorMessage.textContent = 'Password must be at least 6 characters';
        errorMessage.style.display = 'block';
        return;
      }

      if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
        errorMessage.textContent = 'Please enter a valid email';
        errorMessage.style.display = 'block';
        return;
      }

      if (!/^\+?\d{10,15}$/.test(contactNumber)) {
        errorMessage.textContent = 'Please enter a valid contact number';
        errorMessage.style.display = 'block';
        return;
      }

      // Simulate checking if email is already registered
      const existingVendors = JSON.parse(localStorage.getItem('vendors') || '[]');
      if (existingVendors.some(vendor => vendor.email === email)) {
        errorMessage.textContent = 'Email is already registered';
        errorMessage.style.display = 'block';
        return;
      }

      // Store vendor data (in real app, send to backend)
      const newVendor = {
        restaurantName,
        email,
        password, // In real app, hash this
        category,
        contactNumber,
        registeredAt: new Date().toISOString()
      };
      existingVendors.push(newVendor);
      localStorage.setItem('vendors', JSON.stringify(existingVendors));

      // Show success message and redirect
      successMessage.style.display = 'block';
      setTimeout(() => {
        window.location.href = 'vendor-login.php';
      }, 2000);
    };

    // Initialize page
    document.addEventListener('DOMContentLoaded', function() {
      // Fade-in animation
      const registerCard = document.querySelector('.register-card');
      registerCard.classList.add('visible');

      // Allow registration on Enter key
      document.getElementById('confirmPassword').addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
          handleRegister();
        }
      });
    });
  </script>
</body>

</html>