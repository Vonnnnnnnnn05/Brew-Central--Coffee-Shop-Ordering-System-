<?php
session_start();
include('../conn.php');

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'customer') {
    header("Location: ../login.php");
    exit;
}

if (isset($_POST['cart_id']) && isset($_POST['quantity'])) {
    $cart_id = $_POST['cart_id'];
    $quantity = (int)$_POST['quantity'];
    
    mysqli_query($conn, "UPDATE cart SET quantity='$quantity' WHERE cart_id='$cart_id'");
}

header("Location: cart.php");
exit;
?>
