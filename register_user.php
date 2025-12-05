<?php
include('conn.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $fullname = trim($_POST['fullname']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $contact_number = trim($_POST['contact_number']);
    $address = trim($_POST['address']);
    $role = 'customer';

    // Hash the password securely
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Check if email already exists
    $checkEmail = mysqli_query($conn, "SELECT * FROM users WHERE email='$email'");
    if (mysqli_num_rows($checkEmail) > 0) {
        echo "<script>
                alert('Email already exists! Please use another.');
                window.location.href = 'register.php';
              </script>";
        exit;
    }

    // Insert user with hashed password
    $query = "INSERT INTO users (fullname, email, password, role, contact_number, address)
              VALUES ('$fullname', '$email', '$hashedPassword', '$role', '$contact_number', '$address')";

    if (mysqli_query($conn, $query)) {
        echo "<script>
                alert('Registration successful! Please log in.');
                window.location.href = 'login.php';
              </script>";
    } else {
        echo "<script>
                alert('Registration failed! Please try again.');
                window.location.href = 'register.php';
              </script>";
    }
}
?>
