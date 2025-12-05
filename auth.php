<?php
session_start();
include('conn.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    $query = "SELECT * FROM users WHERE email = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();

        // Verify password
        if (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['fullname'] = $user['fullname'];
            $_SESSION['role'] = $user['role'];

            // Redirect by role
            if ($user['role'] === 'admin') {
                 echo "<script>alert('Welcome Admin!'); window.location='admin/dashboard.php';</script>";
                
            } elseif ($user['role'] === 'staff') {
                 echo "<script>alert('Welcome Staff!'); window.location='staff/dashboard.php';</script>";
            } else {
                 echo "<script>alert('Welcome Customer!'); window.location='customer/dashboard.php';</script>";
            }
            exit;
        } else {
            echo "<script>alert('Invalid password!'); window.location='login.php';</script>";
        }
    } else {
        echo "<script>alert('Email not found!'); window.location='login.php';</script>";
    }
}
?>
