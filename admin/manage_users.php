<?php
session_start();
include('../conn.php');

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: ../login.php");
    exit;
}

// Delete user
if (isset($_GET['delete'])) {
    $user_id = $_GET['delete'];
    $query = "DELETE FROM users WHERE user_id='$user_id'";
    mysqli_query($conn, $query);
    header("Location: manage_users.php");
    exit;
}

// Get all users
$users_query = "SELECT * FROM users ORDER BY created_at DESC";
$users = mysqli_query($conn, $users_query);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Manage Users</title>
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
    .badge.bg-danger {
      background-color: #a0522d !important;
    }
    .badge.bg-warning {
      background-color: #d4a574 !important;
      color: #4b2e13 !important;
    }
    .badge.bg-primary {
      background-color: #6f4e37 !important;
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
        <h2>Manage Users</h2>
        <a href="add_user.php" class="btn btn-primary mb-3">Add New User</a>
        
        <div class="card">
          <div class="card-body">
            <table class="table table-striped">
              <thead>
                <tr>
                  <th>ID</th>
                  <th>Full Name</th>
                  <th>Email</th>
                  <th>Role</th>
                  <th>Contact Number</th>
                  <th>Address</th>
                  <th>Registered</th>
                  <th>Actions</th>
                </tr>
              </thead>
              <tbody>
                <?php while ($user = mysqli_fetch_assoc($users)): ?>
                <tr>
                  <td><?php echo $user['user_id']; ?></td>
                  <td><?php echo htmlspecialchars($user['fullname']); ?></td>
                  <td><?php echo htmlspecialchars($user['email']); ?></td>
                  <td>
                    <span class="badge bg-<?php 
                      echo $user['role'] == 'admin' ? 'danger' : 
                           ($user['role'] == 'staff' ? 'warning' : 'primary'); 
                    ?>"><?php echo ucfirst($user['role']); ?></span>
                  </td>
                  <td><?php echo htmlspecialchars($user['contact_number'] ?? 'N/A'); ?></td>
                  <td><?php echo htmlspecialchars($user['address'] ?? 'N/A'); ?></td>
                  <td><?php echo date('M d, Y', strtotime($user['created_at'])); ?></td>
                  <td>
                    <a href="edit_user.php?id=<?php echo $user['user_id']; ?>" class="btn btn-sm btn-warning">Edit</a>
                    <?php if ($user['user_id'] != $_SESSION['user_id']): ?>
                    <a href="?delete=<?php echo $user['user_id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Delete this user? This will also delete their orders and cart items.')">Delete</a>
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
