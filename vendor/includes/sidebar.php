  <!-- Navbar -->
  <nav class="navbar navbar-expand-lg navbar-light fixed-top">
    <div class="container">
      <a class="navbar-brand" href="index.php">
        <i class="fas fa-utensils me-2"></i>FoodieHub
      </a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav ms-auto">
          <li class="nav-item">
            <a class="nav-link" href="index.php">Dashboard</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="vendor-orders.php">Orders</a>
          </li>
          <li class="nav-item">
            <a class="nav-link active" href="vendor-menu.php">Menu</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="vendor-details.php">Settings</a>
          </li>
          <li class="nav-item ms-2">
            <a class="btn btn-logout" href="#" onclick="handleLogout()">Logout</a>
          </li>
        </ul>
      </div>
    </div>
  </nav>

  <!-- Sidebar -->
  <div class="sidebar" id="sidebar" style="z-index: 2;">
    <ul class="nav flex-column">
      <li class="nav-item">
        <a class="nav-link" href="vendor.php"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="vendor-orders.php"><i class="fas fa-clipboard-list"></i> Orders</a>
      </li>
      <li class="nav-item">
        <a class="nav-link active" href="vendor-menu.php"><i class="fas fa-utensils"></i> Menu</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="vendor-details.php"><i class="fas fa-cog"></i> Settings</a>
      </li>
    </ul>
  </div>

  <!-- Sidebar Toggle for Mobile -->
  <button class="sidebar-toggle d-none" id="sidebarToggle">
    <i class="fas fa-bars"></i>
  </button>


  