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

// Get all orders
$orders_query = "SELECT * FROM orders WHERE user_id='$user_id' ORDER BY order_date DESC";
$orders = mysqli_query($conn, $orders_query);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>My Orders</title>
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
        <h4 class="text-center mb-4">☕ Coffee Haven</h4>
        <hr class="text-light">
        <a href="dashboard.php">Dashboard</a>
        <a href="../products.php">Browse Products</a>
        <a href="cart.php">Shopping Cart</a>
        <a href="orders.php" class="active">My Orders</a>
        <a href="feedback.php">Send Feedback</a>
        <a href="logout.php" class="text-danger mt-3 d-block">Logout</a>
      </div>

      <!-- Main Content -->
      <div class="col-md-10 p-4">
    <h2>My Orders</h2>
    
    <?php if (isset($_GET['success'])): ?>
    <div class="alert alert-success">Order placed successfully!</div>
    <?php endif; ?>
    
    <?php if (mysqli_num_rows($orders) > 0): ?>
    <?php while ($order = mysqli_fetch_assoc($orders)): ?>
    <div class="card mb-3">
      <div class="card-header d-flex justify-content-between">
        <span><strong>Order #<?php echo $order['order_id']; ?></strong></span>
        <span class="badge bg-<?php 
          echo $order['order_status'] == 'Completed' ? 'success' : 
               ($order['order_status'] == 'Cancelled' ? 'danger' : 'warning'); 
        ?>"><?php echo $order['order_status']; ?></span>
      </div>
      <div class="card-body">
        <p><strong>Order Date:</strong> <?php echo date('M d, Y h:i A', strtotime($order['order_date'])); ?></p>
        <p><strong>Total Amount:</strong> ₱<?php echo number_format($order['total_amount'], 2); ?></p>
        <p><strong>Payment Method:</strong> <?php echo $order['payment_method']; ?></p>
        <p><strong>Delivery Address:</strong> <?php echo htmlspecialchars($order['address']); ?></p>
        
        <h6>Order Items:</h6>
        <?php
        $order_id = $order['order_id'];
        $items_query = "SELECT oi.*, p.product_name FROM order_items oi 
                        JOIN products p ON oi.product_id = p.product_id 
                        WHERE oi.order_id='$order_id'";
        $items = mysqli_query($conn, $items_query);
        ?>
        <table class="table table-sm">
          <thead>
            <tr>
              <th>Product</th>
              <th>Price</th>
              <th>Quantity</th>
              <th>Subtotal</th>
            </tr>
          </thead>
          <tbody>
            <?php while ($item = mysqli_fetch_assoc($items)): ?>
            <tr>
              <td><?php echo htmlspecialchars($item['product_name']); ?></td>
              <td>₱<?php echo number_format($item['price'], 2); ?></td>
              <td><?php echo $item['quantity']; ?></td>
              <td>₱<?php echo number_format($item['price'] * $item['quantity'], 2); ?></td>
            </tr>
            <?php endwhile; ?>
          </tbody>
        </table>
      </div>
    </div>
    <?php endwhile; ?>
    <?php else: ?>
    <div class="alert alert-info">No orders yet. <a href="../products.php">Start shopping</a></div>
    <?php endif; ?>
      </div>
    </div>
  </div>
</body>
</html>
