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

// Get all products
$products_query = "SELECT p.*, c.category_name FROM products p 
                   LEFT JOIN categories c ON p.category_id = c.category_id 
                   ORDER BY p.product_name ASC";
$products = mysqli_query($conn, $products_query);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>View Products</title>
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
    .badge.bg-danger {
      background-color: #a0522d !important;
    }
    .badge.bg-warning {
      background-color: #d4a574 !important;
      color: #4b2e13 !important;
    }
    .badge.bg-success {
      background-color: #8b6f47 !important;
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
        <a href="manage_orders.php">Manage Orders</a>
        <a href="manage_products.php">Manage Products</a>
        <a href="view_products.php" class="active">View Products</a>
        <a href="logout.php" class="text-danger mt-3 d-block">Logout</a>
      </div>

      <!-- Main Content -->
      <div class="col-md-10 p-4">
    <h2>Products List</h2>
    
    <div class="card">
      <div class="card-body">
        <table class="table table-striped">
          <thead>
            <tr>
              <th>ID</th>
              <th>Image</th>
              <th>Product Name</th>
              <th>Category</th>
              <th>Price</th>
              <th>Stock</th>
              <th>Status</th>
            </tr>
          </thead>
          <tbody>
            <?php while ($product = mysqli_fetch_assoc($products)): ?>
            <tr>
              <td><?php echo $product['product_id']; ?></td>
              <td>
                <?php if ($product['image']): ?>
                  <img src="../<?php echo $product['image']; ?>" width="50" height="50" style="object-fit: cover;">
                <?php else: ?>
                  <span class="text-muted">No image</span>
                <?php endif; ?>
              </td>
              <td><?php echo htmlspecialchars($product['product_name']); ?></td>
              <td><?php echo htmlspecialchars($product['category_name'] ?? 'N/A'); ?></td>
              <td>₱<?php echo number_format($product['price'], 2); ?></td>
              <td>
                <span class="badge bg-<?php echo $product['stock'] < 10 ? 'danger' : 'success'; ?>">
                  <?php echo $product['stock']; ?>
                </span>
              </td>
              <td>
                <?php if ($product['stock'] == 0): ?>
                  <span class="badge bg-danger">Out of Stock</span>
                <?php elseif ($product['stock'] < 10): ?>
                  <span class="badge bg-warning">Low Stock</span>
                <?php else: ?>
                  <span class="badge bg-success">In Stock</span>
                <?php endif; ?>
              </td>
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
