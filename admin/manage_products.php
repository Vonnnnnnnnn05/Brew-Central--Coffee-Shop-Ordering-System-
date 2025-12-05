<?php
session_start();
include('../conn.php');

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: ../login.php");
    exit;
}

// Delete product
if (isset($_GET['delete'])) {
    $product_id = $_GET['delete'];
    $query = "DELETE FROM products WHERE product_id='$product_id'";
    mysqli_query($conn, $query);
    header("Location: manage_products.php");
    exit;
}

// Get all products with category names
$products = mysqli_query($conn, "SELECT p.*, c.category_name FROM products p LEFT JOIN categories c ON p.category_id = c.category_id ORDER BY p.created_at DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Manage Products</title>
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
    .btn-info {
      background-color: #c9a66b;
      border-color: #c9a66b;
      color: #fff;
    }
    .btn-info:hover {
      background-color: #8b6f47;
      border-color: #8b6f47;
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
    .btn-danger {
      background-color: #a0522d;
      border-color: #a0522d;
    }
    .btn-danger:hover {
      background-color: #8b4513;
      border-color: #8b4513;
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
    .btn-info:active, .btn-info:focus {
      background-color: #8b6f47 !important;
      border-color: #8b6f47 !important;
      box-shadow: 0 0 0 0.25rem rgba(201, 166, 107, 0.25) !important;
    }
    .btn-warning:active, .btn-warning:focus {
      background-color: #c9a66b !important;
      border-color: #c9a66b !important;
      box-shadow: 0 0 0 0.25rem rgba(212, 165, 116, 0.25) !important;
    }
    .btn-danger:active, .btn-danger:focus {
      background-color: #8b4513 !important;
      border-color: #8b4513 !important;
      box-shadow: 0 0 0 0.25rem rgba(160, 82, 45, 0.25) !important;
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
        <a href="manage_products.php" class="active">Manage Products</a>
        <a href="manage_categories.php">Categories</a>
        <a href="manage_orders.php">Orders</a>
        <a href="inventory_logs.php">Inventory Logs</a>
        <a href="manage_feedback.php">Feedback</a>
        <a href="manage_users.php">Manage Users</a>
        <a href="logout.php" class="text-danger mt-3 d-block">Logout</a>
      </div>

      <!-- Main Content -->
      <div class="col-md-10 p-4">
    <h2>Manage Products</h2>
    <div class="mb-3">
      <a href="add_product.php" class="btn btn-primary">Add New Product</a>
      <a href="manage_categories.php" class="btn btn-info">Manage Categories</a>
    </div>
    
    <div class="card">
      <div class="card-body">
        <table class="table table-striped">
          <thead>
            <tr>
              <th>ID</th>
              <th>Image</th>
              <th>Name</th>
              <th>Category</th>
              <th>Price</th>
              <th>Stock</th>
              <th>Actions</th>
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
              <td><?php echo $product['stock']; ?></td>
              <td>
                <a href="edit_product.php?id=<?php echo $product['product_id']; ?>" class="btn btn-sm btn-warning">Edit</a>
                <a href="?delete=<?php echo $product['product_id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Delete this product?')">Delete</a>
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
