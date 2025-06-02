<?php
session_start();
include 'config/db.php';

// Fetch vendors from database
$vendorsQuery = "SELECT v.*, 
                        COUNT(DISTINCT mi.id) as menu_items_count,
                        GROUP_CONCAT(DISTINCT mi.category) as available_categories
                 FROM vendors v 
                 LEFT JOIN menu_items mi ON v.id = mi.vendor_id 
                 GROUP BY v.id 
                 ORDER BY v.created_at DESC";

$vendorsResult = mysqli_query($conn, $vendorsQuery);
$vendors = [];

if ($vendorsResult) {
  while ($row = mysqli_fetch_assoc($vendorsResult)) {
    $vendors[] = $row;
  }
}

// Get vendor images
$vendorImages = [];
$imagesQuery = "SELECT vendor_id, image_path FROM vendor_images";
$imagesResult = mysqli_query($conn, $imagesQuery);

if ($imagesResult) {
  while ($row = mysqli_fetch_assoc($imagesResult)) {
    $vendorImages[$row['vendor_id']] = $row['image_path'];
  }
}

// Get vendor schedules for today
$today = date('Y-m-d');
$schedulesQuery = "SELECT vendor_id, start_time, end_time FROM vendor_schedules WHERE schedule_date = '$today'";
$schedulesResult = mysqli_query($conn, $schedulesQuery);
$vendorSchedules = [];

if ($schedulesResult) {
  while ($row = mysqli_fetch_assoc($schedulesResult)) {
    $vendorSchedules[$row['vendor_id']] = [
      'start_time' => $row['start_time'],
      'end_time' => $row['end_time']
    ];
  }
}
?>
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

    .shop-badge.new {
      background: var(--info-color);
    }

    .shop-badge.open {
      background: var(--success-color);
    }

    .shop-badge.closed {
      background: var(--danger-color);
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

    .shop-contact {
      color: var(--gray-800);
      font-size: 0.9rem;
      margin-bottom: 1rem;
      display: flex;
      align-items: center;
      gap: 0.5rem;
    }

    .shop-meta {
      display: flex;
      justify-content: space-between;
      align-items: center;
      font-size: 0.9rem;
      color: var(--gray-800);
      margin-bottom: 1rem;
    }

    .menu-items-count {
      display: flex;
      align-items: center;
      gap: 0.25rem;
      font-weight: 500;
    }

    .opening-hours {
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
            <input type="text" id="searchInput" placeholder="Search restaurants...">
          </div>
        </div>
        <div class="col-lg-5 mb-3 mb-lg-0">
          <div class="filter-buttons">
            <button class="filter-btn active" data-category="all">All</button>
            <?php
            // Get unique categories from vendors
            $categories = array_unique(array_column($vendors, 'category'));
            foreach ($categories as $category) {
              if (!empty($category)) {
                echo '<button class="filter-btn" data-category="' . strtolower($category) . '">' . ucfirst($category) . '</button>';
              }
            }
            ?>
          </div>
        </div>
        <div class="col-lg-3">
          <select class="sort-dropdown w-100" id="sortSelect">
            <option value="name">Sort by Name</option>
            <option value="category">Category</option>
            <option value="newest">Newest First</option>
            <option value="oldest">Oldest First</option>
          </select>
        </div>
      </div>
    </div>
  </section>

  <!-- Shops Grid -->
  <section class="shops-grid">
    <div class="container">
      <div class="row" id="shopsContainer">
        <?php if (empty($vendors)): ?>
          <div class="col-12 no-results">
            <i class="fas fa-store-slash"></i>
            <h3>No restaurants available</h3>
            <p>Check back later for new restaurants!</p>
          </div>
        <?php else: ?>
          <?php foreach ($vendors as $vendor): ?>
            <?php
            // Determine if vendor is open
            $isOpen = false;
            $openingHours = 'Hours not set';

            if (isset($vendorSchedules[$vendor['id']])) {
              $currentTime = date('H:i:s');
              $startTime = $vendorSchedules[$vendor['id']]['start_time'];
              $endTime = $vendorSchedules[$vendor['id']]['end_time'];

              $isOpen = ($currentTime >= $startTime && $currentTime <= $endTime);
              $openingHours = date('g:i A', strtotime($startTime)) . ' - ' . date('g:i A', strtotime($endTime));
            }

            // Get vendor image or use default
            $vendorImage = isset($vendorImages[$vendor['id']]) ? $vendorImages[$vendor['id']] : null;
            $defaultImage = "data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 400 220'><rect fill='%23ff6b35' width='400' height='220'/><circle fill='%23ffffff' cx='200' cy='110' r='60' opacity='0.3'/><text x='200' y='120' text-anchor='middle' fill='white' font-size='40'>🍽️</text></svg>";

            // Determine badges
            $badges = [];
            if ($isOpen) {
              $badges[] = ['type' => 'open', 'text' => 'Open Now'];
            } else {
              $badges[] = ['type' => 'closed', 'text' => 'Closed'];
            }

            // Check if vendor is new (created within last 7 days)
            $createdDate = new DateTime($vendor['created_at']);
            $now = new DateTime();
            $daysDiff = $now->diff($createdDate)->days;

            if ($daysDiff <= 7) {
              $badges[] = ['type' => 'new', 'text' => 'New'];
            }
            ?>

            <div class="col-lg-4 col-md-6 mb-4 vendor-card" data-category="<?php echo strtolower($vendor['category']); ?>" data-name="<?php echo strtolower($vendor['restaurant_name']); ?>">
              <div class="shop-card fade-in" onclick="viewVendorMenu(<?php echo $vendor['id']; ?>)">
                <div class="badge-container">
                  <?php foreach ($badges as $badge): ?>
                    <span class="shop-badge <?php echo $badge['type']; ?>">
                      <?php echo $badge['text']; ?>
                    </span>
                  <?php endforeach; ?>
                </div>

                <img src="vendor/<?php echo $vendorImage ? htmlspecialchars($vendorImage) : $defaultImage; ?>"
                  alt="<?php echo htmlspecialchars($vendor['restaurant_name']); ?>"
                  class="shop-image">

                <div class="shop-info">
                  <div class="shop-category"><?php echo strtoupper(htmlspecialchars($vendor['category'])); ?></div>
                  <h5 class="shop-title"><?php echo htmlspecialchars($vendor['restaurant_name']); ?></h5>

                  <div class="shop-contact">
                    <i class="fas fa-phone"></i>
                    <?php echo htmlspecialchars($vendor['contact_number']); ?>
                  </div>

                  <div class="shop-meta">
                    <div class="menu-items-count">
                      <i class="fas fa-utensils me-1"></i>
                      <?php echo $vendor['menu_items_count']; ?> items
                    </div>
                    <div class="opening-hours">
                      <i class="fas fa-clock me-1"></i>
                      <?php echo $openingHours; ?>
                    </div>
                  </div>

                  <?php if (!empty($vendor['available_categories'])): ?>
                    <div class="mb-2">
                      <small class="text-muted">
                        <i class="fas fa-tags me-1"></i>
                        <?php echo htmlspecialchars($vendor['available_categories']); ?>
                      </small>
                    </div>
                  <?php endif; ?>

                  <small class="text-muted d-block mb-3">
                    <i class="fas fa-calendar-alt me-1"></i>
                    Joined <?php echo date('M d, Y', strtotime($vendor['created_at'])); ?>
                  </small>

                  <a href="menu.php" class="view-menu-btn">
                    View Menu
                  </a>
                </div>
              </div>
            </div>
          <?php endforeach; ?>
        <?php endif; ?>
      </div>
    </div>
  </section>

  <!-- Bootstrap JS -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/js/bootstrap.bundle.min.js"></script>

  <!-- Custom JavaScript -->
  <script>
    // DOM Elements
    const shopsContainer = document.getElementById('shopsContainer');
    const searchInput = document.getElementById('searchInput');
    const sortSelect = document.getElementById('sortSelect');
    const filterButtons = document.querySelectorAll('.filter-btn');
    const vendorCards = document.querySelectorAll('.vendor-card');

    let currentCategory = 'all';
    let currentSort = 'name';

    // Initialize page
    document.addEventListener('DOMContentLoaded', function() {
      // Trigger fade-in animation
      setTimeout(() => {
        document.querySelectorAll('.fade-in').forEach(el => {
          el.classList.add('visible');
        });
      }, 100);
    });

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
      const searchTerm = searchInput.value.toLowerCase();
      let visibleCards = [];

      vendorCards.forEach(card => {
        const category = card.dataset.category;
        const name = card.dataset.name;

        // Check category filter
        const categoryMatch = currentCategory === 'all' || category === currentCategory;

        // Check search filter
        const searchMatch = !searchTerm || name.includes(searchTerm);

        if (categoryMatch && searchMatch) {
          card.style.display = 'block';
          visibleCards.push(card);
        } else {
          card.style.display = 'none';
        }
      });

      // Apply sorting to visible cards
      if (visibleCards.length > 0) {
        const container = visibleCards[0].parentNode;
        visibleCards.sort((a, b) => {
          switch (currentSort) {
            case 'name':
              return a.dataset.name.localeCompare(b.dataset.name);
            case 'category':
              return a.dataset.category.localeCompare(b.dataset.category);
            case 'newest':
              // This would require additional data attributes for proper sorting
              return 0;
            case 'oldest':
              // This would require additional data attributes for proper sorting
              return 0;
            default:
              return 0;
          }
        });

        // Re-append sorted cards
        visibleCards.forEach(card => {
          container.appendChild(card);
        });
      }

      // Show no results message if needed
      checkNoResults(visibleCards.length);
    }

    // Check if no results and show message
    function checkNoResults(visibleCount) {
      const existingNoResults = document.querySelector('.no-results');

      if (visibleCount === 0 && !existingNoResults) {
        const noResultsHtml = `
          <div class="col-12 no-results">
            <i class="fas fa-search"></i>
            <h3>No restaurants found</h3>
            <p>Try adjusting your search or filters</p>
          </div>
        `;
        shopsContainer.insertAdjacentHTML('beforeend', noResultsHtml);
      } else if (visibleCount > 0 && existingNoResults) {
        existingNoResults.remove();
      }
    }

    // View vendor menu function
    function viewVendorMenu(vendorId) {
      window.location.href = `menu.php?vendor_id=${vendorId}`;
    }

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

    // Accessibility: Handle keyboard navigation for filter buttons
    filterButtons.forEach(button => {
      button.addEventListener('keydown', function(event) {
        if (event.key === 'Enter' || event.key === ' ') {
          event.preventDefault();
          this.click();
        }
      });
    });
  </script>
</body>

</html>