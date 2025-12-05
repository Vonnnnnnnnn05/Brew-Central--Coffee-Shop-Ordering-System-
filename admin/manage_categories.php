<?php
session_start();
include('../conn.php');

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: ../login.php");
    exit;
}

// Add category
if (isset($_POST['add_category'])) {
    $category_name = mysqli_real_escape_string($conn, $_POST['category_name']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    
    $query = "INSERT INTO categories (category_name, description) VALUES ('$category_name', '$description')";
    if (mysqli_query($conn, $query)) {
        $success = "Category added successfully!";
    } else {
        $error = "Error adding category!";
    }
}

// Delete category
if (isset($_GET['delete'])) {
    $category_id = $_GET['delete'];
    $query = "DELETE FROM categories WHERE category_id='$category_id'";
    mysqli_query($conn, $query);
    header("Location: manage_categories.php");
    exit;
}

// Get all categories
$categories = mysqli_query($conn, "SELECT * FROM categories ORDER BY category_name ASC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Manage Categories</title>
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
    .btn-danger {
      background-color: #a0522d;
      border-color: #a0522d;
    }
    .btn-danger:hover {
      background-color: #8b4513;
      border-color: #8b4513;
    }
    .alert-success {
      background-color: #f5e6d3;
      border-color: #d4a574;
      color: #4b2e13;
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
        <a href="manage_products.php">Manage Products</a>
        <a href="manage_categories.php" class="active">Categories</a>
        <a href="manage_orders.php">Orders</a>
        <a href="inventory_logs.php">Inventory Logs</a>
        <a href="manage_feedback.php">Feedback</a>
        <a href="manage_users.php">Manage Users</a>
        <a href="logout.php" class="text-danger mt-3 d-block">Logout</a>
      </div>

      <!-- Main Content -->
      <div class="col-md-10 p-4">
    <h2>Manage Categories</h2>
    
    <?php if (isset($success)) echo "<div class='alert alert-success'>$success</div>"; ?>
    <?php if (isset($error)) echo "<div class='alert alert-danger'>$error</div>"; ?>
    
    <!-- Add Category Form -->
    <div class="card mb-4">
      <div class="card-body">
        <h5>Add New Category</h5>
        <form method="POST">
          <div class="mb-3">
            <label>Category Name</label>
            <input type="text" name="category_name" class="form-control" required>
          </div>
          <div class="mb-3">
            <label>Description</label>
            <textarea name="description" class="form-control" rows="3"></textarea>
          </div>
          <button type="submit" name="add_category" class="btn btn-primary">Add Category</button>
        </form>
      </div>
    </div>
    
    <!-- Categories List -->
    <div class="card">
      <div class="card-body">
        <h5>All Categories</h5>
        <table class="table table-striped">
          <thead>
            <tr>
              <th>ID</th>
              <th>Name</th>
              <th>Description</th>
              <th>Created At</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            <?php while ($cat = mysqli_fetch_assoc($categories)): ?>
            <tr>
              <td><?php echo $cat['category_id']; ?></td>
              <td><?php echo htmlspecialchars($cat['category_name']); ?></td>
              <td><?php echo htmlspecialchars($cat['description']); ?></td>
              <td><?php echo $cat['created_at']; ?></td>
              <td>
                <a href="?delete=<?php echo $cat['category_id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Delete this category?')">Delete</a>
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
