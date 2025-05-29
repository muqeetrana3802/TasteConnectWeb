<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Browse Restaurants - FoodieHub</title>
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

    .sort-dropdown {
      border: 2px solid var(--gray-300);
      border-radius: var(--border-radius);
      padding: 0.5rem 1rem;
      font-weight: 500;
      transition: var(--transition);
    }

    .sort-dropdown:focus {
      border-color: var(--primary-color);
      outline: none;
      box-shadow: 0 0 0 0.2rem rgba(255, 107, 53, 0.25);
    }

    /* Shop Cards */
    .shops-grid {
      padding: 3rem 0;
    }

    .shop-card {
      background: var(--white);
      border-radius: var(--border-radius);
      overflow: hidden;
      box-shadow: var(--shadow);
      transition: var(--transition);
      height: 100%;
      cursor: pointer;
      position: relative;
    }

    .shop-card:hover {
      transform: translateY(-10px);
      box-shadow: var(--shadow-hover);
    }

    .shop-card .badge-container {
      position: absolute;
      top: 1rem;
      left: 1rem;
      z-index: 10;
    }

    .shop-badge {
      background: var(--success-color);
      color: var(--white);
      padding: 0.25rem 0.75rem;
      border-radius: 15px;
      font-size: 0.8rem;
      font-weight: 600;
      margin-right: 0.5rem;
    }

    .shop-badge.featured {
      background: var(--warning-color);
    }

    .shop-badge.new {
      background: var(--info-color);
    }

    .shop-image {
      width: 100%;
      height: 220px;
      object-fit: cover;
      transition: var(--transition);
    }

    .shop-card:hover .shop-image {
      transform: scale(1.05);
    }

    .shop-info {
      padding: 1.5rem;
    }

    .shop-title {
      font-size: 1.25rem;
      font-weight: 700;
      color: var(--dark-color);
      margin-bottom: 0.5rem;
    }

    .shop-category {
      color: var(--primary-color);
      font-weight: 600;
      font-size: 0.9rem;
      margin-bottom: 0.5rem;
    }

    .shop-description {
      color: var(--gray-800);
      font-size: 0.95rem;
      margin-bottom: 1rem;
      line-height: 1.5;
    }

    .shop-rating {
      display: flex;
      align-items: center;
      gap: 0.5rem;
      margin-bottom: 1rem;
    }

    .rating-stars {
      color: var(--secondary-color);
      font-size: 0.9rem;
    }

    .rating-text {
      color: var(--gray-800);
      font-size: 0.9rem;
      font-weight: 500;
    }

    .shop-meta {
      display: flex;
      justify-content: space-between;
      align-items: center;
      font-size: 0.9rem;
      color: var(--gray-800);
      margin-bottom: 1rem;
    }

    .delivery-time {
      display: flex;
      align-items: center;
      gap: 0.25rem;
    }

    .delivery-fee {
      font-weight: 600;
      color: var(--success-color);
    }

    .view-menu-btn {
      background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
      border: none;
      color: var(--white);
      padding: 0.75rem 1.5rem;
      border-radius: 25px;
      font-weight: 600;
      transition: var(--transition);
      width: 100%;
      text-decoration: none;
      display: inline-block;
      text-align: center;
    }

    .view-menu-btn:hover {
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

    /* Responsive */
    @media (max-width: 768px) {
      .page-header h1 {
        font-size: 2rem;
      }

      .filter-buttons {
        justify-content: center;
        margin-top: 1rem;
      }

      .shop-card {
        margin-bottom: 2rem;
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
          <h1>Browse Restaurants</h1>
          <p>Discover amazing restaurants in your area and get your favorite food delivered</p>
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
            <input type="text" id="searchInput" placeholder="Search restaurants, cuisine, or dish...">
          </div>
        </div>
        <div class="col-lg-5 mb-3 mb-lg-0">
          <div class="filter-buttons">
            <button class="filter-btn active" data-category="all">All</button>
            <button class="filter-btn" data-category="pizza">Pizza</button>
            <button class="filter-btn" data-category="burger">Burgers</button>
            <button class="filter-btn" data-category="asian">Asian</button>
            <button class="filter-btn" data-category="indian">Indian</button>
            <button class="filter-btn" data-category="mexican">Mexican</button>
            <button class="filter-btn" data-category="dessert">Desserts</button>
          </div>
        </div>
        <div class="col-lg-3">
          <select class="sort-dropdown w-100" id="sortSelect">
            <option value="rating">Sort by Rating</option>
            <option value="delivery-time">Delivery Time</option>
            <option value="delivery-fee">Delivery Fee</option>
            <option value="name">Restaurant Name</option>
          </select>
        </div>
      </div>
    </div>
  </section>

  <!-- Shops Grid -->
  <section class="shops-grid">
    <div class="container">
      <div class="row" id="shopsContainer">
        <!-- Loading spinner initially -->
        <div class="col-12 loading" id="loadingSpinner">
          <div class="spinner"></div>
        </div>
      </div>
    </div>
  </section>

  <!-- Bootstrap JS -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/js/bootstrap.bundle.min.js"></script>

  <!-- Custom JavaScript -->
  <script>
    // Sample shop data (in real app, this would come from API)
    const shopsData = [{
        id: 1,
        name: "Pizza Palace",
        category: "pizza",
        description: "Authentic Italian pizzas with fresh ingredients and traditional recipes",
        rating: 4.8,
        reviews: 234,
        deliveryTime: "25-35",
        deliveryFee: "Free",
        image: "data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 400 220'><rect fill='%23ff6b35' width='400' height='220'/><circle fill='%23ffffff' cx='200' cy='110' r='60' opacity='0.3'/><text x='200' y='120' text-anchor='middle' fill='white' font-size='40'>🍕</text></svg>",
        badges: ["featured"],
        featured: true
      },
      {
        id: 2,
        name: "Burger Junction",
        category: "burger",
        description: "Juicy gourmet burgers and crispy fries made with premium ingredients",
        rating: 4.6,
        reviews: 189,
        deliveryTime: "20-30",
        deliveryFee: "$2.99",
        image: "data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 400 220'><rect fill='%234caf50' width='400' height='220'/><circle fill='%23ffffff' cx='200' cy='110' r='60' opacity='0.3'/><text x='200' y='120' text-anchor='middle' fill='white' font-size='40'>🍔</text></svg>",
        badges: ["new"]
      },
      {
        id: 3,
        name: "Asian Delight",
        category: "asian",
        description: "Fresh sushi, ramen, and authentic Asian fusion cuisine",
        rating: 4.9,
        reviews: 312,
        deliveryTime: "30-45",
        deliveryFee: "Free",
        image: "data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 400 220'><rect fill='%23ffa726' width='400' height='220'/><circle fill='%23ffffff' cx='200' cy='110' r='60' opacity='0.3'/><text x='200' y='120' text-anchor='middle' fill='white' font-size='40'>🍜</text></svg>",
        badges: ["featured"]
      },
      {
        id: 4,
        name: "Spice Garden",
        category: "indian",
        description: "Traditional Indian curries, biryanis, and tandoor specialties",
        rating: 4.7,
        reviews: 156,
        deliveryTime: "35-50",
        deliveryFee: "$1.99",
        image: "data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 400 220'><rect fill='%23e91e63' width='400' height='220'/><circle fill='%23ffffff' cx='200' cy='110' r='60' opacity='0.3'/><text x='200' y='120' text-anchor='middle' fill='white' font-size='40'>🍛</text></svg>",
        badges: []
      },
      {
        id: 5,
        name: "Taco Fiesta",
        category: "mexican",
        description: "Authentic Mexican tacos, burritos, and fresh guacamole",
        rating: 4.5,
        reviews: 203,
        deliveryTime: "25-40",
        deliveryFee: "$2.49",
        image: "data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 400 220'><rect fill='%23ff9800' width='400' height='220'/><circle fill='%23ffffff' cx='200' cy='110' r='60' opacity='0.3'/><text x='200' y='120' text-anchor='middle' fill='white' font-size='40'>🌮</text></svg>",
        badges: ["new"]
      },
      {
        id: 6,
        name: "Sweet Dreams",
        category: "dessert",
        description: "Delicious cakes, pastries, and artisanal ice cream",
        rating: 4.8,
        reviews: 145,
        deliveryTime: "15-25",
        deliveryFee: "Free",
        image: "data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 400 220'><rect fill='%239c27b0' width='400' height='220'/><circle fill='%23ffffff' cx='200' cy='110' r='60' opacity='0.3'/><text x='200' y='120' text-anchor='middle' fill='white' font-size='40'>🍰</text></svg>",
        badges: []
      },
      {
        id: 7,
        name: "Mediterranean Grill",
        category: "mediterranean",
        description: "Fresh Mediterranean dishes, grilled meats, and healthy salads",
        rating: 4.6,
        reviews: 178,
        deliveryTime: "30-45",
        deliveryFee: "$1.49",
        image: "data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 400 220'><rect fill='%2300bcd4' width='400' height='220'/><circle fill='%23ffffff' cx='200' cy='110' r='60' opacity='0.3'/><text x='200' y='120' text-anchor='middle' fill='white' font-size='40'>🥗</text></svg>",
        badges: []
      },
      {
        id: 8,
        name: "BBQ Masters",
        category: "bbq",
        description: "Smoky BBQ ribs, pulled pork, and grilled specialties",
        rating: 4.7,
        reviews: 267,
        deliveryTime: "40-55",
        deliveryFee: "$3.49",
        image: "data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 400 220'><rect fill='%235d4037' width='400' height='220'/><circle fill='%23ffffff' cx='200' cy='110' r='60' opacity='0.3'/><text x='200' y='120' text-anchor='middle' fill='white' font-size='40'>🍖</text></svg>",
        badges: ["featured"]
      }
    ];

    let currentShops = [...shopsData];
    let currentCategory = 'all';
    let currentSort = 'rating';

    // DOM Elements
    const shopsContainer = document.getElementById('shopsContainer');
    const searchInput = document.getElementById('searchInput');
    const sortSelect = document.getElementById('sortSelect');
    const filterButtons = document.querySelectorAll('.filter-btn');
    const loadingSpinner = document.getElementById('loadingSpinner');

    // Initialize page
    document.addEventListener('DOMContentLoaded', function() {
      setTimeout(() => {
        loadingSpinner.style.display = 'none';
        renderShops(currentShops);
      }, 1000);
    });

    // Render shops
    function renderShops(shops) {
      if (shops.length === 0) {
        shopsContainer.innerHTML = `
                    <div class="col-12 no-results">
                        <i class="fas fa-search"></i>
                        <h3>No restaurants found</h3>
                        <p>Try adjusting your search or filters</p>
                    </div>
                `;
        return;
      }

      const shopsHtml = shops.map(shop => `
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="shop-card fade-in" onclick="viewShop(${shop.id})">
                        <div class="badge-container">
                            ${shop.badges.map(badge => `
                                <span class="shop-badge ${badge}">
                                    ${badge === 'featured' ? 'Featured' : badge === 'new' ? 'New' : badge}
                                </span>
                            `).join('')}
                        </div>
                        <img src="${shop.image}" alt="${shop.name}" class="shop-image">
                        <div class="shop-info">
                            <div class="shop-category">${shop.category.toUpperCase()}</div>
                            <h5 class="shop-title">${shop.name}</h5>
                            <p class="shop-description">${shop.description}</p>
                            <div class="shop-rating">
                                <div class="rating-stars">
                                    ${generateStars(shop.rating)}
                                </div>
                                <span class="rating-text">${shop.rating} (${shop.reviews} reviews)</span>
                            </div>
                            <div class="shop-meta">
                                <div class="delivery-time">
                                    <i class="fas fa-clock me-1"></i>
                                    ${shop.deliveryTime} min
                                </div>
                                <div class="delivery-fee">
                                    ${shop.deliveryFee === 'Free' ? 'Free delivery' : shop.deliveryFee + ' delivery'}
                                </div>
                            </div>
                            <a href="menu.php?shop=${shop.id}" class="view-menu-btn">
                                <i class="fas fa-utensils me-2"></i>View Menu
                            </a>
                        </div>
                    </div>
                </div>
            `).join('');

      shopsContainer.innerHTML = shopsHtml;

      // Trigger fade-in animation
      setTimeout(() => {
        document.querySelectorAll('.fade-in').forEach(el => {
          el.classList.add('visible');
        });
      }, 100);
    }

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

    // Filter functionality
    filterButtons.forEach(button => {
      button.addEventListener('click', function() {
        // Update active filter button
        filterButtons.forEach(btn => btn.classList.remove('active'));
        this.classList.add('active');

        currentCategory = this.dataset.category;
        applyFilters();
      });
    });

    // Search functionality
    searchInput.addEventListener('input', function() {
      applyFilters();
    });

    // Sort functionality
    sortSelect.addEventListener('change', function() {
      currentSort = this.value;
      applyFilters();
    });

    // Apply filters and search
    function applyFilters() {
      let filteredShops = [...shopsData];

      // Apply category filter
      if (currentCategory !== 'all') {
        filteredShops = filteredShops.filter(shop =>
          shop.category === currentCategory
        );
      }

      // Apply search filter
      const searchTerm = searchInput.value.toLowerCase();
      if (searchTerm) {
        filteredShops = filteredShops.filter(shop =>
          shop.name.toLowerCase().includes(searchTerm) ||
          shop.description.toLowerCase().includes(searchTerm) ||
          shop.category.toLowerCase().includes(searchTerm)
        );
      }

      // Apply sorting
      filteredShops.sort((a, b) => {
        switch (currentSort) {
          case 'rating':
            return b.rating - a.rating;
          case 'delivery-time':
            return parseInt(a.deliveryTime) - parseInt(b.deliveryTime);
          case 'delivery-fee':
            if (a.deliveryFee === 'Free') return -1;
            if (b.deliveryFee === 'Free') return 1;
            return parseFloat(a.deliveryFee.replace('$', '')) - parseFloat(b.deliveryFee.replace('$', ''));
          case 'name':
            return a.name.localeCompare(b.name);
          default:
            return 0;
        }
      });

      currentShops = filteredShops;
      renderShops(currentShops);
    }

    // View shop function
    function viewShop(shopId) {
      // In a real app, this would navigate to shop detail page
      const shop = shopsData.find(s => s.id === shopId);
      alert(`Viewing ${shop.name}! This would navigate to the restaurant detail page.`);
      window.location.href = `menu.php?shop=${shopId}`;
    }

    // Lazy load images (optional enhancement)
    document.addEventListener('DOMContentLoaded', function() {
      const images = document.querySelectorAll('.shop-image');
      if ('IntersectionObserver' in window) {
        const imageObserver = new IntersectionObserver((entries, observer) => {
          entries.forEach(entry => {
            if (entry.isIntersecting) {
              const image = entry.target;
              image.src = image.dataset.src || image.src;
              imageObserver.unobserve(image);
            }
          });
        });

        images.forEach(image => {
          imageObserver.observe(image);
        });
      }
    });

    // Debounce function to optimize search input
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

    // Add debounced search
    searchInput.addEventListener('input', debounce(function() {
      applyFilters();
    }, 300));

    // Handle window resize for responsive adjustments
    window.addEventListener('resize', function() {
      // Re-render shops to adjust layout if needed
      renderShops(currentShops);
    });

    // Accessibility: Handle keyboard navigation for filter buttons
    filterButtons.forEach(button => {
      button.addEventListener('keydown', function(event) {
        if (event.key === 'Enter' || event.key === ' ') {
          event.preventDefault();
          this.click();
        }
      });
    });

    // Handle initial page load animation
    window.addEventListener('load', function() {
      document.querySelectorAll('.shop-card').forEach((card, index) => {
        setTimeout(() => {
          card.classList.add('visible');
        }, index * 100);
      });
    });
  </script>
</body>

</html>