<?php
session_start();
include('../conn.php');

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: ../login.php");
    exit;
}

// Get inventory logs with product and user info
$logs_query = "SELECT il.*, p.product_name, u.fullname 
               FROM inventory_logs il 
               JOIN products p ON il.product_id = p.product_id 
               LEFT JOIN users u ON il.changed_by = u.user_id 
               ORDER BY il.date_changed DESC";
$logs = mysqli_query($conn, $logs_query);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Inventory Logs</title>
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
        <h4 class="text-center mb-4">☕ Coffee Central</h4>
        <a href="dashboard.php">Dashboard</a>
        <a href="manage_products.php">Manage Products</a>
        <a href="manage_categories.php">Categories</a>
        <a href="manage_orders.php">Orders</a>
        <a href="inventory_logs.php" class="active">Inventory Logs</a>
        <a href="manage_feedback.php">Feedback</a>
        <a href="manage_users.php">Manage Users</a>
        <a href="logout.php" class="text-danger mt-3 d-block">Logout</a>
      </div>

      <!-- Main Content -->
      <div class="col-md-10 p-4">
    <h2>Inventory Logs</h2>
    
    <div class="card">
      <div class="card-body">
        <table class="table table-striped">
          <thead>
            <tr>
              <th>Date</th>
              <th>Product</th>
              <th>Change Type</th>
              <th>Quantity Changed</th>
              <th>Changed By</th>
              <th>Remarks</th>
            </tr>
          </thead>
          <tbody>
            <?php while ($log = mysqli_fetch_assoc($logs)): ?>
            <tr>
              <td><?php echo date('M d, Y h:i A', strtotime($log['date_changed'])); ?></td>
              <td><?php echo htmlspecialchars($log['product_name']); ?></td>
              <td>
                <span class="badge bg-<?php 
                  echo $log['change_type'] == 'add' ? 'success' : 
                       ($log['change_type'] == 'remove' ? 'danger' : 'warning'); 
                ?>"><?php echo ucfirst($log['change_type']); ?></span>
              </td>
              <td><?php echo $log['quantity_changed']; ?></td>
              <td><?php echo htmlspecialchars($log['fullname'] ?? 'N/A'); ?></td>
              <td><?php echo htmlspecialchars($log['remarks'] ?? ''); ?></td>
            </tr>
            <?php endwhile; ?>
          </tbody>
        </table>
      </div>
    </div>
      </div>
    </div>
  </div>
</body>
</html>
