<?php
session_start();
include('../conn.php');

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'customer') {
    header("Location: ../login.php");
    exit;
}

if (!isset($_GET['product_id'])) {
    header("Location: ../products.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$product_id = $_GET['product_id'];
$quantity = isset($_GET['quantity']) ? (int)$_GET['quantity'] : 1;

// Check if product already in cart
$check = mysqli_query($conn, "SELECT * FROM cart WHERE user_id='$user_id' AND product_id='$product_id'");

if (mysqli_num_rows($check) > 0) {
    // Update quantity
    $cart = mysqli_fetch_assoc($check);
    $new_quantity = $cart['quantity'] + $quantity;
    mysqli_query($conn, "UPDATE cart SET quantity='$new_quantity' WHERE cart_id='{$cart['cart_id']}'");
} else {
    // Add new item
    mysqli_query($conn, "INSERT INTO cart (user_id, product_id, quantity) VALUES ('$user_id', '$product_id', '$quantity')");
}

header("Location: cart.php");
exit;
?>
