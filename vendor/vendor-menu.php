<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Menu Management - FoodieHub</title>
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

    /* Menu Container */
    .menu-container {
      background: var(--white);
      border-radius: var(--border-radius);
      box-shadow: var(--shadow);
      padding: 2rem;
      margin: 3rem 0;
    }

    .menu-title {
      font-size: 2rem;
      font-weight: 700;
      color: var(--dark-color);
      margin-bottom: 1.5rem;
    }

    .menu-item {
      display: flex;
      align-items: center;
      padding: 1rem;
      border-bottom: 1px solid var(--gray-200);
      margin-bottom: 1rem;
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

    .btn {
      background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
      border: none;
      color: var(--white);
      padding: 0.75rem 1.5rem;
      border-radius: 25px;
      font-weight: 600;
      transition: var(--transition);
    }

    .btn:hover {
      transform: translateY(-2px);
      box-shadow: var(--shadow);
      color: var(--white);
    }

    .btn-danger {
      background: linear-gradient(135deg, var(--danger-color), #c0392b);
    }

    .form-group label {
      font-weight: 600;
      color: var(--gray-800);
      margin-bottom: 0.5rem;
    }

    .form-group input,
    .form-group select,
    .form-group textarea {
      border: 2px solid var(--gray-300);
      border-radius: 8px;
      padding: 0.75rem;
      font-size: 1rem;
      transition: var(--transition);
    }

    .form-group input:focus,
    .form-group select:focus,
    .form-group textarea:focus {
      border-color: var(--primary-color);
      box-shadow: 0 0 0 0.2rem rgba(255, 107, 53, 0.25);
      outline: none;
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

      .menu-container {
        padding: 1.5rem;
      }

      .menu-item {
        flex-direction: column;
        align-items: flex-start;
      }

      .menu-item img {
        margin-bottom: 1rem;
      }
    }

    /* Fade In Animation */
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
    <section class="section">
      <div class="container">
        <h2 class="mb-4">Menu Management</h2>
        <div class="menu-container fade-in">
          <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="menu-title">Menu Items</h2>
            <button class="btn" data-bs-toggle="modal" data-bs-target="#addMenuItemModal">Add New Item</button>
          </div>
          <div id="menuItems"></div>
          <div class="text-center mt-4">
            <button class="btn" onclick="proceedToSchedule()">Proceed to Schedule</button>
          </div>
        </div>
      </div>
    </section>
  </div>

  <!-- Add Menu Item Modal -->
  <div class="modal fade" id="addMenuItemModal" tabindex="-1" aria-labelledby="addMenuItemModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="addMenuItemModalLabel">Add Menu Item</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="form-group mb-3">
            <label for="itemName">Item Name</label>
            <input type="text" class="form-control" id="itemName" placeholder="Enter item name">
          </div>
          <div class="form-group mb-3">
            <label for="itemCategory">Category</label>
            <select class="form-control" id="itemCategory">
              <option value="Pizzas">Pizzas</option>
              <option value="Sides">Sides</option>
              <option value="Drinks">Drinks</option>
              <option value="Desserts">Desserts</option>
            </select>
          </div>
          <div class="form-group mb-3">
            <label for="itemDescription">Description</label>
            <textarea class="form-control" id="itemDescription" placeholder="Enter description"></textarea>
          </div>
          <div class="form-group mb-3">
            <label for="itemPrice">Price ($)</label>
            <input type="number" class="form-control" id="itemPrice" step="0.01" placeholder="Enter price">
          </div>
          <div class="form-group mb-3">
            <label for="itemImage">Image URL</label>
            <input type="text" class="form-control" id="itemImage" placeholder="Enter image URL">
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
    // Initialize menu items
    let menuItems = JSON.parse(localStorage.getItem('menuItems')) || [];

    // DOM Elements
    const sidebar = document.getElementById('sidebar');
    const sidebarToggle = document.getElementById('sidebarToggle');

    // Initialize page
    document.addEventListener('DOMContentLoaded', function() {
      // Render menu items
      renderMenuItems();

      // Highlight active sidebar link
      const currentPage = window.location.pathname.split('/').pop() || 'vendor-menu.php';
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
    });

    // Render menu items
    function renderMenuItems() {
      const menuItemsContainer = document.getElementById('menuItems');
      menuItemsContainer.innerHTML = menuItems.length ? menuItems.map(item => `
        <div class="menu-item fade-in">
          <img src="${item.image || 'data:image/svg+xml,<svg xmlns=\"http://www.w3.org/2000/svg\" viewBox=\"0 0 300 180\"><rect fill=\"%23ff6b35\" width=\"300\" height=\"180\"/><circle fill=\"%23ffa726\" cx=\"150\" cy=\"90\" r=\"50\"/><text x=\"150\" y=\"100\" text-anchor=\"middle\" fill=\"white\" font-size=\"28\">🍽️</text></svg>'}" alt="${item.name}">
          <div class="menu-item-info">
            <div class="menu-item-title">${item.name}</div>
            <div class="menu-item-price">$${item.price.toFixed(2)}</div>
            <div>${item.category}</div>
            <div>${item.description}</div>
          </div>
          <div>
            <button class="btn btn-danger" onclick="deleteMenuItem(${item.id})">Delete</button>
          </div>
        </div>
      `).join('') : '<p class="text-center">No menu items added yet.</p>';
    }

    // Add menu item
    window.addMenuItem = function() {
      const newItem = {
        id: menuItems.length + 1,
        name: document.getElementById('itemName').value,
        category: document.getElementById('itemCategory').value,
        description: document.getElementById('itemDescription').value,
        price: parseFloat(document.getElementById('itemPrice').value),
        image: document.getElementById('itemImage').value
      };

      if (newItem.name && newItem.price) {
        menuItems.push(newItem);
        localStorage.setItem('menuItems', JSON.stringify(menuItems));
        renderMenuItems();
        bootstrap.Modal.getInstance(document.getElementById('addMenuItemModal')).hide();
        alert('Menu item added successfully!');
      } else {
        alert('Please fill in all required fields.');
      }
    };

    // Delete menu item
    window.deleteMenuItem = function(id) {
      if (confirm('Are you sure you want to delete this item?')) {
        menuItems = menuItems.filter(item => item.id !== id);
        localStorage.setItem('menuItems', JSON.stringify(menuItems));
        renderMenuItems();
        alert('Menu item deleted!');
      }
    };

    // Proceed to schedule
    window.proceedToSchedule = function() {
      if (menuItems.length === 0) {
        alert('Please add at least one menu item before proceeding.');
        return;
      }
      alert('Menu saved successfully! Please set your restaurant schedule.');
      window.location.href = 'vendor-schedule.php';
    };
  </script>
</body>

</html>