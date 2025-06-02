<?php 
session_start();
include 'config/db.php'

?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="shortcut icon" href="../assets/img/logostaste.png" type="image/x-png">
  <title>About TasteConnect</title>
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

    /* Section Styling */
    .section {
      padding: 5rem 0;
    }

    .section-title {
      text-align: center;
      margin-bottom: 3rem;
    }

    .section-title h2 {
      font-size: 2.5rem;
      font-weight: 700;
      color: var(--dark-color);
      margin-bottom: 1rem;
      position: relative;
    }

    .section-title h2::after {
      content: '';
      position: absolute;
      bottom: -10px;
      left: 50%;
      transform: translateX(-50%);
      width: 80px;
      height: 4px;
      background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
      border-radius: 2px;
    }

    .section-title p {
      color: var(--gray-800);
      font-size: 1.1rem;
    }

    /* About Content */
    .about-content {
      display: flex;
      align-items: center;
      gap: 3rem;
    }

    .about-image {
      flex: 1;
    }

    .about-image div {
      width: 100%;
      height: 400px;
      border-radius: var(--border-radius);
      box-shadow: var(--shadow);
      display: flex;
      align-items: center;
      justify-content: center;
      color: var(--white);
      font-size: 4rem;
      position: relative;
    }

    .about-text {
      flex: 1;
    }

    /* Team Section */
    .team-card {
      background: var(--white);
      border-radius: var(--border-radius);
      overflow: hidden;
      box-shadow: var(--shadow);
      transition: var(--transition);
      height: 100%;
      text-align: center;
    }

    .team-card:hover {
      transform: translateY(-10px);
      box-shadow: var(--shadow-hover);
    }

    .team-image {
      width: 100%;
      height: 250px;
      object-fit: cover;
    }

    .team-info {
      padding: 1.5rem;
    }

    .team-name {
      font-size: 1.25rem;
      font-weight: 700;
      color: var(--dark-color);
      margin-bottom: 0.5rem;
    }

    .team-role {
      color: var(--primary-color);
      font-weight: 600;
      font-size: 0.9rem;
      margin-bottom: 0.5rem;
    }

    .team-bio {
      color: var(--gray-800);
      font-size: 0.9rem;
      line-height: 1.5;
    }

    /* Values Section */
    .values-section {
      background: var(--gray-100);
    }

    .value-card {
      text-align: center;
      padding: 2rem;
      background: var(--white);
      border-radius: var(--border-radius);
      box-shadow: var(--shadow);
      transition: var(--transition);
      height: 100%;
    }

    .value-card:hover {
      transform: translateY(-5px);
      box-shadow: var(--shadow-hover);
    }

    .value-icon {
      width: 80px;
      height: 80px;
      background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      margin: 0 auto 1.5rem;
      color: var(--white);
      font-size: 2rem;
    }

    /* Stats Section */
    .stats-section {
      background: linear-gradient(135deg, var(--dark-color), #34495e);
      color: var(--white);
      padding: 3rem 0;
    }

    .stat-item {
      text-align: center;
    }

    .stat-number {
      font-size: 2.5rem;
      font-weight: 700;
      color: var(--primary-color);
      margin-bottom: 0.5rem;
    }

    .stat-label {
      font-size: 1rem;
      opacity: 0.9;
    }

    /* Responsive */
    @media (max-width: 768px) {
      .page-header h1 {
        font-size: 2rem;
      }

      .section-title h2 {
        font-size: 2rem;
      }

      .about-content {
        flex-direction: column;
      }

      .team-card {
        margin-bottom: 2rem;
      }

      .value-card {
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

    /* Smooth scroll */
    html {
      scroll-behavior: smooth;
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
          <h1>About TasteConnect</h1>
          <p>Connecting food lovers with amazing restaurants since 2024</p>
        </div>
      </div>
    </div>
  </section>

  <!-- Our Story Section -->
  <section class="section">
    <div class="container">
      <div class="section-title fade-in">
        <h2>Our Story</h2>
        <p>Learn about our journey and passion for food</p>
      </div>
      <div class="about-content">
        <div class="about-image fade-in">
          <div style="background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));">
            <i class="fas fa-utensils"></i>
            <div style="position: absolute; bottom: 20px; right: 20px; font-size: 2rem;">🍕🍔🍜</div>
          </div>
        </div>
        <div class="about-text fade-in">
          <h3>Our Mission</h3>
          <p>FoodieHub was founded in 2024 with a simple yet powerful mission: to make great food accessible to everyone while empowering local restaurants to thrive. We believe that food is more than just sustenance—it's a way to connect communities, celebrate cultures, and create memorable experiences.</p>
          <p>Our platform bridges the gap between hungry customers and talented restaurant owners, providing a seamless experience for discovering, ordering, and enjoying delicious meals. From mom-and-pop eateries to gourmet kitchens, we’re proud to showcase the diversity and creativity of local cuisines.</p>
          <p>At FoodieHub, we’re committed to innovation, quality, and customer satisfaction, ensuring every order is delivered with care and every restaurant is supported to succeed.</p>
        </div>
      </div>
    </div>
  </section>

  <!-- Our Team Section -->
  <section class="section" style="background: var(--gray-100);">
    <div class="container">
      <div class="section-title fade-in">
        <h2>Meet Our Team</h2>
        <p>The passionate people behind FoodieHub</p>
      </div>
      <div class="row">
        <div class="col-lg-4 col-md-6 mb-4">
          <div class="team-card fade-in">
            <div style="background: linear-gradient(135deg, var(--primary-color), var(--secondary-color)); height: 250px; display: flex; align-items: center; justify-content: center;">
              <i class="fas fa-user" style="font-size: 4rem; color: var(--white);"></i>
            </div>
            <div class="team-info">
              <h5 class="team-name">Jane Doe</h5>
              <div class="team-role">Founder & CEO</div>
              <p class="team-bio">Jane is a food enthusiast with a vision to revolutionize the food delivery industry by connecting local restaurants with customers.</p>
            </div>
          </div>
        </div>
        <div class="col-lg-4 col-md-6 mb-4">
          <div class="team-card fade-in">
            <div style="background: linear-gradient(135deg, var(--primary-color), var(--secondary-color)); height: 250px; display: flex; align-items: center; justify-content: center;">
              <i class="fas fa-user" style="font-size: 4rem; color: var(--white);"></i>
            </div>
            <div class="team-info">
              <h5 class="team-name">John Smith</h5>
              <div class="team-role">CTO</div>
              <p class="team-bio">John leads our tech team, ensuring a seamless and innovative platform for both customers and restaurant owners.</p>
            </div>
          </div>
        </div>
        <div class="col-lg-4 col-md-6 mb-4">
          <div class="team-card fade-in">
            <div style="background: linear-gradient(135deg, var(--primary-color), var(--secondary-color)); height: 250px; display: flex; align-items: center; justify-content: center;">
              <i class="fas fa-user" style="font-size: 4rem; color: var(--white);"></i>
            </div>
            <div class="team-info">
              <h5 class="team-name">Emily Johnson</h5>
              <div class="team-role">Head of Operations</div>
              <p class="team-bio">Emily oversees our logistics and vendor partnerships, ensuring every order is delivered with speed and accuracy.</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- Our Values Section -->
  <section class="section values-section">
    <div class="container">
      <div class="section-title fade-in">
        <h2>Our Values</h2>
        <p>What drives us to deliver the best experience</p>
      </div>
      <div class="row">
        <div class="col-lg-4 mb-4">
          <div class="value-card fade-in">
            <div class="value-icon">
              <i class="fas fa-heart"></i>
            </div>
            <h5>Customer Satisfaction</h5>
            <p>We prioritize your happiness, ensuring every order is a delightful experience from start to finish.</p>
          </div>
        </div>
        <div class="col-lg-4 mb-4">
          <div class="value-card fade-in">
            <div class="value-icon">
              <i class="fas fa-store"></i>
            </div>
            <h5>Support Local</h5>
            <p>We empower local restaurants by providing them a platform to showcase their culinary creations.</p>
          </div>
        </div>
        <div class="col-lg-4 mb-4">
          <div class="value-card fade-in">
            <div class="value-icon">
              <i class="fas fa-leaf"></i>
            </div>
            <h5>Sustainability</h5>
            <p>We’re committed to eco-friendly practices, from packaging to delivery, to protect our planet.</p>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- Stats Section -->
  <section class="stats-section">
    <div class="container">
      <div class="row text-center">
        <div class="col-md-4 stat-item fade-in">
          <div class="stat-number">500+</div>
          <div class="stat-label">Restaurants</div>
        </div>
        <div class="col-md-4 stat-item fade-in">
          <div class="stat-number">10K+</div>
          <div class="stat-label">Happy Customers</div>
        </div>
        <div class="col-md-4 stat-item fade-in">
          <div class="stat-number">50K+</div>
          <div class="stat-label">Orders Delivered</div>
        </div>
      </div>
    </div>
  </section>

  <!-- Footer -->
  <footer class="footer" style="background: var(--dark-color); color: var(--white); padding: 3rem 0 1rem;">
    <div class="container">
      <div class="row">
        <div class="col-lg-4 mb-4">
          <h5 class="mb-3">
            <i class="fas fa-utensils me-2 text-primary"></i>FoodieHub
          </h5>
          <p>Your favorite food delivery platform connecting you with the best local restaurants.</p>
          <div class="social-links">
            <a href="#"><i class="fab fa-facebook-f"></i></a>
            <a href="#"><i class="fab fa-twitter"></i></a>
            <a href="#"><i class="fab fa-instagram"></i></a>
            <a href="#"><i class="fab fa-linkedin-in"></i></a>
          </div>
        </div>
        <div class="col-lg-2 col-md-6 mb-4">
          <h6 class="mb-3">Quick Links</h6>
          <div class="footer-links">
            <div class="mb-2"><a href="index.php">Home</a></div>
            <div class="mb-2"><a href="shops.php">Shops</a></div>
            <div class="mb-2"><a href="menu.php">Menu</a></div>
            <div class="mb-2"><a href="about.php">About</a></div>
          </div>
        </div>
        <div class="col-lg-2 col-md-6 mb-4">
          <h6 class="mb-3">Support</h6>
          <div class="footer-links">
            <div class="mb-2"><a href="index.php#contact">Contact</a></div>
            <div class="mb-2"><a href="#">Help Center</a></div>
            <div class="mb-2"><a href="#">Privacy Policy</a></div>
            <div class="mb-2"><a href="#">Terms of Service</a></div>
          </div>
        </div>
        <div class="col-lg-4 mb-4">
          <h6 class="mb-3">Contact Info</h6>
          <div class="mb-2">
            <i class="fas fa-map-marker-alt me-2 text-primary"></i>
            123 Food Street, Delivery City, DC 12345
          </div>
          <div class="mb-2">
            <i class="fas fa-phone me-2 text-primary"></i>
            +1 (555) 123-4567
          </div>
          <div class="mb-2">
            <i class="fas fa-envelope me-2 text-primary"></i>
            info@foodiehub.com
          </div>
        </div>
      </div>
      <hr class="my-4" style="border-color: var(--gray-800);">
      <div class="row align-items-center">
        <div class="col-md-6">
          <p class="mb-0">© 2024 FoodieHub. All rights reserved.</p>
        </div>
        <div class="col-md-6 text-md-end">
          <p class="mb-0">Made with <i class="fas fa-heart text-danger"></i> for food lovers</p>
        </div>
      </div>
    </div>
  </footer>

  <!-- Bootstrap JS -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/js/bootstrap.bundle.min.js"></script>

  <!-- Custom JavaScript -->
  <script>
    // Smooth scrolling for navigation links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
      anchor.addEventListener('click', function(e) {
        e.preventDefault();
        const target = document.querySelector(this.getAttribute('href'));
        if (target) {
          const headerOffset = 80;
          const elementPosition = target.getBoundingClientRect().top;
          const offsetPosition = elementPosition + window.pageYOffset - headerOffset;

          window.scrollTo({
            top: offsetPosition,
            behavior: 'smooth'
          });
        }
      });
    });

    // Navbar background change on scroll
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

    // Fade in animation on scroll
    const observerOptions = {
      threshold: 0.1,
      rootMargin: '0px 0px -50px 0px'
    };

    const observer = new IntersectionObserver(function(entries) {
      entries.forEach(entry => {
        if (entry.isIntersecting) {
          entry.target.classList.add('visible');
        }
      });
    }, observerOptions);

    // Observe all fade-in elements
    document.querySelectorAll('.fade-in').forEach(el => {
      observer.observe(el);
    });

    // Login button functionality
    document.querySelector('.btn-login').addEventListener('click', function(e) {
      e.preventDefault();
      alert('Login/Register functionality will be implemented in the next phase. Stay tuned!');
    });

    // Counter animation for stats section
    function animateCounter(element, target) {
      let current = 0;
      const increment = target / 100;
      const timer = setInterval(() => {
        current += increment;
        if (current >= target) {
          current = target;
          clearInterval(timer);
        }
        element.textContent = Math.floor(current) + (target >= 1000 ? 'K+' : '+');
      }, 20);
    }

    // Trigger counter animation when stats section is visible
    const statsSection = document.querySelector('.stats-section');
    const statsObserver = new IntersectionObserver((entries) => {
      entries.forEach(entry => {
        if (entry.isIntersecting) {
          const counters = entry.target.querySelectorAll('.stat-number');
          counters.forEach((counter, index) => {
            const targets = [500, 10, 50]; // 500+, 10K+, 50K+
            animateCounter(counter, targets[index]);
          });
          statsObserver.unobserve(entry.target);
        }
      });
    }, {
      threshold: 0.5
    });

    statsObserver.observe(statsSection);

    // Team card hover effects
    document.querySelectorAll('.team-card, .value-card').forEach(card => {
      card.addEventListener('mouseenter', function() {
        this.style.transform = 'translateY(-10px) scale(1.02)';
      });

      card.addEventListener('mouseleave', function() {
        this.style.transform = 'translateY(0) scale(1)';
      });
    });

    // Initialize animations on page load
    window.addEventListener('load', function() {
      document.querySelectorAll('.page-header .fade-in').forEach(el => {
        setTimeout(() => {
          el.classList.add('visible');
        }, 500);
      });
    });
  </script>
</body>

</html>