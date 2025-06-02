<?php
session_start();

if (!isset($_SESSION['admin_id'])) {
  header("Location: login.php?error=Please log in to access this page");
  exit;
}
require_once '../config/db.php';


// Handle add customer
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_customer'])) {
  $first_name = $_POST['first_name'];
  $last_name = $_POST['last_name'];
  $email = $_POST['email'];
  $phone = $_POST['phone'];
  $address = $_POST['address'];
  $city = $_POST['city'];
  $postal_code = $_POST['postal_code'];

  $add_query = "INSERT INTO users (first_name, last_name, email, phone, address, city, postal_code) VALUES (?, ?, ?, ?, ?, ?, ?)";
  $stmt = $conn->prepare($add_query);
  $stmt->bind_param("sssssss", $first_name, $last_name, $email, $phone, $address, $city, $postal_code);
  $stmt->execute();
  header("Location: customers.php?success=Customer added successfully");
  exit;
}

// Handle edit customer
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_customer'])) {
  $customer_id = $_POST['customer_id'];
  $first_name = $_POST['first_name'];
  $last_name = $_POST['last_name'];
  $email = $_POST['email'];
  $phone = $_POST['phone'];
  $address = $_POST['address'];
  $city = $_POST['city'];
  $postal_code = $_POST['postal_code'];

  $update_query = "UPDATE users SET first_name = ?, last_name = ?, email = ?, phone = ?, address = ?, city = ?, postal_code = ? WHERE id = ?";
  $stmt = $conn->prepare($update_query);
  $stmt->bind_param("sssssssi", $first_name, $last_name, $email, $phone, $address, $city, $postal_code, $customer_id);
  $stmt->execute();
  header("Location: customers.php?success=Customer updated successfully");
  exit;
}

// Handle delete customer
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['delete_customer'])) {
  $customer_id = $_POST['customer_id'];
  $delete_query = "DELETE FROM users WHERE id = ?";
  $stmt = $conn->prepare($delete_query);
  $stmt->bind_param("i", $customer_id);
  $stmt->execute();
  header("Location: customers.php?success=Customer deleted successfully");
  exit;
}

// Handle export customers
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['export_customers'])) {
  header('Content-Type: text/csv; charset=utf-8');
  header('Content-Disposition: attachment; filename=customers_export.csv');
  $output = fopen('php://output', 'w');
  fputcsv($output, ['ID', 'First Name', 'Last Name', 'Email', 'Phone', 'Address', 'City', 'Postal Code', 'Total Orders', 'Total Spent', 'Joined']);

  $customers_query = "SELECT id, first_name, last_name, email, phone, address, city, postal_code, created_at FROM users";
  $customers_result = $conn->query($customers_query);
  while ($customer = $customers_result->fetch_assoc()) {
    $stats = $customer_stats[$customer['id']] ?? ['total_orders' => 0, 'total_spent' => 0];
    fputcsv($output, [
      $customer['id'],
      $customer['first_name'],
      $customer['last_name'],
      $customer['email'],
      $customer['phone'],
      $customer['address'],
      $customer['city'],
      $customer['postal_code'],
      $stats['total_orders'],
      number_format($stats['total_spent'], 2),
      date('Y-m-d', strtotime($customer['created_at']))
    ]);
  }
  fclose($output);
  exit;
}

// Fetch edit customer details
$edit_customer = null;
if (isset($_GET['action']) && $_GET['action'] == 'edit' && isset($_GET['customer_id'])) {
  $customer_id = $_GET['customer_id'];
  $edit_query = "SELECT * FROM users WHERE id = ?";
  $stmt = $conn->prepare($edit_query);
  $stmt->bind_param("i", $customer_id);
  $stmt->execute();
  $edit_customer = $stmt->get_result()->fetch_assoc();
}

// Fetch all customers
$customers_query = "SELECT id, first_name, last_name, email, phone, address, city, postal_code, created_at 
                    FROM users";
$customers_result = $conn->query($customers_query);

// Fetch total orders and spent per customer
$customer_stats_query = "SELECT user_id, COUNT(*) as total_orders, SUM(total) as total_spent 
                        FROM orders 
                        GROUP BY user_id";
$customer_stats_result = $conn->query($customer_stats_query);
$customer_stats = [];
while ($row = $customer_stats_result->fetch_assoc()) {
  $customer_stats[$row['user_id']] = $row;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>FoodHub - Customers Management</title>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
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
      --sidebar-width: 280px;
    }

    body {
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      background-color: var(--light-gray);
      overflow-x: hidden;
    }

    .sidebar {
      position: fixed;
      top: 0;
      left: 0;
      width: var(--sidebar-width);
      height: 100vh;
      background: linear-gradient(135deg, var(--primary-color), #ff8c42);
      color: var(--white);
      z-index: 1000;
      transition: var(--transition);
      overflow-y: auto;
    }

    .sidebar.collapsed {
      width: 80px;
    }

    .sidebar-header {
      padding: 2rem 1.5rem;
      text-align: center;
      border-bottom: 1px solid rgba(255, 255, 255, 0.1);
      position: relative;
    }

    .sidebar-header::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      bottom: 0;
      background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><circle cx="20" cy="20" r="2" fill="rgba(255,255,255,0.1)"/><circle cx="80" cy="80" r="3" fill="rgba(255,255,255,0.1)"/></svg>');
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
      font-size: 2.5rem;
      margin-bottom: 0.5rem;
      position: relative;
      z-index: 1;
    }

    .brand-name {
      font-size: 1.5rem;
      font-weight: bold;
      position: relative;
      z-index: 1;
    }

    .sidebar-menu {
      padding: 1rem 0;
    }

    .menu-item {
      display: block;
      padding: 1rem 1.5rem;
      color: var(--white);
      text-decoration: none;
      transition: var(--transition);
      border: none;
      background: none;
      width: 100%;
      text-align: left;
      cursor: pointer;
    }

    .menu-item:hover,
    .menu-item.active {
      background: rgba(255, 255, 255, 0.15);
      color: var(--white);
      transform: translateX(5px);
    }

    .menu-item i {
      width: 20px;
      margin-right: 1rem;
      font-size: 1.1rem;
    }

    .sidebar.collapsed .menu-item span {
      opacity: 0;
      width: 0;
    }

    .main-content {
      margin-left: var(--sidebar-width);
      transition: var(--transition);
      min-height: 100vh;
    }

    .main-content.expanded {
      margin-left: 80px;
    }

    .header {
      background: var(--white);
      padding: 1rem 2rem;
      box-shadow: var(--shadow);
      display: flex;
      justify-content: space-between;
      align-items: center;
      position: sticky;
      top: 0;
      z-index: 100;
    }

    .header-left {
      display: flex;
      align-items: center;
      gap: 1rem;
    }

    .sidebar-toggle {
      background: none;
      border: none;
      font-size: 1.2rem;
      color: var(--dark-gray);
      cursor: pointer;
      padding: 0.5rem;
      border-radius: 8px;
      transition: var(--transition);
    }

    .sidebar-toggle:hover {
      background: var(--light-gray);
    }

    .search-bar {
      position: relative;
      min-width: 300px;
    }

    .search-bar input {
      border: 2px solid var(--light-gray);
      border-radius: 25px;
      padding: 0.75rem 1rem 0.75rem 3rem;
      width: 100%;
      font-size: 0.9rem;
      transition: var(--transition);
    }

    .search-bar input:focus {
      border-color: var(--primary-color);
      box-shadow: 0 0 0 0.2rem rgba(255, 107, 53, 0.25);
      outline: none;
    }

    .search-bar i {
      position: absolute;
      left: 1rem;
      top: 50%;
      transform: translateY(-50%);
      color: var(--medium-gray);
    }

    .header-right {
      display: flex;
      align-items: center;
      gap: 1rem;
    }

    .notification-bell {
      position: relative;
      background: none;
      border: none;
      font-size: 1.2rem;
      color: var(--dark-gray);
      cursor: pointer;
      padding: 0.5rem;
      border-radius: 50%;
      transition: var(--transition);
    }

    .notification-bell:hover {
      background: var(--light-gray);
    }

    .notification-badge {
      position: absolute;
      top: 0;
      right: 0;
      background: var(--danger-color);
      color: var(--white);
      border-radius: 50%;
      width: 18px;
      height: 18px;
      font-size: 0.7rem;
      display: flex;
      align-items: center;
      justify-content: center;
    }

    .user-dropdown {
      display: flex;
      align-items: center;
      gap: 0.5rem;
      cursor: pointer;
      padding: 0.5rem;
      border-radius: 8px;
      transition: var(--transition);
    }

    .user-dropdown:hover {
      background: var(--light-gray);
    }

    .user-avatar {
      width: 40px;
      height: 40px;
      border-radius: 50%;
      background: linear-gradient(135deg, var(--primary-color), #ff8c42);
      display: flex;
      align-items: center;
      justify-content: center;
      color: var(--white);
      font-weight: bold;
    }

    .dashboard-content {
      padding: 2rem;
    }

    .page-title {
      color: var(--dark-color);
      font-size: 2rem;
      font-weight: bold;
      margin-bottom: 0.5rem;
    }

    .page-subtitle {
      color: var(--medium-gray);
      margin-bottom: 2rem;
    }

    .quick-actions {
      display: flex;
      gap: 1rem;
      margin-bottom: 2rem;
    }

    .quick-action-btn {
      display: inline-flex;
      align-items: center;
      gap: 0.5rem;
      padding: 0.5rem 1rem;
      background: var(--light-gray);
      color: var(--dark-gray);
      border: 1px solid var(--medium-gray);
      border-radius: 8px;
      font-size: 0.9rem;
      font-weight: 600;
      text-decoration: none;
      cursor: pointer;
      transition: var(--transition);
    }

    .quick-action-btn:hover {
      background: var(--primary-color);
      color: var(--white);
      border-color: var(--primary-color);
      transform: translateY(-2px);
    }

    .quick-action-btn i {
      font-size: 1rem;
    }

    .content-card {
      background: var(--white);
      border-radius: var(--border-radius);
      box-shadow: var(--shadow);
      overflow: hidden;
      margin-bottom: 2rem;
    }

    .card-header {
      padding: 1.5rem;
      border-bottom: 1px solid var(--light-gray);
      display: flex;
      justify-content: space-between;
      align-items: center;
    }

    .card-title {
      color: var(--dark-color);
      font-weight: bold;
      font-size: 1.2rem;
    }

    .card-body {
      padding: 1.5rem;
    }

    .custom-table {
      width: 100%;
      margin-bottom: 0;
    }

    .custom-table th {
      background: var(--light-gray);
      color: var(--dark-gray);
      font-weight: 600;
      font-size: 0.9rem;
      padding: 1rem;
      border: none;
    }

    .custom-table td {
      padding: 1rem;
      border-bottom: 1px solid var(--light-gray);
      vertical-align: middle;
    }

    .custom-table tr:hover {
      background: var(--light-orange);
    }

    .action-btn {
      padding: 0.5rem;
      border: none;
      border-radius: 8px;
      cursor: pointer;
      transition: var(--transition);
      margin: 0 0.25rem;
    }

    .action-btn:hover {
      transform: translateY(-2px);
    }

    .action-btn.view {
      background: rgba(40, 167, 69, 0.1);
      color: var(--success-color);
    }

    .action-btn.edit {
      background: rgba(23, 162, 184, 0.1);
      color: var(--info-color);
    }

    .action-btn.delete {
      background: rgba(220, 53, 69, 0.1);
      color: var(--danger-color);
    }

    .filter-bar {
      margin-bottom: 1rem;
    }

    .filter-bar input {
      border: 2px solid var(--light-gray);
      border-radius: 25px;
      padding: 0.75rem 1rem;
      width: 100%;
      max-width: 300px;
      font-size: 0.9rem;
      transition: var(--transition);
    }

    .filter-bar input:focus {
      border-color: var(--primary-color);
      box-shadow: 0 0 0 0.2rem rgba(255, 107, 53, 0.25);
      outline: none;
    }

    @media (max-width: 768px) {
      .sidebar {
        transform: translateX(-100%);
      }

      .sidebar.mobile-open {
        transform: translateX(0);
      }

      .main-content {
        margin-left: 0;
      }

      .header {
        padding: 1rem;
      }

      .search-bar {
        display: none;
      }

      .dashboard-content {
        padding: 1rem;
      }

      .quick-actions {
        flex-direction: column;
      }
    }
  </style>
</head>

<body>
  <!-- Sidebar -->
  <?php include 'includes/sidebar.php'; ?>

  <!-- Main Content -->
  <div class="main-content" id="mainContent">
    <!-- Header -->
    <?php include 'includes/header.php'; ?>

    <!-- Dashboard Content -->
    <div class="dashboard-content">
      <h1 class="page-title">Customers Management</h1>
      <p class="page-subtitle">Manage and view customer details and their order history.</p>

      <!-- Add Customer Form -->
      <?php if (isset($_GET['action']) && $_GET['action'] == 'add'): ?>
        <div class="content-card">
          <div class="card-header">
            <h3 class="card-title">Add New Customer</h3>
          </div>
          <div class="card-body">
            <form method="post" action="">
              <div class="mb-3">
                <label for="first_name" class="form-label">First Name</label>
                <input type="text" name="first_name" id="first_name" class="form-control" required>
              </div>
              <div class="mb-3">
                <label for="last_name" class="form-label">Last Name</label>
                <input type="text" name="last_name" id="last_name" class="form-control" required>
              </div>
              <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" name="email" id="email" class="form-control" required>
              </div>
              <div class="mb-3">
                <label for="phone" class="form-label">Phone</label>
                <input type="text" name="phone" id="phone" class="form-control" required>
              </div>
              <div class="mb-3">
                <label for="address" class="form-label">Address</label>
                <input type="text" name="address" id="address" class="form-control" required>
              </div>
              <div class="mb-3">
                <label for="city" class="form-label">City</label>
                <input type="text" name="city" id="city" class="form-control">
              </div>
              <div class="mb-3">
                <label for="postal_code" class="form-label">Postal Code</label>
                <input type="text" name="postal_code" id="postal_code" class="form-control">
              </div>
              <button type="submit" name="add_customer" class="btn btn-primary">Add Customer</button>
              <a href="customers.php" class="btn btn-secondary">Cancel</a>
            </form>
          </div>
        </div>
      <?php endif; ?>

      <!-- Edit Customer Form -->
      <?php if ($edit_customer): ?>
        <div class="content-card">
          <div class="card-header">
            <h3 class="card-title">Edit Customer #<?php echo htmlspecialchars($edit_customer['id']); ?></h3>
          </div>
          <div class="card-body">
            <form method="post" action="">
              <input type="hidden" name="customer_id" value="<?php echo $edit_customer['id']; ?>">
              <div class="mb-3">
                <label for="first_name" class="form-label">First Name</label>
                <input type="text" name="first_name" id="first_name" class="form-control" value="<?php echo htmlspecialchars($edit_customer['first_name']); ?>" required>
              </div>
              <div class="mb-3">
                <label for="last_name" class="form-label">Last Name</label>
                <input type="text" name="last_name" id="last_name" class="form-control" value="<?php echo htmlspecialchars($edit_customer['last_name']); ?>" required>
              </div>
              <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" name="email" id="email" class="form-control" value="<?php echo htmlspecialchars($edit_customer['email']); ?>" required>
              </div>
              <div class="mb-3">
                <label for="phone" class="form-label">Phone</label>
                <input type="text" name="phone" id="phone" class="form-control" value="<?php echo htmlspecialchars($edit_customer['phone']); ?>" required>
              </div>
              <div class="mb-3">
                <label for="address" class="form-label">Address</label>
                <input type="text" name="address" id="address" class="form-control" value="<?php echo htmlspecialchars($edit_customer['address']); ?>" required>
              </div>
              <div class="mb-3">
                <label for="city" class="form-label">City</label>
                <input type="text" name="city" id="city" class="form-control" value="<?php echo htmlspecialchars($edit_customer['city']); ?>">
              </div>
              <div class="mb-3">
                <label for="postal_code" class="form-label">Postal Code</label>
                <input type="text" name="postal_code" id="postal_code" class="form-control" value="<?php echo htmlspecialchars($edit_customer['postal_code']); ?>">
              </div>
              <button type="submit" name="update_customer" class="btn btn-primary">Update Customer</button>
              <a href="customers.php" class="btn btn-secondary">Cancel</a>
            </form>
          </div>
        </div>
      <?php endif; ?>

      <!-- Quick Actions -->
      <div class="quick-actions">
        <a href="customers.php?action=add" class="quick-action-btn"><i class="fas fa-plus"></i> Add New Customer</a>
        <form method="post" action="" style="display:inline;">
          <button type="submit" name="export_customers" class="quick-action-btn"><i class="fas fa-download"></i> Export Customers</button>
        </form>
      </div>

      <!-- Filter Bar -->
      <div class="filter-bar">
        <input type="text" id="customerFilter" placeholder="Filter by customer name or email...">
      </div>

      <!-- All Customers -->
      <!-- All Customers -->
      <div class="content-card">
        <div class="card-header">
          <h3 class="card-title">All Customers</h3>
        </div>
        <div class="card-body" style="padding: 0;">
          <div class="table-responsive"> <!-- Added Bootstrap responsive class -->
            <table class="custom-table" id="customersTable">
              <thead>
                <tr>
                  <th>Customer</th>
                  <th>Email</th>
                  <th>Phone</th>
                  <th>Address</th>
                  <th>Total Orders</th>
                  <th>Total Spent</th>
                  <th>Joined</th>
                  <th>Actions</th>
                </tr>
              </thead>
              <tbody>
                <?php while ($customer = $customers_result->fetch_assoc()): ?>
                  <?php
                  $stats = $customer_stats[$customer['id']] ?? ['total_orders' => 0, 'total_spent' => 0];
                  ?>
                  <tr>
                    <td>
                      <div style="display: flex; align-items: center; gap: 0.75rem;">
                        <div style="width: 40px; height: 40px; background: linear-gradient(135deg, var(--primary-color), #ff8c42); border-radius: 8px; display: flex; align-items: center; justify-content: center; color: white; font-weight: bold;">
                          <?php echo htmlspecialchars(substr($customer['first_name'], 0, 1) . substr($customer['last_name'], 0, 1)); ?>
                        </div>
                        <div>
                          <div style="font-weight: 600;"><?php echo htmlspecialchars($customer['first_name'] . ' ' . $customer['last_name']); ?></div>
                          <div style="font-size: 0.8rem; color: var(--medium-gray);">Customer ID: <?php echo htmlspecialchars($customer['id']); ?></div>
                        </div>
                      </div>
                    </td>
                    <td><?php echo htmlspecialchars($customer['email']); ?></td>
                    <td><?php echo htmlspecialchars($customer['phone']); ?></td>
                    <td>
                      <?php
                      $address = $customer['address'] . ($customer['city'] ? ', ' . $customer['city'] : '') . ($customer['postal_code'] ? ' ' . $customer['postal_code'] : '');
                      echo htmlspecialchars($address);
                      ?>
                    </td>
                    <td><?php echo htmlspecialchars($stats['total_orders']); ?></td>
                    <td>PKR <?php echo number_format($stats['total_spent'], 2); ?></td>
                    <td><?php echo date('Y-m-d', strtotime($customer['created_at'])); ?></td>
                    <td>
                      <!-- <button class="action-btn view" data-customer-id="<?php echo $customer['id']; ?>"><i class="fas fa-eye"></i></button> -->
                      <form method="get" action="" style="display:inline;">
                        <input type="hidden" name="action" value="edit">
                        <input type="hidden" name="customer_id" value="<?php echo $customer['id']; ?>">
                        <button type="submit" class="action-btn edit"><i class="fas fa-edit"></i></button>
                      </form>
                      <form method="post" action="" style="display:inline;" onsubmit="return confirmDelete(event)">
                        <input type="hidden" name="customer_id" value="<?php echo $customer['id']; ?>">
                        <input type="hidden" name="delete_customer" value="1">
                        <button type="submit" class="action-btn delete"><i class="fas fa-trash"></i></button>
                      </form>
                    </td>
                  </tr>
                <?php endwhile; ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>

  <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script>
    // Sidebar toggle
    const sidebarToggle = document.getElementById('sidebarToggle');
    const sidebar = document.getElementById('sidebar');
    const mainContent = document.getElementById('mainContent');
    sidebarToggle.addEventListener('click', function() {
      sidebar.classList.toggle('collapsed');
      mainContent.classList.toggle('expanded');
    });

    // Search bar logging
    const searchInput = document.querySelector('.search-bar input');
    searchInput.addEventListener('input', function() {
      console.log('Searching for:', this.value.toLowerCase());
    });

    // Notification and user dropdown logging
    document.querySelector('.notification-bell').addEventListener('click', function() {
      console.log('Notifications clicked');
    });
    document.querySelector('.user-dropdown').addEventListener('click', function() {
      console.log('User dropdown clicked');
    });

    // SweetAlert for delete confirmation
    function confirmDelete(event) {
      event.preventDefault();
      const form = event.target;
      Swal.fire({
        title: 'Are you sure?',
        text: "You won't be able to revert this!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#ff6b35',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Yes, delete it!'
      }).then((result) => {
        if (result.isConfirmed) {
          form.submit();
        }
      });
      return false;
    }

    // SweetAlert for success message
    <?php if (isset($_GET['success'])): ?>
      Swal.fire({
        icon: 'success',
        title: 'Success',
        text: '<?php echo htmlspecialchars($_GET['success']); ?>',
        confirmButtonColor: '#ff6b35'
      });
    <?php endif; ?>

    // View button action
    document.querySelectorAll('.action-btn.view').forEach(button => {
      button.addEventListener('click', function() {
        const customerId = this.getAttribute('data-customer-id');
        Swal.fire({
          icon: 'info',
          title: 'Customer Details',
          text: `Viewing details for Customer ID: ${customerId}`,
          confirmButtonColor: '#ff6b35'
        });
      });
    });

    // Table filter
    const filterInput = document.getElementById('customerFilter');
    filterInput.addEventListener('input', function() {
      const filterValue = this.value.toLowerCase();
      const rows = document.querySelectorAll('#customersTable tbody tr');
      rows.forEach(row => {
        const name = row.cells[0].textContent.toLowerCase();
        const email = row.cells[1].textContent.toLowerCase();
        row.style.display = (name.includes(filterValue) || email.includes(filterValue)) ? '' : 'none';
      });
    });
  </script>
</body>

</html>
<?php $conn->close(); ?>