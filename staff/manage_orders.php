<?php
session_start();
include('../conn.php');

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'staff') {
    header("Location: ../login.php");
    exit;
}

// Get staff info
$user_id = $_SESSION['user_id'];
$user_query = mysqli_query($conn, "SELECT * FROM users WHERE user_id='$user_id'");
$user = mysqli_fetch_assoc($user_query);

// Get all orders
$orders_query = "SELECT o.*, u.fullname, u.email, u.contact_number 
                 FROM orders o 
                 JOIN users u ON o.user_id = u.user_id 
                 ORDER BY o.order_date DESC";
$orders = mysqli_query($conn, $orders_query);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Manage Orders</title>
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
    .badge.bg-success {
      background-color: #8b6f47 !important;
    }
    .badge.bg-warning {
      background-color: #d4a574 !important;
      color: #4b2e13 !important;
    }
    .badge.bg-danger {
      background-color: #a0522d !important;
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
        <a href="dashboard.php">Dashboard</a>
        <a href="manage_orders.php" class="active">Manage Orders</a>
        <a href="manage_products.php">Manage Products</a>
        <a href="view_products.php">View Products</a>
        <a href="logout.php" class="text-danger mt-3 d-block">Logout</a>
      </div>

      <!-- Main Content -->
      <div class="col-md-10 p-4">
    <h2>Manage Orders</h2>
    
    <div class="card">
      <div class="card-body">
        <?php while ($order = mysqli_fetch_assoc($orders)): ?>
        <div class="border p-3 mb-3">
          <div class="row">
            <div class="col-md-8">
              <h5>Order #<?php echo $order['order_id']; ?></h5>
              <p><strong>Customer:</strong> <?php echo htmlspecialchars($order['fullname']); ?></p>
              <p><strong>Contact:</strong> <?php echo htmlspecialchars($order['contact_number']); ?></p>
              <p><strong>Address:</strong> <?php echo htmlspecialchars($order['address']); ?></p>
              <p><strong>Order Date:</strong> <?php echo date('M d, Y h:i A', strtotime($order['order_date'])); ?></p>
              <p><strong>Total:</strong> ₱<?php echo number_format($order['total_amount'], 2); ?></p>
              
              <h6>Items:</h6>
              <?php
              $order_id = $order['order_id'];
              $items_query = "SELECT oi.*, p.product_name FROM order_items oi 
                              JOIN products p ON oi.product_id = p.product_id 
                              WHERE oi.order_id='$order_id'";
              $items = mysqli_query($conn, $items_query);
              ?>
              <ul>
                <?php while ($item = mysqli_fetch_assoc($items)): ?>
                <li><?php echo htmlspecialchars($item['product_name']); ?> - Qty: <?php echo $item['quantity']; ?></li>
                <?php endwhile; ?>
              </ul>
            </div>
            <div class="col-md-4">
              <form method="POST" action="update_order_status.php">
                <input type="hidden" name="order_id" value="<?php echo $order['order_id']; ?>">
                <label>Status:</label>
                <select name="order_status" class="form-control mb-2">
                  <option value="Pending" <?php echo $order['order_status'] == 'Pending' ? 'selected' : ''; ?>>Pending</option>
                  <option value="Preparing" <?php echo $order['order_status'] == 'Preparing' ? 'selected' : ''; ?>>Preparing</option>
                  <option value="Ready" <?php echo $order['order_status'] == 'Ready' ? 'selected' : ''; ?>>Ready</option>
                  <option value="Completed" <?php echo $order['order_status'] == 'Completed' ? 'selected' : ''; ?>>Completed</option>
                </select>
                <button type="submit" class="btn btn-primary btn-sm w-100">Update Status</button>
              </form>
              <span class="badge bg-<?php 
                echo $order['order_status'] == 'Completed' ? 'success' : 
                     ($order['order_status'] == 'Cancelled' ? 'danger' : 'warning'); 
              ?> mt-2 d-block"><?php echo $order['order_status']; ?></span>
            </div>
          </div>
        </div>
        <?php endwhile; ?>
      </div>
    </div>
      </div>
    </div>
  </div>
</body>
</html>
