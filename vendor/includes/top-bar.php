<?php

// Database connection
// $servername = "localhost";
// $username = "root";
// $password = "";
// $dbname = "foodiehub";

// try {
//   $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
//   $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

//   $vendor_name = "Vendor"; 
//   if (isset($_SESSION['vendor_id'])) {
//     $stmt = $conn->prepare("SELECT restaurant_name FROM vendors WHERE id = :vendor_id");
//     $stmt->bindParam(':vendor_id', $_SESSION['vendor_id']);
//     $stmt->execute();
//     $vendor = $stmt->fetch(PDO::FETCH_ASSOC);
//     if ($vendor) {
//       $vendor_name = htmlspecialchars($vendor['restaurant_name']);
//     }
//   }
// } catch (PDOException $e) {
//   $vendor_name = "Vendor";
// }

?>

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
          <!-- <span class="nav-link"><i class="fas fa-user me-2"></i><?php echo $vendor_name; ?></span> -->
        </li>
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
          <a class="btn btn-logout" href="logout.php">Logout</a>
        </li>
      </ul>
    </div>
  </div>
</nav>