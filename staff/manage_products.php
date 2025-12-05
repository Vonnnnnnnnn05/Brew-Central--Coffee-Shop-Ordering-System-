<?php
session_start();
include('../conn.php');

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'staff') {
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

// Get staff info
$user_id = $_SESSION['user_id'];
$query = mysqli_query($conn, "SELECT * FROM users WHERE user_id='$user_id'");
$user = mysqli_fetch_assoc($query);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Manage Products - Staff</title>
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
        <a href="manage_products.php" class="active">Manage Products</a>
        <a href="view_products.php">View Products</a>
        <a href="logout.php" class="text-danger mt-3 d-block">Logout</a>
      </div>

      <!-- Main Content -->
      <div class="col-md-10 p-4">
        <h2 class="mb-4">Manage Products</h2>
        <a href="add_product.php" class="btn btn-primary mb-3">+ Add New Product</a>

        <div class="card p-3">
          <div class="table-responsive">
            <table class="table table-hover">
              <thead>
                <tr>
                  <th>ID</th>
                  <th>Image</th>
                  <th>Product Name</th>
                  <th>Category</th>
                  <th>Price</th>
                  <th>Stock</th>
                  <th>Actions</th>
                </tr>
              </thead>
              <tbody>
                <?php if (mysqli_num_rows($products) > 0): ?>
                  <?php while ($product = mysqli_fetch_assoc($products)): ?>
                    <tr>
                      <td><?php echo $product['product_id']; ?></td>
                      <td>
                        <?php if ($product['image']): ?>
                          <img src="../<?php echo $product['image']; ?>" width="50" height="50" style="object-fit: cover; border-radius: 5px;">
                        <?php else: ?>
                          <div style="width: 50px; height: 50px; background: #ccc; border-radius: 5px;"></div>
                        <?php endif; ?>
                      </td>
                      <td><?php echo htmlspecialchars($product['product_name']); ?></td>
                      <td><?php echo htmlspecialchars($product['category_name'] ?? 'N/A'); ?></td>
                      <td>₱<?php echo number_format($product['price'], 2); ?></td>
                      <td>
                        <span class="badge <?php echo $product['stock'] < 10 ? 'bg-danger' : 'bg-success'; ?>">
                          <?php echo $product['stock']; ?>
                        </span>
                      </td>
                      <td>
                        <a href="edit_product.php?id=<?php echo $product['product_id']; ?>" class="btn btn-sm btn-warning">Edit</a>
                        <a href="?delete=<?php echo $product['product_id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this product?')">Delete</a>
                      </td>
                    </tr>
                  <?php endwhile; ?>
                <?php else: ?>
                  <tr>
                    <td colspan="7" class="text-center">No products found.</td>
                  </tr>
                <?php endif; ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
</body>
</html>
