<?php
// Start the vendor session
session_name('vendor_session');
session_start();

// Redirect to login if not authenticated
if (!isset($_SESSION['vendor_id'])) {
  header("Location: vendor-login.php");
  exit();
}

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "foodiehub";

try {
  $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
  $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
  die("Connection failed: " . $e->getMessage());
}

// Handle AJAX requests
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['action'])) {
  header('Content-Type: application/json');
  $vendor_id = $_SESSION['vendor_id'];
  $response = ['success' => false, 'message' => ''];

  try {
    if ($_POST['action'] == 'add') {
      $schedule_date = $_POST['date'];
      $start_time = $_POST['start_time'];
      $end_time = $_POST['end_time'];

      // Validate inputs
      if (empty($schedule_date) || empty($start_time) || empty($end_time)) {
        throw new Exception("All fields are required.");
      }
      if (!DateTime::createFromFormat('Y-m-d', $schedule_date)) {
        throw new Exception("Invalid date format.");
      }
      if (!DateTime::createFromFormat('H:i', $start_time) || !DateTime::createFromFormat('H:i', $end_time)) {
        throw new Exception("Invalid time format.");
      }
      if (strtotime($end_time) <= strtotime($start_time)) {
        throw new Exception("End time must be after start time.");
      }

      $stmt = $conn->prepare("INSERT INTO vendor_schedules (vendor_id, schedule_date, start_time, end_time) VALUES (:vendor_id, :schedule_date, :start_time, :end_time)");
      $stmt->bindParam(':vendor_id', $vendor_id);
      $stmt->bindParam(':schedule_date', $schedule_date);
      $stmt->bindParam(':start_time', $start_time);
      $stmt->bindParam(':end_time', $end_time);
      $stmt->execute();

      $response['success'] = true;
      $response['message'] = "Availability added successfully!";
      $response['id'] = $conn->lastInsertId();
    } elseif ($_POST['action'] == 'edit') {
      $id = intval($_POST['id']);
      $schedule_date = $_POST['date'];
      $start_time = $_POST['start_time'];
      $end_time = $_POST['end_time'];

      // Validate inputs
      if (empty($schedule_date) || empty($start_time) || empty($end_time)) {
        throw new Exception("All fields are required.");
      }
      if (!DateTime::createFromFormat('Y-m-d', $schedule_date)) {
        throw new Exception("Invalid date format.");
      }
      if (!DateTime::createFromFormat('H:i', $start_time) || !DateTime::createFromFormat('H:i', $end_time)) {
        throw new Exception("Invalid time format.");
      }
      if (strtotime($end_time) <= strtotime($start_time)) {
        throw new Exception("End time must be after start time.");
      }

      $stmt = $conn->prepare("UPDATE vendor_schedules SET schedule_date = :schedule_date, start_time = :start_time, end_time = :end_time WHERE id = :id AND vendor_id = :vendor_id");
      $stmt->bindParam(':schedule_date', $schedule_date);
      $stmt->bindParam(':start_time', $start_time);
      $stmt->bindParam(':end_time', $end_time);
      $stmt->bindParam(':id', $id);
      $stmt->bindParam(':vendor_id', $vendor_id);
      $stmt->execute();

      if ($stmt->rowCount() > 0) {
        $response['success'] = true;
        $response['message'] = "Availability updated successfully!";
      } else {
        throw new Exception("No availability found or you don't have permission.");
      }
    } elseif ($_POST['action'] == 'delete') {
      $id = intval($_POST['id']);
      $stmt = $conn->prepare("DELETE FROM vendor_schedules WHERE id = :id AND vendor_id = :vendor_id");
      $stmt->bindParam(':id', $id);
      $stmt->bindParam(':vendor_id', $vendor_id);
      $stmt->execute();

      if ($stmt->rowCount() > 0) {
        $response['success'] = true;
        $response['message'] = "Availability deleted successfully!";
      } else {
        throw new Exception("No availability found or you don't have permission.");
      }
    }
  } catch (Exception $e) {
    $response['message'] = $e->getMessage();
  }

  echo json_encode($response);
  exit();
}

// Fetch schedules for FullCalendar
try {
  $stmt = $conn->prepare("SELECT id, schedule_date, start_time, end_time FROM vendor_schedules WHERE vendor_id = :vendor_id");
  $stmt->bindParam(':vendor_id', $_SESSION['vendor_id']);
  $stmt->execute();
  $schedules = $stmt->fetchAll(PDO::FETCH_ASSOC);

  $events = [];
  foreach ($schedules as $schedule) {
    $events[] = [
      'id' => $schedule['id'],
      'title' => $schedule['start_time'] . ' - ' . $schedule['end_time'],
      'start' => $schedule['schedule_date'] . 'T' . $schedule['start_time'],
      'end' => $schedule['schedule_date'] . 'T' . $schedule['end_time']
    ];
  }
  $events_json = json_encode($events);
} catch (PDOException $e) {
  $events_json = '[]';
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="shortcut icon" href="../assets/img/logostaste.png" type="image/x-png">
  <title>Restaurant Availability - TasteConnect</title>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/fullcalendar@5.3.0/main.min.css" rel="stylesheet">
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

    /* Page Header */
    .page-header {
      background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
      color: var(--white);
      padding: 6rem 0;
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

    /* Calendar Section */
    .calendar-container {
      background: var(--white);
      border-radius: var(--border-radius);
      box-shadow: var(--shadow);
      padding: 2rem;
      margin: 3rem 0;
    }

    .calendar-title {
      font-size: 2rem;
      font-weight: 700;
      color: var(--dark-color);
      margin-bottom: 1.5rem;
      text-align: center;
    }

    #calendar {
      max-width: 900px;
      margin: 0 auto;
    }

    .form-group label {
      font-weight: 600;
      color: var(--gray-800);
      margin-bottom: 0.5rem;
    }

    .form-group input {
      border: 2px solid var(--gray-300);
      border-radius: 8px;
      padding: 0.75rem;
      font-size: 1rem;
      transition: var(--transition);
    }

    .form-group input:focus {
      border-color: var(--primary-color);
      box-shadow: 0 0 0 0.2rem rgba(255, 107, 53, 0.25);
      outline: none;
    }

    .btn {
      background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
      border: none;
      color: var(--white);
      padding: 1rem 2rem;
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

    /* Messages */
    .error-message {
      color: var(--danger-color);
      font-size: 0.9rem;
      margin-bottom: 1rem;
      text-align: center;
      display: none;
    }

    .success-message {
      color: var(--success-color);
      font-size: 0.9rem;
      margin-bottom: 1rem;
      text-align: center;
      display: none;
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

      .page-header h1 {
        font-size: 2rem;
      }

      .calendar-container {
        padding: 1.5rem;
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
    <!-- Page Header -->
    <section class="page-header">
      <div class="container">
        <div class="row">
          <div class="col-lg-8">
            <h1>Restaurant Availability</h1>
            <p>Set your availability to let customers know when you're open.</p>
          </div>
        </div>
      </div>
    </section>

    <!-- Calendar Section -->
    <section class="section">
      <div class="container">
        <div class="calendar-container fade-in">
          <h2 class="calendar-title">Availability Calendar</h2>
          <div class="error-message" id="errorMessage"></div>
          <div class="success-message" id="successMessage"></div>
          <div id="calendar"></div>
          <div class="text-center mt-4">
            <a href="index.php" class="btn">Finish Setup</a>
          </div>
        </div>
      </div>
    </section>
  </div>

  <!-- Add/Edit Availability Modal -->
  <div class="modal fade" id="availabilityModal" tabindex="-1" aria-labelledby="availabilityModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="availabilityModalLabel">Add Availability</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form id="availabilityForm">
          <div class="modal-body">
            <input type="hidden" id="eventId">
            <div class="form-group mb-3">
              <label for="eventDate">Date</label>
              <input type="date" class="form-control" id="eventDate" required>
            </div>
            <div class="form-group mb-3">
              <label for="startTime">Start Time</label>
              <input type="time" class="form-control" id="startTime" required>
            </div>
            <div class="form-group mb-3">
              <label for="endTime">End Time</label>
              <input type="time" class="form-control" id="endTime" required>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-primary" id="saveEvent">Save</button>
            <button type="button" class="btn btn-danger" id="deleteEvent" style="display: none;">Delete</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <!-- Bootstrap and FullCalendar JS -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.3.0/main.min.js"></script>
  <script>
    document.addEventListener('DOMContentLoaded', function() {
      // Initialize FullCalendar
      var calendarEl = document.getElementById('calendar');
      var calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        headerToolbar: {
          left: 'prev,next today',
          center: 'title',
          right: 'dayGridMonth,timeGridWeek,timeGridDay'
        },
        events: <?php echo $events_json; ?>,
        selectable: true,
        select: function(info) {
          // Open modal to add new event
          document.getElementById('availabilityModalLabel').textContent = 'Add Availability';
          document.getElementById('eventId').value = '';
          document.getElementById('eventDate').value = info.startStr.split('T')[0];
          document.getElementById('startTime').value = '10:00';
          document.getElementById('endTime').value = '22:00';
          document.getElementById('deleteEvent').style.display = 'none';
          new bootstrap.Modal(document.getElementById('availabilityModal')).show();
        },
        eventClick: function(info) {
          // Open modal to edit/delete event
          document.getElementById('availabilityModalLabel').textContent = 'Edit Availability';
          document.getElementById('eventId').value = info.event.id;
          document.getElementById('eventDate').value = info.event.startStr.split('T')[0];
          document.getElementById('startTime').value = info.event.start.toTimeString().slice(0, 5);
          document.getElementById('endTime').value = info.event.end.toTimeString().slice(0, 5);
          document.getElementById('deleteEvent').style.display = 'inline-block';
          new bootstrap.Modal(document.getElementById('availabilityModal')).show();
        }
      });
      calendar.render();

      // Toggle sidebar on mobile
      const sidebar = document.getElementById('sidebar');
      const sidebarToggle = document.getElementById('sidebarToggle');
      sidebarToggle.addEventListener('click', function() {
        sidebar.classList.toggle('active');
      });

      // Show sidebar toggle on mobile
      if (window.innerWidth <= 992) {
        sidebarToggle.classList.remove('d-none');
      }

      // Fade-in animation
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

      // Handle form submission
      document.getElementById('availabilityForm').addEventListener('submit', function(e) {
        e.preventDefault();
        const id = document.getElementById('eventId').value;
        const date = document.getElementById('eventDate').value;
        const startTime = document.getElementById('startTime').value;
        const endTime = document.getElementById('endTime').value;
        const action = id ? 'edit' : 'add';

        fetch('', {
            method: 'POST',
            headers: {
              'Content-Type': 'application/x-www-form-urlencoded'
            },
            body: new URLSearchParams({
              action: action,
              id: id,
              date: date,
              start_time: startTime,
              end_time: endTime
            })
          })
          .then(response => response.json())
          .then(data => {
            const errorMessage = document.getElementById('errorMessage');
            const successMessage = document.getElementById('successMessage');
            errorMessage.style.display = 'none';
            successMessage.style.display = 'none';

            if (data.success) {
              successMessage.textContent = data.message;
              successMessage.style.display = 'block';
              bootstrap.Modal.getInstance(document.getElementById('availabilityModal')).hide();
              if (action === 'add') {
                calendar.addEvent({
                  id: data.id,
                  title: startTime + ' - ' + endTime,
                  start: date + 'T' + startTime,
                  end: date + 'T' + endTime
                });
              } else {
                const event = calendar.getEventById(id);
                event.setProp('title', startTime + ' - ' + endTime);
                event.setStart(date + 'T' + startTime);
                event.setEnd(date + 'T' + endTime);
              }
            } else {
              errorMessage.textContent = data.message;
              errorMessage.style.display = 'block';
            }
          })
          .catch(error => {
            document.getElementById('errorMessage').textContent = 'An error occurred.';
            document.getElementById('errorMessage').style.display = 'block';
          });
      });

      // Handle delete
      document.getElementById('deleteEvent').addEventListener('click', function() {
        if (confirm('Are you sure you want to delete this availability?')) {
          const id = document.getElementById('eventId').value;
          fetch('', {
              method: 'POST',
              headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
              },
              body: new URLSearchParams({
                action: 'delete',
                id: id
              })
            })
            .then(response => response.json())
            .then(data => {
              const errorMessage = document.getElementById('errorMessage');
              const successMessage = document.getElementById('successMessage');
              errorMessage.style.display = 'none';
              successMessage.style.display = 'none';

              if (data.success) {
                successMessage.textContent = data.message;
                successMessage.style.display = 'block';
                bootstrap.Modal.getInstance(document.getElementById('availabilityModal')).hide();
                calendar.getEventById(id).remove();
              } else {
                errorMessage.textContent = data.message;
                errorMessage.style.display = 'block';
              }
            })
            .catch(error => {
              document.getElementById('errorMessage').textContent = 'An error occurred.';
              document.getElementById('errorMessage').style.display = 'block';
            });
        }
      });
    });
  </script>
</body>

</html>