<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Order Management - FoodieHub</title>
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
<?php include 'includes/sidebar.php'; ?>


  <!-- Main Content -->
  <div class="main-content">
    <!-- Orders Section -->
    <section class="section">
      <div class="container">
        <h2 class="mb-4">Order Management</h2>
        <div id="ordersList">
          <!-- Orders will be rendered here -->
        </div>
      </div>
    </section>
  </div>

  <!-- Bootstrap JS -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/js/bootstrap.bundle.min.js"></script>

  <!-- Custom JavaScript -->
  <script>
    // Sample data (in real app, this would come from API)
    const orders = [{
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
    ];

    // DOM Elements
    const sidebar = document.getElementById('sidebar');
    const sidebarToggle = document.getElementById('sidebarToggle');
    const ordersListContainer = document.getElementById('ordersList');

    // Initialize page
    document.addEventListener('DOMContentLoaded', function() {
      renderOrders();

      // Highlight active sidebar link
      const currentPage = window.location.pathname.split('/').pop() || 'vendor-orders.php';
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

    // Render orders
    function renderOrders() {
      ordersListContainer.innerHTML = orders.map(order => `
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

    // Update order status
    window.updateOrderStatus = function(orderId, status) {
      const order = orders.find(o => o.id === orderId);
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