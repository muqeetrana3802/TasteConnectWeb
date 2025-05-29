<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
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

    /* Navbar Styles */
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

    /* Sidebar */
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

    /* Main Content */
    .main-content {
      margin-left: 250px;
      padding: 2rem;
      margin-top: 76px;
    }

    /* Dashboard Cards */
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

    /* Restaurant Info */
    .restaurant-info .info-item {
      margin-bottom: 1rem;
    }

    .restaurant-info .info-label {
      font-weight: 600;
      color: var(--gray-800);
    }

    .btn-edit {
      background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
      border: none;
      color: var(--white);
      padding: 0.5rem 1rem;
      border-radius: 25px;
      font-weight: 500;
      transition: var(--transition);
    }

    .btn-edit:hover {
      transform: translateY(-2px);
      box-shadow: var(--shadow);
      color: var(--white);
    }

    /* Menu Items */
    .menu-item {
      display: flex;
      align-items: center;
      padding: 1rem;
      border-bottom: 1px solid var(--gray-200);
    }

    .menu-item img {
      width: 80px;
      height: 80px;
      object-fit: cover;
      border-radius: var(--border-radius);
      margin-right: 1rem;
    }

    .menu-item-info {
      flex: 1;
    }

    .menu-item-title {
      font-size: 1rem;
      font-weight: 600;
      margin-bottom: 0.25rem;
    }

    .menu-item-price {
      color: var(--primary-color);
      font-weight: 600;
    }

    .menu-item-actions button {
      margin-left: 0.5rem;
    }

    /* Orders */
    .order-item {
      background: var(--white);
      border-radius: var(--border-radius);
      padding: 1.5rem;
      margin-bottom: 1rem;
      box-shadow: var(--shadow);
    }

    .order-item-header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 1rem;
    }

    .order-status {
      padding: 0.25rem 0.75rem;
      border-radius: 25px;
      font-size: 0.85rem;
      font-weight: 600;
    }

    .order-status.pending {
      background: var(--warning-color);
      color: var(--white);
    }

    .order-status.accepted {
      background: var(--success-color);
      color: var(--white);
    }

    .order-status.delivered {
      background: var(--info-color);
      color: var(--white);
    }

    .order-items {
      margin-bottom: 1rem;
    }

    /* Modal */
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

    /* Responsive */
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

    /* Fade in animation */
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
  <nav class="navbar navbar-expand-lg navbar-light fixed-top">
    <div class="container">
      <a class="navbar-brand" href="index.php">
        <i class="fas fa-utensils me-2"></i>TasteConnect
      </a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav ms-auto">
          <li class="nav-item">
            <a class="nav-link active" href="vendor.php">Dashboard</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="vendor-orders.php">Orders</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="vendor-menu.php">Menu</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="vendor-details.php">Settings</a>
          </li>
          <li class="nav-item ms-2">
            <a class="btn btn-logout" href="index.php">Logout</a>
          </li>
        </ul>
      </div>
    </div>
  </nav>

  <!-- Sidebar -->
  <div class="sidebar" id="sidebar">
    <ul class="nav flex-column">
      <li class="nav-item">
        <a class="nav-link active" href="vendor.php"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="vendor-orders.php"><i class="fas fa-clipboard-list"></i> Orders</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="vendor-menu.php"><i class="fas fa-utensils"></i> Menu</a>
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

  <!-- Main Content -->
  <div class="main-content">
    <!-- Dashboard Section -->
    <section id="dashboard" class="section">
      <div class="container">
        <h2 class="mb-4">Vendor Dashboard</h2>
        <div class="row">
          <div class="col-lg-4 mb-4">
            <div class="dashboard-card fade-in">
              <h5>Total Orders</h5>
              <p class="display-4 text-center text-primary">120</p>
            </div>
          </div>
          <div class="col-lg-4 mb-4">
            <div class="dashboard-card fade-in">
              <h5>Revenue</h5>
              <p class="display-4 text-center text-primary">$2,450.50</p>
            </div>
          </div>
          <div class="col-lg-4 mb-4">
            <div class="dashboard-card fade-in">
              <h5>Pending Orders</h5>
              <p class="display-4 text-center text-primary">8</p>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- Restaurant Info Section -->
    <section id="settings" class="section">
      <div class="container">
        <h2 class="mb-4">Restaurant Info</h2>
        <div class="dashboard-card fade-in">
          <div class="restaurant-info">
            <div class="info-item">
              <span class="info-label">Name:</span> Pizza Palace
            </div>
            <div class="info-item">
              <span class="info-label">Category:</span> Pizza
            </div>
            <div class="info-item">
              <span class="info-label">Address:</span> 123 Food Street, Delivery City
            </div>
            <div class="info-item">
              <span class="info-label">Delivery Time:</span> 25-35 min
            </div>
            <div class="info-item">
              <span class="info-label">Delivery Fee:</span> Free
            </div>
            <button class="btn btn-edit" onclick="window.location.href='vendor-details.php'">Edit Info</button>
          </div>
        </div>
      </div>
    </section>

    <!-- Menu Management Section -->
    <section id="menu" class="section">
      <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
          <h2>Menu Management</h2>
          <button class="btn btn-edit" onclick="window.location.href='vendor-menu.php'">Manage Menu</button>
        </div>
        <div class="dashboard-card fade-in">
          <div id="menuItems">
            <!-- Menu items will be rendered here -->
          </div>
        </div>
      </div>
    </section>

    <!-- Orders Section -->
    <section id="orders" class="section">
      <div class="container">
        <h2 class="mb-4">Order Management</h2>
        <div id="ordersList">
          <!-- Orders will be rendered here -->
        </div>
      </div>
    </section>
  </div>

  <!-- Edit Restaurant Modal (Retained but not used since redirecting to vendor-details.php) -->
  <div class="modal fade" id="editRestaurantModal" tabindex="-1" aria-labelledby="editRestaurantModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="editRestaurantModalLabel">Edit Restaurant Info</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="mb-3">
            <label for="restaurantName" class="form-label">Restaurant Name</label>
            <input type="text" class="form-control" id="restaurantName" value="Pizza Palace">
          </div>
          <div class="mb-3">
            <label for="restaurantCategory" class="form-label">Category</label>
            <input type="text" class="form-control" id="restaurantCategory" value="Pizza">
          </div>
          <div class="mb-3">
            <label for="restaurantAddress" class="form-label">Address</label>
            <input type="text" class="form-control" id="restaurantAddress" value="123 Food Street, Delivery City">
          </div>
          <div class="mb-3">
            <label for="deliveryTime" class="form-label">Delivery Time (min)</label>
            <input type="text" class="form-control" id="deliveryTime" value="25-35">
          </div>
          <div class="mb-3">
            <label for="deliveryFee" class="form-label">Delivery Fee</label>
            <input type="text" class="form-control" id="deliveryFee" value="Free">
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          <button type="button" class="btn btn-primary" onclick="saveRestaurantInfo()">Save Changes</button>
        </div>
      </div>
    </div>
  </div>

  <!-- Add Menu Item Modal (Retained but not used since redirecting to vendor-menu.php) -->
  <div class="modal fade" id="addMenuItemModal" tabindex="-1" aria-labelledby="addMenuItemModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="addMenuItemModalLabel">Add Menu Item</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="mb-3">
            <label for="itemName" class="form-label">Item Name</label>
            <input type="text" class="form-control" id="itemName">
          </div>
          <div class="mb-3">
            <label for="itemCategory" class="form-label">Category</label>
            <select class="form-control" id="itemCategory">
              <option value="Pizzas">Pizzas</option>
              <option value="Sides">Sides</option>
              <option value="Drinks">Drinks</option>
              <option value="Desserts">Desserts</option>
            </select>
          </div>
          <div class="mb-3">
            <label for="itemDescription" class="form-label">Description</label>
            <textarea class="form-control" id="itemDescription"></textarea>
          </div>
          <div class="mb-3">
            <label for="itemPrice" class="form-label">Price ($)</label>
            <input type="number" class="form-control" id="itemPrice" step="0.01">
          </div>
          <div class="mb-3">
            <label for="itemImage" class="form-label">Image URL</label>
            <input type="text" class="form-control" id="itemImage">
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          <button type="button" class="btn btn-primary" onclick="addMenuItem()">Add Item</button>
        </div>
      </div>
    </div>
  </div>

  <!-- Bootstrap JS -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/js/bootstrap.bundle.min.js"></script>

  <!-- Custom JavaScript -->
  <script>
    // Sample data (in real app, this would come from API)
    const vendorData = {
      restaurant: {
        name: "Pizza Palace",
        category: "Pizza",
        address: "123 Food Street, Delivery City",
        deliveryTime: "25-35",
        deliveryFee: "Free"
      },
      menu: [{
          id: 1,
          name: "Margherita Pizza",
          category: "Pizzas",
          description: "Classic pizza with fresh tomatoes, mozzarella, and basil",
          price: 12.99,
          image: "data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 300 180'><rect fill='%23ff6b35' width='300' height='180'/><circle fill='%23ffa726' cx='150' cy='90' r='50'/><text x='150' y='100' text-anchor='middle' fill='white' font-size='28'>🍕</text></svg>"
        },
        {
          id: 2,
          name: "Garlic Bread",
          category: "Sides",
          description: "Crispy garlic bread with herbs",
          price: 4.99,
          image: "data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 300 180'><rect fill='%23ff9800' width='300' height='180'/><circle fill='%23ffa726' cx='150' cy='90' r='50'/><text x='150' y='100' text-anchor='middle' fill='white' font-size='28'>🥖</text></svg>"
        }
      ],
      orders: [{
          id: 101,
          customer: "John Doe",
          items: [{
              name: "Margherita Pizza",
              quantity: 1,
              price: 12.99
            },
            {
              name: "Cola",
              quantity: 2,
              price: 2.99
            }
          ],
          total: 18.97,
          status: "pending",
          date: "2025-05-27 20:30"
        },
        {
          id: 102,
          customer: "Jane Smith",
          items: [{
            name: "Pepperoni Pizza",
            quantity: 1,
            price: 14.99
          }],
          total: 14.99,
          status: "accepted",
          date: "2025-05-27 19:45"
        }
      ]
    };

    // DOM Elements
    const sidebar = document.getElementById('sidebar');
    const sidebarToggle = document.getElementById('sidebarToggle');
    const menuItemsContainer = document.getElementById('menuItems');
    const ordersListContainer = document.getElementById('ordersList');

    // Initialize page
    document.addEventListener('DOMContentLoaded', function() {
      renderMenuItems();
      renderOrders();
      updateRestaurantInfo();

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

    // Render menu items
    function renderMenuItems() {
      menuItemsContainer.innerHTML = vendorData.menu.map(item => `
        <div class="menu-item">
          <img src="${item.image}" alt="${item.name}">
          <div class="menu-item-info">
            <div class="menu-item-title">${item.name}</div>
            <div class="menu-item-price">$${item.price.toFixed(2)}</div>
            <div>${item.category}</div>
            <div>${item.description}</div>
          </div>
          <div class="menu-item-actions">
            <button class="btn btn-edit" onclick="window.location.href='vendor-menu.php'">Edit</button>
            <button class="btn btn-danger" onclick="deleteMenuItem(${item.id})">Delete</button>
          </div>
        </div>
      `).join('');
    }

    // Render orders
    function renderOrders() {
      ordersListContainer.innerHTML = vendorData.orders.map(order => `
        <div class="order-item fade-in">
          <div class="order-item-header">
            <div>
              <strong>Order #${order.id}</strong> - ${order.customer}
              <div class="text-muted">${order.date}</div>
            </div>
            <div class="order-status ${order.status}">${order.status.charAt(0).toUpperCase() + order.status.slice(1)}</div>
          </div>
          <div class="order-items">
            ${order.items.map(item => `
              <div>${item.name} x${item.quantity} - $${(item.price * item.quantity).toFixed(2)}</div>
            `).join('')}
          </div>
          <div class="order-total">Total: $${order.total.toFixed(2)}</div>
          <div class="mt-2">
            <select onchange="updateOrderStatus(${order.id}, this.value)">
              <option value="pending" ${order.status === 'pending' ? 'selected' : ''}>Pending</option>
              <option value="accepted" ${order.status === 'accepted' ? 'selected' : ''}>Accepted</option>
              <option value="delivered" ${order.status === 'delivered' ? 'selected' : ''}>Delivered</option>
            </select>
          </div>
        </div>
      `).join('');
    }

    // Update restaurant info
    function updateRestaurantInfo() {
      const infoContainer = document.querySelector('.restaurant-info');
      infoContainer.innerHTML = `
        <div class="info-item"><span class="info-label">Name:</span> ${vendorData.restaurant.name}</div>
        <div class="info-item"><span class="info-label">Category:</span> ${vendorData.restaurant.category}</div>
        <div class="info-item"><span class="info-label">Address:</span> ${vendorData.restaurant.address}</div>
        <div class="info-item"><span class="info-label">Delivery Time:</span> ${vendorData.restaurant.deliveryTime} min</div>
        <div class="info-item"><span class="info-label">Delivery Fee:</span> ${vendorData.restaurant.deliveryFee}</div>
        <button class="btn btn-edit" onclick="window.location.href='vendor-details.php'">Edit Info</button>
      `;
    }

    // Save restaurant info (not used since redirecting to vendor-details.php)
    window.saveRestaurantInfo = function() {
      vendorData.restaurant.name = document.getElementById('restaurantName').value;
      vendorData.restaurant.category = document.getElementById('restaurantCategory').value;
      vendorData.restaurant.address = document.getElementById('restaurantAddress').value;
      vendorData.restaurant.deliveryTime = document.getElementById('deliveryTime').value;
      vendorData.restaurant.deliveryFee = document.getElementById('deliveryFee').value;
      updateRestaurantInfo();
      bootstrap.Modal.getInstance(document.getElementById('editRestaurantModal')).hide();
      alert('Restaurant info updated!');
    };

    // Add menu item (not used since redirecting to vendor-menu.php)
    window.addMenuItem = function() {
      const newItem = {
        id: vendorData.menu.length + 1,
        name: document.getElementById('itemName').value,
        category: document.getElementById('itemCategory').value,
        description: document.getElementById('itemDescription').value,
        price: parseFloat(document.getElementById('itemPrice').value),
        image: document.getElementById('itemImage').value || 'data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 300 180"><rect fill="%23ff6b35" width="300" height="180"/><circle fill="%23ffa726" cx="150" cy="90" r="50"/><text x="150" y="100" text-anchor="middle" fill="white" font-size="28">🍽️</text></svg>'
      };
      if (newItem.name && newItem.price) {
        vendorData.menu.push(newItem);
        renderMenuItems();
        bootstrap.Modal.getInstance(document.getElementById('addMenuItemModal')).hide();
        alert('Menu item added!');
      } else {
        alert('Please fill in all required fields.');
      }
    };

    // Delete menu item
    window.deleteMenuItem = function(id) {
      if (confirm('Are you sure you want to delete this item?')) {
        vendorData.menu = vendorData.menu.filter(item => item.id !== id);
        renderMenuItems();
        alert('Menu item deleted!');
      }
    };

    // Update order status
    window.updateOrderStatus = function(orderId, status) {
      const order = vendorData.orders.find(o => o.id === orderId);
      if (order) {
        order.status = status;
        renderOrders();
        alert(`Order ${orderId} status updated to ${status}!`);
      }
    };

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