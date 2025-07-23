<?php
// cart.php - Updated for Flawsome Beauty with CBPOS functionality
require 'connections.php';
session_start();

$user_id = $_SESSION['user_id'] ?? 0;
if (!$user_id) {
    echo "<script>alert('Please log in to view your cart.'); window.location.href='login.php';</script>";
    exit;
}

$total = 0;
$qry = $conn->query("SELECT c.*, p.name, p.price, p.image_url FROM cart c 
                      INNER JOIN products p ON p.id = c.product_id 
                      WHERE c.user_id = $user_id");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cart - Flawsome Beauty</title>
    <link rel="stylesheet" href="stylescart.css"> 
</head>
<body>

<section class="py-5">
    <div class="container">
        <div class="hero-image"></div>
        <div class="card rounded-0">
            <div class="card-body">
                <h3><b>Cart List</b></h3>
                <hr class="border-dark">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Image</th> <!-- New Column -->
                            <th>Product</th>
                            <th>Price</th>
                            <th>Quantity</th>
                            <th>Total</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = $qry->fetch_assoc()): ?>
                        <tr>
                            <td>
                                <img src="<?= htmlspecialchars($row['image_url']) ?>" alt="<?= htmlspecialchars($row['name']) ?>" width="70" height="70">
                            </td> <!-- Displaying Image -->
                            <td><?= htmlspecialchars($row['name']) ?></td>
                            <td>RS:<?= number_format($row['price'], 2) ?></td>
                            <td>
                                <form method="POST" action="update-cart.php">
                                    <input type="hidden" name="cart_id" value="<?= $row['id'] ?>">
                                    <input type="number" name="quantity" value="<?= $row['quantity'] ?>" min="1">
                                    <button type="submit" class="btn btn-sm btn-primary">Update</button>
                                </form>
                            </td>
                            <td>RS:<?= number_format($row['price'] * $row['quantity'], 2) ?></td>
                            <td><a href="remove-cart-item.php?id=<?= $row['id'] ?>" class="btn btn-danger">Remove</a></td>
                        </tr>
                        <?php $total += $row['price'] * $row['quantity']; endwhile; ?>
                    </tbody>
                </table>
                <h4 class="text-right">Total: RS:<?= number_format($total, 2) ?></h4>
                <a href="checkout.php" class="btn btn-success">Proceed to Checkout</a>
            </div>
        </div>
    </div>
</section>

