<style>
  /* Dropdown Menu Styling */
  .user-dropdown {
    position: relative;
    display: flex;
    align-items: center;
    cursor: pointer;
    padding: 0.5rem;
    border-radius: 8px;
    transition: all 0.3s ease;
  }

  .user-dropdown:hover {
    background-color: #f8f9fa;
  }

  .dropdown-menu {
    position: absolute;
    top: 100%;
    right: 0;
    background-color: #fff;
    border: 1px solid #e0e0e0;
    border-radius: 4px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    min-width: 150px;
    z-index: 1000;
    display: none;
  }

  .dropdown-menu.show {
    display: block;
  }

  .dropdown-item {
    display: block;
    padding: 10px 15px;
    color: #2c3e50;
    text-decoration: none;
    font-size: 0.9rem;
  }

  .dropdown-item:hover {
    background-color: #f8f9fa;
  }

  .header-right {
    position: relative;
  }

  /* Header Styling */
  .header {
    background: #ffffff;
    padding: 1rem 2rem;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
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
    color: #495057;
    cursor: pointer;
    padding: 0.5rem;
    border-radius: 8px;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    justify-content: center;
    width: 40px;
    height: 40px;
  }

  .sidebar-toggle:hover {
    background: #f8f9fa;
  }

  .sidebar-toggle i {
    margin: 0;
  }

  .user-avatar {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background: linear-gradient(135deg, #ff6b35, #ff8c42);
    display: flex;
    align-items: center;
    justify-content: center;
    color: #ffffff;
    font-weight: bold;
  }

  /* Sidebar Styling */
  #sidebar {
    width: 250px;
    height: 100vh;
    background-color: #2c3e50;
    /* Matches --dark-color */
    position: fixed;
    top: 0;
    left: 0;
    transition: all 0.3s ease;
    z-index: 1000;
    padding-top: 1rem;
    color: #ffffff;
  }

  #sidebar .logo {
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 1rem;
    font-size: 1.5rem;
    font-weight: bold;
    color: #ffffff;
    margin-bottom: 2rem;
  }

  /* Menu Items (Your Sidebar Links Styling) */
  #sidebar .menu-item {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.5rem 1rem;
    color: #ffffff;
    text-decoration: none;
    font-size: 1rem;
    border-radius: 8px;
    margin: 0 1rem;
    transition: all 0.3s ease;
  }

  #sidebar .menu-item:hover {
    background-color: #f8f9fa;
    /* Matches --light-gray */
    color: #2c3e50;
    /* Matches --dark-color */
  }

  #sidebar .menu-item.active {
    background: linear-gradient(135deg, #ff6b35, #ff8c42);
    /* Matches --primary-color gradient */
    color: #ffffff;
  }

  #sidebar .menu-item i {
    margin-right: 0.5rem;
  }

  #sidebar .menu-item span {
    display: inline-block;
  }

  /* Close Button Styling */
  .close-sidebar {
    background: none;
    border: none;
    font-size: 1.2rem;
    color: #ffffff;
    cursor: pointer;
    padding: 0.5rem;
    border-radius: 8px;
    transition: all 0.3s ease;
    display: none;
    /* Hidden by default, shown on small screens */
    position: absolute;
    top: 1rem;
    right: 1rem;
  }

  .close-sidebar:hover {
    background: #f8f9fa;
    /* Matches --light-gray */
    color: #2c3e50;
    /* Matches --dark-color */
  }

  /* Media Query for Small Screens */
  @media (max-width: 768px) {
    #sidebar {
      transform: translateX(-100%);
      /* Hidden off-screen by default */
    }

    #sidebar.collapsed {
      transform: translateX(0);
      /* Slide in when toggled */
    }

    .sidebar-toggle {
      display: flex;
      /* Ensure hamburger is visible on small screens */
    }

    .close-sidebar {
      display: flex;
      /* Show close button on small screens */
    }
  }
</style>

<!-- Sidebar -->
<div id="sidebar">
  <button class="close-sidebar" id="closeSidebar"><i class="fas fa-times"></i></button>
  <div class="logo">TasteConnect</div>
  <a href="index.php" class="menu-item active"><i class="fas fa-tachometer-alt"></i><span>Dashboard</span></a>
  <a href="orders.php" class="menu-item"><i class="fas fa-shopping-cart"></i><span>Orders</span></a>
  <a href="vendors.php" class="menu-item"><i class="fas fa-store"></i><span>Restaurants</span></a>
  <a href="customers.php" class="menu-item"><i class="fas fa-users"></i><span>Customers</span></a>
  <a href="../index.php" class="menu-item" target="_blank"><i class="fas fa-globe"></i><span>Visit Website</span></a>
  <a href="logout.php" class="menu-item"><i class="fas fa-sign-out-alt"></i><span>Logout</span></a>
</div>

<!-- Header -->
<header class="header">
  <div class="header-left">
    <button class="sidebar-toggle" id="sidebarToggle"><i class="fas fa-bars"></i></button>
  </div>
  <div class="header-right">
    <div class="user-dropdown">
      <div class="user-avatar">FH</div>
      <div>
        <?php
        $username = "admin";
        $foodiehub = "FoodieHub";
        ?>
        <div style="font-weight: 600; color: #2c3e50;"><?php echo htmlspecialchars($username); ?></div>
        <div style="font-size: 0.8rem; color: #6c757d;"><?php echo htmlspecialchars($foodiehub); ?></div>
      </div>
      <i class="fas fa-chevron-down" style="color: #6c757d;"></i>
      <div class="dropdown-menu">
        <a href="logout.php" class="dropdown-item">Logout</a>
      </div>
    </div>
  </div>
</header>

<script>
  const sidebarToggle = document.getElementById('sidebarToggle');
  const closeSidebar = document.getElementById('closeSidebar');
  const sidebar = document.getElementById('sidebar');
  const userDropdown = document.querySelector('.user-dropdown');
  const dropdownMenu = document.querySelector('.dropdown-menu');

  // Toggle sidebar (open)
  if (sidebarToggle && sidebar) {
    sidebarToggle.addEventListener('click', function() {
      sidebar.classList.toggle('collapsed');
    });
  }

  // Close sidebar
  if (closeSidebar && sidebar) {
    closeSidebar.addEventListener('click', function() {
      sidebar.classList.remove('collapsed');
    });
  }

  // Toggle dropdown menu
  if (userDropdown && dropdownMenu) {
    userDropdown.addEventListener('click', function(e) {
      e.stopPropagation();
      dropdownMenu.classList.toggle('show');
    });

    // Close dropdown when clicking outside
    document.addEventListener('click', function(e) {
      if (!userDropdown.contains(e.target)) {
        dropdownMenu.classList.remove('show');
      }
    });
  }
</script>