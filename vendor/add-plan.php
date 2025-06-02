<?php
session_name('vendor_session');
session_start();
if (!isset($_SESSION['vendor_id'])) {
  header("Location: vendor_login.php");
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

// Initialize dashboard data variables
$total_orders = "N/A";
$revenue = "N/A";
$pending_orders = "N/A";
$total_subscriptions = "N/A";

try {
  $vendor_id = $_SESSION['vendor_id'];

  // Fetch total orders
  $stmt = $conn->prepare("SELECT COUNT(*) AS total FROM orders WHERE vendor_id = :vendor_id");
  $stmt->bindParam(':vendor_id', $vendor_id);
  $stmt->execute();
  $result = $stmt->fetch(PDO::FETCH_ASSOC);
  $total_orders = ($result['total'] > 0) ? $result['total'] : "N/A";

  // Fetch revenue
  $stmt = $conn->prepare("SELECT SUM(total) AS total_revenue FROM orders WHERE vendor_id = :vendor_id");
  $stmt->bindParam(':vendor_id', $vendor_id);
  $stmt->execute();
  $result = $stmt->fetch(PDO::FETCH_ASSOC);
  $revenue = (!empty($result['total_revenue']) && $result['total_revenue'] > 0) ? "PKR " . number_format($result['total_revenue'], 2) : "0.00";

  // Fetch pending orders
  $stmt = $conn->prepare("SELECT COUNT(*) AS pending FROM orders WHERE vendor_id = :vendor_id AND status = 'pending'");
  $stmt->bindParam(':vendor_id', $vendor_id);
  $stmt->execute();
  $result = $stmt->fetch(PDO::FETCH_ASSOC);
  $pending_orders = ($result['pending'] > 0) ? $result['pending'] : "0";

  // Fetch total active subscriptions
  $stmt = $conn->prepare("SELECT COUNT(*) AS total FROM subscriptions WHERE vendor_id = :vendor_id AND status = 'active'");
  $stmt->bindParam(':vendor_id', $vendor_id);
  $stmt->execute();
  $result = $stmt->fetch(PDO::FETCH_ASSOC);
  $total_subscriptions = ($result['total'] > 0) ? $result['total'] : "0";
} catch (PDOException $e) {
  error_log("Error fetching dashboard data: " . $e->getMessage());
  $total_orders = "N/A";
  $revenue = "N/A";
  $pending_orders = "N/A";
  $total_subscriptions = "N/A";
}

// Handle subscription creation
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['create_subscription'])) {
  try {
    $plan_type = $_POST['plan_type'];
    $dish_limit = (int)$_POST['dish_limit'];
    $meal_times = implode(',', $_POST['meal_times']);
    $price = (float)$_POST['price'];
    $validity_period = (int)$_POST['validity_period'];
    $validity_unit = $_POST['validity_unit'];
    $non_subscriber_delivery_fee = (float)$_POST['non_subscriber_delivery_fee'];
    $status = 'active';

    // Set discount percentage based on plan type
    $discount_percentage = match ($plan_type) {
      'Basic' => 10.00,
      'Standard' => 15.00,
      'Premium' => 20.00,
      default => 0.00
    };

    $stmt = $conn->prepare("
      INSERT INTO subscriptions (vendor_id, plan_type, dish_limit, meal_times, price, validity_period, validity_unit, discount_percentage, non_subscriber_delivery_fee, status, created_at)
      VALUES (:vendor_id, :plan_type, :dish_limit, :meal_times, :price, :validity_period, :validity_unit, :discount_percentage, :non_subscriber_delivery_fee, :status, NOW())
    ");
    $stmt->bindParam(':vendor_id', $vendor_id);
    $stmt->bindParam(':plan_type', $plan_type);
    $stmt->bindParam(':dish_limit', $dish_limit);
    $stmt->bindParam(':meal_times', $meal_times);
    $stmt->bindParam(':price', $price);
    $stmt->bindParam(':validity_period', $validity_period);
    $stmt->bindParam(':validity_unit', $validity_unit);
    $stmt->bindParam(':discount_percentage', $discount_percentage);
    $stmt->bindParam(':non_subscriber_delivery_fee', $non_subscriber_delivery_fee);
    $stmt->bindParam(':status', $status);
    $stmt->execute();
  } catch (PDOException $e) {
    error_log("Error creating subscription: " . $e->getMessage());
  }
}

// Handle subscription update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_subscription'])) {
  try {
    $subscription_id = (int)$_POST['subscription_id'];
    $plan_type = $_POST['plan_type'];
    $dish_limit = (int)$_POST['dish_limit'];
    $meal_times = implode(',', $_POST['meal_times']);
    $price = (float)$_POST['price'];
    $validity_period = (int)$_POST['validity_period'];
    $validity_unit = $_POST['validity_unit'];
    $non_subscriber_delivery_fee = (float)$_POST['non_subscriber_delivery_fee'];
    $status = $_POST['status'];

    // Set discount percentage based on plan type
    $discount_percentage = match ($plan_type) {
      'Basic' => 10.00,
      'Standard' => 15.00,
      'Premium' => 20.00,
      default => 0.00
    };

    $stmt = $conn->prepare("
      UPDATE subscriptions 
      SET plan_type = :plan_type, 
          dish_limit = :dish_limit, 
          meal_times = :meal_times, 
          price = :price, 
          validity_period = :validity_period, 
          validity_unit = :validity_unit, 
          discount_percentage = :discount_percentage, 
          non_subscriber_delivery_fee = :non_subscriber_delivery_fee, 
          status = :status, 
          updated_at = NOW()
      WHERE id = :subscription_id AND vendor_id = :vendor_id
    ");
    $stmt->bindParam(':subscription_id', $subscription_id);
    $stmt->bindParam(':vendor_id', $vendor_id);
    $stmt->bindParam(':plan_type', $plan_type);
    $stmt->bindParam(':dish_limit', $dish_limit);
    $stmt->bindParam(':meal_times', $meal_times);
    $stmt->bindParam(':price', $price);
    $stmt->bindParam(':validity_period', $validity_period);
    $stmt->bindParam(':validity_unit', $validity_unit);
    $stmt->bindParam(':discount_percentage', $discount_percentage);
    $stmt->bindParam(':non_subscriber_delivery_fee', $non_subscriber_delivery_fee);
    $stmt->bindParam(':status', $status);
    $stmt->execute();
  } catch (PDOException $e) {
    error_log("Error updating subscription: " . $e->getMessage());
  }
}

// Handle subscription deletion
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_subscription'])) {
  try {
    $subscription_id = (int)$_POST['subscription_id'];
    $stmt = $conn->prepare("DELETE FROM subscriptions WHERE id = :subscription_id AND vendor_id = :vendor_id");
    $stmt->bindParam(':subscription_id', $subscription_id);
    $stmt->bindParam(':vendor_id', $vendor_id);
    $stmt->execute();
  } catch (PDOException $e) {
    error_log("Error deleting subscription: " . $e->getMessage());
  }
}

// Fetch subscription plans
$subscriptions = [];
try {
  $stmt = $conn->prepare("SELECT * FROM subscriptions WHERE vendor_id = :vendor_id");
  $stmt->bindParam(':vendor_id', $vendor_id);
  $stmt->execute();
  $subscriptions = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
  error_log("Error fetching subscriptions: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="shortcut icon" href="../assets/img/logostaste.png" type="image/x-png">
  <title>Vendor Dashboard - TasteConnect</title>
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

    .main-content {
      margin-left: 250px;
      padding: 2rem;
      margin-top: 76px;
    }

    .dashboard-card {
      background: var(--white);
      border-radius: var(--border-radius);
      box-shadow: var(--shadow);
      padding: 1.5rem;
      transition: var(--transition);
      height: 100%;
    }

    .dashboard-card:hover {
      transform: translateY(-5px);
      box-shadow: var(--shadow-hover);
    }

    .dashboard-card h5 {
      font-size: 1.25rem;
      font-weight: 700;
      color: var(--dark-color);
      margin-bottom: 1rem;
    }

    .subscription-item {
      background: var(--white);
      border-radius: var(--border-radius);
      padding: 1.5rem;
      margin-bottom: 1rem;
      box-shadow: var(--shadow);
      display: flex;
      justify-content: space-between;
      align-items: center;
    }

    .subscription-item-info {
      flex: 1;
    }

    .subscription-item-title {
      font-size: 1.1rem;
      font-weight: 600;
      margin-bottom: 0.5rem;
    }

    .subscription-item-details {
      color: var(--gray-800);
      font-size: 0.9rem;
    }

    .subscription-item-actions button {
      margin-left: 0.5rem;
    }

    .modal-content {
      border-radius: var(--border-radius);
      box-shadow: var(--shadow);
    }

    .modal-header {
      background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
      color: var(--white);
      border-radius: var(--border-radius) var(--border-radius) 0 0;
    }

    .modal-footer {
      border-top: none;
    }

    .btn-primary {
      background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
      border: none;
      border-radius: 25px;
    }

    .btn-primary:hover {
      transform: translateY(-2px);
      box-shadow: var(--shadow);
    }

    .btn-danger {
      background: var(--danger-color);
      border: none;
      border-radius: 25px;
    }

    .btn-danger:hover {
      transform: translateY(-2px);
      box-shadow: var(--shadow);
    }

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


    <!-- Subscription Management Section -->
    <section id="subscriptions" class="section mt-5">
      <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
          <h2>Manage Subscriptions</h2>
          <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addSubscriptionModal">
            <i class="fas fa-plus"></i> Add Subscription
          </button>
          <button class="btn btn-primary">
            <i class="fas fa-plus"></i> <a href="vendor_subscriptions.php" class="text-decoration-none text-white">Check Subscribers</a>
          </button>
        </div>
        <div class="row">
          <div class="col-12">
            <?php if (empty($subscriptions)): ?>
              <p class="text-center text-muted">No subscriptions found. Create one to get started.</p>
            <?php else: ?>
              <?php foreach ($subscriptions as $subscription): ?>
                <div class="subscription-item fade-in">
                  <div class="subscription-item-info">
                    <h5 class="subscription-item-title"><?php echo htmlspecialchars($subscription['plan_type']); ?> Plan</h5>
                    <p class="subscription-item-details">
                      Dishes: <?php echo htmlspecialchars($subscription['dish_limit']); ?> per meal<br>
                      Meal Times: <?php echo htmlspecialchars($subscription['meal_times']); ?><br>
                      Price: PKR <?php echo number_format($subscription['price'], 2); ?><br>
                      Validity: <?php echo htmlspecialchars($subscription['validity_period']) . ' ' . htmlspecialchars($subscription['validity_unit']); ?><br>
                      Discount: <?php echo number_format($subscription['discount_percentage'], 2); ?>%<br>
                      Non-Subscriber Delivery Fee: PKR <?php echo number_format($subscription['non_subscriber_delivery_fee'], 2); ?><br>
                      Status: <?php echo htmlspecialchars($subscription['status']); ?>
                    </p>
                  </div>
                  <div class="subscription-item-actions">
                    <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#editSubscriptionModal<?php echo $subscription['id']; ?>"><i class="fas fa-edit"></i> Edit</button>
                    <form method="POST" style="display: inline;">
                      <input type="hidden" name="subscription_id" value="<?php echo $subscription['id']; ?>">
                      <button type="submit" name="delete_subscription" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this subscription?');"><i class="fas fa-trash"></i> Delete</button>
                    </form>
                  </div>
                </div>

                <!-- Edit Subscription Modal -->
                <div class="modal fade" id="editSubscriptionModal<?php echo $subscription['id']; ?>" tabindex="-1" aria-labelledby="editSubscriptionModalLabel<?php echo $subscription['id']; ?>" aria-hidden="true">
                  <div class="modal-dialog">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h5 class="modal-title" id="editSubscriptionModalLabel<?php echo $subscription['id']; ?>">Edit Subscription</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                      </div>
                      <form method="POST">
                        <div class="modal-body">
                          <input type="hidden" name="subscription_id" value="<?php echo $subscription['id']; ?>">
                          <div class="mb-3">
                            <label for="plan_type_<?php echo $subscription['id']; ?>" class="form-label">Plan Type</label>
                            <select class="form-select" id="plan_type_<?php echo $subscription['id']; ?>" name="plan_type" required>
                              <option value="Basic" <?php echo $subscription['plan_type'] == 'Basic' ? 'selected' : ''; ?>>Basic</option>
                              <option value="Standard" <?php echo $subscription['plan_type'] == 'Standard' ? 'selected' : ''; ?>>Standard</option>
                              <option value="Premium" <?php echo $subscription['plan_type'] == 'Premium' ? 'selected' : ''; ?>>Premium</option>
                            </select>
                          </div>
                          <div class="mb-3">
                            <label for="dish_limit_<?php echo $subscription['id']; ?>" class="form-label">Dish Limit per Meal</label>
                            <input type="number" class="form-control" id="dish_limit_<?php echo $subscription['id']; ?>" name="dish_limit" min="1" value="<?php echo htmlspecialchars($subscription['dish_limit']); ?>" required>
                          </div>
                          <div class="mb-3">
                            <label for="meal_times_<?php echo $subscription['id']; ?>" class="form-label">Meal Times</label>
                            <div>
                              <?php
                              $meal_times = explode(',', $subscription['meal_times']);
                              ?>
                              <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" id="morning_<?php echo $subscription['id']; ?>" name="meal_times[]" value="Morning" <?php echo in_array('Morning', $meal_times) ? 'checked' : ''; ?>>
                                <label class="form-check-label" for="morning_<?php echo $subscription['id']; ?>">Morning</label>
                              </div>
                              <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" id="afternoon_<?php echo $subscription['id']; ?>" name="meal_times[]" value="Afternoon" <?php echo in_array('Afternoon', $meal_times) ? 'checked' : ''; ?>>
                                <label class="form-check-label" for="afternoon_<?php echo $subscription['id']; ?>">Afternoon</label>
                              </div>
                              <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" id="evening_<?php echo $subscription['id']; ?>" name="meal_times[]" value="Evening" <?php echo in_array('Evening', $meal_times) ? 'checked' : ''; ?>>
                                <label class="form-check-label" for="evening_<?php echo $subscription['id']; ?>">Evening</label>
                              </div>
                            </div>
                          </div>
                          <div class="mb-3">
                            <label for="price_<?php echo $subscription['id']; ?>" class="form-label">Price (PKR)</label>
                            <input type="number" step="0.01" class="form-control" id="price_<?php echo $subscription['id']; ?>" name="price" min="0" value="<?php echo htmlspecialchars($subscription['price']); ?>" required>
                          </div>
                          <div class="mb-3">
                            <label for="validity_period_<?php echo $subscription['id']; ?>" class="form-label">Validity Period</label>
                            <div class="input-group">
                              <input type="number" class="form-control" id="validity_period_<?php echo $subscription['id']; ?>" name="validity_period" min="1" value="<?php echo htmlspecialchars($subscription['validity_period']); ?>" required>
                              <select class="form-select" id="validity_unit_<?php echo $subscription['id']; ?>" name="validity_unit" required>
                                <option value="Days" <?php echo $subscription['validity_unit'] == 'Days' ? 'selected' : ''; ?>>Days</option>
                                <option value="Weeks" <?php echo $subscription['validity_unit'] == 'Weeks' ? 'selected' : ''; ?>>Weeks</option>
                                <option value="Months" <?php echo $subscription['validity_unit'] == 'Months' ? 'selected' : ''; ?>>Months</option>
                              </select>
                            </div>
                          </div>
                          <div class="mb-3">
                            <label for="non_subscriber_delivery_fee_<?php echo $subscription['id']; ?>" class="form-label">Non-Subscriber Delivery Fee (PKR)</label>
                            <input type="number" step="0.01" class="form-control" id="non_subscriber_delivery_fee_<?php echo $subscription['id']; ?>" name="non_subscriber_delivery_fee" min="0" value="<?php echo htmlspecialchars($subscription['non_subscriber_delivery_fee']); ?>" required>
                          </div>
                          <div class="mb-3">
                            <label for="status_<?php echo $subscription['id']; ?>" class="form-label">Status</label>
                            <select class="form-select" id="status_<?php echo $subscription['id']; ?>" name="status" required>
                              <option value="active" <?php echo $subscription['status'] == 'active' ? 'selected' : ''; ?>>Active</option>
                              <option value="inactive" <?php echo $subscription['status'] == 'inactive' ? 'selected' : ''; ?>>Inactive</option>
                            </select>
                          </div>
                        </div>
                        <div class="modal-footer">
                          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                          <button type="submit" name="update_subscription" class="btn btn-primary">Update Subscription</button>
                        </div>
                      </form>
                    </div>
                  </div>
                </div>
              <?php endforeach; ?>
            <?php endif; ?>
          </div>
        </div>
      </div>
    </section>

    <!-- Add Subscription Modal -->
    <div class="modal fade" id="addSubscriptionModal" tabindex="-1" aria-labelledby="addSubscriptionModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="addSubscriptionModalLabel">Add New Subscription</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <form method="POST">
            <div class="modal-body">
              <div class="mb-3">
                <label for="plan_type" class="form-label">Plan Type</label>
                <select class="form-select" id="plan_type" name="plan_type" required>
                  <option value="Basic">Basic (10% Discount, Free Delivery)</option>
                  <option value="Standard">Standard (15% Discount, Free Delivery)</option>
                  <option value="Premium">Premium (20% Discount, Free Delivery)</option>
                </select>
              </div>
              <div class="mb-3">
                <label for="dish_limit" class="form-label">Dish Limit per Meal</label>
                <input type="number" class="form-control" id="dish_limit" name="dish_limit" min="1" required>
              </div>
              <div class="mb-3">
                <label for="meal_times" class="form-label">Meal Times</label>
                <div>
                  <div class="form-check form-check-inline">
                    <input class="form-check-input" type="checkbox" id="morning" name="meal_times[]" value="Morning">
                    <label class="form-check-label" for="morning">Morning</label>
                  </div>
                  <div class="form-check form-check-inline">
                    <input class="form-check-input" type="checkbox" id="afternoon" name="meal_times[]" value="Afternoon">
                    <label class="form-check-label" for="afternoon">Afternoon</label>
                  </div>
                  <div class="form-check form-check-inline">
                    <input class="form-check-input" type="checkbox" id="evening" name="meal_times[]" value="Evening">
                    <label class="form-check-label" for="evening">Evening</label>
                  </div>
                </div>
              </div>
              <div class="mb-3">
                <label for="price" class="form-label">Price (PKR)</label>
                <input type="number" step="0.01" class="form-control" id="price" name="price" min="0" required>
              </div>
              <div class="mb-3">
                <label for="validity_period" class="form-label">Validity Period</label>
                <div class="input-group">
                  <input type="number" class="form-control" id="validity_period" name="validity_period" min="1" required>
                  <select class="form-select" id="validity_unit" name="validity_unit" required>
                    <option value="Days">Days</option>
                    <option value="Weeks">Weeks</option>
                    <option value="Months">Months</option>
                  </select>
                </div>
              </div>
              <div class="mb-3">
                <label for="non_subscriber_delivery_fee" class="form-label">Non-Subscriber Delivery Fee (PKR)</label>
                <input type="number" step="0.01" class="form-control" id="non_subscriber_delivery_fee" name="non_subscriber_delivery_fee" min="0" value="250.00" required>
              </div>
            </div>
            <div class="modal-footer">
              <button type="button" class=" btn btn-secondary" data-bs-dismiss="modal">Close</button>
              <button type="submit" name="create_subscription" class="btn btn-primary">Create Subscription</button>
            </div>
          </form>
        </div>
      </div>
    </div>
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
      // Highlight active sidebar link
      const currentPage = window.location.pathname.split('/').pop() || 'vendor.php';
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
    });

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
  </script>
</body>

</html>