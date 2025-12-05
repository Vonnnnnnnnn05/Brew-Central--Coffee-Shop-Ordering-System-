<?php
session_start();
include('../conn.php');

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'customer') {
    header("Location: ../login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// Get user info
$user_query = mysqli_query($conn, "SELECT * FROM users WHERE user_id='$user_id'");
$user = mysqli_fetch_assoc($user_query);

// Get cart items
$cart_query = "SELECT c.*, p.product_name, p.price, p.stock 
               FROM cart c 
               JOIN products p ON c.product_id = p.product_id 
               WHERE c.user_id = '$user_id'";
$cart_items = mysqli_query($conn, $cart_query);

// Calculate total
$total = 0;
$items = [];
while ($item = mysqli_fetch_assoc($cart_items)) {
    $items[] = $item;
    $total += $item['price'] * $item['quantity'];
}

if (empty($items)) {
    header("Location: cart.php");
    exit;
}

// Handle checkout
if (isset($_POST['place_order'])) {
    $address = mysqli_real_escape_string($conn, $_POST['address']);
    $payment_method = mysqli_real_escape_string($conn, $_POST['payment_method']);
    
    // Create order
    $order_query = "INSERT INTO orders (user_id, total_amount, payment_method, address) 
                    VALUES ('$user_id', '$total', '$payment_method', '$address')";
    
    if (mysqli_query($conn, $order_query)) {
        $order_id = mysqli_insert_id($conn);
        
        // Add order items and update stock
        foreach ($items as $item) {
            $product_id = $item['product_id'];
            $quantity = $item['quantity'];
            $price = $item['price'];
            
            // Insert order item
            mysqli_query($conn, "INSERT INTO order_items (order_id, product_id, quantity, price) 
                                 VALUES ('$order_id', '$product_id', '$quantity', '$price')");
            
            // Update product stock
            mysqli_query($conn, "UPDATE products SET stock = stock - $quantity WHERE product_id = '$product_id'");
            
            // Log inventory change
            $log_query = "INSERT INTO inventory_logs (product_id, changed_by, change_type, quantity_changed, remarks) 
                          VALUES ('$product_id', '$user_id', 'remove', '$quantity', 'Order #$order_id')";
            mysqli_query($conn, $log_query);
        }
        
        // Clear cart
        mysqli_query($conn, "DELETE FROM cart WHERE user_id='$user_id'");
        
        header("Location: orders.php?success=1");
        exit;
    } else {
        $error = "Error placing order!";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Checkout</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
  <nav class="navbar navbar-dark" style="background-color: #6f4e37;">
    <div class="container">
      <a class="navbar-brand" href="../products.php">☕ Brew Central</a>
      <a class="btn btn-light btn-sm" href="cart.php">Back to Cart</a>
    </div>
  </nav>

  <div class="container mt-4">
    <h2>Checkout</h2>
    
    <?php if (isset($error)) echo "<div class='alert alert-danger'>$error</div>"; ?>
    
    <div class="row">
      <div class="col-md-8">
        <div class="card mb-3">
          <div class="card-body">
            <h5>Order Summary</h5>
            <table class="table">
              <thead>
                <tr>
                  <th>Product</th>
                  <th>Price</th>
                  <th>Quantity</th>
                  <th>Subtotal</th>
                </tr>
              </thead>
              <tbody>
                <?php foreach ($items as $item): ?>
                <tr>
                  <td><?php echo htmlspecialchars($item['product_name']); ?></td>
                  <td>₱<?php echo number_format($item['price'], 2); ?></td>
                  <td><?php echo $item['quantity']; ?></td>
                  <td>₱<?php echo number_format($item['price'] * $item['quantity'], 2); ?></td>
                </tr>
                <?php endforeach; ?>
              </tbody>
              <tfoot>
                <tr>
                  <th colspan="3" class="text-end">Total:</th>
                  <th>₱<?php echo number_format($total, 2); ?></th>
                </tr>
              </tfoot>
            </table>
          </div>
        </div>
        
        <div class="card">
          <div class="card-body">
            <h5>Delivery Information</h5>
            <form method="POST">
              <div class="mb-3">
                <label>Delivery Address</label>
                <textarea name="address" class="form-control" rows="3" required><?php echo htmlspecialchars($user['address'] ?? ''); ?></textarea>
              </div>
              
              <div class="mb-3">
                <label>Payment Method</label>
                <select name="payment_method" class="form-control" required>
                  <option value="COD">Cash on Delivery</option>
                  <option value="GCash">GCash</option>
                  <option value="PayMaya">PayMaya</option>
                </select>
              </div>
              
              <button type="submit" name="place_order" class="btn btn-success btn-lg w-100">Place Order</button>
            </form>
          </div>
        </div>
      </div>
      
      <div class="col-md-4">
        <div class="card">
          <div class="card-body">
            <h5>Order Total</h5>
            <hr>
            <div class="d-flex justify-content-between mb-2">
              <span>Subtotal:</span>
              <span>₱<?php echo number_format($total, 2); ?></span>
            </div>
            <div class="d-flex justify-content-between mb-2">
              <span>Delivery Fee:</span>
              <span>₱0.00</span>
            </div>
            <hr>
            <div class="d-flex justify-content-between">
              <strong>Total:</strong>
              <strong>₱<?php echo number_format($total, 2); ?></strong>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</body>
</html>
