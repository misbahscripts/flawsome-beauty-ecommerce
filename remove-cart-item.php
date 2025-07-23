<?php
require 'connections.php';
session_start();

$user_id = $_SESSION['user_id'] ?? 0;
if (!$user_id) {
    echo "<script>alert('Please log in to modify your cart.'); window.location.href='login.php';</script>";
    exit;
}

// Get cart item ID from URL
$cart_id = $_GET['id'] ?? 0;

if ($cart_id) {
    $stmt = $conn->prepare("DELETE FROM cart WHERE id = ? AND user_id = ?");
    $stmt->bind_param("ii", $cart_id, $user_id);
    
    if ($stmt->execute()) {
        echo "<script>alert('Item removed from cart!'); window.location.href='cart.php';</script>";
    } else {
        echo "<script>alert('Failed to remove item. Try again.'); window.location.href='cart.php';</script>";
    }
} else {
    echo "<script>alert('Invalid request.'); window.location.href='cart.php';</script>";
}
?>
