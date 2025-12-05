<?php
session_start();
include('../conn.php');

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: ../login.php");
    exit;
}

// Handle form submission
if (isset($_POST['add_product'])) {
    $product_name = mysqli_real_escape_string($conn, $_POST['product_name']);
    $category_id = $_POST['category_id'];
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $price = $_POST['price'];
    $stock = $_POST['stock'];
    
    // Handle image upload
    $image = null;
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $upload_dir = '../uploads/';
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }
        $image_name = time() . '_' . $_FILES['image']['name'];
        $image_path = $upload_dir . $image_name;
        if (move_uploaded_file($_FILES['image']['tmp_name'], $image_path)) {
            $image = 'uploads/' . $image_name;
        }
    }
    
    $query = "INSERT INTO products (product_name, category_id, description, price, stock, image) 
              VALUES ('$product_name', '$category_id', '$description', '$price', '$stock', '$image')";
    
    if (mysqli_query($conn, $query)) {
        // Log inventory change
        $product_id = mysqli_insert_id($conn);
        $user_id = $_SESSION['user_id'];
        $log_query = "INSERT INTO inventory_logs (product_id, changed_by, change_type, quantity_changed, remarks) 
                      VALUES ('$product_id', '$user_id', 'add', '$stock', 'Initial stock')";
        mysqli_query($conn, $log_query);
        
        header("Location: manage_products.php");
        exit;
    } else {
        $error = "Error adding product!";
    }
}

// Get categories
$categories = mysqli_query($conn, "SELECT * FROM categories ORDER BY category_name ASC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Add Product</title>
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
    .form-control:focus, .form-select:focus {
      border-color: #6f4e37;
      box-shadow: 0 0 0 0.25rem rgba(111, 78, 55, 0.25);
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
    <h2>Add New Product</h2>
    <a href="manage_products.php" class="btn btn-secondary mb-3">Back to Products</a>
    
    <?php if (isset($error)) echo "<div class='alert alert-danger'>$error</div>"; ?>
    
    <div class="card">
      <div class="card-body">
        <form method="POST" enctype="multipart/form-data">
          <div class="mb-3">
            <label>Product Name</label>
            <input type="text" name="product_name" class="form-control" required>
          </div>
          
          <div class="mb-3">
            <label>Category</label>
            <select name="category_id" class="form-control" required>
              <option value="">Select Category</option>
              <?php while ($cat = mysqli_fetch_assoc($categories)): ?>
                <option value="<?php echo $cat['category_id']; ?>"><?php echo htmlspecialchars($cat['category_name']); ?></option>
              <?php endwhile; ?>
            </select>
          </div>
          
          <div class="mb-3">
            <label>Description</label>
            <textarea name="description" class="form-control" rows="4"></textarea>
          </div>
          
          <div class="mb-3">
            <label>Price (₱)</label>
            <input type="number" name="price" class="form-control" step="0.01" required>
          </div>
          
          <div class="mb-3">
            <label>Stock</label>
            <input type="number" name="stock" class="form-control" required>
          </div>
          
          <div class="mb-3">
            <label>Product Image</label>
            <input type="file" name="image" class="form-control" accept="image/*">
          </div>
          
          <button type="submit" name="add_product" class="btn btn-primary">Add Product</button>
        </form>
      </div>
    </div>
      </div>
    </div>
  </div>
</body>
</html>
