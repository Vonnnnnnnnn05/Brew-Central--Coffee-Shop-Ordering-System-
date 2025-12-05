<?php
session_start();
include('../conn.php');

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'customer') {
    header("Location: ../login.php");
    exit;
}

if (isset($_GET['cart_id'])) {
    $cart_id = $_GET['cart_id'];
    mysqli_query($conn, "DELETE FROM cart WHERE cart_id='$cart_id'");
}

header("Location: cart.php");
exit;
?>
