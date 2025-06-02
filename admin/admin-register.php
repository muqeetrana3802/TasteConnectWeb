<?php
session_start();
require_once '../config/db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $username = trim($_POST['username']);
  $password = password_hash(trim($_POST['password']), PASSWORD_DEFAULT);
  $security_question = trim($_POST['security_question']);
  $security_answer = password_hash(trim($_POST['security_answer']), PASSWORD_DEFAULT);

  // Validate inputs
  if (empty($username) || empty($password) || empty($security_question) || empty($security_answer)) {
    $error = "All fields are required.";
  } else {
    // Check if username already exists
    $query = "SELECT id FROM admin WHERE username = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
      $error = "Username already exists.";
    } else {
      // Insert new admin
      $query = "INSERT INTO admin (username, password, security_question, security_answer) VALUES (?, ?, ?, ?)";
      $stmt = $conn->prepare($query);
      $stmt->bind_param("ssss", $username, $password, $security_question, $security_answer);
      if ($stmt->execute()) {
        header("Location: login.php?success=Admin registered successfully");
        exit;
      } else {
        $error = "Failed to register admin.";
      }
    }
  }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="shortcut icon" href="../assets/img/logostaste.png" type="image/x-png">
  <title>TasteConnect - Admin Registration</title>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
  <!-- Add your CSS file here -->
</head>

<body>
  <div class="container">
    <h1>Admin Registration</h1>
    <?php if (isset($error)): ?>
      <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>
    <form method="post" action="">
      <div class="mb-3">
        <label for="username" class="form-label">Username</label>
        <input type="text" name="username" id="username" class="form-control" required>
      </div>
      <div class="mb-3">
        <label for="password" class="form-label">Password</label>
        <input type="password" name="password" id="password" class="form-control" required>
      </div>
      <div class="mb-3">
        <label for="security_question" class="form-label">Security Question</label>
        <select name="security_question" id="security_question" class="form-control" required>
          <option value="">Select a security question</option>
          <option value="What is the name of your first pet?">What is the name of your first pet?</option>
          <option value="What was the name of your first school?">What was the name of your first school?</option>
          <option value="What is your mother’s maiden name?">What is your mother’s maiden name?</option>
        </select>
      </div>
      <div class="mb-3">
        <label for="security_answer" class="form-label">Security Answer</label>
        <input type="text" name="security_answer" id="security_answer" class="form-control" required>
      </div>
      <button type="submit" class="btn btn-primary">Register</button>
    </form>
  </div>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</body>

</html>
<?php if (isset($conn)) $conn->close(); ?>