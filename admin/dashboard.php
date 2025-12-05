<?php
session_start();
include('../conn.php');

// Example simple authentication check
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: ../login.php");
    exit;
}

// Get statistics
$total_products = mysqli_query($conn, "SELECT COUNT(*) as count FROM products");
$products_count = mysqli_fetch_assoc($total_products)['count'];

$total_orders = mysqli_query($conn, "SELECT COUNT(*) as count FROM orders");
$orders_count = mysqli_fetch_assoc($total_orders)['count'];

$total_customers = mysqli_query($conn, "SELECT COUNT(*) as count FROM users WHERE role='customer'");
$customers_count = mysqli_fetch_assoc($total_customers)['count'];

$total_staff = mysqli_query($conn, "SELECT COUNT(*) as count FROM users WHERE role='staff'");
$staff_count = mysqli_fetch_assoc($total_staff)['count'];

$total_admins = mysqli_query($conn, "SELECT COUNT(*) as count FROM users WHERE role='admin'");
$admins_count = mysqli_fetch_assoc($total_admins)['count'];

$total_users = mysqli_query($conn, "SELECT COUNT(*) as count FROM users");
$users_count = mysqli_fetch_assoc($total_users)['count'];

// Get recent orders
$recent_orders = mysqli_query($conn, "SELECT o.*, u.fullname FROM orders o 
                                      JOIN users u ON o.user_id = u.user_id 
                                      ORDER BY o.order_date DESC LIMIT 5")
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Dashboard | Coffee Central</title>
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
    .sidebar a:hover {
      background-color: #4b2e13;
    }
    .navbar {
      background-color: #6f4e37;
    }
    .navbar-brand {
      color: white;
    }
    .card {
      border: none;
      border-radius: 12px;
      box-shadow: 0 2px 6px rgba(0,0,0,0.1);
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
        <a href="manage_feedback.php">Feedback</a>
        <a href="manage_users.php">Manage Users</a>
        <a href="logout.php" class="text-danger mt-3 d-block">Logout</a>
      </div>

      <!-- Main Content -->
      <div class="col-md-10 p-4">
        <nav class="navbar navbar-expand-lg navbar-dark mb-4">
          <div class="container-fluid">
            <a class="navbar-brand" href="#">Admin Dashboard</a>
          </div>
        </nav>

        <div class="row g-3">
          <div class="col-md-3">
            <div class="card p-3 text-center">
              <h5>Total Products</h5>
              <h2 class="text-primary"><?php echo $products_count; ?></h2>
            </div>
          </div>
          <div class="col-md-3">
            <div class="card p-3 text-center">
              <h5>Total Orders</h5>
              <h2 class="text-success"><?php echo $orders_count; ?></h2>
            </div>
          </div>
          <div class="col-md-3">
            <div class="card p-3 text-center">
              <h5>Total Users</h5>
              <h2 class="text-info"><?php echo $users_count; ?></h2>
            </div>
          </div>
          <div class="col-md-3">
            <div class="card p-3 text-center">
              <h5>Customers</h5>
              <h2 class="text-warning"><?php echo $customers_count; ?></h2>
            </div>
          </div>
        </div>

        <div class="row g-3 mt-1">
          <div class="col-md-6">
            <div class="card p-3 text-center">
              <h5>Staff Members</h5>
              <h2 class="text-secondary"><?php echo $staff_count; ?></h2>
            </div>
          </div>
          <div class="col-md-6">
            <div class="card p-3 text-center">
              <h5>Administrators</h5>
              <h2 class="text-danger"><?php echo $admins_count; ?></h2>
            </div>
          </div>
        </div>

        <div class="mt-5">
          <h4>Recent Orders</h4>
          <table class="table table-striped table-bordered">
            <thead class="table-light">
              <tr>
                <th>Order ID</th>
                <th>Customer</th>
                <th>Total</th>
                <th>Status</th>
              </tr>
            </thead>
            <tbody>
              <?php while ($order = mysqli_fetch_assoc($recent_orders)): ?>
              <tr>
                <td>#<?php echo $order['order_id']; ?></td>
                <td><?php echo htmlspecialchars($order['fullname']); ?></td>
                <td>₱<?php echo number_format($order['total_amount'], 2); ?></td>
                <td>
                  <span class="badge bg-<?php 
                    if ($order['order_status'] == 'Ready') {
                      echo 'success';
                    } elseif ($order['order_status'] == 'Cancelled') {
                      echo 'danger';
                    } else {
                      echo 'secondary';
                    }
                  ?>"><?php echo $order['order_status']; ?></span>
                </td>
              </tr>
              <?php endwhile; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</body>
</html>
