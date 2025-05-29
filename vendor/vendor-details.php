<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Settings - FoodieHub</title>
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

    .navbar-nav .nav-link {
      font-weight: 500;
      color: var(--dark-color) !important;
      margin: 0 0.5rem;
      transition: var(--transition);
      position: relative;
    }

    .navbar-nav .nav-link:hover,
    .navbar-nav .nav-link.active {
      color: var(--primary-color) !important;
    }

    .navbar-nav .nav-link::after {
      content: '';
      position: absolute;
      width: 0;
      height: 2px;
      bottom: -5px;
      left: 50%;
      background-color: var(--primary-color);
      transition: var(--transition);
      transform: translateX(-50%);
    }

    .navbar-nav .nav-link:hover::after,
    .navbar-nav .nav-link.active::after {
      width: 100%;
    }

    .btn-logout {
      background: linear-gradient(135deg, var(--danger-color), #c0392b);
      border: none;
      color: var(--white);
      padding: 0.5rem 1.5rem;
      border-radius: 25px;
      font-weight: 500;
      transition: var(--transition);
    }

    .btn-logout:hover {
      transform: translateY(-2px);
      box-shadow: var(--shadow);
      color: var(--white);
    }

    /* Sidebar */
    .sidebar {
      position: fixed;
      top: 76px;
      left: 0;
      width: 250px;
      height: calc(100vh - 76px);
      background: var(--white);
      box-shadow: var(--shadow);
      padding: 2rem;
      overflow-y: auto;
      transition: var(--transition);
    }

    .sidebar .nav-link {
      display: flex;
      align-items: center;
      padding: 0.75rem 1rem;
      margin-bottom: 0.5rem;
      border-radius: var(--border-radius);
      color: var(--dark-color);
      font-weight: 500;
    }

    .sidebar .nav-link:hover,
    .sidebar .nav-link.active {
      background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
      color: var(--white) !important;
    }

    .sidebar .nav-link i {
      margin-right: 0.75rem;
    }

    /* Main Content */
    .main-content {
      margin-left: 250px;
      padding: 2rem;
      margin-top: 76px;
    }

    /* Settings Container */
    .settings-container {
      background: var(--white);
      border-radius: var(--border-radius);
      box-shadow: var(--shadow);
      padding: 2rem;
      margin: 3rem 0;
    }

    .settings-title {
      font-size: 2rem;
      font-weight: 700;
      color: var(--dark-color);
      margin-bottom: 1.5rem;
    }

    .form-group label {
      font-weight: 600;
      color: var(--gray-800);
      margin-bottom: 0.5rem;
    }

    .form-group input,
    .form-group select {
      border: 2px solid var(--gray-300);
      border-radius: 8px;
      padding: 0.75rem;
      font-size: 1rem;
      transition: var(--transition);
    }

    .form-group input:focus,
    .form-group select:focus {
      border-color: var(--primary-color);
      box-shadow: 0 0 0 0.2rem rgba(255, 107, 53, 0.25);
      outline: none;
    }

    .btn-save {
      background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
      border: none;
      color: var(--white);
      padding: 0.75rem 1.5rem;
      border-radius: 25px;
      font-weight: 600;
      transition: var(--transition);
    }

    .btn-save:hover {
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

    /* Responsive */
    @media (max-width: 992px) {
      .sidebar {
        width: 100%;
        left: -100%;
        top: 0;
        z-index: 1000;
      }

      .sidebar.active {
        left: 0;
      }

      .main-content {
        margin-left: 0;
      }

      .sidebar-toggle {
        display: block;
        position: fixed;
        top: 90px;
        left: 1rem;
        background: var(--primary-color);
        color: var(--white);
        border-radius: 50%;
        width: 40px;
        height: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 1001;
      }

      .settings-container {
        padding: 1.5rem;
      }
    }

    /* Fade In Animation */
    .fade-in {
      opacity: 0;
      transform: translateY(30px);
      transition: all 0.6s ease;
    }

    .fade-in.visible {
      opacity: 1;
      transform: translateY(0);
    }
  </style>
</head>

<body>
 <?php include 'includes/sidebar.php'; ?>

  <!-- Main Content -->
  <div class="main-content">
    <section class="section">
      <div class="container">
        <h2 class="mb-4">Settings</h2>
        <div class="settings-container fade-in">
          <h2 class="settings-title">Update Vendor Details</h2>
          <div id="errorMessage" class="error-message"></div>
          <div id="successMessage" class="success-message">Details updated successfully!</div>
          <div class="form-group mb-3">
            <label for="restaurantName">Restaurant Name</label>
            <input type="text" class="form-control" id="restaurantName" placeholder="Enter restaurant name">
          </div>
          <div class="form-group mb-3">
            <label for="email">Email</label>
            <input type="email" class="form-control" id="email" placeholder="Enter your email" readonly>
          </div>
          <div class="form-group mb-3">
            <label for="contactNumber">Contact Number</label>
            <input type="tel" class="form-control" id="contactNumber" placeholder="Enter contact number">
          </div>
          <div class="form-group mb-3">
            <label for="category">Restaurant Category</label>
            <select class="form-control" id="category">
              <option value="" disabled>Select category</option>
              <option value="Pizza">Pizza</option>
              <option value="Burgers">Burgers</option>
              <option value="Sushi">Sushi</option>
              <option value="Italian">Italian</option>
              <option value="Desserts">Desserts</option>
              <option value="Fast Food">Fast Food</option>
            </select>
          </div>
          <div class="form-group mb-3">
            <label for="newPassword">New Password (optional)</label>
            <input type="password" class="form-control" id="newPassword" placeholder="Enter new password">
          </div>
          <div class="form-group mb-3">
            <label for="confirmNewPassword">Confirm New Password</label>
            <input type="password" class="form-control" id="confirmNewPassword" placeholder="Confirm new password">
          </div>
          <button class="btn btn-save" onclick="handleUpdate()">Save Changes</button>
        </div>
      </div>
    </section>
  </div>

  <!-- Bootstrap JS -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/js/bootstrap.bundle.min.js"></script>

  <!-- Custom JavaScript -->
  <script>
    // DOM Elements
    const sidebar = document.getElementById('sidebar');
    const sidebarToggle = document.getElementById('sidebarToggle');

    // Initialize page
    document.addEventListener('DOMContentLoaded', function() {
      // Load vendor details
      loadVendorDetails();

      // Highlight active sidebar link
      const currentPage = window.location.pathname.split('/').pop() || 'vendor-settings.php';
      document.querySelectorAll('.sidebar .nav-link').forEach(link => {
        const href = link.getAttribute('href');
        if (href === currentPage) {
          link.classList.add('active');
        } else {
          link.classList.remove('active');
        }
      });

      // Toggle sidebar on mobile
      sidebarToggle.addEventListener('click', function() {
        sidebar.classList.toggle('active');
      });

      // Show sidebar toggle on mobile
      if (window.innerWidth <= 992) {
        sidebarToggle.classList.remove('d-none');
      }

      // Fade-in animations
      const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
          if (entry.isIntersecting) {
            entry.target.classList.add('visible');
          }
        });
      }, {
        threshold: 0.1
      });

      document.querySelectorAll('.fade-in').forEach(el => observer.observe(el));

      // Navbar scroll effect
      window.addEventListener('scroll', function() {
        const navbar = document.querySelector('.navbar');
        if (window.scrollY > 50) {
          navbar.style.background = 'rgba(255, 255, 255, 0.95)';
          navbar.style.backdropFilter = 'blur(10px)';
        } else {
          navbar.style.background = '#ffffff';
          navbar.style.backdropFilter = 'none';
        }
      });

      // Allow save on Enter key
      document.getElementById('confirmNewPassword').addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
          handleUpdate();
        }
      });
    });

    // Load vendor details
    function loadVendorDetails() {
      const vendors = JSON.parse(localStorage.getItem('vendors') || '[]');
      const loggedInVendor = vendors.find(v => v.email === localStorage.getItem('vendorEmail')) || {};
      document.getElementById('restaurantName').value = loggedInVendor.restaurantName || '';
      document.getElementById('email').value = loggedInVendor.email || '';
      document.getElementById('contactNumber').value = loggedInVendor.contactNumber || '';
      document.getElementById('category').value = loggedInVendor.category || '';
    }

    // Handle update
    window.handleUpdate = function() {
      const restaurantName = document.getElementById('restaurantName').value.trim();
      const contactNumber = document.getElementById('contactNumber').value.trim();
      const category = document.getElementById('category').value;
      const newPassword = document.getElementById('newPassword').value.trim();
      const confirmNewPassword = document.getElementById('confirmNewPassword').value.trim();
      const errorMessage = document.getElementById('errorMessage');
      const successMessage = document.getElementById('successMessage');

      // Reset messages
      errorMessage.style.display = 'none';
      successMessage.style.display = 'none';

      // Validation
      if (!restaurantName || !contactNumber || !category) {
        errorMessage.textContent = 'Please fill in all required fields';
        errorMessage.style.display = 'block';
        return;
      }

      if (!/^\+?\d{10,15}$/.test(contactNumber)) {
        errorMessage.textContent = 'Please enter a valid contact number';
        errorMessage.style.display = 'block';
        return;
      }

      if (newPassword || confirmNewPassword) {
        if (newPassword !== confirmNewPassword) {
          errorMessage.textContent = 'Passwords do not match';
          errorMessage.style.display = 'block';
          return;
        }
        if (newPassword.length < 6) {
          errorMessage.textContent = 'Password must be at least 6 characters';
          errorMessage.style.display = 'block';
          return;
        }
      }

      // Update vendor data
      const vendors = JSON.parse(localStorage.getItem('vendors') || '[]');
      const vendorEmail = localStorage.getItem('vendorEmail');
      const vendorIndex = vendors.findIndex(v => v.email === vendorEmail);
      if (vendorIndex !== -1) {
        vendors[vendorIndex] = {
          ...vendors[vendorIndex],
          restaurantName,
          contactNumber,
          category,
          password: newPassword || vendors[vendorIndex].password
        };
        localStorage.setItem('vendors', JSON.stringify(vendors));
        successMessage.style.display = 'block';
        setTimeout(() => {
          window.location.href = 'vendor.php';
        }, 2000);
      } else {
        errorMessage.textContent = 'Vendor not found';
        errorMessage.style.display = 'block';
      }
    };
  </script>
</body>

</html>