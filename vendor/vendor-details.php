<?php
// Start the vendor session securely
session_name('vendor_session');
session_start();

// Check if vendor is logged in
if (!isset($_SESSION['vendor_id'])) {
  header("Location: vendor_login.php");
  exit();
}

// Database connection
$host = "localhost";
$db = "foodiehub";
$user = "root";
$pass = "";

try {
  $conn = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
  $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
  die("Connection failed: " . $e->getMessage());
}

$error_message = '';
$success_message = '';
$vendor_data = [];

// Fetch vendor data
try {
  $stmt = $conn->prepare("SELECT restaurant_name, email, category, contact_number FROM vendors WHERE id = :id");
  $stmt->bindParam(':id', $_SESSION['vendor_id']);
  $stmt->execute();
  $vendor_data = $stmt->fetch(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
  $error_message = "Error fetching data: " . $e->getMessage();
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update'])) {
  // Sanitize inputs
  $restaurant_name = htmlspecialchars(trim($_POST['restaurantName'] ?? ''));
  $email = filter_var(trim($_POST['email'] ?? ''), FILTER_SANITIZE_EMAIL);
  $password = $_POST['password'] ?? '';
  $confirm_password = $_POST['confirmPassword'] ?? '';
  $category = htmlspecialchars(trim($_POST['category'] ?? ''));
  $contact_number = htmlspecialchars(trim($_POST['contactNumber'] ?? ''));

  // Validation
  if (!$restaurant_name || !$email || !$category || !$contact_number) {
    $error_message = "Please fill in all required fields.";
  } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $error_message = "Please enter a valid email address.";
  } elseif (!preg_match("/^\+?\d{10,15}$/", $contact_number)) {
    $error_message = "Please enter a valid contact number.";
  } elseif ($password && strlen($password) < 6) {
    $error_message = "Password must be at least 6 characters long.";
  } elseif ($password && $password !== $confirm_password) {
    $error_message = "Passwords do not match.";
  } else {
    try {
      // Check if email is taken by another vendor
      $stmt = $conn->prepare("SELECT id FROM vendors WHERE email = :email AND id != :id");
      $stmt->bindParam(':email', $email);
      $stmt->bindParam(':id', $_SESSION['vendor_id']);
      $stmt->execute();

      if ($stmt->fetch()) {
        $error_message = "Email is already registered by another account.";
      } else {
        // Prepare update query
        $query = "UPDATE vendors SET restaurant_name = :restaurant_name, email = :email, category = :category, contact_number = :contact_number";
        $params = [
          ':restaurant_name' => $restaurant_name,
          ':email' => $email,
          ':category' => $category,
          ':contact_number' => $contact_number,
          ':id' => $_SESSION['vendor_id']
        ];

        // Handle password update if provided
        if (!empty($password)) {
          $hashed_password = password_hash($password, PASSWORD_DEFAULT);
          $query .= ", password = :password";
          $params[':password'] = $hashed_password;
        }

        $query .= " WHERE id = :id";
        $stmt = $conn->prepare($query);
        foreach ($params as $key => $value) {
          $stmt->bindValue($key, $value);
        }

        $stmt->execute();
        $success_message = "🎉 Profile updated successfully!";
        header("refresh:2;url=index.php");
      }
    } catch (PDOException $e) {
      $error_message = "Database error: " . $e->getMessage();
    }
  }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="shortcut icon" href="../assets/img/logostaste.png" type="image/x-png">
  <title>Edit Vendor Profile - TasteConnect</title>
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

    .edit-card {
      background: var(--white);
      border-radius: var(--border-radius);
      box-shadow: var(--shadow);
      padding: 2rem;
      width: 100%;
      max-width: 500px;
      transition: var(--transition);
    }

    .edit-card:hover {
      transform: translateY(-5px);
      box-shadow: var(--shadow-hover);
    }

    .edit-card h2 {
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

    .btn-update {
      background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
      border: none;
      color: var(--white);
      padding: 0.75rem;
      border-radius: 25px;
      font-weight: 500;
      width: 100%;
      transition: var(--transition);
    }

    .btn-update:hover {
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

    .dashboard-link,
    .home-link {
      color: var(--primary-color);
      text-decoration: none;
      font-weight: 500;
      transition: var(--transition);
    }

    .dashboard-link:hover,
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
      .edit-card {
        margin: 1rem;
        padding: 1.5rem;
      }
    }
  </style>
</head>

<body>
  <!-- Edit Profile Form -->
  <div class="edit-card fade-in">
    <h2><i class="fas fa-utensils me-2"></i>Edit Vendor Profile</h2>
    <div id="errorMessage" class="error-message"><?php echo htmlspecialchars($error_message); ?></div>
    <div id="successMessage" class="success-message"><?php echo htmlspecialchars($success_message); ?></div>
    <form method="POST" action="">
      <div class="mb-3">
        <label for="restaurantName" class="form-label">Restaurant Name</label>
        <input type="text" class="form-control" id="restaurantName" name="restaurantName" placeholder="Enter restaurant name" value="<?php echo htmlspecialchars($vendor_data['restaurant_name'] ?? ''); ?>" required>
      </div>
      <div class="mb-3">
        <label for="email" class="form-label">Email</label>
        <input type="email" class="form-control" id="email" name="email" placeholder="Enter your email" value="<?php echo htmlspecialchars($vendor_data['email'] ?? ''); ?>" required>
      </div>
      <div class="mb-3">
        <label for="password" class="form-label">New Password (optional)</label>
        <input type="password" class="form-control" id="password" name="password" placeholder="Enter new password">
      </div>
      <div class="mb-3">
        <label for="confirmPassword" class="form-label">Confirm New Password</label>
        <input type="password" class="form-control" id="confirmPassword" name="confirmPassword" placeholder="Confirm new password">
      </div>
      <div class="mb-3">
        <label for="restaurantImages" class="form-label">Upload New Restaurant Images (optional, 4-5)</label>
        <input type="file" class="form-control" name="restaurantImages[]" id="restaurantImages" multiple accept="image/*">
      </div>
      <div class="mb-3">
        <label for="category" class="form-label">Restaurant Category</label>
        <select class="form-select" id="category" name="category" required>
          <option value="" disabled>Select category</option>
          <option value="Pizza" <?php echo ($vendor_data['category'] ?? '') == 'Pizza' ? 'selected' : ''; ?>>Pizza</option>
          <option value="Burgers" <?php echo ($vendor_data['category'] ?? '') == 'Burgers' ? 'selected' : ''; ?>>Burgers</option>
          <option value="Sushi" <?php echo ($vendor_data['category'] ?? '') == 'Sushi' ? 'selected' : ''; ?>>Sushi</option>
          <option value="Italian" <?php echo ($vendor_data['category'] ?? '') == 'Italian' ? 'selected' : ''; ?>>Italian</option>
          <option value="Desserts" <?php echo ($vendor_data['category'] ?? '') == 'Desserts' ? 'selected' : ''; ?>>Desserts</option>
          <option value="Fast Food" <?php echo ($vendor_data['category'] ?? '') == 'Fast Food' ? 'selected' : ''; ?>>Fast Food</option>
        </select>
      </div>
      <div class="mb-3">
        <label for="contactNumber" class="form-label">Contact Number</label>
        <input type="tel" class="form-control" id="contactNumber" name="contactNumber" placeholder="Enter contact number" value="<?php echo htmlspecialchars($vendor_data['contact_number'] ?? ''); ?>" required>
      </div>
      <button type="submit" name="update" class="btn btn-update">Update Profile</button>
    </form>
    <div class="text-center mt-3">
      <p><a href="index.php" class="dashboard-link">Back to Dashboard</a></p>
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
      const editCard = document.querySelector('.edit-card');
      editCard.classList.add('visible');

      // Allow update on Enter key
      document.getElementById('confirmPassword').addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
          document.querySelector('form').submit();
        }
      });
    });
  </script>
</body>

</html>