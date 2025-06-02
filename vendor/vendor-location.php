<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="shortcut icon" href="../assets/img/logostaste.png" type="image/x-png">
  <title>Add Restaurant Location - TasteConnect</title>
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

    .btn-login {
      background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
      border: none;
      color: var(--white);
      padding: 0.5rem 1.5rem;
      border-radius: 25px;
      font-weight: 500;
      transition: var(--transition);
    }

    .btn-login:hover {
      transform: translateY(-2px);
      box-shadow: var(--shadow);
      color: var(--white);
    }

    /* Page Header */
    .page-header {
      background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
      color: var(--white);
      padding: 6rem 0 3rem;
      margin-top: 76px;
      position: relative;
      overflow: hidden;
    }

    .page-header::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      bottom: 0;
      background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><circle fill="%23ffffff" cx="20" cy="20" r="2" opacity="0.1"/><circle fill="%23ffffff" cx="80" cy="40" r="1.5" opacity="0.1"/><circle fill="%23ffffff" cx="40" cy="70" r="1" opacity="0.1"/><circle fill="%23ffffff" cx="90" cy="80" r="2.5" opacity="0.1"/></svg>');
      background-size: 100px 100px;
      animation: float 20s infinite linear;
    }

    @keyframes float {
      0% {
        background-position: 0 0;
      }

      100% {
        background-position: 100px 100px;
      }
    }

    .page-header h1 {
      font-size: 3rem;
      font-weight: 700;
      margin-bottom: 1rem;
      position: relative;
      z-index: 2;
    }

    .page-header p {
      font-size: 1.2rem;
      opacity: 0.9;
      position: relative;
      z-index: 2;
    }

    /* Form Section */
    .form-container {
      background: var(--white);
      border-radius: var(--border-radius);
      box-shadow: var(--shadow);
      padding: 2rem;
      margin: 3rem 0;
    }

    .form-title {
      font-size: 2rem;
      font-weight: 700;
      color: var(--dark-color);
      margin-bottom: 1.5rem;
      text-align: center;
    }

    .form-group label {
      font-weight: 600;
      color: var(--gray-800);
      margin-bottom: 0.5rem;
    }

    .form-group input {
      border: 2px solid var(--gray-300);
      border-radius: 8px;
      padding: 0.75rem;
      font-size: 1rem;
      transition: var(--transition);
    }

    .form-group input:focus {
      border-color: var(--primary-color);
      box-shadow: 0 0 0 0.2rem rgba(255, 107, 53, 0.25);
      outline: none;
    }

    .map-placeholder {
      height: 300px;
      background: var(--gray-200);
      border-radius: var(--border-radius);
      display: flex;
      align-items: center;
      justify-content: center;
      margin-bottom: 1.5rem;
      color: var(--gray-800);
      font-size: 1.2rem;
    }

    .btn {
      background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
      border: none;
      color: var(--white);
      padding: 1rem 2rem;
      border-radius: 25px;
      font-weight: 600;
      transition: var(--transition);
    }

    .btn:hover {
      transform: translateY(-2px);
      box-shadow: var(--shadow);
      color: var(--white);
    }

    /* Responsive */
    @media (max-width: 768px) {
      .page-header h1 {
        font-size: 2rem;
      }

      .form-container {
        padding: 1.5rem;
      }

      .map-placeholder {
        height: 200px;
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


  <!-- Page Header -->
  <section class="page-header">
    <div class="container">
      <div class="row">
        <div class="col-lg-8">
          <h1>Add Your Restaurant Location</h1>
          <p>Help customers find your restaurant with accurate location details.</p>
        </div>
      </div>
    </div>
  </section>

  <!-- Form Section -->
  <section class="section">
    <div class="container">
      <div class="form-container fade-in">
        <h2 class="form-title">Restaurant Location</h2>
        <form id="locationForm">
          <div class="form-group mb-3">
            <label for="address">Street Address</label>
            <input type="text" class="form-control" id="address" placeholder="Enter street address" required>
          </div>
          <div class="form-group mb-3">
            <label for="city">City</label>
            <input type="text" class="form-control" id="city" placeholder="Enter city" required>
          </div>
          <div class="form-group mb-3">
            <label for="zipCode">Zip Code</label>
            <input type="text" class="form-control" id="zipCode" placeholder="Enter zip code" required>
          </div>
          <div class="form-group mb-3">
            <label for="latitude">Latitude (optional)</label>
            <input type="number" class="form-control" id="latitude" step="any" placeholder="Enter latitude">
          </div>
          <div class="form-group mb-3">
            <label for="longitude">Longitude (optional)</label>
            <input type="number" class="form-control" id="longitude" step="any" placeholder="Enter longitude">
          </div>
          <div class="map-placeholder">
            Interactive Map Placeholder (Google Maps or OpenStreetMap would be integrated here)
          </div>
          <div class="text-center">
            <button type="submit" class="btn">Save Location</button>
          </div>
        </form>
      </div>
    </div>
  </section>

  <!-- Bootstrap JS -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/js/bootstrap.bundle.min.js"></script>

  <!-- Custom JavaScript -->
  <script>
    document.addEventListener('DOMContentLoaded', function() {
      // Fade-in animation
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

      // Form submission
      const locationForm = document.getElementById('locationForm');
      locationForm.addEventListener('submit', function(e) {
        e.preventDefault();
        const locationData = {
          address: document.getElementById('address').value,
          city: document.getElementById('city').value,
          zipCode: document.getElementById('zipCode').value,
          latitude: document.getElementById('latitude').value || null,
          longitude: document.getElementById('longitude').value || null
        };

        // Save to localStorage (simulating backend)
        localStorage.setItem('locationData', JSON.stringify(locationData));
        alert('Location saved successfully! Please add your restaurant details.');
        window.location.href = 'vendor-details.php';
      });
    });
  </script>
</body>

</html>