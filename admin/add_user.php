<?php
session_start();
include('../conn.php');

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: ../login.php");
    exit;
}

// Handle form submission
if (isset($_POST['add_user'])) {
    $fullname = mysqli_real_escape_string($conn, $_POST['fullname']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $role = $_POST['role'];
    $contact_number = mysqli_real_escape_string($conn, $_POST['contact_number']);
    $address = mysqli_real_escape_string($conn, $_POST['address']);
    
    // Check if email already exists
    $check_email = mysqli_query($conn, "SELECT * FROM users WHERE email='$email'");
    if (mysqli_num_rows($check_email) > 0) {
        $error = "Email already exists!";
    } else {
        $query = "INSERT INTO users (fullname, email, password, role, contact_number, address) 
                  VALUES ('$fullname', '$email', '$password', '$role', '$contact_number', '$address')";
        
        if (mysqli_query($conn, $query)) {
            header("Location: manage_users.php");
            exit;
        } else {
            $error = "Error adding user!";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Add User</title>
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
    .btn-primary:active, .btn-primary:focus {
      background-color: #4b2e13 !important;
      border-color: #4b2e13 !important;
      box-shadow: 0 0 0 0.25rem rgba(111, 78, 55, 0.25) !important;
    }
    .btn-secondary:active, .btn-secondary:focus {
      background-color: #6f4e37 !important;
      border-color: #6f4e37 !important;
      box-shadow: 0 0 0 0.25rem rgba(139, 111, 71, 0.25) !important;
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
        <a href="manage_users.php" class="active">Manage Users</a>
        <a href="logout.php" class="text-danger mt-3 d-block">Logout</a>
      </div>

      <!-- Main Content -->
      <div class="col-md-10 p-4">
        <h2>Add New User</h2>
        <a href="manage_users.php" class="btn btn-secondary mb-3">Back to Users</a>
        
        <?php if (isset($error)) echo "<div class='alert alert-danger'>$error</div>"; ?>
        
        <div class="card">
          <div class="card-body">
            <form method="POST">
              <div class="mb-3">
                <label>Full Name</label>
                <input type="text" name="fullname" class="form-control" required>
              </div>
              
              <div class="mb-3">
                <label>Email</label>
                <input type="email" name="email" class="form-control" required>
              </div>
              
              <div class="mb-3">
                <label>Password</label>
                <input type="password" name="password" class="form-control" required minlength="6">
                <small class="text-muted">Minimum 6 characters</small>
              </div>
              
              <div class="mb-3">
                <label>Role</label>
                <select name="role" class="form-control" required>
                  <option value="">Select Role</option>
                  <option value="customer">Customer</option>
                  <option value="staff">Staff</option>
                  <option value="admin">Admin</option>
                </select>
              </div>
              
              <div class="mb-3">
                <label>Contact Number</label>
                <input type="text" name="contact_number" class="form-control">
              </div>
              
              <div class="mb-3">
                <label>Address</label>
                <textarea name="address" class="form-control" rows="3"></textarea>
              </div>
              
              <button type="submit" name="add_user" class="btn btn-primary">Add User</button>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</body>
</html>
