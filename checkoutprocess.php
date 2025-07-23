<?php
require 'connections.php';
session_start();

$user_id = $_SESSION['user_id'] ?? 0;
if (!$user_id) {
    echo "<script>alert('Please log in to place an order.'); window.location.href='login.php';</script>";
    exit;
}

// Validate required fields
$required_fields = ['name', 'email', 'address1', 'city', 'zipcode', 'phone1', 'payment_method'];
foreach ($required_fields as $field) {
    if (empty($_POST[$field])) {
        echo "<script>alert('Please fill all required fields.'); window.history.back();</script>";
        exit;
    }
}

// Get user inputs safely
$name = trim($_POST['name']);
$email = trim($_POST['email']);
$address1 = trim($_POST['address1']);
$address2 = trim($_POST['address2'] ?? '');
$city = trim($_POST['city']);
$zipcode = trim($_POST['zipcode']);
$phone1 = trim($_POST['phone1']);
$phone2 = trim($_POST['phone2'] ?? '');
$payment_method = trim($_POST['payment_method']);

// Set payment details
$card_number = ($payment_method === "Card") ? trim($_POST['card_number'] ?? '') : '';
$expiry_date = ($payment_method === "Card") ? trim($_POST['expiry_date'] ?? '') : '';
$cvv = ($payment_method === "Card") ? trim($_POST['cvv'] ?? '') : '';
$upi_id = ($payment_method === "UPI") ? trim($_POST['upi_id'] ?? '') : '';
$google_pay = ($payment_method === "GooglePay") ? trim($_POST['google_pay'] ?? '') : '';

// Check if the cart has items
$qry = $conn->query("SELECT c.*, p.name, p.price FROM cart c INNER JOIN products p ON p.id = c.product_id WHERE c.user_id = $user_id");

if ($qry->num_rows == 0) {
    echo "<script>alert('Your cart is empty. Add items before checkout.'); window.location.href='cart.php';</script>";
    exit;
}

$order_date = date("Y-m-d H:i:s");
$status = "Pending";

// Insert order details for each product
while ($row = $qry->fetch_assoc()) {
    $product_id = $row['product_id'];
    $quantity = $row['quantity']; // Fetching quantity from cart

    $stmt = $conn->prepare("INSERT INTO orders (user_id, product_id, quantity, name, email, address1, address2, city, zipcode, phone1, phone2, payment_method, card_number, expiry_date, cvv, upi_id, google_pay, order_date, status) 
                            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

    $stmt->bind_param("iiissssssssssssssss", 
        $user_id, $product_id, $quantity, $name, $email, $address1, $address2, $city, $zipcode, 
        $phone1, $phone2, $payment_method, $card_number, $expiry_date, $cvv, 
        $upi_id, $google_pay, $order_date, $status
    );

    if (!$stmt->execute()) {
        echo "<script>alert('Error placing order: " . $stmt->error . "'); window.history.back();</script>";
        exit;
    }
}

// Clear cart after order placement
$conn->query("DELETE FROM cart WHERE user_id = $user_id");

// Redirect back to checkout.php with success message
header("Location: checkout.php?success=1");
exit;
?>
