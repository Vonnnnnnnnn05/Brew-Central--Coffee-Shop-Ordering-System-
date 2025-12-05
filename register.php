<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Coffee Haven | Register</title>
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

    .register-container {
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
    }

    .register-card {
      background-color: white;
      padding: 2rem 2.5rem;
      border-radius: 15px;
      box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
      width: 100%;
      max-width: 420px;
    }

    .register-card h3 {
      color: var(--primary-color);
      font-weight: 700;
      text-align: center;
      margin-bottom: 1.5rem;
    }

    .form-control:focus {
      border-color: var(--secondary-color);
      box-shadow: 0 0 0 0.25rem rgba(201, 166, 107, 0.25);
    }

    .btn-register {
      background-color: var(--primary-color);
      color: white;
      border: none;
      transition: 0.3s;
    }

    .btn-register:hover {
      background-color: var(--primary-dark);
    }

    .login-link {
      text-align: center;
      margin-top: 1rem;
    }

    .login-link a {
      color: var(--primary-color);
      text-decoration: none;
      font-weight: 500;
    }

    .login-link a:hover {
      text-decoration: underline;
    }
  </style>
</head>
<body>

  <div class="register-container">
    <div class="register-card">
      <h3>Create Your Account</h3>
      <form action="register_user.php" method="POST">
        <div class="mb-3">
          <label for="fullname" class="form-label">Full Name</label>
          <input type="text" class="form-control" id="fullname" name="fullname" required>
        </div>

        <div class="mb-3">
          <label for="email" class="form-label">Email Address</label>
          <input type="email" class="form-control" id="email" name="email" required>
        </div>

        <div class="mb-3">
          <label for="password" class="form-label">Password</label>
          <input type="password" class="form-control" id="password" name="password" required>
        </div>

        <div class="mb-3">
          <label for="contact_number" class="form-label">Contact Number</label>
          <input type="text" class="form-control" id="contact_number" name="contact_number" required>
        </div>

        <div class="mb-3">
          <label for="address" class="form-label">Address</label>
          <input type="text" class="form-control" id="address" name="address" required>
        </div>

        <button type="submit" class="btn btn-register w-100">Register</button>
      </form>

      <div class="login-link">
        <p>Already have an account? <a href="login.php">Login here</a></p>
      </div>
    </div>
  </div>

</body>
</html>
