<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Coffee Haven | Login</title>
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
    }

    .login-container {
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
    }

    .login-card {
      background-color: white;
      padding: 2rem 2.5rem;
      border-radius: 15px;
      box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
      width: 100%;
      max-width: 420px;
    }

    .login-card h3 {
      color: var(--primary-color);
      font-weight: 700;
      text-align: center;
      margin-bottom: 1.5rem;
    }

    .form-control:focus {
      border-color: var(--secondary-color);
      box-shadow: 0 0 0 0.25rem rgba(201, 166, 107, 0.25);
    }

    .btn-login {
      background-color: var(--primary-color);
      color: white;
      border: none;
      transition: 0.3s;
      border-radius: 8px;
    }

    .btn-login:hover {
      background-color: var(--primary-dark);
    }

    .register-link {
      text-align: center;
      margin-top: 1rem;
    }

    .register-link a {
      color: var(--primary-color);
      text-decoration: none;
      font-weight: 500;
    }

    .register-link a:hover {
      text-decoration: underline;
    }
  </style>
</head>
<body>

  <div class="login-container">
    <div class="login-card">
      <h3>Welcome Back ☕</h3>
      <form action="auth.php" method="POST">
        <div class="mb-3">
          <label for="email" class="form-label">Email Address</label>
          <input type="email" class="form-control" id="email" name="email" placeholder="Enter your email" required>
        </div>

        <div class="mb-3">
          <label for="password" class="form-label">Password</label>
          <input type="password" class="form-control" id="password" name="password" placeholder="Enter your password" required>
        </div>

        <button type="submit" class="btn btn-login w-100">Login</button>
      </form>

      <div class="register-link">
        <p>Don't have an account? <a href="register.php">Register here</a></p>
      </div>
    </div>
  </div>

</body>
</html>
