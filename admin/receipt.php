<?php
session_start();

if (!isset($_SESSION['admin_id'])) {
  header("Location: login.php?error=Please log in to access this page");
  exit;
}
require_once '../config/db.php';
require_once '../vendor/tecnickcom/tcpdf/tcpdf.php'; // Include TCPDF library (ensure the library is in the 'tcpdf' directory)

// Check if order_id is provided
if (!isset($_GET['order_id']) || !is_numeric($_GET['order_id'])) {
  header("Location: orders.php");
  exit;
}

$order_id = $_GET['order_id'];

// Fetch order details
$order_query = "SELECT o.id, o.user_id, o.vendor_id, o.total, o.order_date, o.status, 
                       u.first_name, u.last_name, v.restaurant_name
                FROM orders o
                JOIN users u ON o.user_id = u.id
                JOIN vendors v ON o.vendor_id = v.id
                WHERE o.id = ?";
$stmt = $conn->prepare($order_query);
$stmt->bind_param("i", $order_id);
$stmt->execute();
$order = $stmt->get_result()->fetch_assoc();

if (!$order) {
  header("Location: orders.php");
  exit;
}

// Fetch order items
$order_items_query = "SELECT m.name, oi.quantity, m.price
                      FROM order_items oi
                      JOIN menu_items m ON oi.menu_item_id = m.id
                      WHERE oi.order_id = ?";
$stmt = $conn->prepare($order_items_query);
$stmt->bind_param("i", $order_id);
$stmt->execute();
$order_items_result = $stmt->get_result();
$order_items = [];
while ($row = $order_items_result->fetch_assoc()) {
  $order_items[] = $row;
}

// Handle PDF download
if (isset($_GET['download']) && $_GET['download'] == 'pdf') {
  // Create new PDF document
  $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
  $pdf->SetCreator(PDF_CREATOR);
  $pdf->SetAuthor('FoodHub');
  $pdf->SetTitle('Order Receipt #' . $order['id']);
  $pdf->SetHeaderData('', 0, 'FoodHub Order Receipt', 'Order #' . $order['id']);
  $pdf->setHeaderFont(array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
  $pdf->setFooterFont(array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
  $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
  $pdf->SetMargins(15, 15, 15);
  $pdf->SetHeaderMargin(10);
  $pdf->SetFooterMargin(10);
  $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
  $pdf->setFont('helvetica', '', 12);
  $pdf->AddPage();

  // Generate HTML content for PDF
  $html = '
    <h1 style="color: #ff6b35; text-align: center;">FoodHub Order Receipt</h1>
    <p style="text-align: center;">Order #' . htmlspecialchars($order['id']) . '</p>
    <h3>Order Details</h3>
    <p><strong>Customer:</strong> ' . htmlspecialchars($order['first_name'] . ' ' . $order['last_name']) . '</p>
    <p><strong>Restaurant:</strong> ' . htmlspecialchars($order['restaurant_name']) . '</p>
    <p><strong>Order Date:</strong> ' . date('Y-m-d H:i:s', strtotime($order['order_date'])) . '</p>
    <p><strong>Status:</strong> ' . htmlspecialchars($order['status']) . '</p>
    <h3>Order Items</h3>
    <table border="1" cellpadding="5">
        <tr style="background-color: #f8f9fa;">
            <th>Item</th>
            <th>Quantity</th>
            <th>Price</th>
            <th>Total</th>
        </tr>';
  foreach ($order_items as $item) {
    $html .= '
        <tr>
            <td>' . htmlspecialchars($item['name']) . '</td>
            <td>' . htmlspecialchars($item['quantity']) . '</td>
            <td>PKR ' . number_format($item['price'], 2) . '</td>
            <td>PKR ' . number_format($item['quantity'] * $item['price'], 2) . '</td>
        </tr>';
  }
  $html .= '
    </table>
    <p style="text-align: right; font-weight: bold; color: #ff6b35;">Total: PKR ' . number_format($order['total'], 2) . '</p>';

  // Write HTML to PDF
  $pdf->writeHTML($html, true, false, true, false, '');
  $pdf->Output('receipt_order_' . $order['id'] . '.pdf', 'D'); // Download the PDF
  exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>FoodHub - Order Receipt</title>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
  <style>
    :root {
      --primary-color: #ff6b35;
      --secondary-color: #f8f9fa;
      --dark-color: #2c3e50;
      --light-gray: #f8f9fa;
      --medium-gray: #6c757d;
      --white: #ffffff;
      --shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
      --border-radius: 15px;
      --transition: all 0.3s ease;
    }

    body {
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      background-color: var(--light-gray);
      padding: 2rem;
    }

    .receipt-container {
      max-width: 800px;
      margin: 0 auto;
      background: var(--white);
      border-radius: var(--border-radius);
      box-shadow: var(--shadow);
      padding: 2rem;
    }

    .receipt-header {
      text-align: center;
      border-bottom: 2px solid var(--light-gray);
      padding-bottom: 1rem;
      margin-bottom: 2rem;
    }

    .receipt-header h1 {
      color: var(--primary-color);
      font-size: 2rem;
      font-weight: bold;
    }

    .receipt-details {
      margin-bottom: 2rem;
    }

    .receipt-details p {
      margin: 0.5rem 0;
      font-size: 1rem;
      color: var(--dark-color);
    }

    .receipt-details strong {
      color: var(--medium-gray);
    }

    .receipt-table {
      width: 100%;
      border-collapse: collapse;
      margin-bottom: 1rem;
    }

    .receipt-table th,
    .receipt-table td {
      padding: 0.75rem;
      text-align: left;
      border-bottom: 1px solid var(--light-gray);
    }

    .receipt-table th {
      background: var(--light-gray);
      color: var(--dark-color);
      font-weight: 600;
    }

    .receipt-footer {
      text-align: right;
      margin-top: 1rem;
    }

    .receipt-footer p {
      font-size: 1.2rem;
      font-weight: bold;
      color: var(--primary-color);
    }

    .btn-back,
    .btn-download {
      display: inline-block;
      padding: 0.75rem 1.5rem;
      background: var(--primary-color);
      color: var(--white);
      border-radius: 8px;
      text-decoration: none;
      transition: var(--transition);
      margin-right: 1rem;
    }

    .btn-back:hover,
    .btn-download:hover {
      background: #e55a2e;
      transform: translateY(-2px);
    }

    @media print {

      .btn-back,
      .btn-download {
        display: none;
      }

      body {
        background: none;
      }
    }
  </style>
</head>

<body>
  <div class="receipt-container">
    <div class="receipt-header">
      <h1>FoodHub Order Receipt</h1>
      <p>Order #<?php echo htmlspecialchars($order['id']); ?></p>
    </div>
    <div class="receipt-details">
      <p><strong>Customer:</strong> <?php echo htmlspecialchars($order['first_name'] . ' ' . $order['last_name']); ?></p>
      <p><strong>Restaurant:</strong> <?php echo htmlspecialchars($order['restaurant_name']); ?></p>
      <p><strong>Order Date:</strong> <?php echo date('Y-m-d H:i:s', strtotime($order['order_date'])); ?></p>
      <p><strong>Status:</strong> <?php echo htmlspecialchars($order['status']); ?></p>
    </div>
    <table class="receipt-table">
      <thead>
        <tr>
          <th>Item</th>
          <th>Quantity</th>
          <th>Price</th>
          <th>Total</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($order_items as $item): ?>
          <tr>
            <td><?php echo htmlspecialchars($item['name']); ?></td>
            <td><?php echo htmlspecialchars($item['quantity']); ?></td>
            <td>PKR <?php echo number_format($item['price'], 2); ?></td>
            <td>PKR <?php echo number_format($item['quantity'] * $item['price'], 2); ?></td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
    <div class="receipt-footer">
      <p>Total: PKR <?php echo number_format($order['total'], 2); ?></p>
    </div>
    <a href="receipt.php?order_id=<?php echo $order['id']; ?>&download=pdf" class="btn-download">Download PDF</a>
    <a href="orders.php" class="btn-back">Back to Orders</a>
  </div>
</body>

</html>
<?php $conn->close(); ?>