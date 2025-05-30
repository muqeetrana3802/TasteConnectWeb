<?php
session_start();
include 'config/db.php'

?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Restaurant Menu - FoodieHub</title>
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

    .btn-login {
      background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
      border: none;
      color: var(--white);
      padding: 0.5rem 1.5rem;
      border-radius: 25px;
      font-weight: 500;
      transition: var(--transition);
    }

    .btn-login:hover {
      transform: translateY(-2px);
      box-shadow: var(--shadow);
      color: var(--white);
    }

    /* Page Header */
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
      0% {
        background-position: 0 0;
      }

      100% {
        background-position: 100px 100px;
      }
    }

    .page-header h1 {
      font-size: 3rem;
      font-weight: 700;
      margin-bottom: 1rem;
      position: relative;
      z-index: 2;
    }

    .page-header p {
      font-size: 1.2rem;
      opacity: 0.9;
      position: relative;
      z-index: 2;
    }

    .shop-meta {
      display: flex;
      align-items: center;
      gap: 1rem;
      font-size: 0.9rem;
      color: var(--white);
      margin-top: 1rem;
    }

    .rating-stars {
      color: var(--secondary-color);
      font-size: 0.9rem;
    }

    /* Search and Filter Section */
    .search-filter-section {
      background: var(--white);
      padding: 2rem 0;
      box-shadow: var(--shadow);
      position: sticky;
      top: 76px;
      z-index: 100;
    }

    .search-box {
      position: relative;
    }

    .search-box input {
      border: 2px solid var(--gray-300);
      border-radius: 50px;
      padding: 1rem 1.5rem 1rem 3rem;
      font-size: 1rem;
      transition: var(--transition);
      width: 100%;
    }

    .search-box input:focus {
      border-color: var(--primary-color);
      box-shadow: 0 0 0 0.2rem rgba(255, 107, 53, 0.25);
      outline: none;
    }

    .search-box .search-icon {
      position: absolute;
      left: 1rem;
      top: 50%;
      transform: translateY(-50%);
      color: var(--gray-500);
      font-size: 1.1rem;
    }

    .filter-buttons {
      display: flex;
      gap: 0.5rem;
      flex-wrap: wrap;
    }

    .filter-btn {
      background: var(--gray-200);
      border: none;
      color: var(--dark-color);
      padding: 0.5rem 1rem;
      border-radius: 25px;
      font-weight: 500;
      transition: var(--transition);
      cursor: pointer;
    }

    .filter-btn:hover,
    .filter-btn.active {
      background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
      color: var(--white);
      transform: translateY(-2px);
    }

    /* Menu Items */
    .menu-grid {
      padding: 3rem 0;
    }

    .menu-card {
      background: var(--white);
      border-radius: var(--border-radius);
      overflow: hidden;
      box-shadow: var(--shadow);
      transition: var(--transition);
      height: 100%;
      cursor: pointer;
      position: relative;
    }

    .menu-card:hover {
      transform: translateY(-10px);
      box-shadow: var(--shadow-hover);
    }

    .menu-image {
      width: 100%;
      height: 180px;
      object-fit: cover;
      transition: var(--transition);
    }

    .menu-card:hover .menu-image {
      transform: scale(1.05);
    }

    .menu-info {
      padding: 1.5rem;
    }

    .menu-title {
      font-size: 1.1rem;
      font-weight: 700;
      color: var(--dark-color);
      margin-bottom: 0.5rem;
    }

    .menu-category {
      color: var(--primary-color);
      font-weight: 600;
      font-size: 0.85rem;
      margin-bottom: 0.5rem;
    }

    .menu-description {
      color: var(--gray-800);
      font-size: 0.9rem;
      margin-bottom: 1rem;
      line-height: 1.5;
    }

    .menu-price {
      color: var(--primary-color);
      font-weight: 700;
      font-size: 1.1rem;
      margin-bottom: 1rem;
    }

    .add-to-cart-btn {
      background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
      border: none;
      color: var(--white);
      padding: 0.75rem 1.5rem;
      border-radius: 25px;
      font-weight: 600;
      transition: var(--transition);
      width: 100%;
      text-align: center;
    }

    .add-to-cart-btn:hover {
      color: var(--white);
      transform: translateY(-2px);
      box-shadow: var(--shadow);
    }

    /* No Results */
    .no-results {
      text-align: center;
      padding: 4rem 0;
      color: var(--gray-800);
    }

    .no-results i {
      font-size: 4rem;
      color: var(--gray-400);
      margin-bottom: 1.5rem;
    }

    /* Loading Animation */
    .loading {
      display: flex;
      justify-content: center;
      align-items: center;
      padding: 3rem 0;
    }

    .spinner {
      width: 50px;
      height: 50px;
      border: 4px solid var(--gray-300);
      border-top: 4px solid var(--primary-color);
      border-radius: 50%;
      animation: spin 1s linear infinite;
    }

    @keyframes spin {
      0% {
        transform: rotate(0deg);
      }

      100% {
        transform: rotate(360deg);
      }
    }

    /* Cart Sidebar */
    .cart-sidebar {
      position: fixed;
      top: 0;
      right: -400px;
      width: 400px;
      height: 100%;
      background: var(--white);
      box-shadow: -5px 0 15px rgba(0, 0, 0, 0.1);
      transition: right 0.3s ease;
      z-index: 1000;
      padding: 2rem;
      overflow-y: auto;
    }

    .cart-sidebar.open {
      right: 0;
    }

    .cart-header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 1.5rem;
    }

    .cart-close {
      background: none;
      border: none;
      font-size: 1.5rem;
      color: var(--dark-color);
      cursor: pointer;
    }

    .cart-item {
      display: flex;
      align-items: center;
      padding: 1rem 0;
      border-bottom: 1px solid var(--gray-200);
    }

    .cart-item img {
      width: 60px;
      height: 60px;
      object-fit: cover;
      border-radius: var(--border-radius);
      margin-right: 1rem;
    }

    .cart-item-info {
      flex: 1;
    }

    .cart-item-title {
      font-size: 1rem;
      font-weight: 600;
      margin-bottom: 0.25rem;
    }

    .cart-item-price {
      color: var(--primary-color);
      font-weight: 600;
    }

    .cart-item-remove {
      background: none;
      border: none;
      color: var(--danger-color);
      font-size: 1.2rem;
      cursor: pointer;
    }

    .cart-total {
      margin-top: 1.5rem;
      font-size: 1.1rem;
      font-weight: 700;
      text-align: right;
    }

    .checkout-btn {
      background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
      border: none;
      color: var(--white);
      padding: 1rem;
      border-radius: 25px;
      font-weight: 600;
      width: 100%;
      text-align: center;
      margin-top: 1rem;
    }

    .checkout-btn:hover {
      transform: translateY(-2px);
      box-shadow: var(--shadow);
    }

    .cart-toggle {
      position: fixed;
      bottom: 2rem;
      right: 2rem;
      background: var(--primary-color);
      color: var(--white);
      width: 60px;
      height: 60px;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 1.5rem;
      cursor: pointer;
      box-shadow: var(--shadow);
      transition: var(--transition);
    }

    .cart-toggle:hover {
      background: var(--secondary-color);
      transform: scale(1.1);
    }

    .cart-count {
      position: absolute;
      top: -10px;
      right: -10px;
      background: var(--danger-color);
      color: var(--white);
      border-radius: 50%;
      width: 24px;
      height: 24px;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 0.8rem;
      font-weight: 600;
    }

    /* Responsive */
    @media (max-width: 768px) {
      .page-header h1 {
        font-size: 2rem;
      }

      .filter-buttons {
        justify-content: center;
        margin-top: 1rem;
      }

      .menu-card {
        margin-bottom: 2rem;
      }

      .cart-sidebar {
        width: 100%;
        right: -100%;
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
  <?php include 'includes/navbar.php'; ?>

  <!-- Page Header -->
  <section class="page-header">
    <div class="container">
      <div class="row">
        <div class="col-lg-8">
          <h1 id="restaurantName">Restaurant Menu</h1>
          <p>Explore delicious dishes and add your favorites to the cart</p>
          <div class="shop-meta">
            <div class="rating-stars" id="restaurantRating"></div>
            <div class="delivery-time"><i class="fas fa-clock me-1"></i><span id="restaurantDeliveryTime"></span> min</div>
            <div class="delivery-fee" id="restaurantDeliveryFee"></div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- Search and Filter Section -->
  <section class="search-filter-section">
    <div class="container">
      <div class="row align-items-center">
        <div class="col-lg-4 mb-3 mb-lg-0">
          <div class="search-box">
            <i class="fas fa-search search-icon"></i>
            <input type="text" id="searchInput" placeholder="Search dishes...">
          </div>
        </div>
        <div class="col-lg-8">
          <div class="filter-buttons" id="categoryFilters">
            <button class="filter-btn active" data-category="all">All</button>
            <!-- Dynamic categories will be added here -->
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- Menu Grid -->
  <section class="menu-grid">
    <div class="container">
      <div class="row" id="menuContainer">
        <!-- Loading spinner initially -->
        <div class="col-12 loading" id="loadingSpinner">
          <div class="spinner"></div>
        </div>
      </div>
    </div>
  </section>

  <!-- Cart Sidebar -->
  <div class="cart-sidebar" id="cartSidebar">
    <div class="cart-header">
      <h4>Your Cart</h4>
      <button class="cart-close" id="cartClose"><i class="fas fa-times"></i></button>
    </div>
    <div id="cartItems"></div>
    <div class="cart-total" id="cartTotal">Total: $0.00</div>
    <button class="checkout-btn" id="checkoutBtn">Proceed to Checkout</button>
  </div>
  <div class="cart-toggle" id="cartToggle">
    <i class="fas fa-shopping-cart"></i>
    <span class="cart-count" id="cartCount">0</span>
  </div>

  <!-- Bootstrap JS -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/js/bootstrap.bundle.min.js"></script>

  <!-- Custom JavaScript -->
  <script>
    // Sample menu data (in real app, this would come from API based on shop ID)
    const menuData = {
      1: {
        shop: {
          name: "Pizza Palace",
          category: "pizza",
          rating: 4.8,
          reviews: 234,
          deliveryTime: "25-35",
          deliveryFee: "Free"
        },
        categories: ["Pizzas", "Sides", "Drinks", "Desserts"],
        items: [{
            id: 1,
            name: "Margherita Pizza",
            category: "Pizzas",
            description: "Classic pizza with fresh tomatoes, mozzarella, and basil",
            price: 12.99,
            image: "data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 300 180'><rect fill='%23ff6b35' width='300' height='180'/><circle fill='%23ffa726' cx='150' cy='90' r='50'/><text x='150' y='100' text-anchor='middle' fill='white' font-size='28'>🍕</text></svg>"
          },
          {
            id: 2,
            name: "Pepperoni Pizza",
            category: "Pizzas",
            description: "Spicy pepperoni with mozzarella and tomato sauce",
            price: 14.99,
            image: "data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 300 180'><rect fill='%23e91e63' width='300' height='180'/><circle fill='%23ffa726' cx='150' cy='90' r='50'/><text x='150' y='100' text-anchor='middle' fill='white' font-size='28'>🍕</text></svg>"
          },
          {
            id: 3,
            name: "Garlic Bread",
            category: "Sides",
            description: "Crispy garlic bread with herbs",
            price: 4.99,
            image: "data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 300 180'><rect fill='%23ff9800' width='300' height='180'/><circle fill='%23ffa726' cx='150' cy='90' r='50'/><text x='150' y='100' text-anchor='middle' fill='white' font-size='28'>🥖</text></svg>"
          },
          {
            id: 4,
            name: "Cola",
            category: "Drinks",
            description: "Refreshing cola drink",
            price: 2.99,
            image: "data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 300 180'><rect fill='%2366bb6a' width='300' height='180'/><circle fill='%23ffa726' cx='150' cy='90' r='50'/><text x='150' y='100' text-anchor='middle' fill='white' font-size='28'>🥤</text></svg>"
          },
          {
            id: 5,
            name: "Tiramisu",
            category: "Desserts",
            description: "Classic Italian dessert with coffee and cream",
            price: 6.99,
            image: "data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 300 180'><rect fill='%239c27b0' width='300' height='180'/><circle fill='%23ffa726' cx='150' cy='90' r='50'/><text x='150' y='100' text-anchor='middle' fill='white' font-size='28'>🍰</text></svg>"
          }
        ]
      },
      2: {
        shop: {
          name: "Burger Junction",
          category: "burger",
          rating: 4.6,
          reviews: 189,
          deliveryTime: "20-30",
          deliveryFee: "$2.99"
        },
        categories: ["Burgers", "Sides", "Drinks"],
        items: [{
            id: 6,
            name: "Classic Cheeseburger",
            category: "Burgers",
            description: "Juicy beef patty with cheese, lettuce, and tomato",
            price: 9.99,
            image: "data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 300 180'><rect fill='%234caf50' width='300' height='180'/><circle fill='%23ffa726' cx='150' cy='90' r='50'/><text x='150' y='100' text-anchor='middle' fill='white' font-size='28'>🍔</text></svg>"
          },
          {
            id: 7,
            name: "French Fries",
            category: "Sides",
            description: "Crispy golden fries",
            price: 3.99,
            image: "data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 300 180'><rect fill='%23ff9800' width='300' height='180'/><circle fill='%23ffa726' cx='150' cy='90' r='50'/><text x='150' y='100' text-anchor='middle' fill='white' font-size='28'>🍟</text></svg>"
          },
          {
            id: 8,
            name: "Milkshake",
            category: "Drinks",
            description: "Creamy vanilla milkshake",
            price: 4.99,
            image: "data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 300 180'><rect fill='%2300bcd4' width='300' height='180'/><circle fill='%23ffa726' cx='150' cy='90' r='50'/><text x='150' y='100' text-anchor='middle' fill='white' font-size='28'>🥤</text></svg>"
          }
        ]
      }
      // Add more shop menus as needed
    };

    let currentMenu = [];
    let currentCategory = 'all';
    let cart = [];

    // DOM Elements
    const restaurantName = document.getElementById('restaurantName');
    const restaurantRating = document.getElementById('restaurantRating');
    const restaurantDeliveryTime = document.getElementById('restaurantDeliveryTime');
    const restaurantDeliveryFee = document.getElementById('restaurantDeliveryFee');
    const menuContainer = document.getElementById('menuContainer');
    const searchInput = document.getElementById('searchInput');
    const categoryFilters = document.getElementById('categoryFilters');
    const cartSidebar = document.getElementById('cartSidebar');
    const cartClose = document.getElementById('cartClose');
    const cartToggle = document.getElementById('cartToggle');
    const cartItemsContainer = document.getElementById('cartItems');
    const cartTotal = document.getElementById('cartTotal');
    const cartCount = document.getElementById('cartCount');
    const checkoutBtn = document.getElementById('checkoutBtn');
    const loadingSpinner = document.getElementById('loadingSpinner');

    // Initialize page
    document.addEventListener('DOMContentLoaded', function() {
      // Get shop ID from URL
      const urlParams = new URLSearchParams(window.location.search);
      const shopId = urlParams.get('shop') || '1';
      const shopData = menuData[shopId];

      if (shopData) {
        // Update header
        restaurantName.textContent = shopData.shop.name;
        restaurantRating.innerHTML = generateStars(shopData.shop.rating) + ` (${shopData.shop.reviews} reviews)`;
        restaurantDeliveryTime.textContent = shopData.shop.deliveryTime;
        restaurantDeliveryFee.textContent = shopData.shop.deliveryFee === 'Free' ? 'Free delivery' : shopData.shop.deliveryFee + ' delivery';

        // Render category filters
        categoryFilters.innerHTML = `
          <button class="filter-btn active" data-category="all">All</button>
          ${shopData.categories.map(category => `
            <button class="filter-btn" data-category="${category.toLowerCase()}">${category}</button>
          `).join('')}
        `;

        currentMenu = shopData.items;
        setTimeout(() => {
          loadingSpinner.style.display = 'none';
          renderMenu(currentMenu);
        }, 1000);

        // Add event listeners for filters
        document.querySelectorAll('.filter-btn').forEach(button => {
          button.addEventListener('click', function() {
            document.querySelectorAll('.filter-btn').forEach(btn => btn.classList.remove('active'));
            this.classList.add('active');
            currentCategory = this.dataset.category;
            applyFilters();
          });
        });
      } else {
        menuContainer.innerHTML = `
          <div class="col-12 no-results">
            <i class="fas fa-search"></i>
            <h3>Restaurant not found</h3>
            <p>Please select a valid restaurant.</p>
          </div>
        `;
        loadingSpinner.style.display = 'none';
      }
    });

    // Generate star rating
    function generateStars(rating) {
      const fullStars = Math.floor(rating);
      const hasHalfStar = rating % 1 !== 0;
      let stars = '';

      for (let i = 0; i < fullStars; i++) {
        stars += '<i class="fas fa-star"></i>';
      }

      if (hasHalfStar) {
        stars += '<i class="fas fa-star-half-alt"></i>';
      }

      const emptyStars = 5 - fullStars - (hasHalfStar ? 1 : 0);
      for (let i = 0; i < emptyStars; i++) {
        stars += '<i class="far fa-star"></i>';
      }

      return stars;
    }

    // Render menu items
    function renderMenu(items) {
      if (items.length === 0) {
        menuContainer.innerHTML = `
          <div class="col-12 no-results">
            <i class="fas fa-search"></i>
            <h3>No dishes found</h3>
            <p>Try adjusting your search or filters</p>
          </div>
        `;
        return;
      }

      const menuHtml = items.map(item => `
        <div class="col-lg-4 col-md-6 mb-4">
          <div class="menu-card fade-in">
            <img src="${item.image}" alt="${item.name}" class="menu-image">
            <div class="menu-info">
              <div class="menu-category">${item.category}</div>
              <h5 class="menu-title">${item.name}</h5>
              <p class="menu-description">${item.description}</p>
              <div class="menu-price">$${item.price.toFixed(2)}</div>
              <button class="add-to-cart-btn" data-id="${item.id}">
                <i class="fas fa-cart-plus me-2"></i>Add to Cart
              </button>
            </div>
          </div>
        </div>
      `).join('');

      menuContainer.innerHTML = menuHtml;

      // Trigger fade-in animation
      setTimeout(() => {
        document.querySelectorAll('.fade-in').forEach(el => {
          el.classList.add('visible');
        });
      }, 100);

      // Add event listeners for add to cart buttons
      document.querySelectorAll('.add-to-cart-btn').forEach(button => {
        button.addEventListener('click', function() {
          const itemId = this.dataset.id;
          const item = currentMenu.find(i => i.id == itemId);
          addToCart(item);
        });
      });
    }

    // Search functionality
    searchInput.addEventListener('input', debounce(function() {
      applyFilters();
    }, 300));

    // Apply filters and search
    function applyFilters() {
      const urlParams = new URLSearchParams(window.location.search);
      const shopId = urlParams.get('shop') || '1';
      let filteredItems = [...menuData[shopId].items];

      // Apply category filter
      if (currentCategory !== 'all') {
        filteredItems = filteredItems.filter(item => item.category.toLowerCase() === currentCategory);
      }

      // Apply search filter
      const searchTerm = searchInput.value.toLowerCase();
      if (searchTerm) {
        filteredItems = filteredItems.filter(item =>
          item.name.toLowerCase().includes(searchTerm) ||
          item.description.toLowerCase().includes(searchTerm) ||
          item.category.toLowerCase().includes(searchTerm)
        );
      }

      currentMenu = filteredItems;
      renderMenu(currentMenu);
    }

    // Cart functionality
    function addToCart(item) {
      const existingItem = cart.find(cartItem => cartItem.id === item.id);
      if (existingItem) {
        existingItem.quantity += 1;
      } else {
        cart.push({
          ...item,
          quantity: 1
        });
      }
      updateCart();
      openCart();
    }

    function removeFromCart(itemId) {
      cart = cart.filter(item => item.id !== itemId);
      updateCart();
    }

    function updateCart() {
      cartItemsContainer.innerHTML = cart.map(item => `
        <div class="cart-item">
          <img src="${item.image}" alt="${item.name}">
          <div class="cart-item-info">
            <div class="cart-item-title">${item.name}</div>
            <div class="cart-item-price">$${item.price.toFixed(2)} x ${item.quantity}</div>
          </div>
          <button class="cart-item-remove" data-id="${item.id}">
            <i class="fas fa-trash"></i>
          </button>
        </div>
      `).join('');

      const total = cart.reduce((sum, item) => sum + item.price * item.quantity, 0);
      cartTotal.textContent = `Total: $${total.toFixed(2)}`;
      cartCount.textContent = cart.reduce((sum, item) => sum + item.quantity, 0);

      // Add event listeners for remove buttons
      document.querySelectorAll('.cart-item-remove').forEach(button => {
        button.addEventListener('click', function() {
          const itemId = parseInt(this.dataset.id);
          removeFromCart(itemId);
        });
      });
    }

    function openCart() {
      cartSidebar.classList.add('open');
    }

    function closeCart() {
      cartSidebar.classList.remove('open');
    }

    cartToggle.addEventListener('click', openCart);
    cartClose.addEventListener('click', closeCart);

    checkoutBtn.addEventListener('click', function() {
      if (cart.length === 0) {
        alert('Your cart is empty!');
      } else {
        alert('Proceeding to checkout! This would navigate to the checkout page.');
        // In a real app, navigate to checkout page
      }
    });

    // Debounce function
    function debounce(func, wait) {
      let timeout;
      return function executedFunction(...args) {
        const later = () => {
          clearTimeout(timeout);
          func(...args);
        };
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
      };
    }

    // Accessibility: Handle keyboard navigation for filter buttons
    document.querySelectorAll('.filter-btn').forEach(button => {
      button.addEventListener('keydown', function(event) {
        if (event.key === 'Enter' || event.key === ' ') {
          event.preventDefault();
          this.click();
        }
      });
    });

    // Handle initial page load animation
    window.addEventListener('load', function() {
      document.querySelectorAll('.menu-card').forEach((card, index) => {
        setTimeout(() => {
          card.classList.add('visible');
        }, index * 100);
      });
    });
  </script>
</body>

</html>