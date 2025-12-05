<?php
session_start();
include('../conn.php');

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'customer') {
    header("Location: ../login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// Get user info
$user_query = mysqli_query($conn, "SELECT * FROM users WHERE user_id='$user_id'");
$user = mysqli_fetch_assoc($user_query);

// Handle feedback submission
if (isset($_POST['send_feedback'])) {
    $message = mysqli_real_escape_string($conn, $_POST['message']);
    
    $query = "INSERT INTO feedback (user_id, message) VALUES ('$user_id', '$message')";
    if (mysqli_query($conn, $query)) {
        $success = "Feedback sent successfully!";
    } else {
        $error = "Error sending feedback!";
    }
}

// Get user's feedback
$feedback_query = "SELECT * FROM feedback WHERE user_id='$user_id' ORDER BY date_sent DESC";
$feedbacks = mysqli_query($conn, $feedback_query);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Feedback</title>
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
    .btn-primary {
      background-color: #6f4e37;
      border-color: #6f4e37;
    }
    .btn-primary:hover {
      background-color: #4b2e13;
      border-color: #4b2e13;
    }
    .alert-success {
      background-color: #f5e6d3;
      border-color: #d4a574;
      color: #4b2e13;
    }
    .alert-info {
      background-color: #f5e6d3;
      border-color: #c9a66b;
      color: #4b2e13;
    }
  </style>
</head>
<body>
  <div class="container-fluid">
    <div class="row">
      <!-- Sidebar -->
      <div class="col-md-2 sidebar">
        <h4 class="text-center mb-4">☕ Coffee Haven</h4>
        <hr class="text-light">
        <a href="dashboard.php">Dashboard</a>
        <a href="../products.php">Browse Products</a>
        <a href="cart.php">Shopping Cart</a>
        <a href="orders.php">My Orders</a>
        <a href="feedback.php" class="active">Send Feedback</a>
        <a href="logout.php" class="text-danger mt-3 d-block">Logout</a>
      </div>

      <!-- Main Content -->
      <div class="col-md-10 p-4">
    <h2>Send Feedback</h2>
    
    <?php if (isset($success)) echo "<div class='alert alert-success'>$success</div>"; ?>
    <?php if (isset($error)) echo "<div class='alert alert-danger'>$error</div>"; ?>
    
    <div class="card mb-4">
      <div class="card-body">
        <form method="POST">
          <div class="mb-3">
            <label>Your Message</label>
            <textarea name="message" class="form-control" rows="5" required placeholder="Share your thoughts, suggestions, or concerns..."></textarea>
          </div>
          <button type="submit" name="send_feedback" class="btn btn-primary">Send Feedback</button>
        </form>
      </div>
    </div>
    
    <h4>Your Previous Feedback</h4>
    <?php if (mysqli_num_rows($feedbacks) > 0): ?>
    <?php while ($feedback = mysqli_fetch_assoc($feedbacks)): ?>
    <div class="card mb-3">
      <div class="card-body">
        <p><strong>Sent on:</strong> <?php echo date('M d, Y h:i A', strtotime($feedback['date_sent'])); ?></p>
        <p><strong>Your Message:</strong><br><?php echo nl2br(htmlspecialchars($feedback['message'])); ?></p>
        <?php if ($feedback['reply']): ?>
        <div class="alert alert-info mt-2">
          <strong>Admin Reply:</strong><br>
          <?php echo nl2br(htmlspecialchars($feedback['reply'])); ?>
        </div>
        <?php else: ?>
        <p class="text-muted"><em>Waiting for admin response...</em></p>
        <?php endif; ?>
      </div>
    </div>
    <?php endwhile; ?>
    <?php else: ?>
    <div class="alert alert-info">You haven't sent any feedback yet.</div>
    <?php endif; ?>
      </div>
    </div>
  </div>
</body>
</html>
