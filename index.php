<?php 
session_start();
include 'config/db.php'

?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>FoodieHub - Order Your Favorite Food Online</title>
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
      --gray-800: #495057;
      --shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
      --border-radius: 12px;
      --transition: all 0.3s ease;
    }

    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    body {
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      line-height: 1.6;
      color: var(--dark-color);
    }

    /* Custom scrollbar */
    ::-webkit-scrollbar {
      width: 8px;
    }

    ::-webkit-scrollbar-track {
      background: var(--gray-200);
    }

    ::-webkit-scrollbar-thumb {
      background: var(--primary-color);
      border-radius: 4px;
    }

    /* Navbar */
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

    .navbar-nav .nav-link:hover {
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

    .navbar-nav .nav-link:hover::after {
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

    /* Hero Section */
    .hero {
      background: linear-gradient(135deg, rgba(255, 107, 53, 0.9), rgba(255, 167, 38, 0.9)),
        url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1200 600"><rect fill="%23ff6b35" width="1200" height="600"/><circle fill="%23ffa726" cx="200" cy="150" r="80" opacity="0.3"/><circle fill="%23ffa726" cx="800" cy="400" r="120" opacity="0.2"/><circle fill="%23ffffff" cx="400" cy="300" r="60" opacity="0.1"/><circle fill="%23ffffff" cx="1000" cy="200" r="100" opacity="0.1"/></svg>');
      background-size: cover;
      background-position: center;
      min-height: 100vh;
      display: flex;
      align-items: center;
      color: var(--white);
      position: relative;
    }

    .hero::before {
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

    .hero-content {
      position: relative;
      z-index: 2;
    }

    .hero h1 {
      font-size: 3.5rem;
      font-weight: 700;
      margin-bottom: 1.5rem;
      text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
    }

    .hero p {
      font-size: 1.2rem;
      margin-bottom: 2rem;
      opacity: 0.9;
    }

    .btn-cta {
      background: var(--white);
      color: var(--primary-color);
      padding: 1rem 2.5rem;
      border-radius: 50px;
      font-weight: 600;
      font-size: 1.1rem;
      border: none;
      transition: var(--transition);
      text-decoration: none;
      display: inline-block;
    }

    .btn-cta:hover {
      transform: translateY(-3px);
      box-shadow: 0 8px 25px rgba(0, 0, 0, 0.2);
      color: var(--primary-color);
    }

    /* Section styling */
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

    /* Shop cards */
    .shop-card,
    .menu-card {
      background: var(--white);
      border-radius: var(--border-radius);
      overflow: hidden;
      box-shadow: var(--shadow);
      transition: var(--transition);
      height: 100%;
    }

    .shop-card:hover,
    .menu-card:hover {
      transform: translateY(-10px);
      box-shadow: 0 15px 35px rgba(0, 0, 0, 0.15);
    }

    .shop-card img,
    .menu-card img {
      width: 100%;
      height: 200px;
      object-fit: cover;
    }

    .card-body {
      padding: 1.5rem;
    }

    .shop-rating {
      color: var(--secondary-color);
      font-size: 0.9rem;
    }

    .price {
      color: var(--primary-color);
      font-weight: 700;
      font-size: 1.2rem;
    }

    .btn-order {
      background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
      border: none;
      color: var(--white);
      padding: 0.5rem 1.5rem;
      border-radius: 25px;
      font-weight: 500;
      transition: var(--transition);
      width: 100%;
    }

    .btn-order:hover {
      transform: translateY(-2px);
      box-shadow: var(--shadow);
      color: var(--white);
    }

    /* How it works */
    .how-it-works {
      background: var(--gray-100);
    }

    .step-card {
      text-align: center;
      padding: 2rem;
      background: var(--white);
      border-radius: var(--border-radius);
      box-shadow: var(--shadow);
      transition: var(--transition);
      height: 100%;
    }

    .step-card:hover {
      transform: translateY(-5px);
    }

    .step-icon {
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

    .step-number {
      position: absolute;
      top: -10px;
      right: -10px;
      background: var(--accent-color);
      color: var(--white);
      width: 30px;
      height: 30px;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      font-weight: 700;
      font-size: 0.9rem;
    }

    /* Admin demo section */
    .admin-demo {
      background: linear-gradient(135deg, var(--dark-color), #34495e);
      color: var(--white);
    }

    .admin-icon {
      font-size: 4rem;
      color: var(--secondary-color);
      margin-bottom: 1.5rem;
    }

    /* About section */
    .about-content {
      display: flex;
      align-items: center;
      gap: 3rem;
    }

    .about-image {
      flex: 1;
    }

    .about-image img {
      width: 100%;
      border-radius: var(--border-radius);
      box-shadow: var(--shadow);
    }

    .about-text {
      flex: 1;
    }

    /* Contact form */
    .contact {
      background: var(--gray-100);
    }

    .form-floating .form-control {
      border: 2px solid var(--gray-300);
      border-radius: var(--border-radius);
      transition: var(--transition);
    }

    .form-floating .form-control:focus {
      border-color: var(--primary-color);
      box-shadow: 0 0 0 0.2rem rgba(255, 107, 53, 0.25);
    }

    .btn-submit {
      background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
      border: none;
      color: var(--white);
      padding: 1rem 3rem;
      border-radius: 50px;
      font-weight: 600;
      transition: var(--transition);
    }

    .btn-submit:hover {
      transform: translateY(-2px);
      box-shadow: var(--shadow);
      color: var(--white);
    }

    /* Footer */
    .footer {
      background: var(--dark-color);
      color: var(--white);
      padding: 3rem 0 1rem;
    }

    .footer-links a {
      color: var(--light-color);
      text-decoration: none;
      transition: var(--transition);
    }

    .footer-links a:hover {
      color: var(--primary-color);
    }

    .social-links a {
      display: inline-block;
      width: 40px;
      height: 40px;
      background: var(--primary-color);
      color: var(--white);
      text-align: center;
      line-height: 40px;
      border-radius: 50%;
      margin: 0 0.5rem;
      transition: var(--transition);
    }

    .social-links a:hover {
      background: var(--secondary-color);
      transform: translateY(-3px);
    }

    /* Responsive */
    @media (max-width: 768px) {
      .hero h1 {
        font-size: 2.5rem;
      }

      .section-title h2 {
        font-size: 2rem;
      }

      .about-content {
        flex-direction: column;
      }

      .navbar-nav {
        text-align: center;
        margin-top: 1rem;
      }
    }

    /* Smooth scroll */
    html {
      scroll-behavior: smooth;
    }

    /* Animation classes */
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

  <!-- Hero Section -->
  <section id="home" class="hero">
    <div class="container">
      <div class="row align-items-center">
        <div class="col-lg-6">
          <div class="hero-content fade-in">
            <h1>Order Your Favorite Food Online</h1>
            <p>Discover amazing restaurants and get your favorite meals delivered right to your doorstep. Fast, easy, and delicious!</p>
            <a href="#shops" class="btn-cta">
              <i class="fas fa-search me-2"></i>Browse Shops
            </a>
          </div>
        </div>
        <div class="col-lg-6">
          <div class="hero-image fade-in">
            <div style="background: url('data:image/svg+xml,<svg xmlns=&quot;http://www.w3.org/2000/svg&quot; viewBox=&quot;0 0 400 300&quot;><rect fill=&quot;%23ffffff&quot; width=&quot;400&quot; height=&quot;300&quot; rx=&quot;20&quot; opacity=&quot;0.1&quot;/><circle fill=&quot;%23ffa726&quot; cx=&quot;200&quot; cy=&quot;150&quot; r=&quot;80&quot;/><rect fill=&quot;%23ff6b35&quot; x=&quot;150&quot; y=&quot;120&quot; width=&quot;100&quot; height=&quot;60&quot; rx=&quot;10&quot;/><text x=&quot;200&quot; y=&quot;155&quot; text-anchor=&quot;middle&quot; fill=&quot;white&quot; font-size=&quot;24&quot; font-weight=&quot;bold&quot;>🍕</text></svg>'); background-size: contain; background-repeat: no-repeat; height: 300px;"></div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- Shops Section -->
  <section id="shops" class="section">
    <div class="container">
      <div class="section-title fade-in">
        <h2>Popular Restaurants</h2>
        <p>Choose from hundreds of restaurants and get your food delivered</p>
      </div>
      <div class="row">
        <div class="col-lg-4 col-md-6 mb-4">
          <div class="shop-card fade-in">
            <img src="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 400 200'><rect fill='%23ff6b35' width='400' height='200'/><circle fill='%23ffffff' cx='200' cy='100' r='50' opacity='0.3'/><text x='200' y='110' text-anchor='middle' fill='white' font-size='36'>🍕</text></svg>" alt="Pizza Palace">
            <div class="card-body">
              <h5 class="card-title">Pizza Palace</h5>
              <p class="card-text">Authentic Italian pizzas with fresh ingredients</p>
              <div class="shop-rating mb-2">
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <span class="ms-1">4.8 (120 reviews)</span>
              </div>
              <small class="text-muted">30-45 min • Free delivery</small>
            </div>
          </div>
        </div>
        <div class="col-lg-4 col-md-6 mb-4">
          <div class="shop-card fade-in">
            <img src="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 400 200'><rect fill='%234caf50' width='400' height='200'/><circle fill='%23ffffff' cx='200' cy='100' r='50' opacity='0.3'/><text x='200' y='110' text-anchor='middle' fill='white' font-size='36'>🍔</text></svg>" alt="Burger Junction">
            <div class="card-body">
              <h5 class="card-title">Burger Junction</h5>
              <p class="card-text">Juicy burgers and crispy fries made to order</p>
              <div class="shop-rating mb-2">
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star-half-alt"></i>
                <span class="ms-1">4.6 (89 reviews)</span>
              </div>
              <small class="text-muted">25-35 min • $2.99 delivery</small>
            </div>
          </div>
        </div>
        <div class="col-lg-4 col-md-6 mb-4">
          <div class="shop-card fade-in">
            <img src="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 400 200'><rect fill='%23ffa726' width='400' height='200'/><circle fill='%23ffffff' cx='200' cy='100' r='50' opacity='0.3'/><text x='200' y='110' text-anchor='middle' fill='white' font-size='36'>🍜</text></svg>" alt="Asian Delight">
            <div class="card-body">
              <h5 class="card-title">Asian Delight</h5>
              <p class="card-text">Fresh sushi, ramen, and Asian fusion cuisine</p>
              <div class="shop-rating mb-2">
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <span class="ms-1">4.9 (156 reviews)</span>
              </div>
              <small class="text-muted">20-30 min • Free delivery</small>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- Menu Section -->
  <section id="menu" class="section" style="background: var(--gray-100);">
    <div class="container">
      <div class="section-title fade-in">
        <h2>Popular Dishes</h2>
        <p>Most ordered items across all restaurants</p>
      </div>
      <div class="row">
        <div class="col-lg-3 col-md-6 mb-4">
          <div class="menu-card fade-in">
            <img src="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 300 200'><rect fill='%23ff6b35' width='300' height='200'/><circle fill='%23ffa726' cx='150' cy='100' r='60'/><text x='150' y='110' text-anchor='middle' fill='white' font-size='28'>🍕</text></svg>" alt="Margherita Pizza">
            <div class="card-body">
              <h6 class="card-title">Margherita Pizza</h6>
              <p class="price">$12.99</p>
              <button class="btn btn-order">Order Now</button>
            </div>
          </div>
        </div>
        <div class="col-lg-3 col-md-6 mb-4">
          <div class="menu-card fade-in">
            <img src="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 300 200'><rect fill='%234caf50' width='300' height='200'/><circle fill='%23ffa726' cx='150' cy='100' r='60'/><text x='150' y='110' text-anchor='middle' fill='white' font-size='28'>🍔</text></svg>" alt="Classic Cheeseburger">
            <div class="card-body">
              <h6 class="card-title">Classic Cheeseburger</h6>
              <p class="price">$9.99</p>
              <button class="btn btn-order">Order Now</button>
            </div>
          </div>
        </div>
        <div class="col-lg-3 col-md-6 mb-4">
          <div class="menu-card fade-in">
            <img src="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 300 200'><rect fill='%23ffa726' width='300' height='200'/><circle fill='%23ff6b35' cx='150' cy='100' r='60'/><text x='150' y='110' text-anchor='middle' fill='white' font-size='28'>🍜</text></svg>" alt="Chicken Ramen">
            <div class="card-body">
              <h6 class="card-title">Chicken Ramen</h6>
              <p class="price">$11.50</p>
              <button class="btn btn-order">Order Now</button>
            </div>
          </div>
        </div>
        <div class="col-lg-3 col-md-6 mb-4">
          <div class="menu-card fade-in">
            <img src="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 300 200'><rect fill='%23e91e63' width='300' height='200'/><circle fill='%23ffa726' cx='150' cy='100' r='60'/><text x='150' y='110' text-anchor='middle' fill='white' font-size='28'>🍰</text></svg>" alt="Chocolate Cake">
            <div class="card-body">
              <h6 class="card-title">Chocolate Cake</h6>
              <p class="price">$6.99</p>
              <button class="btn btn-order">Order Now</button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- How It Works Section -->
  <section id="how-it-works" class="section how-it-works">
    <div class="container">
      <div class="section-title fade-in">
        <h2>How It Works</h2>
        <p>Simple steps to get your favorite food delivered</p>
      </div>
      <div class="row">
        <div class="col-lg-4 mb-4">
          <div class="step-card fade-in">
            <div class="step-icon position-relative">
              <i class="fas fa-store"></i>
              <div class="step-number">1</div>
            </div>
            <h5>Vendor Adds Shop & Menu</h5>
            <p>Restaurant owners register and add their shops with delicious menu items and pricing</p>
          </div>
        </div>
        <div class="col-lg-4 mb-4">
          <div class="step-card fade-in">
            <div class="step-icon position-relative">
              <i class="fas fa-shopping-cart"></i>
              <div class="step-number">2</div>
            </div>
            <h5>User Places Order</h5>
            <p>Customers browse restaurants, select items, and place orders with secure payment options</p>
          </div>
        </div>
        <div class="col-lg-4 mb-4">
          <div class="step-card fade-in">
            <div class="step-icon position-relative">
              <i class="fas fa-clipboard-check"></i>
              <div class="step-number">3</div>
            </div>
            <h5>Admin Processes Order</h5>
            <p>Our admin team receives the order and coordinates with vendors for quick delivery</p>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- Admin Demo Section -->
  <section class="section admin-demo">
    <div class="container">
      <div class="row align-items-center">
        <div class="col-lg-6 text-center">
          <div class="admin-icon fade-in">
            <i class="fas fa-receipt"></i>
          </div>
        </div>
        <div class="col-lg-6">
          <div class="fade-in">
            <h2>Admin Order Management</h2>
            <p class="lead">Our efficient admin system ensures every order is processed quickly and accurately.</p>
            <p>Once an order is received, our admin team will:</p>
            <ul class="list-unstyled">
              <li><i class="fas fa-check text-success me-2"></i>Generate detailed order slips</li>
              <li><i class="fas fa-check text-success me-2"></i>Notify vendors immediately</li>
              <li><i class="fas fa-check text-success me-2"></i>Track order status in real-time</li>
              <li><i class="fas fa-check text-success me-2"></i>Coordinate delivery logistics</li>
            </ul>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- About Section -->
  <section id="about" class="section">
    <div class="container">
      <div class="about-content">
        <div class="about-image fade-in">
          <div style="background: linear-gradient(135deg, var(--primary-color), var(--secondary-color)); height: 400px; border-radius: var(--border-radius); display: flex; align-items: center; justify-content: center; color: white; font-size: 4rem;">
            <i class="fas fa-utensils"></i>
            <div style="position: absolute; bottom: 20px; right: 20px; font-size: 2rem;">🍕🍔🍜</div>
          </div>
        </div>
        <div class="about-text fade-in">
          <h2>About FoodieHub</h2>
          <p class="lead">Connecting food lovers with amazing restaurants since 2024</p>
          <p>Our mission is to bridge the gap between hungry customers and talented restaurant owners. We believe that great food should be accessible to everyone, and that local businesses deserve a platform to showcase their culinary creations.</p>
          <p>FoodieHub provides a seamless experience for both customers and vendors. Customers can easily discover new restaurants, browse menus, and get their favorite meals delivered quickly. Restaurant owners can focus on what they do best - creating delicious food - while we handle the digital ordering and delivery coordination.</p>
          <div class="mt-4">
            <div class="row">
              <div class="col-4 text-center">
                <h4 class="text-primary">500+</h4>
                <small>Restaurants</small>
              </div>
              <div class="col-4 text-center">
                <h4 class="text-primary">10K+</h4>
                <small>Happy Customers</small>
              </div>
              <div class="col-4 text-center">
                <h4 class="text-primary">50K+</h4>
                <small>Orders Delivered</small>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- Footer -->
  <footer class="footer">
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
            <div class="mb-2"><a href="#home">Home</a></div>
            <div class="mb-2"><a href="#shops">Shops</a></div>
            <div class="mb-2"><a href="#menu">Menu</a></div>
            <div class="mb-2"><a href="#about">About</a></div>
          </div>
        </div>
        <div class="col-lg-2 col-md-6 mb-4">
          <h6 class="mb-3">Support</h6>
          <div class="footer-links">
            <div class="mb-2"><a href="#contact">Contact</a></div>
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
          <p class="mb-0">&copy; 2024 FoodieHub. All rights reserved.</p>
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

    // Contact form submission
    document.getElementById('contactForm').addEventListener('submit', function(e) {
      e.preventDefault();

      // Get form data
      const name = document.getElementById('name').value;
      const email = document.getElementById('email').value;
      const message = document.getElementById('message').value;

      // Simple validation
      if (name && email && message) {
        // Show success message
        alert('Thank you for your message! We\'ll get back to you soon.');

        // Reset form
        this.reset();
      } else {
        alert('Please fill in all fields.');
      }
    });

    // Order button functionality
    document.querySelectorAll('.btn-order').forEach(button => {
      button.addEventListener('click', function() {
        const itemName = this.closest('.card-body').querySelector('.card-title').textContent;
        alert(`Great choice! "${itemName}" has been added to your cart. Please login to complete your order.`);
      });
    });


    // Initialize animations on page load
    window.addEventListener('load', function() {
      // Add visible class to hero content immediately
      document.querySelectorAll('.hero .fade-in').forEach(el => {
        setTimeout(() => {
          el.classList.add('visible');
        }, 500);
      });
    });

    // Add some interactive hover effects
    document.querySelectorAll('.shop-card, .menu-card').forEach(card => {
      card.addEventListener('mouseenter', function() {
        this.style.transform = 'translateY(-10px) scale(1.02)';
      });

      card.addEventListener('mouseleave', function() {
        this.style.transform = 'translateY(0) scale(1)';
      });
    });

    // Add click effect to CTA button
    document.querySelector('.btn-cta').addEventListener('click', function(e) {
      e.preventDefault();

      // Create ripple effect
      const ripple = document.createElement('span');
      ripple.style.position = 'absolute';
      ripple.style.borderRadius = '50%';
      ripple.style.background = 'rgba(255, 255, 255, 0.6)';
      ripple.style.transform = 'scale(0)';
      ripple.style.animation = 'ripple 0.6s linear';

      this.appendChild(ripple);

      setTimeout(() => {
        ripple.remove();
        // Navigate to shops section
        document.querySelector('#shops').scrollIntoView({
          behavior: 'smooth',
          block: 'start'
        });
      }, 300);
    });

    // Add CSS for ripple animation
    const style = document.createElement('style');
    style.textContent = `
            @keyframes ripple {
                to {
                    transform: scale(4);
                    opacity: 0;
                }
            }
            
            .btn-cta {
                position: relative;
                overflow: hidden;
            }
        `;
    document.head.appendChild(style);

    // Counter animation for about section stats
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

    // Trigger counter animation when about section is visible
    const aboutSection = document.querySelector('#about');
    const aboutObserver = new IntersectionObserver((entries) => {
      entries.forEach(entry => {
        if (entry.isIntersecting) {
          const counters = entry.target.querySelectorAll('.text-primary');
          counters.forEach((counter, index) => {
            const targets = [500, 10, 50]; // 500+, 10K+, 50K+
            animateCounter(counter, targets[index]);
          });
          aboutObserver.unobserve(entry.target);
        }
      });
    }, {
      threshold: 0.5
    });

    aboutObserver.observe(aboutSection);
  </script>
</body>

</html>