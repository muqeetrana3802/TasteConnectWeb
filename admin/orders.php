<?php
session_start();

if (!isset($_SESSION['admin_id'])) {
  header("Location: login.php?error=Please log in to access this page");
  exit;
}
require_once '../config/db.php';

// Handle delete
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['delete'])) {
  $delete_id = $_POST['order_id'];
  $delete_query = "DELETE FROM orders WHERE id = ?";
  $stmt = $conn->prepare($delete_query);
  $stmt->bind_param("i", $delete_id);
  $stmt->execute();
  header("Location: orders.php?success=Order deleted successfully");
  exit;
}

// Handle update
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update'])) {
  $update_id = $_POST['update_id'];
  $new_status = $_POST['status'];
  $update_query = "UPDATE orders SET status = ? WHERE id = ?";
  $stmt = $conn->prepare($update_query);
  $stmt->bind_param("si", $new_status, $update_id);
  $stmt->execute();
  header("Location: orders.php?success=Order updated successfully");
  exit;
}

// Handle export orders
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['export_orders'])) {
  header('Content-Type: text/csv; charset=utf-8');
  header('Content-Disposition: attachment; filename=orders_export.csv');
  $output = fopen('php://output', 'w');
  fputcsv($output, ['Order ID', 'Customer', 'Restaurant', 'Items', 'Amount (PKR)', 'Date', 'Status']);

  $orders_query = "SELECT o.id, o.user_id, o.vendor_id, o.total, o.order_date, o.status, 
                            u.first_name, u.last_name, v.restaurant_name
                     FROM orders o
                     JOIN users u ON o.user_id = u.id
                     JOIN vendors v ON o.vendor_id = v.id";
  $orders_result = $conn->query($orders_query);

  $order_items_query = "SELECT oi.order_id, m.name, oi.quantity
                          FROM order_items oi
                          JOIN menu_items m ON oi.menu_item_id = m.id";
  $order_items_result = $conn->query($order_items_query);
  $order_items = [];
  while ($row = $order_items_result->fetch_assoc()) {
    $order_items[$row['order_id']][] = $row['quantity'] . 'x ' . $row['name'];
  }

  while ($order = $orders_result->fetch_assoc()) {
    fputcsv($output, [
      $order['id'],
      $order['first_name'] . ' ' . $order['last_name'],
      $order['restaurant_name'],
      implode(', ', $order_items[$order['id']] ?? []),
      number_format($order['total'], 2),
      date('Y-m-d', strtotime($order['order_date'])),
      $order['status']
    ]);
  }
  fclose($output);
  exit;
}

// Fetch edit order if action=edit
$edit_order = null;
if (isset($_GET['action']) && $_GET['action'] == 'edit' && isset($_GET['order_id'])) {
  $edit_id = $_GET['order_id'];
  $edit_query = "SELECT * FROM orders WHERE id = ?";
  $stmt = $conn->prepare($edit_query);
  $stmt->bind_param("i", $edit_id);
  $stmt->execute();
  $edit_order = $stmt->get_result()->fetch_assoc();
}

// Fetch all orders with customer and vendor details
$orders_query = "SELECT o.id, o.user_id, o.vendor_id, o.total, o.order_date, o.status, 
                        u.first_name, u.last_name, v.restaurant_name
                 FROM orders o
                 JOIN users u ON o.user_id = u.id
                 JOIN vendors v ON o.vendor_id = v.id";
$orders_result = $conn->query($orders_query);

// Fetch order items
$order_items_query = "SELECT oi.order_id, m.name, oi.quantity
                      FROM order_items oi
                      JOIN menu_items m ON oi.menu_item_id = m.id";
$order_items_result = $conn->query($order_items_query);
$order_items = [];
while ($row = $order_items_result->fetch_assoc()) {
  $order_items[$row['order_id']][] = $row['quantity'] . 'x ' . $row['name'];
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>FoodHub - Orders Management</title>
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

    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
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

    .status-badge {
      padding: 0.25rem 0.75rem;
      border-radius: 20px;
      font-size: 0.8rem;
      font-weight: 600;
      text-transform: uppercase;
    }

    .status-badge.pending {
      background: rgba(255, 193, 7, 0.2);
      color: var(--warning-color);
    }

    .status-badge.processing {
      background: rgba(23, 162, 184, 0.2);
      color: var(--info-color);
    }

    .status-badge.completed {
      background: rgba(40, 167, 69, 0.2);
      color: var(--success-color);
    }

    .status-badge.cancelled {
      background: rgba(220, 53, 69, 0.2);
      color: var(--danger-color);
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

    .action-btn.receipt {
      background: rgba(0, 123, 255, 0.1);
      color: #007bff;
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
    }
  </style>
</head>

<body>
  <?php include 'includes/sidebar.php' ?>

  <!-- Main Content -->
  <div class="main-content" id="mainContent">
    <!-- Header -->
    <?php include 'includes/header.php'; ?>

    <!-- Dashboard Content -->
    <div class="dashboard-content">
      <h1 class="page-title">Orders Management</h1>
      <p class="page-subtitle">Manage and track all orders across the platform.</p>

      <!-- Filter Bar -->
      <div class="filter-bar">
        <input type="text" id="orderFilter" placeholder="Filter by customer or restaurant...">
      </div>

      <!-- All Orders -->
      <div class="content-card">
        <div class="card-header">
          <h3 class="card-title">All Orders</h3>
          <div style="display: flex; gap: 1rem;">
            <select id="statusFilter" style="padding: 0.5rem; border: 1px solid var(--light-gray); border-radius: 5px;">
              <option value="">All Status</option>
              <option value="Pending">Pending</option>
              <option value="Processing">Processing</option>
              <option value="Completed">Completed</option>
              <option value="Cancelled">Cancelled</option>
            </select>
            <form method="post" action="" style="display:inline;">
              <button type="submit" name="export_orders" class="quick-action-btn"><i class="fas fa-download"></i> Export</button>
            </form>
          </div>
        </div>
        <div class="card-body" style="padding: 0;">
          <div class="table-responsive"> <!-- Added Bootstrap responsive class -->
            <table class="custom-table" id="ordersTable">
              <thead>
                <tr>
                  <th>Order ID</th>
                  <th>Customer</th>
                  <th>Restaurant</th>
                  <th>Items</th>
                  <th>Amount</th>
                  <th>Date</th>
                  <th>Status</th>
                  <th>Actions</th>
                </tr>
              </thead>
              <tbody>
                <?php while ($order = $orders_result->fetch_assoc()): ?>
                  <tr>
                    <td>#<?php echo htmlspecialchars($order['id']); ?></td>
                    <td><?php echo htmlspecialchars($order['first_name'] . ' ' . $order['last_name']); ?></td>
                    <td><?php echo htmlspecialchars($order['restaurant_name']); ?></td>
                    <td><?php echo htmlspecialchars(implode(', ', $order_items[$order['id']] ?? [])); ?></td>
                    <td>PKR <?php echo number_format($order['total'], 2); ?></td>
                    <td><?php echo date('Y-m-d', strtotime($order['order_date'])); ?></td>
                    <td><span class="status-badge <?php echo strtolower($order['status']); ?>"><?php echo htmlspecialchars($order['status']); ?></span></td>
                    <td>
                      <!-- <button class="action-btn view" data-order-id="<?php echo $order['id']; ?>"><i class="fas fa-eye"></i></button> -->
                      <form method="get" action="" style="display:inline;">
                        <input type="hidden" name="action" value="edit">
                        <input type="hidden" name="order_id" value="<?php echo $order['id']; ?>">
                        <button type="submit" class="action-btn edit"><i class="fas fa-edit"></i></button>
                      </form>
                      <form method="post" action="" style="display:inline;" onsubmit="return confirmDelete(event)">
                        <input type="hidden" name="order_id" value="<?php echo $order['id']; ?>">
                        <input type="hidden" name="delete" value="1">
                        <button type="submit" class="action-btn delete"><i class="fas fa-trash"></i></button>
                      </form>
                      <a href="receipt.php?order_id=<?php echo $order['id']; ?>" class="action-btn receipt"><i class="fas fa-file-invoice"></i></a>
                    </td>
                  </tr>
                <?php endwhile; ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>

      <!-- Edit Order Form -->
      <?php if ($edit_order): ?>
        <div class="content-card">
          <div class="card-header">
            <h3 class="card-title">Edit Order #<?php echo htmlspecialchars($edit_order['id']); ?></h3>
          </div>
          <div class="card-body">
            <form method="post" action="">
              <input type="hidden" name="update_id" value="<?php echo $edit_order['id']; ?>">
              <div class="mb-3">
                <label for="status" class="form-label">Status</label>
                <select name="status" id="status" class="form-select">
                  <option value="Pending" <?php if ($edit_order['status'] == 'Pending') echo 'selected'; ?>>Pending</option>
                  <option value="Processing" <?php if ($edit_order['status'] == 'Processing') echo 'selected'; ?>>Processing</option>
                  <option value="Completed" <?php if ($edit_order['status'] == 'Completed') echo 'selected'; ?>>Completed</option>
                  <option value="Cancelled" <?php if ($edit_order['status'] == 'Cancelled') echo 'selected'; ?>>Cancelled</option>
                </select>
              </div>
              <button type="submit" name="update" class="btn btn-primary">Update</button>
              <a href="orders.php" class="btn btn-secondary">Cancel</a>
            </form>
          </div>
        </div>
      <?php endif; ?>
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
        const orderId = this.getAttribute('data-order-id');
        Swal.fire({
          icon: 'info',
          title: 'Order Details',
          text: `Viewing details for Order ID: ${orderId}`,
          confirmButtonColor: '#ff6b35'
        });
      });
    });

    // Table filter
    const filterInput = document.getElementById('orderFilter');
    filterInput.addEventListener('input', function() {
      const filterValue = this.value.toLowerCase();
      const rows = document.querySelectorAll('#ordersTable tbody tr');
      rows.forEach(row => {
        const customer = row.cells[1].textContent.toLowerCase();
        const restaurant = row.cells[2].textContent.toLowerCase();
        row.style.display = (customer.includes(filterValue) || restaurant.includes(filterValue)) ? '' : 'none';
      });
    });

    // Status filter
    const statusFilter = document.getElementById('statusFilter');
    statusFilter.addEventListener('change', function() {
      const statusValue = this.value.toLowerCase();
      const rows = document.querySelectorAll('#ordersTable tbody tr');
      rows.forEach(row => {
        const status = row.cells[6].textContent.toLowerCase();
        row.style.display = (statusValue === '' || status === statusValue) ? '' : 'none';
      });
    });
  </script>
</body>

</html>
<?php $conn->close(); ?>