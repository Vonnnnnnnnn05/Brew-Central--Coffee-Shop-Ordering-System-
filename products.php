<?php
session_start();
include('conn.php');

// Get all products with categories
$category_filter = isset($_GET['category']) ? $_GET['category'] : '';
$search = isset($_GET['search']) ? $_GET['search'] : '';

$query = "SELECT p.*, c.category_name FROM products p 
          LEFT JOIN categories c ON p.category_id = c.category_id 
          WHERE p.stock > 0";

if ($category_filter) {
    $query .= " AND p.category_id = '$category_filter'";
}

if ($search) {
    $search_term = mysqli_real_escape_string($conn, $search);
    $query .= " AND p.product_name LIKE '%$search_term%'";
}

$query .= " ORDER BY p.created_at DESC";
$products = mysqli_query($conn, $query);

// Get all categories
$categories = mysqli_query($conn, "SELECT * FROM categories ORDER BY category_name ASC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Products | Brew Central</title>
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
    .btn-secondary {
      background-color: #8b6f47;
      border-color: #8b6f47;
    }
    .btn-secondary:hover {
      background-color: #6f4e37;
      border-color: #6f4e37;
    }
    .text-primary {
      color: #6f4e37 !important;
    }
  </style>
</head>
<body>
  <?php if (isset($_SESSION['user_id']) && $_SESSION['role'] == 'customer'): ?>
  <div class="container-fluid">
    <div class="row">
      <!-- Sidebar -->
      <div class="col-md-2 sidebar">
        <h4 class="text-center mb-4">☕ Coffee Haven</h4>
        <hr class="text-light">
        <a href="customer/dashboard.php">Dashboard</a>
        <a href="products.php" class="active">Browse Products</a>
        <a href="customer/cart.php">Shopping Cart</a>
        <a href="customer/orders.php">My Orders</a>
        <a href="customer/feedback.php">Send Feedback</a>
        <a href="customer/logout.php" class="text-danger mt-3 d-block">Logout</a>
      </div>

      <!-- Main Content -->
      <div class="col-md-10 p-4">
  <?php else: ?>
  <!-- Navbar for non-logged in users -->
  <nav class="navbar navbar-expand-lg navbar-dark" style="background-color: #6f4e37;">
    <div class="container">
      <a class="navbar-brand" href="index.php">☕ Brew Central</a>
      <div class="navbar-nav ms-auto">
        <a class="nav-link" href="login.php">Login</a>
        <a class="nav-link" href="register.php">Register</a>
      </div>
    </div>
  </nav>
  <div class="container mt-4">
  <?php endif; ?>
  
    <h2 class="mb-4">Our Products</h2>
    
    <!-- Filter and Search -->
    <div class="row mb-4">
      <div class="col-md-6">
        <form method="GET" class="d-flex">
          <input type="text" name="search" class="form-control me-2" placeholder="Search products..." value="<?php echo htmlspecialchars($search); ?>">
          <button type="submit" class="btn btn-primary">Search</button>
        </form>
      </div>
      <div class="col-md-6">
        <form method="GET">
          <select name="category" class="form-control" onchange="this.form.submit()">
            <option value="">All Categories</option>
            <?php while ($cat = mysqli_fetch_assoc($categories)): ?>
              <option value="<?php echo $cat['category_id']; ?>" <?php echo $category_filter == $cat['category_id'] ? 'selected' : ''; ?>>
                <?php echo htmlspecialchars($cat['category_name']); ?>
              </option>
            <?php endwhile; ?>
          </select>
        </form>
      </div>
    </div>

    <!-- Products Grid -->
    <div class="row">
      <?php while ($product = mysqli_fetch_assoc($products)): ?>
      <div class="col-md-4 mb-4">
        <div class="card h-100">
          <?php if ($product['image']): ?>
            <img src="<?php echo $product['image']; ?>" class="card-img-top" style="height: 200px; object-fit: cover;">
          <?php else: ?>
            <div class="bg-secondary" style="height: 200px; display: flex; align-items: center; justify-content: center; color: white;">
              No Image
            </div>
          <?php endif; ?>
          <div class="card-body">
            <h5 class="card-title"><?php echo htmlspecialchars($product['product_name']); ?></h5>
            <p class="text-muted"><?php echo htmlspecialchars($product['category_name']); ?></p>
            <p class="card-text"><?php echo htmlspecialchars(substr($product['description'], 0, 100)); ?>...</p>
            <h5 class="text-primary">₱<?php echo number_format($product['price'], 2); ?></h5>
            <p class="text-muted">Stock: <?php echo $product['stock']; ?></p>
            <?php if (isset($_SESSION['user_id']) && $_SESSION['role'] == 'customer'): ?>
              <a href="customer/add_to_cart.php?product_id=<?php echo $product['product_id']; ?>" class="btn btn-primary">Add to Cart</a>
            <?php else: ?>
              <a href="login.php" class="btn btn-secondary">Login to Purchase</a>
            <?php endif; ?>
          </div>
        </div>
      </div>
      <?php endwhile; ?>
    </div>
  
  <?php if (isset($_SESSION['user_id']) && $_SESSION['role'] == 'customer'): ?>
      </div>
    </div>
  </div>
  <?php else: ?>
  </div>
  <?php endif; ?>
</body>
</html>
