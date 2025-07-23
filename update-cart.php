<?php
// update-cart.php - Updates cart item quantity
require 'connections.php';
session_start();

$user_id = $_SESSION['user_id'] ?? 0;
if (!$user_id) {
    echo "<script>alert('Please log in to update your cart.'); window.location.href='login.php';</script>";
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['cart_id'], $_POST['quantity'])) {
    $cart_id = intval($_POST['cart_id']);
    $quantity = intval($_POST['quantity']);

    if ($quantity < 1) {
        // If quantity is less than 1, remove the item from the cart
        $conn->query("DELETE FROM cart WHERE id = $cart_id AND user_id = $user_id");
    } else {
        // Otherwise, update the quantity
        $conn->query("UPDATE cart SET quantity = $quantity WHERE id = $cart_id AND user_id = $user_id");
    }

    echo "<script>window.location.href='cart.php';</script>";
    exit;
} else {
    echo "<script>alert('Invalid request.'); window.location.href='cart.php';</script>";
    exit;
}
?>
