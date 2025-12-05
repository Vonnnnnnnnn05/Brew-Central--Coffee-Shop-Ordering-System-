<?php
session_start();
include('../conn.php');

// Check if user is logged in
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'customer') {
  header("Location: login.php");
  exit;
}

// Get user info
$user_id = $_SESSION['user_id'];
$query = mysqli_query($conn, "SELECT * FROM users WHERE user_id='$user_id'");
$user = mysqli_fetch_assoc($query);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Coffee Haven | Dashboard</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    :root {
      --primary-color: #6f4e37;
      --primary-dark: #4b2e13;
      --secondary-color: #c9a66b;
      --accent-color: #d4a574;
      --light-bg: #faf7f2;
      --dark-bg: #2c1b0e;
    }

    body {
      background-color: var(--light-bg);
      font-family: "Poppins", sans-serif;
    }

    .sidebar {
      background-color: var(--primary-color);
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
      background-color: var(--primary-dark);
    }

    .card {
      border: none;
      border-radius: 15px;
      box-shadow: 0 4px 8px rgba(0,0,0,0.1);
      background-color: #fff;
    }
    .btn-outline-dark {
      border-color: #6f4e37;
      color: #6f4e37;
    }
    .btn-outline-dark:hover {
      background-color: #6f4e37;
      border-color: #6f4e37;
      color: white;
    }
    h5 {
      color: #6f4e37 !important;
    }
  </style>
</head>
<body>

  <div class="container-fluid">
    <div class="row">
      <!-- Sidebar -->
      <div class="col-md-2 sidebar">
        <h4 class="text-center mb-4">☕ Coffee Haven</h4>
        <p class="text-center text-light small">Welcome, <?php echo htmlspecialchars($user['fullname']); ?>!</p>
        <hr class="text-light">
        <a href="dashboard.php" class="active">Dashboard</a>
        <a href="../products.php">Browse Products</a>
        <a href="cart.php">Shopping Cart</a>
        <a href="orders.php">My Orders</a>
        <a href="feedback.php">Send Feedback</a>
        <a href="logout.php" class="text-danger mt-3 d-block">Logout</a>
      </div>

      <!-- Main Content -->
      <div class="col-md-10 p-4">
        <h2 class="mb-4">Customer Dashboard</h2>

        <div class="row g-4">
          <!-- Featured Products -->
          <div class="col-md-6 col-lg-4">
            <div class="card p-3">
              <h5 class="text-center text-uppercase" style="color: var(--primary-color);">Featured Products</h5>
              <p class="text-muted text-center">Explore our best-selling coffee beans and drinks!</p>
              <a href="../products.php" class="btn btn-sm btn-outline-dark d-block">View Products</a>
            </div>
          </div>

          <!-- Shopping Cart -->
          <div class="col-md-6 col-lg-4">
            <div class="card p-3">
              <h5 class="text-center text-uppercase" style="color: var(--primary-color);">Shopping Cart</h5>
              <p class="text-muted text-center">View and manage your cart items.</p>
              <a href="cart.php" class="btn btn-sm btn-outline-dark d-block">View Cart</a>
            </div>
          </div>

          <!-- Order History -->
          <div class="col-md-6 col-lg-4">
            <div class="card p-3">
              <h5 class="text-center text-uppercase" style="color: var(--primary-color);">Your Orders</h5>
              <p class="text-muted text-center">Track your previous purchases and order details.</p>
              <a href="orders.php" class="btn btn-sm btn-outline-dark d-block">View Order History</a>
            </div>
          </div>

          <!-- Feedback -->
          <div class="col-md-6 col-lg-4">
            <div class="card p-3">
              <h5 class="text-center text-uppercase" style="color: var(--primary-color);">Send Feedback</h5>
              <p class="text-muted text-center">Share your thoughts and suggestions with us.</p>
              <a href="feedback.php" class="btn btn-sm btn-outline-dark d-block">Send Feedback</a>
            </div>
          </div>

          <!-- Profile Info -->
          <div class="col-md-6 col-lg-4">
            <div class="card p-3">
              <h5 class="text-center text-uppercase" style="color: var(--primary-color);">Profile Info</h5>
              <p class="text-muted text-center">Email: <?php echo htmlspecialchars($user['email']); ?></p>
              <p class="text-muted text-center">Contact: <?php echo htmlspecialchars($user['contact_number']); ?></p>
              <p class="text-muted text-center">Address: <?php echo htmlspecialchars($user['address']); ?></p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

</body>
</html>
