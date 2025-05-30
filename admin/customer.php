<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>FoodHub - Customers</title>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
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

    .menu-item span {
      transition: var(--transition);
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

    .content-card {
      background: var(--white);
      border-radius: var(--border-radius);
      box-shadow: var(--shadow);
      overflow: hidden;
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

    .status-badge.completed {
      background: rgba(40, 167, 69, 0.2);
      color: var(--success-color);
    }

    .status-badge.pending {
      background: rgba(255, 193, 7, 0.2);
      color: var(--warning-color);
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

    .action-btn.edit {
      background: rgba(23, 162, 184, 0.1);
      color: var(--info-color);
    }

    .action-btn.delete {
      background: rgba(220, 53, 69, 0.1);
      color: var(--danger-color);
    }

    .action-btn.view {
      background: rgba(40, 167, 69, 0.1);
      color: var(--success-color);
    }

    .quick-actions {
      display: flex;
      gap: 1rem;
      margin-bottom: 2rem;
    }

    .quick-action-btn {
      background: linear-gradient(135deg, var(--primary-color), #ff8c42);
      color: var(--white);
      border: none;
      padding: 0.75rem 1.5rem;
      border-radius: 10px;
      font-weight: 600;
      cursor: pointer;
      transition: var(--transition);
      display: flex;
      align-items: center;
      gap: 0.5rem;
    }

    .quick-action-btn:hover {
      transform: translateY(-2px);
      box-shadow: 0 5px 15px rgba(255, 107, 53, 0.4);
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
  <div class="sidebar" id="sidebar">
    <div class="sidebar-header">
      <div class="brand-logo">
        <i class="fas fa-utensils"></i>
      </div>
      <div class="brand-name">FoodHub</div>
    </div>
    <nav class="sidebar-menu">
      <a href="dashboard.html" class="menu-item" data-page="dashboard">
        <i class="fas fa-tachometer-alt"></i>
        <span>Dashboard</span>
      </a>
      <a href="orders.html" class="menu-item" data-page="orders">
        <i class="fas fa-shopping-cart"></i>
        <span>Orders</span>
      </a>
      <a href="restaurants.html" class="menu-item" data-page="restaurants">
        <i class="fas fa-store"></i>
        <span>Restaurants</span>
      </a>
      <a href="customers.html" class="menu-item active" data-page="customers">
        <i class="fas fa-users"></i>
        <span>Customers</span>
      </a>
      <a href="delivery.html" class="menu-item" data-page="delivery">
        <i class="fas fa-truck"></i>
        <span>Delivery</span>
      </a>
      <a href="analytics.html" class="menu-item" data-page="analytics">
        <i class="fas fa-chart-line"></i>
        <span>Analytics</span>
      </a>
      <a href="settings.html" class="menu-item" data-page="settings">
        <i class="fas fa-cog"></i>
        <span>Settings</span>
      </a>
      <a href="support.html" class="menu-item" data-page="support">
        <i class="fas fa-headset"></i>
        <span>Support</span>
      </a>
    </nav>
  </div>

  <!-- Main Content -->
  <div class="main-content" id="mainContent">
    <!-- Header -->
    <header class="header">
      <div class="header-left">
        <button class="sidebar-toggle" id="sidebarToggle">
          <i class="fas fa-bars"></i>
        </button>
        <div class="search-bar">
          <i class="fas fa-search"></i>
          <input type="text" placeholder="Search customers...">
        </div>
      </div>
      <div class="header-right">
        <button class="notification-bell">
          <i class="fas fa-bell"></i>
          <span class="notification-badge">5</span>
        </button>
        <div class="user-dropdown">
          <div class="user-avatar">JD</div>
          <div>
            <div style="font-weight: 600; color: var(--dark-color);">John Doe</div>
            <div style="font-size: 0.8rem; color: var(--medium-gray);">Admin</div>
          </div>
          <i class="fas fa-chevron-down" style="color: var(--medium-gray);"></i>
        </div>
      </div>
    </header>

    <!-- Customers Content -->
    <div class="dashboard-content">
      <div id="customers-page">
        <h1 class="page-title">Manage Customers</h1>
        <p class="page-subtitle">View and manage customer accounts on FoodHub.</p>

        <!-- Quick Actions -->
        <div class="quick-actions">
          <button class="quick-action-btn">
            <i class="fas fa-plus"></i>
            Add Customer
          </button>
          <button class="quick-action-btn">
            <i class="fas fa-filter"></i>
            Filter Customers
          </button>
          <button class="quick-action-btn">
            <i class="fas fa-download"></i>
            Export Customers
          </button>
        </div>

        <!-- Customers Table -->
        <div class="content-card">
          <div class="card-header">
            <h3 class="card-title">All Customers</h3>
            <div style="display: flex; gap: 1rem;">
              <select style="padding: 0.5rem; border-radius: 8px; border: 1px solid var(--light-gray);">
                <option>All Statuses</option>
                <option>Active</option>
                <option>Pending</option>
              </select>
            </div>
          </div>
          <div class="card-body" style="padding: 0;">
            <table class="custom-table">
              <thead>
                <tr>
                  <th>Name</th>
                  <th>Email</th>
                  <th>Phone</th>
                  <th>Total Orders</th>
                  <th>Total Spent</th>
                  <th>Status</th>
                  <th>Actions</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td>Alice Johnson</td>
                  <td>alice.johnson@email.com</td>
                  <td>(123) 456-7890</td>
                  <td>45</td>
                  <td>$1,230</td>
                  <td><span class="status-badge completed">Active</span></td>
                  <td>
                    <button class="action-btn view"><i class="fas fa-eye"></i></button>
                    <button class="action-btn edit"><i class="fas fa-edit"></i></button>
                    <button class="action-btn delete"><i class="fas fa-trash"></i></button>
                  </td>
                </tr>
                <tr>
                  <td>Bob Smith</td>
                  <td>bob.smith@email.com</td>
                  <td>(234) 567-8901</td>
                  <td>32</td>
                  <td>$890</td>
                  <td><span class="status-badge completed">Active</span></td>
                  <td>
                    <button class="action-btn view"><i class="fas fa-eye"></i></button>
                    <button class="action-btn edit"><i class="fas fa-edit"></i></button>
                  </td>
                </tr>
                <tr>
                  <td>Carol Davis</td>
                  <td>carol.davis@email.com</td>
                  <td>(345) 678-9012</td>
                  <td>20</td>
                  <td>$650</td>
                  <td><span class="status-badge pending">Pending</span></td>
                  <td>
                    <button class="action-btn view"><i class="fas fa-eye"></i></button>
                    <button class="action-btn edit"><i class="fas fa-edit"></i></button>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>

  <script>
    // Sidebar Toggle
    const sidebar = document.getElementById('sidebar');
    const mainContent = document.getElementById('mainContent');
    const sidebarToggle = document.getElementById('sidebarToggle');

    sidebarToggle.addEventListener('click', () => {
      sidebar.classList.toggle('collapsed');
      mainContent.classList.toggle('expanded');
    });

    // Mobile Sidebar Toggle
    if (window.innerWidth <= 768) {
      sidebarToggle.addEventListener('click', () => {
        sidebar.classList.toggle('mobile-open');
      });
    }

    // Menu Item Activation
    const menuItems = document.querySelectorAll('.menu-item');
    menuItems.forEach(item => {
      item.addEventListener('click', () => {
        menuItems.forEach(i => i.classList.remove('active'));
        item.classList.add('active');
      });
    });
  </script>
</body>

</html>