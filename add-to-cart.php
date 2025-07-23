<?php
// add-to-cart.php - Adds products to cart
require 'connections.php';
session_start();

$user_id = $_SESSION['user_id'] ?? 0;
if (!$user_id) {
    echo "<script>alert('Please log in to add items to your cart.'); window.location.href='login.php';</script>";
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $product_id = $_POST['product_id'] ?? 0;
    $quantity = $_POST['quantity'] ?? 1;

    // Fetch product details
    $product_qry = $conn->query("SELECT name, price FROM products WHERE id = $product_id");
    if ($product_qry->num_rows == 0) {
        echo "<script>alert('Invalid product.'); window.location.href='cart.php';</script>";
        exit;
    }

    $product = $product_qry->fetch_assoc();
    $name = $product['name'];
    $price = $product['price'];

    // Check if product is already in cart
    $cart_qry = $conn->query("SELECT * FROM cart WHERE user_id = $user_id AND product_id = $product_id");
    
    if ($cart_qry->num_rows > 0) {
        // Update quantity if item exists
        $conn->query("UPDATE cart SET quantity = quantity + $quantity WHERE user_id = $user_id AND product_id = $product_id");
    } else
    echo "DEBUG: user_id is $user_id<br>";

    {
        // Insert new item
        $conn->query("INSERT INTO cart (user_id, product_id, name, quantity, price) VALUES ($user_id, $product_id, '$name', $quantity, $price)");
    }

    echo "<script>alert('Product added to cart!'); window.location.href='cart.php';</script>";
    exit;
} else {
    echo "<script>alert('Invalid request.'); window.location.href='index.php';</script>";
    exit;
}
?>
