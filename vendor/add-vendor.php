<?php
// Start the vendor session
session_name('vendor_session');
session_start();

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

// Handle registration
$error_message = '';
$success_message = '';
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['register'])) {
  // Sanitize inputs
  $restaurant_name = trim(htmlspecialchars($_POST['restaurantName'], ENT_QUOTES, 'UTF-8'));
  $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
  $password = $_POST['password'];
  $confirm_password = $_POST['confirmPassword'];
  $category = trim(htmlspecialchars($_POST['category'], ENT_QUOTES, 'UTF-8'));
  $contact_number = trim(htmlspecialchars($_POST['contactNumber'], ENT_QUOTES, 'UTF-8'));

  // Server-side validation
  if (empty($restaurant_name) || empty($email) || empty($password) || empty($confirm_password) || empty($category) || empty($contact_number)) {
    $error_message = "Please fill in all fields.";
  } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $error_message = "Please enter a valid email.";
  } elseif (strlen($password) < 6) {
    $error_message = "Password must be at least 6 characters.";
  } elseif ($password !== $confirm_password) {
    $error_message = "Passwords do not match.";
  } elseif (!preg_match("/^\+?\d{10,15}$/", $contact_number)) {
    $error_message = "Please enter a valid contact number.";
  } else {
    try {
      // Check if email already exists
      $stmt = $conn->prepare("SELECT id FROM vendors WHERE email = :email");
      $stmt->bindParam(':email', $email);
      $stmt->execute();
      if ($stmt->fetch()) {
        $error_message = "Email is already registered.";
      } else {
        // Insert new vendor
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $conn->prepare("INSERT INTO vendors (restaurant_name, email, password, category, contact_number) VALUES (:restaurant_name, :email, :password, :category, :contact_number)");
        $stmt->bindParam(':restaurant_name', $restaurant_name);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $hashed_password);
        $stmt->bindParam(':category', $category);
        $stmt->bindParam(':contact_number', $contact_number);
        $stmt->execute();

        $success_message = "Registration successful! Redirecting to login...";
        header("refresh:2;url=vendor-login.php");
      }
    } catch (PDOException $e) {
      $error_message = "Error: " . $e->getMessage();
    }
  }
}
?>

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

    .fade-in {
      opacity: 0;
      transform: translateY(30px);
      transition: all 0.6s ease;
    }

    .fade-in.visible {
      opacity: 1;
      transform: translateY(0);
    }

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
    <div id="errorMessage" class="error-message"><?php echo htmlspecialchars($error_message); ?></div>
    <div id="successMessage" class="success-message"><?php echo htmlspecialchars($success_message); ?></div>
    <form method="POST" action="">
      <div class="mb-3">
        <label for="restaurantName" class="form-label">Restaurant Name</label>
        <input type="text" class="form-control" id="restaurantName" name="restaurantName" placeholder="Enter restaurant name" value="<?php echo isset($_POST['restaurantName']) ? htmlspecialchars($_POST['restaurantName']) : ''; ?>" required>
      </div>
      <div class="mb-3">
        <label for="email" class="form-label">Email</label>
        <input type="email" class="form-control" id="email" name="email" placeholder="Enter your email" value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>" required>
      </div>
      <div class="mb-3">
        <label for="password" class="form-label">Password</label>
        <input type="password" class="form-control" id="password" name="password" placeholder="Enter your password" required>
      </div>
      <div class="mb-3">
        <label for="confirmPassword" class="form-label">Confirm Password</label>
        <input type="password" class="form-control" id="confirmPassword" name="confirmPassword" placeholder="Confirm your password" required>
      </div>
      <div class="mb-3">
        <label for="category" class="form-label">Restaurant Category</label>
        <select class="form-select" id="category" name="category" required>
          <option value="" disabled <?php echo !isset($_POST['category']) ? 'selected' : ''; ?>>Select category</option>
          <option value="Pizza" <?php echo isset($_POST['category']) && $_POST['category'] == 'Pizza' ? 'selected' : ''; ?>>Pizza</option>
          <option value="Burgers" <?php echo isset($_POST['category']) && $_POST['category'] == 'Burgers' ? 'selected' : ''; ?>>Burgers</option>
          <option value="Sushi" <?php echo isset($_POST['category']) && $_POST['category'] == 'Sushi' ? 'selected' : ''; ?>>Sushi</option>
          <option value="Italian" <?php echo isset($_POST['category']) && $_POST['category'] == 'Italian' ? 'selected' : ''; ?>>Italian</option>
          <option value="Desserts" <?php echo isset($_POST['category']) && $_POST['category'] == 'Desserts' ? 'selected' : ''; ?>>Desserts</option>
          <option value="Fast Food" <?php echo isset($_POST['category']) && $_POST['category'] == 'Fast Food' ? 'selected' : ''; ?>>Fast Food</option>
        </select>
      </div>
      <div class="mb-3">
        <label for="contactNumber" class="form-label">Contact Number</label>
        <input type="tel" class="form-control" id="contactNumber" name="contactNumber" placeholder="Enter contact number" value="<?php echo isset($_POST['contactNumber']) ? htmlspecialchars($_POST['contactNumber']) : ''; ?>" required>
      </div>
      <button type="submit" name="register" class="btn btn-register">Register</button>
    </form>
    <div class="text-center mt-3">
      <p>Already have an account? <a href="vendor-login.php" class="login-link">Login here</a></p>
      <p><a href="index.php" class="home-link">Back to Home</a></p>
    </div>
  </div>

  <!-- Bootstrap JS -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/js/bootstrap.bundle.min.js"></script>

  <!-- Custom JavaScript -->
  <script>
    // Initialize page
    document.addEventListener('DOMContentLoaded', function() {
      // Fade-in animation
      const registerCard = document.querySelector('.register-card');
      registerCard.classList.add('visible');

      // Allow registration on Enter key
      document.getElementById('confirmPassword').addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
          document.querySelector('form').submit();
        }
      });
    });
  </script>
</body>

</html>