<?php
session_start();
require_once 'conn.php';

// Fetch featured products
$query = "SELECT p.*, c.category_name 
          FROM products p 
          LEFT JOIN categories c ON p.category_id = c.category_id 
          WHERE p.stock > 0 
          ORDER BY p.product_id DESC 
          LIMIT 6";
$result = mysqli_query($conn, $query);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Brew Central | Coffee Shop</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    :root {
      --primary-color: #6f4e37;
      --primary-dark: #4b2e13;
      --secondary-color: #c9a66b;
      --accent-color: #d4a574;
      --light-bg: #faf7f2;
      --dark-bg: #2c1b0e;
    }

    body {
      background-color: var(--light-bg);
      font-family: 'Poppins', sans-serif;
      color: var(--primary-dark);
    }

    /* Navbar */
    .navbar {
      background-color: var(--primary-color);
    }
    .navbar-brand, .nav-link {
      color: #fff !important;
      font-weight: 500;
    }
    .nav-link:hover {
      color: var(--secondary-color) !important;
    }

    /* Banner */
    .banner {
  background: linear-gradient(rgba(0,0,0,0.5), rgba(0,0,0,0.5)),
              url('https://images.unsplash.com/photo-1509042239860-f550ce710b93') 
              center/cover no-repeat;
  color: #fff;
  text-align: center;
  padding: 150px 20px;
}

    .banner h1 {
      font-size: 3rem;
      font-weight: 700;
      color: var(--secondary-color);
    }
    .banner p {
      font-size: 1.2rem;
      margin-bottom: 1.5rem;
    }
    .btn-shop {
      background-color: var(--secondary-color);
      color: #fff;
      padding: 10px 25px;
      border-radius: 25px;
      transition: 0.3s;
    }
    .btn-shop:hover {
      background-color: var(--accent-color);
      color: #fff;
    }

    /* Product Section */
    .products {
      padding: 60px 0;
      text-align: center;
    }
    .products h2 {
      color: var(--primary-dark);
      margin-bottom: 30px;
      font-weight: 700;
    }
    .product-card {
      border: none;
      border-radius: 15px;
      box-shadow: 0 4px 15px rgba(0,0,0,0.1);
      transition: transform 0.3s ease;
    }
    .product-card:hover {
      transform: translateY(-5px);
    }
    .product-card img {
      border-top-left-radius: 15px;
      border-top-right-radius: 15px;
      height: 200px;
      object-fit: cover;
    }
    .product-card .card-body {
      background-color: #fff;
    }
    .product-card .btn {
      background-color: var(--primary-color);
      color: white;
      border-radius: 25px;
    }
    .product-card .btn:hover {
      background-color: var(--primary-dark);
    }

    /* Footer */
    footer {
      background-color: var(--dark-bg);
      color: #fff;
      text-align: center;
      padding: 15px 0;
      font-size: 0.9rem;
    }
  </style>
</head>
<body>

  <!-- Navbar -->
  <nav class="navbar navbar-expand-lg">
    <div class="container">
      <a class="navbar-brand fw-bold" href="#"> Brew Central</a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
        <ul class="navbar-nav">
          
          <li class="nav-item"><a class="nav-link" href="login.php">Login</a></li>
        </ul>
      </div>
    </div>
  </nav>

  <!-- Banner -->
  <section class="banner">
    <h1>Awaken Your Senses</h1>
    <p>Freshly brewed coffee delivered to your doorstep.</p>
    <a href="#" class="btn-shop">Shop Now</a>
  </section>

  <!-- Product Preview -->
  <section class="products container">
    <h2>Product Menu</h2>
    <div class="row justify-content-center g-4">
      <?php if ($result && mysqli_num_rows($result) > 0): ?>
        <?php while ($product = mysqli_fetch_assoc($result)): ?>
          <div class="col-md-4">
            <div class="card product-card">
              <?php if (!empty($product['image']) && file_exists($product['image'])): ?>
                <img src="<?php echo htmlspecialchars($product['image']); ?>" class="card-img-top" alt="<?php echo htmlspecialchars($product['product_name']); ?>">
              <?php else: ?>
                <div class="bg-secondary" style="height: 200px; display: flex; align-items: center; justify-content: center; color: white;">
                  No Image
                </div>
              <?php endif; ?>
              <div class="card-body">
                <h5 class="card-title"><?php echo htmlspecialchars($product['product_name']); ?></h5>
                <p class="text-muted"><?php echo htmlspecialchars($product['category_name'] ?? 'General'); ?></p>
                <p class="card-text"><?php echo htmlspecialchars(substr($product['description'] ?? '', 0, 100)); ?><?php echo strlen($product['description'] ?? '') > 100 ? '...' : ''; ?></p>
                <h5 class="text-primary">₱<?php echo number_format($product['price'], 2); ?></h5>
                <p class="text-muted">Stock: <?php echo $product['stock']; ?></p>
                <?php if (isset($_SESSION['user_id']) && ($_SESSION['role'] == 'customer' || $_SESSION['role'] == 'staff')): ?>
                  <a href="customer/add_to_cart.php?product_id=<?php echo $product['product_id']; ?>" class="btn">Add to Cart</a>
                <?php else: ?>
                  <a href="login.php" class="btn">Login to Purchase</a>
                <?php endif; ?>
              </div>
            </div>
          </div>
        <?php endwhile; ?>
      <?php else: ?>
        <div class="col-12 text-center">
          <p>No products available at the moment.</p>
        </div>
      <?php endif; ?>
    </div>
  </section>

  <!-- Footer -->
  <footer>
    <p>© 2025 Brew Central. Crafted with love and Coffee.</p>
  </footer>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
