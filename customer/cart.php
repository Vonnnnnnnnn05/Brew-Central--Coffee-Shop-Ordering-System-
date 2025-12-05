<?php
session_start();
include('../conn.php');

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'customer') {
    header("Location: ../login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// Get cart items
$cart_query = "SELECT c.*, p.product_name, p.price, p.image, p.stock 
               FROM cart c 
               JOIN products p ON c.product_id = p.product_id 
               WHERE c.user_id = '$user_id'";
$cart_items = mysqli_query($conn, $cart_query);

// Calculate total
$total = 0;
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Shopping Cart</title>
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
    .btn-primary {
      background-color: #6f4e37;
      border-color: #6f4e37;
    }
    .btn-primary:hover {
      background-color: #4b2e13;
      border-color: #4b2e13;
    }
    .btn-success {
      background-color: #8b6f47;
      border-color: #8b6f47;
    }
    .btn-success:hover {
      background-color: #6f4e37;
      border-color: #6f4e37;
    }
    .btn-danger {
      background-color: #a0522d;
      border-color: #a0522d;
    }
    .btn-danger:hover {
      background-color: #8b4513;
      border-color: #8b4513;
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
        <a href="cart.php" class="active">Shopping Cart</a>
        <a href="orders.php">My Orders</a>
        <a href="feedback.php">Send Feedback</a>
        <a href="logout.php" class="text-danger mt-3 d-block">Logout</a>
      </div>

      <!-- Main Content -->
      <div class="col-md-10 p-4">
    <h2>Your Shopping Cart</h2>
    
    <?php if (mysqli_num_rows($cart_items) > 0): ?>
    <div class="table-responsive">
      <table class="table">
        <thead>
          <tr>
            <th>Product</th>
            <th>Price</th>
            <th>Quantity</th>
            <th>Subtotal</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
          <?php while ($item = mysqli_fetch_assoc($cart_items)): 
            $subtotal = $item['price'] * $item['quantity'];
            $total += $subtotal;
          ?>
          <tr>
            <td>
              <div class="d-flex align-items-center">
                <?php if ($item['image']): ?>
                  <img src="../<?php echo $item['image']; ?>" width="50" height="50" class="me-2" style="object-fit: cover;">
                <?php endif; ?>
                <?php echo htmlspecialchars($item['product_name']); ?>
              </div>
            </td>
            <td>₱<?php echo number_format($item['price'], 2); ?></td>
            <td>
              <form method="POST" action="update_cart.php" class="d-inline">
                <input type="hidden" name="cart_id" value="<?php echo $item['cart_id']; ?>">
                <input type="number" name="quantity" value="<?php echo $item['quantity']; ?>" min="1" max="<?php echo $item['stock']; ?>" style="width: 70px;">
                <button type="submit" class="btn btn-sm btn-primary">Update</button>
              </form>
            </td>
            <td>₱<?php echo number_format($subtotal, 2); ?></td>
            <td>
              <a href="remove_from_cart.php?cart_id=<?php echo $item['cart_id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Remove from cart?')">Remove</a>
            </td>
          </tr>
          <?php endwhile; ?>
        </tbody>
        <tfoot>
          <tr>
            <th colspan="3" class="text-end">Total:</th>
            <th>₱<?php echo number_format($total, 2); ?></th>
            <th></th>
          </tr>
        </tfoot>
      </table>
    </div>
    
    <div class="text-end mt-3">
      <a href="../products.php" class="btn btn-secondary">Continue Shopping</a>
      <a href="checkout.php" class="btn btn-success">Proceed to Checkout</a>
    </div>
    <?php else: ?>
    <div class="alert alert-info">Your cart is empty. <a href="../products.php">Browse products</a></div>
    <?php endif; ?>
      </div>
    </div>
  </div>
</body>
</html>
