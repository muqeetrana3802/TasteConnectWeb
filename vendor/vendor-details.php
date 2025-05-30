<?php
// Start the vendor session
session_name('vendor_session');
session_start();

// Redirect to login if not authenticated
if (!isset($_SESSION['vendor_id'])) {
  header("Location: vendor-login.php");
  exit();
}

// Set timezone to PKT
date_default_timezone_set('Asia/Karachi');

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "foodiehub";

try {
  $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
  $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
  die("Connection failed: " . $e->getMessage());
}

// Initialize messages and vendor array
$error_message = '';
$success_message = '';
$vendor = ['restaurant_name' => 'N/A', 'email' => 'N/A', 'contact_number' => 'N/A', 'category' => '', 'address' => 'N/A'];

// Fetch vendor details
try {
  $stmt = $conn->prepare("SELECT restaurant_name, email, contact_number, category, address FROM vendors WHERE id = :id");
  $stmt->bindParam(':id', $_SESSION['vendor_id']);
  $stmt->execute();
  $vendor_data = $stmt->fetch(PDO::FETCH_ASSOC);
  if ($vendor_data) {
    $vendor['restaurant_name'] = !empty($vendor_data['restaurant_name']) ? $vendor_data['restaurant_name'] : 'N/A';
    $vendor['email'] = !empty($vendor_data['email']) ? $vendor_data['email'] : 'N/A';
    $vendor['contact_number'] = !empty($vendor_data['contact_number']) ? $vendor_data['contact_number'] : 'N/A';
    $vendor['category'] = !empty($vendor_data['category']) ? $vendor_data['category'] : '';
    $vendor['address'] = !empty($vendor_data['address']) ? $vendor_data['address'] : 'N/A';
  } else {
    $error_message = "Vendor not found.";
    header("refresh:2;url=vendor-login.php");
  }
} catch (PDOException $e) {
  $error_message = "Error fetching vendor details: " . $e->getMessage();
}

// Handle update
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update'])) {
  // Sanitize inputs
  $restaurant_name = trim(htmlspecialchars($_POST['restaurantName'], ENT_QUOTES, 'UTF-8'));
  $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
  $contact_number = trim(htmlspecialchars($_POST['contactNumber'], ENT_QUOTES, 'UTF-8'));
  $category = trim(htmlspecialchars($_POST['category'], ENT_QUOTES, 'UTF-8'));
  $address = trim(htmlspecialchars($_POST['address'], ENT_QUOTES, 'UTF-8'));
  $new_password = $_POST['newPassword'];
  $confirm_password = $_POST['confirmPassword'];

  // Server-side validation
  if (empty($restaurant_name) || empty($email) || empty($contact_number) || empty($category) || empty($address)) {
    $error_message = "Restaurant name, email, contact number, category, and address are required.";
  } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $error_message = "Please enter a valid email.";
  } elseif (!preg_match("/^\+?\d{10,15}$/", $contact_number)) {
    $error_message = "Please enter a valid contact number.";
  } elseif (strlen($address) > 255) {
    $error_message = "Address must not exceed 255 characters.";
  } elseif ($new_password !== '' || $confirm_password !== '') {
    if (strlen($new_password) < 6) {
      $error_message = "Password must be at least 6 characters.";
    } elseif ($new_password !== $confirm_password) {
      $error_message = "Passwords do not match.";
    }
  } else {
    try {
      // Check if email is taken by another vendor
      $stmt = $conn->prepare("SELECT id FROM vendors WHERE email = :email AND id != :id");
      $stmt->bindParam(':email', $email);
      $stmt->bindParam(':id', $_SESSION['vendor_id']);
      $stmt->execute();
      if ($stmt->fetch()) {
        $error_message = "Email is already in use by another vendor.";
      } else {
        // Prepare update query
        $query = "UPDATE vendors SET restaurant_name = :restaurant_name, email = :email, contact_number = :contact_number, category = :category, address = :address";
        $params = [
          ':restaurant_name' => $restaurant_name,
          ':email' => $email,
          ':contact_number' => $contact_number,
          ':category' => $category,
          ':address' => $address,
          ':id' => $_SESSION['vendor_id']
        ];

        // Add password update if provided
        if (!empty($new_password)) {
          $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
          $query .= ", password = :password";
          $params[':password'] = $hashed_password;
        }

        $query .= " WHERE id = :id";
        $stmt = $conn->prepare($query);
        $stmt->execute($params);

        if ($stmt->rowCount() > 0) {
          $success_message = "Details updated successfully!";
          // Update displayed vendor details
          $vendor['restaurant_name'] = $restaurant_name;
          $vendor['email'] = $email;
          $vendor['contact_number'] = $contact_number;
          $vendor['category'] = $category;
          $vendor['address'] = $address;
        } else {
          $error_message = "No changes made or vendor not found.";
        }
      }
    } catch (PDOException $e) {
      $error_message = "Error updating details: " . $e->getMessage();
    }
  }
}
?>

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
    .form-group select,
    .form-group textarea {
      border: 2px solid var(--gray-300);
      border-radius: 8px;
      padding: 0.75rem;
      font-size: 1rem;
      transition: var(--transition);
    }

    .form-group input:focus,
    .form-group select:focus,
    .form-group textarea:focus {
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
      text-align: center;
      <?php echo !empty($error_message) ? 'display: block;' : 'display: none;'; ?>
    }

    .success-message {
      color: var(--success-color);
      font-size: 0.9rem;
      margin-top: 0.5rem;
      text-align: center;
      <?php echo !empty($success_message) ? 'display: block;' : 'display: none;'; ?>
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

  <!-- Sidebar Toggle for Mobile -->
  <button class="sidebar-toggle d-none" id="sidebarToggle">
    <i class="fas fa-bars"></i>
  </button>

  <!-- Main Content -->
  <div class="main-content">
    <section class="section">
      <div class="container">
        <h2 class="mb-4">Settings</h2>
        <div class="settings-container fade-in">
          <h2 class="settings-title">Update Vendor Details</h2>
          <div id="errorMessage" class="error-message"><?php echo htmlspecialchars($error_message); ?></div>
          <div id="successMessage" class="success-message"><?php echo htmlspecialchars($success_message); ?></div>
          <form method="POST" action="">
            <div class="form-group mb-3">
              <label for="restaurantName">Restaurant Name</label>
              <input type="text" class="form-control" id="restaurantName" name="restaurantName" placeholder="Enter restaurant name" value="<?php echo htmlspecialchars($vendor['restaurant_name']); ?>" required>
            </div>
            <div class="form-group mb-3">
              <label for="email">Email</label>
              <input type="email" class="form-control" id="email" name="email" placeholder="Enter your email" value="<?php echo htmlspecialchars($vendor['email']); ?>" required>
            </div>
            <div class="form-group mb-3">
              <label for="contactNumber">Contact Number</label>
              <input type="tel" class="form-control" id="contactNumber" name="contactNumber" placeholder="Enter contact number" value="<?php echo htmlspecialchars($vendor['contact_number']); ?>" required>
            </div>
            <div class="form-group mb-3">
              <label for="category">Restaurant Category</label>
              <select class="form-select" id="category" name="category" required>
                <option value="" disabled>Select category</option>
                <option value="Pizza" <?php echo $vendor['category'] == 'Pizza' ? 'selected' : ''; ?>>Pizza</option>
                <option value="Burgers" <?php echo $vendor['category'] == 'Burgers' ? 'selected' : ''; ?>>Burgers</option>
                <option value="Sushi" <?php echo $vendor['category'] == 'Sushi' ? 'selected' : ''; ?>>Sushi</option>
                <option value="Italian" <?php echo $vendor['category'] == 'Italian' ? 'selected' : ''; ?>>Italian</option>
                <option value="Desserts" <?php echo $vendor['category'] == 'Desserts' ? 'selected' : ''; ?>>Desserts</option>
                <option value="Fast Food" <?php echo $vendor['category'] == 'Fast Food' ? 'selected' : ''; ?>>Fast Food</option>
              </select>
            </div>
            <div class="form-group mb-3">
              <label for="address">Address</label>
              <textarea class="form-control" id="address" name="address" placeholder="Enter restaurant address" rows="4" required><?php echo htmlspecialchars($vendor['address']); ?></textarea>
            </div>
            <div class="form-group mb-3">
              <label for="newPassword">New Password (leave blank to keep current)</label>
              <input type="password" class="form-control" id="newPassword" name="newPassword" placeholder="Enter new password">
            </div>
            <div class="form-group mb-3">
              <label for="confirmPassword">Confirm New Password</label>
              <input type="password" class="form-control" id="confirmPassword" name="confirmPassword" placeholder="Confirm new password">
            </div>
            <button type="submit" name="update" class="btn btn-save">Save Changes</button>
          </form>
        </div>
      </div>
    </section>
  </div>

  <!-- Bootstrap JS -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/js/bootstrap.bundle.min.js"></script>

  <!-- Custom JavaScript -->
  <script>
    // Initialize page
    document.addEventListener('DOMContentLoaded', function() {
      // Highlight active sidebar link
      const currentPage = window.location.pathname.split('/').pop() || 'settings.php';
      document.querySelectorAll('.sidebar .nav-link').forEach(link => {
        const href = link.getAttribute('href');
        if (href === currentPage) {
          link.classList.add('active');
        } else {
          link.classList.remove('active');
        }
      });

      // Toggle sidebar on mobile
      const sidebar = document.getElementById('sidebar');
      const sidebarToggle = document.getElementById('sidebarToggle');
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
      document.getElementById('confirmPassword').addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
          document.querySelector('form').submit();
        }
      });
    });
  </script>
</body>

</html>