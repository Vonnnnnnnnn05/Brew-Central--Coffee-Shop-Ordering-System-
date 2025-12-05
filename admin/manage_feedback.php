<?php
session_start();
include('../conn.php');

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: ../login.php");
    exit;
}

// Handle reply submission
if (isset($_POST['send_reply'])) {
    $feedback_id = $_POST['feedback_id'];
    $reply = mysqli_real_escape_string($conn, $_POST['reply']);
    
    $query = "UPDATE feedback SET reply='$reply' WHERE feedback_id='$feedback_id'";
    mysqli_query($conn, $query);
    header("Location: manage_feedback.php");
    exit;
}

// Get all feedback with user info
$feedback_query = "SELECT f.*, u.fullname, u.email 
                   FROM feedback f 
                   JOIN users u ON f.user_id = u.user_id 
                   ORDER BY f.date_sent DESC";
$feedbacks = mysqli_query($conn, $feedback_query);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Manage Feedback</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      background-color: #faf7f2;
      font-family: Arial, sans-serif;
    }
    .sidebar {
      background-color: #6f4e37;
      min-height: 100vh;
      color: white;
      padding-top: 20px;
    }
    .sidebar a {
      color: #f1e4d3;
      text-decoration: none;
      display: block;
      padding: 10px 20px;
      border-radius: 6px;
    }
    .sidebar a:hover, .sidebar a.active {
      background-color: #4b2e13;
    }
    .card {
      border: none;
      border-radius: 12px;
      box-shadow: 0 2px 6px rgba(0,0,0,0.1);
    }
    .btn-primary {
      background-color: #6f4e37;
      border-color: #6f4e37;
    }
    .btn-primary:hover {
      background-color: #4b2e13;
      border-color: #4b2e13;
    }
    .btn-warning {
      background-color: #d4a574;
      border-color: #d4a574;
      color: #4b2e13;
    }
    .btn-warning:hover {
      background-color: #c9a66b;
      border-color: #c9a66b;
    }
    .btn-success {
      background-color: #8b6f47;
      border-color: #8b6f47;
    }
    .btn-success:hover {
      background-color: #6f4e37;
      border-color: #6f4e37;
    }
    .alert-success {
      background-color: #f5e6d3;
      border-color: #d4a574;
      color: #4b2e13;
    }
    .form-control:focus, .form-select:focus {
      border-color: #6f4e37;
      box-shadow: 0 0 0 0.25rem rgba(111, 78, 55, 0.25);
    }
    .btn-primary:active, .btn-primary:focus {
      background-color: #4b2e13 !important;
      border-color: #4b2e13 !important;
      box-shadow: 0 0 0 0.25rem rgba(111, 78, 55, 0.25) !important;
    }
    .btn-warning:active, .btn-warning:focus {
      background-color: #c9a66b !important;
      border-color: #c9a66b !important;
      box-shadow: 0 0 0 0.25rem rgba(212, 165, 116, 0.25) !important;
    }
    .btn-success:active, .btn-success:focus {
      background-color: #6f4e37 !important;
      border-color: #6f4e37 !important;
      box-shadow: 0 0 0 0.25rem rgba(139, 111, 71, 0.25) !important;
    }
  </style>
</head>
<body>
  <div class="container-fluid">
    <div class="row">
      <!-- Sidebar -->
      <div class="col-md-2 sidebar">
        <h4 class="text-center mb-4">☕ Coffee Central</h4>
        <a href="dashboard.php">Dashboard</a>
        <a href="manage_products.php">Manage Products</a>
        <a href="manage_categories.php">Categories</a>
        <a href="manage_orders.php">Orders</a>
        <a href="inventory_logs.php">Inventory Logs</a>
        <a href="manage_feedback.php" class="active">Feedback</a>
        <a href="manage_users.php">Manage Users</a>
        <a href="logout.php" class="text-danger mt-3 d-block">Logout</a>
      </div>

      <!-- Main Content -->
      <div class="col-md-10 p-4">
    <h2>Manage Customer Feedback</h2>
    
    <?php if (mysqli_num_rows($feedbacks) > 0): ?>
    <?php while ($feedback = mysqli_fetch_assoc($feedbacks)): ?>
    <div class="card mb-3">
      <div class="card-header">
        <strong><?php echo htmlspecialchars($feedback['fullname']); ?></strong> (<?php echo htmlspecialchars($feedback['email']); ?>)
        <span class="float-end text-muted"><?php echo date('M d, Y h:i A', strtotime($feedback['date_sent'])); ?></span>
      </div>
      <div class="card-body">
        <p><strong>Message:</strong><br><?php echo nl2br(htmlspecialchars($feedback['message'])); ?></p>
        
        <?php if ($feedback['reply']): ?>
        <div class="alert alert-success">
          <strong>Your Reply:</strong><br>
          <?php echo nl2br(htmlspecialchars($feedback['reply'])); ?>
        </div>
        <button class="btn btn-sm btn-warning" data-bs-toggle="collapse" data-bs-target="#reply<?php echo $feedback['feedback_id']; ?>">Edit Reply</button>
        <?php else: ?>
        <button class="btn btn-sm btn-primary" data-bs-toggle="collapse" data-bs-target="#reply<?php echo $feedback['feedback_id']; ?>">Send Reply</button>
        <?php endif; ?>
        
        <div class="collapse mt-3" id="reply<?php echo $feedback['feedback_id']; ?>">
          <form method="POST">
            <input type="hidden" name="feedback_id" value="<?php echo $feedback['feedback_id']; ?>">
            <textarea name="reply" class="form-control mb-2" rows="3" required><?php echo htmlspecialchars($feedback['reply']); ?></textarea>
            <button type="submit" name="send_reply" class="btn btn-success btn-sm">Send Reply</button>
          </form>
        </div>
      </div>
    </div>
    <?php endwhile; ?>
    <?php else: ?>
    <div class="alert alert-info">No feedback yet.</div>
    <?php endif; ?>
      </div>
    </div>
  </div>
  
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
