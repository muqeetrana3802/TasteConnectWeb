<?php
session_start();
include 'config/db.php';

// Redirect to login if not authenticated
if (!isset($_SESSION['user_id'])) {
  header("Location: login.php");
  exit();
}

// Set timezone to PKT
date_default_timezone_set('Asia/Karachi');

// Fetch user details
$user_id = (int)$_SESSION['user_id'];
$userQuery = "SELECT * FROM users WHERE id = $user_id";
$userResult = mysqli_query($conn, $userQuery);
$user = mysqli_fetch_assoc($userResult);

// Fetch user orders
$ordersQuery = "SELECT o.*, v.restaurant_name 
                FROM orders o 
                LEFT JOIN vendors v ON o.vendor_id = v.id 
                WHERE o.user_id = $user_id 
                ORDER BY o.order_date DESC";
$ordersResult = mysqli_query($conn, $ordersQuery);
$orders = [];
if ($ordersResult) {
  while ($row = mysqli_fetch_assoc($ordersResult)) {
    $orders[] = $row;
  }
}

// Fetch available subscription plans
$subscriptionsQuery = "SELECT s.*, v.restaurant_name 
                      FROM subscriptions s 
                      LEFT JOIN vendors v ON s.vendor_id = v.id 
                      WHERE s.status = 'active'";
$subscriptionsResult = mysqli_query($conn, $subscriptionsQuery);
$subscriptions = [];
if ($subscriptionsResult) {
  while ($row = mysqli_fetch_assoc($subscriptionsResult)) {
    $subscriptions[] = $row;
  }
}

// Fetch user’s active subscriptions
$userSubscriptionsQuery = "SELECT us.*, s.plan_type, s.dish_limit, s.meal_times, s.price, s.discount_percentage, s.non_subscriber_delivery_fee, s.validity_period, s.validity_unit, v.restaurant_name 
                          FROM user_subscriptions us 
                          LEFT JOIN subscriptions s ON us.subscription_id = s.id 
                          LEFT JOIN vendors v ON s.vendor_id = v.id 
                          WHERE us.user_id = $user_id AND us.status = 'active'";
$userSubscriptionsResult = mysqli_query($conn, $userSubscriptionsQuery);
$userSubscriptions = [];
if ($userSubscriptionsResult) {
  while ($row = mysqli_fetch_assoc($userSubscriptionsResult)) {
    $userSubscriptions[] = $row;
  }
}

// Handle profile update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_profile'])) {
  $first_name = mysqli_real_escape_string($conn, $_POST['first_name']);
  $last_name = mysqli_real_escape_string($conn, $_POST['last_name']);
  $email = mysqli_real_escape_string($conn, $_POST['email']);
  $phone = mysqli_real_escape_string($conn, $_POST['phone']);
  $address = mysqli_real_escape_string($conn, $_POST['address']);
  $city = mysqli_real_escape_string($conn, $_POST['city']);
  $postal_code = mysqli_real_escape_string($conn, $_POST['postal_code']);

  $updateQuery = "UPDATE users SET 
                    first_name = '$first_name', 
                    last_name = '$last_name', 
                    email = '$email', 
                    phone = '$phone', 
                    address = '$address', 
                    city = '$city', 
                    postal_code = '$postal_code' 
                    WHERE id = $user_id";
  if (mysqli_query($conn, $updateQuery)) {
    $_SESSION['profile_success'] = "Profile updated successfully!";
    header("Location: user.php");
    exit();
  } else {
    $profile_error = "Error updating profile: " . mysqli_error($conn);
  }
}

// Handle password change
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['change_password'])) {
  $current_password = $_POST['current_password'];
  $new_password = $_POST['new_password'];
  $confirm_password = $_POST['confirm_password'];

  if ($new_password !== $confirm_password) {
    $password_error = "New passwords do not match!";
  } else {
    $passwordQuery = "SELECT password FROM users WHERE id = $user_id";
    $passwordResult = mysqli_query($conn, $passwordQuery);
    $stored_password = mysqli_fetch_assoc($passwordResult)['password'];

    if (password_verify($current_password, $stored_password)) {
      $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
      $updatePasswordQuery = "UPDATE users SET password = '$hashed_password' WHERE id = $user_id";
      if (mysqli_query($conn, $updatePasswordQuery)) {
        $_SESSION['password_success'] = "Password changed successfully!";
        header("Location: user.php");
        exit();
      } else {
        $password_error = "Error changing password: " . mysqli_error($conn);
      }
    } else {
      $password_error = "Current password is incorrect!";
    }
  }
}

// Handle subscription purchase
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['subscribe'])) {
  $subscription_id = (int)$_POST['subscription_id'];
  $subQuery = "SELECT validity_period, validity_unit FROM subscriptions WHERE id = $subscription_id";
  $subResult = mysqli_query($conn, $subQuery);
  if ($subResult && $sub = mysqli_fetch_assoc($subResult)) {
    $validity_period = (int)$sub['validity_period'];
    $validity_unit = $sub['validity_unit'];
    $start_date = date('Y-m-d H:i:s');
    $end_date = date('Y-m-d H:i:s', strtotime("+$validity_period $validity_unit"));
    $status = 'active';

    $insertQuery = "INSERT INTO user_subscriptions (user_id, subscription_id, start_date, end_date, status, created_at)
                    VALUES ($user_id, $subscription_id, '$start_date', '$end_date', '$status', NOW())";
    if (mysqli_query($conn, $insertQuery)) {
      $_SESSION['subscription_success'] = "Successfully subscribed to the plan!";
      header("Location: user.php");
      exit();
    } else {
      $subscription_error = "Error subscribing to plan: " . mysqli_error($conn);
    }
  } else {
    $subscription_error = "Invalid subscription plan.";
  }
}

// Handle subscription cancellation
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['cancel_subscription'])) {
  $user_subscription_id = (int)$_POST['user_subscription_id'];
  $updateQuery = "UPDATE user_subscriptions SET status = 'cancelled', updated_at = NOW() WHERE id = $user_subscription_id AND user_id = $user_id";
  if (mysqli_query($conn, $updateQuery)) {
    $_SESSION['subscription_success'] = "Subscription cancelled successfully!";
    header("Location: user.php");
    exit();
  } else {
    $subscription_error = "Error cancelling subscription: " . mysqli_error($conn);
  }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>User Panel - FoodieHub</title>
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
      0% { background-position: 0 0; }
      100% { background-position: 100px 100px; }
    }

    .page-header h1 {
      font-size: 3rem;
      font-weight: 700;
      margin-bottom: 1rem;
      position: relative;
      z-index: 2;
    }

    .user-panel {
      padding: 3rem 0;
    }

    .sidebar {
      background: var(--white);
      border-radius: var(--border-radius);
      box-shadow: var(--shadow);
      padding: 2rem;
      height: fit-content;
    }

    .sidebar .nav-link {
      color: var(--dark-color);
      font-weight: 500;
      padding: 0.75rem 1rem;
      border-radius: 8px;
      margin-bottom: 0.5rem;
      transition: var(--transition);
      display: flex;
      align-items: center;
    }

    .sidebar .nav-link:hover,
    .sidebar .nav-link.active {
      background: var(--primary-color);
      color: var(--white);
    }

    .sidebar .nav-link i {
      margin-right: 0.75rem;
    }

    .content-area {
      background: var(--white);
      border-radius: var(--border-radius);
      box-shadow: var(--shadow);
      padding: 2rem;
    }

    .order-card,
    .subscription-card,
    .user-subscription-card {
      border: 1px solid var(--gray-300);
      border-radius: var(--border-radius);
      padding: 1.5rem;
      margin-bottom: 1.5rem;
    }

    .order-card h5,
    .subscription-card h5,
    .user-subscription-card h5 {
      color: var(--primary-color);
      font-weight: 600;
    }

    .order-status,
    .subscription-status {
      font-weight: 600;
    }

    .order-status.pending,
    .subscription-status.pending {
      color: var(--warning-color);
    }

    .order-status.processing {
      color: var(--info-color);
    }

    .order-status.delivered,
    .subscription-status.active {
      color: var(--success-color);
    }

    .order-status.cancelled,
    .subscription-status.cancelled {
      color: var(--danger-color);
    }

    .form-group label {
      font-weight: 600;
      margin-bottom: 0.5rem;
    }

    .form-group input {
      border: 2px solid var(--gray-300);
      border-radius: var(--border-radius);
      padding: 0.75rem;
      transition: var(--transition);
    }

    .form-group input:focus {
      border-color: var(--primary-color);
      box-shadow: 0 0 0 0.2rem rgba(255, 107, 53, 0.25);
      outline: none;
    }

    .btn-submit,
    .btn-subscribe {
      background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
      border: none;
      color: var(--white);
      padding: 0.75rem 1.5rem;
      border-radius: 25px;
      font-weight: 600;
      transition: var(--transition);
    }

    .btn-submit:hover,
    .btn-subscribe:hover {
      transform: translateY(-2px);
      box-shadow: var(--shadow);
    }

    .btn-cancel-subscription {
      background: var(--danger-color);
      color: var(--white);
      border-radius: 25px;
      padding: 0.75rem 1.5rem;
      transition: var(--transition);
    }

    .btn-cancel-subscription:hover {
      transform: translateY(-2px);
      box-shadow: var(--shadow);
    }

    .btn-logout {
      background: var(--danger-color);
      color: var(--white);
      border-radius: 25px;
      padding: 0.75rem 1.5rem;
      transition: var(--transition);
      text-align: center;
      display: block;
    }

    .btn-logout:hover {
      background: var(--danger-color);
      transform: translateY(-2px);
      box-shadow: var(--shadow);
      color: var(--white);
    }

    @media (max-width: 768px) {
      .page-header h1 {
        font-size: 2rem;
      }

      .sidebar {
        margin-bottom: 2rem;
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
  <!-- Navbar -->
  <?php include 'includes/navbar.php'; ?>

  <!-- Page Header -->
  <section class="page-header">
    <div class="container">
      <div class="row">
        <div class="col-lg-8">
          <h1>User Panel</h1>
          <p>Manage your profile, track orders, subscriptions, and more</p>
        </div>
      </div>
    </div>
  </section>

  <!-- User Panel -->
  <section class="user-panel">
    <div class="container">
      <div class="row">
        <!-- Sidebar -->
        <div class="col-lg-3 col-md-4">
          <div class="sidebar fade-in">
            <nav class="nav flex-column">
              <a class="nav-link active" href="#profile" data-bs-toggle="tab"><i class="fas fa-user"></i> Profile</a>
              <a class="nav-link" href="#orders" data-bs-toggle="tab"><i class="fas fa-box"></i> My Orders</a>
              <a class="nav-link" href="#subscriptions" data-bs-toggle="tab"><i class="fas fa-ticket-alt"></i> Subscriptions</a>
              <a class="nav-link" href="#password" data-bs-toggle="tab"><i class="fas fa-lock"></i> Change Password</a>
              <a class="nav-link btn-logout" href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
            </nav>
          </div>
        </div>

        <!-- Content Area -->
        <div class="col-lg-9 col-md-8">
          <div class="content-area fade-in">
            <div class="tab-content">
              <!-- Profile Tab -->
              <div class="tab-pane fade show active" id="profile">
                <h3>Profile Settings</h3>
                <?php if (isset($_SESSION['profile_success'])): ?>
                  <div class="alert alert-success">
                    <?php echo $_SESSION['profile_success'];
                    unset($_SESSION['profile_success']); ?>
                  </div>
                <?php endif; ?>
                <?php if (isset($profile_error)): ?>
                  <div class="alert alert-danger">
                    <?php echo $profile_error; ?>
                  </div>
                <?php endif; ?>
                <form method="POST" action="">
                  <div class="row">
                    <div class="col-md-6">
                      <div class="form-group mb-3">
                        <label for="first_name">First Name</label>
                        <input type="text" class="form-control" id="first_name" name="first_name" value="<?php echo htmlspecialchars($user['first_name']); ?>" required>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-group mb-3">
                        <label for="last_name">Last Name</label>
                        <input type="text" class="form-control" id="last_name" name="last_name" value="<?php echo htmlspecialchars($user['last_name']); ?>" required>
                      </div>
                    </div>
                  </div>
                  <div class="form-group mb-3">
                    <label for="email">Email Address</label>
                    <input type="email" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>
                  </div>
                  <div class="form-group mb-3">
                    <label for="phone">Phone Number</label>
                    <input type="text" class="form-control" id="phone" name="phone" value="<?php echo htmlspecialchars($user['phone']); ?>">
                  </div>
                  <div class="form-group mb-3">
                    <label for="address">Address</label>
                    <input type="text" class="form-control" id="address" name="address" value="<?php echo htmlspecialchars($user['address']); ?>">
                  </div>
                  <div class="row">
                    <div class="col-md-6">
                      <div class="form-group mb-3">
                        <label for="city">City</label>
                        <input type="text" class="form-control" id="city" name="city" value="<?php echo htmlspecialchars($user['city']); ?>">
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-group mb-3">
                        <label for="postal_code">Postal Code</label>
                        <input type="text" class="form-control" id="postal_code" name="postal_code" value="<?php echo htmlspecialchars($user['postal_code']); ?>">
                      </div>
                    </div>
                  </div>
                  <button type="submit" name="update_profile" class="btn-submit">Update Profile</button>
                </form>
              </div>

              <!-- Orders Tab -->
              <div class="tab-pane fade" id="orders">
                <h3>My Orders</h3>
                <?php if (empty($orders)): ?>
                  <div class="no-results text-center">
                    <i class="fas fa-box-open fa-2x mb-3"></i>
                    <h4>No orders found</h4>
                    <p>Place your first order now!</p>
                  </div>
                <?php else: ?>
                  <?php foreach ($orders as $order): ?>
                    <div class="order-card">
                      <h5>Order #<?php echo $order['id']; ?> - <?php echo htmlspecialchars($order['restaurant_name']); ?></h5>
                      <p><strong>Date:</strong> <?php echo date('M d, Y H:i', strtotime($order['order_date'])); ?></p>
                      <p><strong>Delivery Address:</strong> <?php echo htmlspecialchars($order['delivery_address']); ?></p>
                      <p><strong>Total:</strong> PKR <?php echo number_format($order['total'], 2); ?></p>
                      <p><strong>Status:</strong> <span class="order-status <?php echo strtolower($order['status']); ?>"><?php echo htmlspecialchars($order['status']); ?></span></p>
                      <a href="order_confirmation.php?order_id=<?php echo $order['id']; ?>" class="btn btn-outline-primary btn-sm">View Details</a>
                    </div>
                  <?php endforeach; ?>
                <?php endif; ?>
              </div>

              <!-- Subscriptions Tab -->
              <div class="tab-pane fade" id="subscriptions">
                <h3>Subscriptions</h3>
                <?php if (isset($_SESSION['subscription_success'])): ?>
                  <div class="alert alert-success">
                    <?php echo $_SESSION['subscription_success'];
                    unset($_SESSION['subscription_success']); ?>
                  </div>
                <?php endif; ?>
                <?php if (isset($subscription_error)): ?>
                  <div class="alert alert-danger">
                    <?php echo $subscription_error; ?>
                  </div>
                <?php endif; ?>

                <!-- Active Subscriptions -->
                <h4>My Active Subscriptions</h4>
                <?php if (empty($userSubscriptions)): ?>
                  <div class="no-results text-center">
                    <i class="fas fa-ticket-alt fa-2x mb-3"></i>
                    <h4>No active subscriptions</h4>
                    <p>Explore available plans below to subscribe!</p>
                  </div>
                <?php else: ?>
                  <?php foreach ($userSubscriptions as $sub): ?>
                    <div class="user-subscription-card">
                      <h5><?php echo htmlspecialchars($sub['plan_type']); ?> - <?php echo htmlspecialchars($sub['restaurant_name']); ?></h5>
                      <p><strong>Dishes:</strong> <?php echo htmlspecialchars($sub['dish_limit']); ?> per meal</p>
                      <p><strong>Meal Times:</strong> <?php echo htmlspecialchars($sub['meal_times']); ?></p>
                      <p><strong>Price:</strong> PKR <?php echo number_format($sub['price'], 2); ?></p>
                      <p><strong>Discount:</strong> <?php echo number_format($sub['discount_percentage'], 2); ?>% on orders</p>
                      <p><strong>Delivery:</strong> Free</p>
                      <p><strong>Validity:</strong> <?php echo htmlspecialchars($sub['validity_period']) . ' ' . htmlspecialchars($sub['validity_unit']); ?></p>
                      <p><strong>Start Date:</strong> <?php echo date('M d, Y', strtotime($sub['start_date'])); ?></p>
                      <p><strong>End Date:</strong> <?php echo date('M d, Y', strtotime($sub['end_date'])); ?></p>
                      <p><strong>Status:</strong> <span class="subscription-status <?php echo strtolower($sub['status']); ?>"><?php echo htmlspecialchars($sub['status']); ?></span></p>
                      <form method="POST" style="display: inline;">
                        <input type="hidden" name="user_subscription_id" value="<?php echo $sub['id']; ?>">
                        <button type="submit" name="cancel_subscription" class="btn-cancel-subscription" onclick="return confirm('Are you sure you want to cancel this subscription?');">Cancel Subscription</button>
                      </form>
                    </div>
                  <?php endforeach; ?>
                <?php endif; ?>

                <!-- Available Subscription Plans -->
                <h4 class="mt-4">Available Subscription Plans</h4>
                <?php if (empty($subscriptions)): ?>
                  <div class="no-results text-center">
                    <i class="fas fa-ticket-alt fa-2x mb-3"></i>
                    <h4>No subscription plans available</h4>
                    <p>Check back later for new plans!</p>
                  </div>
                <?php else: ?>
                  <?php foreach ($subscriptions as $sub): ?>
                    <div class="subscription-card">
                      <h5><?php echo htmlspecialchars($sub['plan_type']); ?> - <?php echo htmlspecialchars($sub['restaurant_name']); ?></h5>
                      <p><strong>Dishes:</strong> <?php echo htmlspecialchars($sub['dish_limit']); ?> per meal</p>
                      <p><strong>Meal Times:</strong> <?php echo htmlspecialchars($sub['meal_times']); ?></p>
                      <p><strong>Price:</strong> PKR <?php echo number_format($sub['price'], 2); ?></p>
                      <p><strong>Discount:</strong> <?php echo number_format($sub['discount_percentage'], 2); ?>% on orders</p>
                      <p><strong>Delivery:</strong> Free for subscribers (Non-subscribers: PKR <?php echo number_format($sub['non_subscriber_delivery_fee'], 2); ?>)</p>
                      <p><strong>Validity:</strong> <?php echo htmlspecialchars($sub['validity_period']) . ' ' . htmlspecialchars($sub['validity_unit']); ?></p>
                      <form method="POST" style="display: inline;">
                        <input type="hidden" name="subscription_id" value="<?php echo $sub['id']; ?>">
                        <button type="submit" name="subscribe" class="btn-subscribe">Subscribe Now</button>
                      </form>
                    </div>
                  <?php endforeach; ?>
                <?php endif; ?>
              </div>

              <!-- Change Password Tab -->
              <div class="tab-pane fade" id="password">
                <h3>Change Password</h3>
                <?php if (isset($_SESSION['password_success'])): ?>
                  <div class="alert alert-success">
                    <?php echo $_SESSION['password_success'];
                    unset($_SESSION['password_success']); ?>
                  </div>
                <?php endif; ?>
                <?php if (isset($password_error)): ?>
                  <div class="alert alert-danger">
                    <?php echo $password_error; ?>
                  </div>
                <?php endif; ?>
                <form method="POST" action="">
                  <div class="form-group mb-3">
                    <label for="current_password">Current Password</label>
                    <input type="password" class="form-control" id="current_password" name="current_password" required>
                  </div>
                  <div class="form-group mb-3">
                    <label for="new_password">New Password</label>
                    <input type="password" class="form-control" id="new_password" name="new_password" required>
                  </div>
                  <div class="form-group mb-3">
                    <label for="confirm_password">Confirm New Password</label>
                    <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
                  </div>
                  <button type="submit" name="change_password" class="btn-submit">Change Password</button>
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- Bootstrap JS -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/js/bootstrap.bundle.min.js"></script>

  <!-- Custom JavaScript -->
  <script>
    document.addEventListener('DOMContentLoaded', function() {
      // Trigger fade-in animation
      setTimeout(() => {
        document.querySelectorAll('.fade-in').forEach(el => {
          el.classList.add('visible');
        });
      }, 100);

      // Handle sidebar navigation
      document.querySelectorAll('.sidebar .nav-link').forEach(link => {
        link.addEventListener('click', function() {
          document.querySelectorAll('.sidebar .nav-link').forEach(l => l.classList.remove('active'));
          this.classList.add('active');
        });
      });
    });
  </script>
</body>

</html>