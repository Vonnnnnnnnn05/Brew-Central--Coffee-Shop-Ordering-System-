<?php
session_start();
include('../conn.php');

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: ../login.php");
    exit;
}

if (isset($_POST['order_id']) && isset($_POST['order_status'])) {
    $order_id = $_POST['order_id'];
    $order_status = $_POST['order_status'];
    $processed_by = $_SESSION['user_id'];
    
    $query = "UPDATE orders SET order_status='$order_status', processed_by='$processed_by' WHERE order_id='$order_id'";
    mysqli_query($conn, $query);
}

header("Location: manage_orders.php");
exit;
?>
