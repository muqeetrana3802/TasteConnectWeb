<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>FoodHub - Admin Dashboard</title>
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

    /* Sidebar Styles */
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

    /* Main Content */
    .main-content {
      margin-left: var(--sidebar-width);
      transition: var(--transition);
      min-height: 100vh;
    }

    .main-content.expanded {
      margin-left: 80px;
    }

    /* Header */
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

    /* Dashboard Content */
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

    /* Stats Cards */
    .stats-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
      gap: 1.5rem;
      margin-bottom: 2rem;
    }

    .stat-card {
      background: var(--white);
      border-radius: var(--border-radius);
      padding: 1.5rem;
      box-shadow: var(--shadow);
      transition: var(--transition);
      position: relative;
      overflow: hidden;
    }

    .stat-card:hover {
      transform: translateY(-5px);
      box-shadow: 0 15px 40px rgba(0, 0, 0, 0.15);
    }

    .stat-card::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      width: 4px;
      height: 100%;
      background: var(--primary-color);
    }

    .stat-card.success::before {
      background: var(--success-color);
    }

    .stat-card.warning::before {
      background: var(--warning-color);
    }

    .stat-card.info::before {
      background: var(--info-color);
    }

    .stat-card.danger::before {
      background: var(--danger-color);
    }

    .stat-header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 1rem;
    }

    .stat-title {
      color: var(--medium-gray);
      font-size: 0.9rem;
      font-weight: 600;
      text-transform: uppercase;
    }

    .stat-icon {
      width: 50px;
      height: 50px;
      border-radius: 12px;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 1.5rem;
      color: var(--white);
    }

    .stat-icon.primary {
      background: linear-gradient(135deg, var(--primary-color), #ff8c42);
    }

    .stat-icon.success {
      background: linear-gradient(135deg, var(--success-color), #34ce57);
    }

    .stat-icon.warning {
      background: linear-gradient(135deg, var(--warning-color), #ffd93d);
    }

    .stat-icon.info {
      background: linear-gradient(135deg, var(--info-color), #20c9e3);
    }

    .stat-icon.danger {
      background: linear-gradient(135deg, var(--danger-color), #f56565);
    }

    .stat-value {
      font-size: 2rem;
      font-weight: bold;
      color: var(--dark-color);
      margin-bottom: 0.5rem;
    }

    .stat-change {
      font-size: 0.85rem;
      display: flex;
      align-items: center;
      gap: 0.25rem;
    }

    .stat-change.positive {
      color: var(--success-color);
    }

    .stat-change.negative {
      color: var(--danger-color);
    }

    /* Content Cards */
    .content-grid {
      display: grid;
      grid-template-columns: 2fr 1fr;
      gap: 2rem;
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
      justify-content: between;
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

    /* Table Styles */
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

    .status-badge.completed {
      background: rgba(40, 167, 69, 0.2);
      color: var(--success-color);
    }

    .status-badge.cancelled {
      background: rgba(220, 53, 69, 0.2);
      color: var(--danger-color);
    }

    .status-badge.processing {
      background: rgba(23, 162, 184, 0.2);
      color: var(--info-color);
    }

    /* Action Buttons */
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

    /* Quick Actions */
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

    /* Responsive Design */
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

      .content-grid {
        grid-template-columns: 1fr;
      }

      .stats-grid {
        grid-template-columns: 1fr;
      }

      .quick-actions {
        flex-direction: column;
      }
    }

    /* Chart Container */
    .chart-container {
      height: 300px;
      display: flex;
      align-items: center;
      justify-content: center;
      background: var(--light-gray);
      border-radius: 10px;
      color: var(--medium-gray);
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
      <a href="#" class="menu-item active" data-page="dashboard">
        <i class="fas fa-tachometer-alt"></i>
        <span>Dashboard</span>
      </a>
      <a href="#" class="menu-item" data-page="orders">
        <i class="fas fa-shopping-cart"></i>
        <span>Orders</span>
      </a>
      <a href="#" class="menu-item" data-page="restaurants">
        <i class="fas fa-store"></i>
        <span>Restaurants</span>
      </a>
      <a href="#" class="menu-item" data-page="customers">
        <i class="fas fa-users"></i>
        <span>Customers</span>
      </a>
      <a href="#" class="menu-item" data-page="delivery">
        <i class="fas fa-truck"></i>
        <span>Delivery</span>
      </a>
      <a href="#" class="menu-item" data-page="analytics">
        <i class="fas fa-chart-line"></i>
        <span>Analytics</span>
      </a>
      <a href="#" class="menu-item" data-page="settings">
        <i class="fas fa-cog"></i>
        <span>Settings</span>
      </a>
      <a href="#" class="menu-item" data-page="support">
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
          <input type="text" placeholder="Search orders, restaurants, customers...">
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

    <!-- Dashboard Content -->
    <div class="dashboard-content">
      <div id="dashboard-page">
        <h1 class="page-title">Dashboard Overview</h1>
        <p class="page-subtitle">Welcome back, John! Here's what's happening with FoodHub today.</p>

        <!-- Quick Actions -->
        <div class="quick-actions">
          <button class="quick-action-btn">
            <i class="fas fa-plus"></i>
            Add Restaurant
          </button>
          <button class="quick-action-btn">
            <i class="fas fa-eye"></i>
            View Orders
          </button>
          <button class="quick-action-btn">
            <i class="fas fa-chart-bar"></i>
            Generate Report
          </button>
        </div>

        <!-- Stats Grid -->
        <div class="stats-grid">
          <div class="stat-card">
            <div class="stat-header">
              <div class="stat-title">Total Orders</div>
              <div class="stat-icon primary">
                <i class="fas fa-shopping-cart"></i>
              </div>
            </div>
            <div class="stat-value">1,247</div>
            <div class="stat-change positive">
              <i class="fas fa-arrow-up"></i>
              +12.5% from last month
            </div>
          </div>

          <div class="stat-card success">
            <div class="stat-header">
              <div class="stat-title">Revenue</div>
              <div class="stat-icon success">
                <i class="fas fa-dollar-sign"></i>
              </div>
            </div>
            <div class="stat-value">$23,450</div>
            <div class="stat-change positive">
              <i class="fas fa-arrow-up"></i>
              +8.2% from last month
            </div>
          </div>

          <div class="stat-card warning">
            <div class="stat-header">
              <div class="stat-title">Active Restaurants</div>
              <div class="stat-icon warning">
                <i class="fas fa-store"></i>
              </div>
            </div>
            <div class="stat-value">89</div>
            <div class="stat-change positive">
              <i class="fas fa-arrow-up"></i>
              +3 new this week
            </div>
          </div>

          <div class="stat-card info">
            <div class="stat-header">
              <div class="stat-title">Delivery Time</div>
              <div class="stat-icon info">
                <i class="fas fa-clock"></i>
              </div>
            </div>
            <div class="stat-value">28 min</div>
            <div class="stat-change negative">
              <i class="fas fa-arrow-down"></i>
              -2 min improvement
            </div>
          </div>
        </div>

        <!-- Content Grid -->
        <div class="content-grid">
          <!-- Recent Orders -->
          <div class="content-card">
            <div class="card-header">
              <h3 class="card-title">Recent Orders</h3>
              <a href="#" style="color: var(--primary-color); text-decoration: none; font-weight: 600;">View All</a>
            </div>
            <div class="card-body" style="padding: 0;">
              <table class="custom-table">
                <thead>
                  <tr>
                    <th>Order ID</th>
                    <th>Customer</th>
                    <th>Restaurant</th>
                    <th>Amount</th>
                    <th>Status</th>
                    <th>Actions</th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <td>#1234</td>
                    <td>Alice Johnson</td>
                    <td>Pizza Palace</td>
                    <td>$24.99</td>
                    <td><span class="status-badge processing">Processing</span></td>
                    <td>
                      <button class="action-btn view"><i class="fas fa-eye"></i></button>
                      <button class="action-btn edit"><i class="fas fa-edit"></i></button>
                    </td>
                  </tr>
                  <tr>
                    <td>#1235</td>
                    <td>Bob Smith</td>
                    <td>Burger House</td>
                    <td>$18.50</td>
                    <td><span class="status-badge completed">Completed</span></td>
                    <td>
                      <button class="action-btn view"><i class="fas fa-eye"></i></button>
                      <button class="action-btn edit"><i class="fas fa-edit"></i></button>
                    </td>
                  </tr>
                  <tr>
                    <td>#1236</td>
                    <td>Carol Davis</td>
                    <td>Sushi Garden</td>
                    <td>$42.75</td>
                    <td><span class="status-badge pending">Pending</span></td>
                    <td>
                      <button class="action-btn view"><i class="fas fa-eye"></i></button>
                      <button class="action-btn edit"><i class="fas fa-edit"></i></button>
                    </td>
                  </tr>
                  <tr>
                    <td>#1237</td>
                    <td>Dave Wilson</td>
                    <td>Taco Express</td>
                    <td>$15.25</td>
                    <td><span class="status-badge cancelled">Cancelled</span></td>
                    <td>
                      <button class="action-btn view"><i class="fas fa-eye"></i></button>
                      <button class="action-btn delete"><i class="fas fa-trash"></i></button>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>

          <!-- Quick Stats -->
          <div class="content-card">
            <div class="card-header">
              <h3 class="card-title">Performance</h3>
            </div>
            <div class="card-body">
              <div class="chart-container">
                <div style="text-align: center;">
                  <i class="fas fa-chart-pie" style="font-size: 3rem; margin-bottom: 1rem;"></i>
                  <p>Revenue Chart</p>
                  <p style="font-size: 0.9rem;">Interactive charts would be integrated here</p>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Additional Stats -->
        <div class="content-card">
          <div class="card-header">
            <h3 class="card-title">Top Performing Restaurants</h3>
          </div>
          <div class="card-body" style="padding: 0;">
            <table class="custom-table">
              <thead>
                <tr>
                  <th>Restaurant</th>
                  <th>Orders</th>
                  <th>Revenue</th>
                  <th>Rating</th>
                  <th>Status</th>
                  <th>Actions</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td>
                    <div style="display: flex; align-items: center; gap: 0.75rem;">
                      <div style="width: 40px; height: 40px; background: linear-gradient(135deg, var(--primary-color), #ff8c42); border-radius: 8px; display: flex; align-items: center; justify-content: center; color: white; font-weight: bold;">PP</div>
                      <div>
                        <div style="font-weight: 600;">Pizza Palace</div>
                        <div style="font-size: 0.8rem; color: var(--medium-gray);">Italian Cuisine</div>
                      </div>
                    </div>
                  </td>
                  <td>245</td>
                  <td>$5,420</td>
                  <td>4.8 ⭐</td>
                  <td><span class="status-badge completed">Active</span></td>
                  <td>
                    <button class="action-btn view"><i class="fas fa-eye"></i></button>
                    <button class="action-btn edit"><i class="fas fa-edit"></i></button>
                  </td>
                </tr>
                <tr>
                  <td>
                    <div style="display: flex; align-items: center; gap: 0.75rem;">
                      <div style="width: 40px; height: 40px; background: linear-gradient(135deg, var(--success-color), #34ce57); border-radius: 8px; display: flex; align-items: center; justify-content: center; color: white; font-weight: bold;">BH</div>
                      <div>
                        <div style="font-weight: 600;">Burger House</div>
                        <div style="font-size: 0.8rem; color: var(--medium-gray);">Fast Food</div>
                      </div>
                    </div>
                  </td>
                  <td>189</td>
                  <td>$3,780</td>
                  <td>4.6 ⭐</td>
                  <td><span class="status-badge completed">Active</span></td>
                  <td>
                    <button class="action-btn view"><i class="fas fa-eye"></i></button>
                    <button class="action-btn edit"><i class="fas fa-edit"></i></button>
                  </td>
                </tr>
                <tr>
                  <td>
                    <div style="display: flex; align-items: center; gap: 0.75rem;">
                      <div style="width: 40px; height: 40px; background: linear-gradient(135deg, var(--info-color), #20c9e3); border-radius: 8px; display: flex; align-items: center; justify-content: center; color: white; font-weight: bold;">SG</div>
                      <div>
                        <div style="font-weight: 600;">Sushi Garden</div>
                        <div style="font-size: 0.8rem; color: var(--medium-gray);">Japanese Cuisine</div>
                      </div>
                    </div>
                  </td>
                  <td>156</td>
                  <td>$4,230</td>
                  <td>4.9 ⭐</td>
                  <td><span class="status-badge completed">Active</span></td>
                  <td>
                    <button class="action-btn view"><i class="fas fa-eye"></i></button>
                    <button class="action-btn edit"><i class="fas fa-edit"></i></button>
                  </td>
                </tr>
                <tr>
                  <td>
                    <div style="display: flex; align-items: center; gap: 0.75rem;">
                      <div style="width: 40px; height: 40px; background: linear-gradient(135deg, var(--warning-color), #ffd93d); border-radius: 8px; display: flex; align-items: center; justify-content: center; color: white; font-weight: bold;">TE</div>
                      <div>
                        <div style="font-weight: 600;">Taco Express</div>
                        <div style="font-size: 0.8rem; color: var(--medium-gray);">Mexican Food</div>
                      </div>
                    </div>
                  </td>
                  <td>98</td>
                  <td>$2,140</td>
                  <td>4.4 ⭐</td>
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

      <!-- Orders Page -->
      <div id="orders-page" style="display: none;">
        <h1 class="page-title">Orders Management</h1>
        <p class="page-subtitle">Manage and track all orders across the platform.</p>

        <div class="content-card">
          <div class="card-header">
            <h3 class="card-title">All Orders</h3>
            <div style="display: flex; gap: 1rem;">
              <select style="padding: 0.5rem; border: 1px solid var(--light-gray); border-radius: 5px;">
                <option>All Status</option>
                <option>Pending</option>
                <option>Processing</option>
                <option>Completed</option>
                <option>Cancelled</option>
              </select>
              <button class="quick-action-btn">
                <i class="fas fa-download"></i>
                Export
              </button>
            </div>
          </div>
          <div class="card-body" style="padding: 0;">
            <table class="custom-table">
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
                <tr>
                  <td>#1234</td>
                  <td>Alice Johnson</td>
                  <td>Pizza Palace</td>
                  <td>2x Margherita Pizza</td>
                  <td>$24.99</td>
                  <td>2024-01-15</td>
                  <td><span class="status-badge processing">Processing</span></td>
                  <td>
                    <button class="action-btn view"><i class="fas fa-eye"></i></button>
                    <button class="action-btn edit"><i class="fas fa-edit"></i></button>
                    <button class="action-btn delete"><i class="fas fa-trash"></i></button>
                  </td>
                </tr>
                <tr>
                  <td>#1235</td>
                  <td>Bob Smith</td>
                  <td>Burger House</td>
                  <td>1x Big Burger, 1x Fries</td>
                  <td>$18.50</td>
                  <td>2024-01-15</td>
                  <td><span class="status-badge completed">Completed</span></td>
                  <td>
                    <button class="action-btn view"><i class="fas fa-eye"></i></button>
                    <button class="action-btn edit"><i class="fas fa-edit"></i></button>
                    <button class="action-btn delete"><i class="fas fa-trash"></i></button>
                  </td>
                </tr>
                <tr>
                  <td>#1236</td>
                  <td>Carol Davis</td>
                  <td>Sushi Garden</td>
                  <td>1x Sushi Platter</td>
                  <td>$42.75</td>
                  <td>2024-01-14</td>
                  <td><span class="status-badge pending">Pending</span></td>
                  <td>
                    <button class="action-btn view"><i class="fas fa-eye"></i></button>
                    <button class="action-btn edit"><i class="fas fa-edit"></i></button>
                    <button class="action-btn delete"><i class="fas fa-trash"></i></button>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>

      <!-- Restaurants Page -->
      <div id="restaurants-page" style="display: none;">
        <h1 class="page-title">Restaurant Management</h1>
        <p class="page-subtitle">Manage restaurant partners and their details.</p>

        <div class="quick-actions">
          <button class="quick-action-btn">
            <i class="fas fa-plus"></i>
            Add New Restaurant
          </button>
          <button class="quick-action-btn">
            <i class="fas fa-upload"></i>
            Bulk Import
          </button>
        </div>

        <div class="content-card">
          <div class="card-header">
            <h3 class="card-title">All Restaurants</h3>
          </div>
          <div class="card-body" style="padding: 0;">
            <table class="custom-table">
              <thead>
                <tr>
                  <th>Restaurant</th>
                  <th>Cuisine Type</th>
                  <th>Orders</th>
                  <th>Revenue</th>
                  <th>Rating</th>
                  <th>Status</th>
                  <th>Actions</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td>
                    <div style="display: flex; align-items: center; gap: 0.75rem;">
                      <div style="width: 40px; height: 40px; background: linear-gradient(135deg, var(--primary-color), #ff8c42); border-radius: 8px; display: flex; align-items: center; justify-content: center; color: white; font-weight: bold;">PP</div>
                      <div>
                        <div style="font-weight: 600;">Pizza Palace</div>
                        <div style="font-size: 0.8rem; color: var(--medium-gray);">123 Main St</div>
                      </div>
                    </div>
                  </td>
                  <td>Italian</td>
                  <td>245</td>
                  <td>$5,420</td>
                  <td>4.8 ⭐</td>
                  <td><span class="status-badge completed">Active</span></td>
                  <td>
                    <button class="action-btn view"><i class="fas fa-eye"></i></button>
                    <button class="action-btn edit"><i class="fas fa-edit"></i></button>
                    <button class="action-btn delete"><i class="fas fa-trash"></i></button>
                  </td>
                </tr>
                <tr>
                  <td>
                    <div style="display: flex; align-items: center; gap: 0.75rem;">
                      <div style="width: 40px; height: 40px; background: linear-gradient(135deg, var(--success-color), #34ce57); border-radius: 8px; display: flex; align-items: center; justify-content: center; color: white; font-weight: bold;">BH</div>
                      <div>
                        <div style="font-weight: 600;">Burger House</div>
                        <div style="font-size: 0.8rem; color: var(--medium-gray);">456 Oak Ave</div>
                      </div>
                    </div>
                  </td>
                  <td>Fast Food</td>
                  <td>189</td>
                  <td>$3,780</td>
                  <td>4.6 ⭐</td>
                  <td><span class="status-badge completed">Active</span></td>
                  <td>
                    <button class="action-btn view"><i class="fas fa-eye"></i></button>
                    <button class="action-btn edit"><i class="fas fa-edit"></i></button>
                    <button class="action-btn delete"><i class="fas fa-trash"></i></button>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>

      <!-- Customers Page -->
      <div id="customers-page" style="display: none;">
        <h1 class="page-title">Customer Management</h1>
        <p class="page-subtitle">View and manage customer accounts and activity.</p>

        <div class="content-card">
          <div class="card-header">
            <h3 class="card-title">Customer List</h3>
          </div>
          <div class="card-body" style="padding: 0;">
            <table class="custom-table">
              <thead>
                <tr>
                  <th>Customer</th>
                  <th>Email</th>
                  <th>Orders</th>
                  <th>Total Spent</th>
                  <th>Join Date</th>
                  <th>Status</th>
                  <th>Actions</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td>
                    <div style="display: flex; align-items: center; gap: 0.75rem;">
                      <div style="width: 40px; height: 40px; background: linear-gradient(135deg, var(--primary-color), #ff8c42); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-weight: bold;">AJ</div>
                      <div>Alice Johnson</div>
                    </div>
                  </td>
                  <td>alice@email.com</td>
                  <td>23</td>
                  <td>$456.78</td>
                  <td>2023-06-15</td>
                  <td><span class="status-badge completed">Active</span></td>
                  <td>
                    <button class="action-btn view"><i class="fas fa-eye"></i></button>
                    <button class="action-btn edit"><i class="fas fa-edit"></i></button>
                  </td>
                </tr>
                <tr>
                  <td>
                    <div style="display: flex; align-items: center; gap: 0.75rem;">
                      <div style="width: 40px; height: 40px; background: linear-gradient(135deg, var(--success-color), #34ce57); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-weight: bold;">BS</div>
                      <div>Bob Smith</div>
                    </div>
                  </td>
                  <td>bob@email.com</td>
                  <td>17</td>
                  <td>$289.50</td>
                  <td>2023-08-22</td>
                  <td><span class="status-badge completed">Active</span></td>
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

      <!-- Other pages (simplified for brevity) -->
      <div id="delivery-page" style="display: none;">
        <h1 class="page-title">Delivery Management</h1>
        <p class="page-subtitle">Track deliveries and manage delivery partners.</p>
        <div class="chart-container">
          <div style="text-align: center;">
            <i class="fas fa-truck" style="font-size: 3rem; margin-bottom: 1rem;"></i>
            <p>Delivery tracking interface would be here</p>
          </div>
        </div>
      </div>

      <div id="analytics-page" style="display: none;">
        <h1 class="page-title">Analytics & Reports</h1>
        <p class="page-subtitle">View detailed analytics and generate reports.</p>
        <div class="chart-container">
          <div style="text-align: center;">
            <i class="fas fa-chart-line" style="font-size: 3rem; margin-bottom: 1rem;"></i>
            <p>Analytics dashboard would be integrated here</p>
          </div>
        </div>
      </div>

      <div id="settings-page" style="display: none;">
        <h1 class="page-title">Settings</h1>
        <p class="page-subtitle">Configure platform settings and preferences.</p>
        <div class="chart-container">
          <div style="text-align: center;">
            <i class="fas fa-cog" style="font-size: 3rem; margin-bottom: 1rem;"></i>
            <p>Settings panel would be here</p>
          </div>
        </div>
      </div>

      <div id="support-page" style="display: none;">
        <h1 class="page-title">Support Center</h1>
        <p class="page-subtitle">Help desk and customer support management.</p>
        <div class="chart-container">
          <div style="text-align: center;">
            <i class="fas fa-headset" style="font-size: 3rem; margin-bottom: 1rem;"></i>
            <p>Support ticketing system would be here</p>
          </div>
        </div>
      </div>
    </div>
  </div>

  <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
  <script>
    // Sidebar toggle functionality
    const sidebarToggle = document.getElementById('sidebarToggle');
    const sidebar = document.getElementById('sidebar');
    const mainContent = document.getElementById('mainContent');

    sidebarToggle.addEventListener('click', function() {
      sidebar.classList.toggle('collapsed');
      mainContent.classList.toggle('expanded');
    });

    // Mobile sidebar toggle
    function toggleMobileSidebar() {
      if (window.innerWidth <= 768) {
        sidebar.classList.toggle('mobile-open');
      }
    }

    // Page navigation
    const menuItems = document.querySelectorAll('.menu-item');
    const pages = document.querySelectorAll('[id$="-page"]');

    menuItems.forEach(item => {
      item.addEventListener('click', function(e) {
        e.preventDefault();

        // Remove active class from all menu items
        menuItems.forEach(menuItem => menuItem.classList.remove('active'));

        // Add active class to clicked item
        this.classList.add('active');

        // Hide all pages
        pages.forEach(page => page.style.display = 'none');

        // Show selected page
        const targetPage = this.getAttribute('data-page');
        const targetElement = document.getElementById(targetPage + '-page');
        if (targetElement) {
          targetElement.style.display = 'block';
        }

        // Close mobile sidebar after selection
        if (window.innerWidth <= 768) {
          sidebar.classList.remove('mobile-open');
        }
      });
    });

    // Handle window resize
    window.addEventListener('resize', function() {
      if (window.innerWidth > 768) {
        sidebar.classList.remove('mobile-open');
      }
    });

    // Search functionality
    const searchInput = document.querySelector('.search-bar input');
    searchInput.addEventListener('input', function() {
      const searchTerm = this.value.toLowerCase();
      // Add search logic here
      console.log('Searching for:', searchTerm);
    });

    // Action button handlers
    document.addEventListener('click', function(e) {
      if (e.target.closest('.action-btn')) {
        const actionType = e.target.closest('.action-btn').classList.contains('view') ? 'view' :
          e.target.closest('.action-btn').classList.contains('edit') ? 'edit' : 'delete';
        console.log('Action:', actionType);
        // Add specific action logic here
      }
    });

    // Quick action button handlers
    document.addEventListener('click', function(e) {
      if (e.target.closest('.quick-action-btn')) {
        const buttonText = e.target.closest('.quick-action-btn').textContent.trim();
        console.log('Quick action:', buttonText);
        // Add quick action logic here
      }
    });

    // Notification bell click
    document.querySelector('.notification-bell').addEventListener('click', function() {
      console.log('Notifications clicked');
      // Add notification dropdown logic here
    });

    // User dropdown click
    document.querySelector('.user-dropdown').addEventListener('click', function() {
      console.log('User dropdown clicked');
      // Add user dropdown logic here
    });

    // Initialize tooltips if using Bootstrap
    if (typeof bootstrap !== 'undefined') {
      var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
      var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
      });
    }

    // Add some interactive effects
    document.addEventListener('DOMContentLoaded', function() {
      // Animate stat cards on load
      const statCards = document.querySelectorAll('.stat-card');
      statCards.forEach((card, index) => {
        setTimeout(() => {
          card.style.opacity = '0';
          card.style.transform = 'translateY(20px)';
          card.style.transition = 'all 0.5s ease';

          setTimeout(() => {
            card.style.opacity = '1';
            card.style.transform = 'translateY(0)';
          }, 100);
        }, index * 100);
      });
    });
  </script>
</body>

</html>