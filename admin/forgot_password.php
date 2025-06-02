<?php
session_start();
require_once '../config/db.php';

$step = 1; // Step 1: Username, Step 2: Security Question
$username = '';
$security_question = '';
$error = '';

// Check if username is provided via GET or POST
if (isset($_GET['username'])) {
  $username = $_GET['username'];
  $step = 2;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  if (isset($_POST['step']) && $_POST['step'] == '1') {
    // Step 1: Check username and get security question
    $username = trim($_POST['username']);

    if (!empty($username)) {
      $query = "SELECT security_question FROM admin WHERE username = ?";
      $stmt = $conn->prepare($query);
      $stmt->bind_param("s", $username);
      $stmt->execute();
      $result = $stmt->get_result();

      if ($result->num_rows === 1) {
        $admin = $result->fetch_assoc();
        $security_question = $admin['security_question'];
        $step = 2;
      } else {
        $error = "Username not found.";
      }
    } else {
      $error = "Please enter a username.";
    }
  } elseif (isset($_POST['step']) && $_POST['step'] == '2') {
    // Step 2: Verify security answer
    $username = trim($_POST['username']);
    $security_answer = trim($_POST['security_answer']);

    if (!empty($username) && !empty($security_answer)) {
      $query = "SELECT id, security_question, security_answer FROM admin WHERE username = ?";
      $stmt = $conn->prepare($query);
      $stmt->bind_param("s", $username);
      $stmt->execute();
      $result = $stmt->get_result();

      if ($result->num_rows === 1) {
        $admin = $result->fetch_assoc();
        $security_question = $admin['security_question'];

        // Check if security_answer exists and verify
        if (!empty($admin['security_answer']) && password_verify($security_answer, $admin['security_answer'])) {
          $_SESSION['reset_admin_id'] = $admin['id'];
          header("Location: reset_password.php");
          exit;
        } else {
          $error = "Incorrect security answer.";
          $step = 2;
        }
      } else {
        $error = "Username not found.";
        $step = 1;
      }
    } else {
      $error = "Please provide both username and security answer.";
      $step = 2;
    }
  }
}

// If we're on step 2 but don't have security question, get it
if ($step == 2 && empty($security_question) && !empty($username)) {
  $query = "SELECT security_question FROM admin WHERE username = ?";
  $stmt = $conn->prepare($query);
  $stmt->bind_param("s", $username);
  $stmt->execute();
  $result = $stmt->get_result();

  if ($result->num_rows === 1) {
    $admin = $result->fetch_assoc();
    $security_question = $admin['security_question'];
  } else {
    $step = 1;
    $username = '';
  }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>FoodHub - Forgot Password</title>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="assets/forget_password.css">
</head>

<body>
  <!-- Animated Background -->
  <div class="bg-animation">
    <div class="floating-shape shape-1"></div>
    <div class="floating-shape shape-2"></div>
    <div class="floating-shape shape-3"></div>
    <div class="floating-shape shape-4"></div>
  </div>

  <div class="main-container">
    <div class="reset-wrapper animate__animated animate__fadeIn">
      <!-- Left Side - Information -->
      <div class="info-section">
        <div class="info-content">
          <div class="info-icon">
            <i class="fas fa-key"></i>
          </div>
          <h1 class="info-title">Password Recovery</h1>
          <p class="info-subtitle">Don't worry! It happens to the best of us. Follow these simple steps to regain access to your account.</p>

          <ul class="steps-list">
            <li>
              <div class="step-number">1</div>
              <span>Enter your username</span>
            </li>
            <li>
              <div class="step-number">2</div>
              <span>Answer security question</span>
            </li>
            <li>
              <div class="step-number">3</div>
              <span>Create new password</span>
            </li>
            <li>
              <div class="step-number">4</div>
              <span>Login with new credentials</span>
            </li>
          </ul>
        </div>
      </div>

      <!-- Right Side - Reset Form -->
      <div class="reset-section">
        <div class="reset-header">
          <h1>Reset Password</h1>
          <p><?php echo ($step == 1) ? 'Enter your username to begin recovery' : 'Answer your security question'; ?></p>
        </div>

        <!-- Step Indicator -->
        <div class="step-indicator">
          <div class="step-item <?php echo ($step >= 1) ? 'active' : ''; ?>">
            <div class="step-circle">1</div>
            <span>Username</span>
          </div>
          <div class="step-divider"></div>
          <div class="step-item <?php echo ($step >= 2) ? 'active' : ''; ?>">
            <div class="step-circle">2</div>
            <span>Security</span>
          </div>
        </div>

        <form method="post" action="" id="resetForm">
          <input type="hidden" name="step" value="<?php echo $step; ?>">

          <?php if ($step == 1): ?>
            <!-- Step 1: Username -->
            <div class="form-floating">
              <input type="text" name="username" id="username" class="form-control" placeholder="Username" value="<?php echo htmlspecialchars($username); ?>" required>
              <label for="username"><i class="fas fa-user me-2"></i>Username</label>
            </div>

            <button type="submit" class="btn btn-reset" id="resetBtn">
              <span class="btn-text">Find Account</span>
              <div class="loading">
                <div class="spinner"></div>
              </div>
            </button>

          <?php else: ?>
            <!-- Step 2: Security Question -->
            <input type="hidden" name="username" value="<?php echo htmlspecialchars($username); ?>">

            <div class="security-question-display">
              <h6>Security Question:</h6>
              <p><?php echo htmlspecialchars($security_question); ?></p>
            </div>

            <div class="form-floating">
              <input type="text" name="security_answer" id="security_answer" class="form-control" placeholder="Your Answer" required>
              <label for="security_answer"><i class="fas fa-question-circle me-2"></i>Your Answer</label>
            </div>

            <button type="submit" class="btn btn-reset" id="resetBtn">
              <span class="btn-text">Verify Answer</span>
              <div class="loading">
                <div class="spinner"></div>
              </div>
            </button>

            <div class="text-center mb-3">
              <a href="?step=1" class="btn-secondary">
                <i class="fas fa-arrow-left me-2"></i>Change Username
              </a>
            </div>
          <?php endif; ?>

          <div class="back-to-login">
            <a href="login.php" class="btn-secondary">
              <i class="fas fa-arrow-left me-2"></i>Back to Login
            </a>
          </div>
        </form>
      </div>
    </div>
  </div>

  <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

  <script>
    // Form submission with loading animation
    document.getElementById('resetForm').addEventListener('submit', function(e) {
      const btn = document.getElementById('resetBtn');
      const btnText = btn.querySelector('.btn-text');
      const loading = btn.querySelector('.loading');

      btnText.style.opacity = '0';
      loading.style.display = 'block';
      btn.disabled = true;
    });

    // Show error message if exists
    <?php if (isset($error) && !empty($error)): ?>
      Swal.fire({
        icon: 'error',
        title: 'Error',
        text: '<?php echo htmlspecialchars($error); ?>',
        confirmButtonColor: '#ff6b35',
        background: '#fff',
        color: '#2c3e50',
        showClass: {
          popup: 'animate__animated animate__shakeX'
        }
      }).then(() => {
        // Reset form button
        const btn = document.getElementById('resetBtn');
        const btnText = btn.querySelector('.btn-text');
        const loading = btn.querySelector('.loading');

        btnText.style.opacity = '1';
        loading.style.display = 'none';
        btn.disabled = false;
      });
    <?php endif; ?>

    // Add focus effects
    document.querySelectorAll('.form-control').forEach(input => {
      input.addEventListener('focus', function() {
        this.parentElement.classList.add('focused');
      });

      input.addEventListener('blur', function() {
        if (this.value === '') {
          this.parentElement.classList.remove('focused');
        }
      });
    });

    // Auto-focus on the main input field
    window.addEventListener('load', function() {
      <?php if ($step == 1): ?>
        document.getElementById('username').focus();
      <?php else: ?>
        document.getElementById('security_answer').focus();
      <?php endif; ?>
    });

    // Keyboard shortcuts
    document.addEventListener('keydown', function(e) {
      if (e.ctrlKey && e.key === 'Enter') {
        document.getElementById('resetForm').submit();
      }
    });
  </script>
</body>

</html>
<?php
if (isset($conn)) {
  $conn->close();
}
?>