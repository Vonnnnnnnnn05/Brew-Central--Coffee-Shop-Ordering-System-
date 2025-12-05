<?php
session_start();
include('../conn.php');

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'staff') {
    header("Location: ../login.php");
    exit;
}

// Get staff info
$user_id = $_SESSION['user_id'];
$query = mysqli_query($conn, "SELECT * FROM users WHERE user_id='$user_id'");
$user = mysqli_fetch_assoc($query);

// Get statistics
$pending_orders = mysqli_query($conn, "SELECT COUNT(*) as count FROM orders WHERE order_status IN ('Pending', 'Preparing')");
$pending_count = mysqli_fetch_assoc($pending_orders)['count'];

$total_products = mysqli_query($conn, "SELECT COUNT(*) as count FROM products");
$products_count = mysqli_fetch_assoc($total_products)['count'];

$low_stock = mysqli_query($conn, "SELECT COUNT(*) as count FROM products WHERE stock < 10");
$low_stock_count = mysqli_fetch_assoc($low_stock)['count'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Staff Dashboard</title>
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
    .text-warning {
      color: #d4a574 !important;
    }
    .text-primary {
      color: #6f4e37 !important;
    }
    .text-danger {
      color: #a0522d !important;
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
  </style>
</head>
<body>
  <div class="container-fluid">
    <div class="row">
      <!-- Sidebar -->
      <div class="col-md-2 sidebar">
        <h4 class="text-center mb-4">☕ Brew Central</h4>
        <p class="text-center text-light small">Welcome, <?php echo htmlspecialchars($user['fullname']); ?>!</p>
        <hr class="text-light">
        <a href="dashboard.php" class="active">Dashboard</a>
        <a href="manage_orders.php">Manage Orders</a>
        <a href="manage_products.php">Manage Products</a>
        <a href="view_products.php">View Products</a>
        <a href="logout.php" class="text-danger mt-3 d-block">Logout</a>
      </div>

      <!-- Main Content -->
      <div class="col-md-10 p-4">
        <h2 class="mb-4">Staff Dashboard</h2>

        <div class="row g-3 mb-4">
          <div class="col-md-4">
            <div class="card p-3 text-center">
              <h5>Pending Orders</h5>
              <h2 class="text-warning"><?php echo $pending_count; ?></h2>
            </div>
          </div>
          <div class="col-md-4">
            <div class="card p-3 text-center">
              <h5>Total Products</h5>
              <h2 class="text-primary"><?php echo $products_count; ?></h2>
            </div>
          </div>
          <div class="col-md-4">
            <div class="card p-3 text-center">
              <h5>Low Stock Items</h5>
              <h2 class="text-danger"><?php echo $low_stock_count; ?></h2>
            </div>
          </div>
        </div>

        <div class="row g-4">
          <div class="col-md-4">
            <div class="card p-3">
              <h5 class="text-center text-uppercase" style="color: #6f4e37;">Manage Orders</h5>
              <p class="text-muted text-center">View and update order statuses</p>
              <a href="manage_orders.php" class="btn btn-outline-dark d-block">Go to Orders</a>
            </div>
          </div>

          <div class="col-md-4">
            <div class="card p-3">
              <h5 class="text-center text-uppercase" style="color: #6f4e37;">Manage Products</h5>
              <p class="text-muted text-center">Add, edit, and manage product inventory</p>
              <a href="manage_products.php" class="btn btn-outline-dark d-block">Manage Products</a>
            </div>
          </div>

          <div class="col-md-4">
            <div class="card p-3">
              <h5 class="text-center text-uppercase" style="color: #6f4e37;">View Products</h5>
              <p class="text-muted text-center">Check product availability and stock</p>
              <a href="view_products.php" class="btn btn-outline-dark d-block">View Products</a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</body>
</html>
