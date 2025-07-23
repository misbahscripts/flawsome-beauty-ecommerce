<?php 
require 'connections.php';
session_start();

$user_id = $_SESSION['user_id'] ?? 0;
if (!$user_id) {
    echo "<script>alert('Please log in to proceed with checkout.'); window.location.href='login.php';</script>";
    exit;
}

// Display success message and redirect if an order was placed
if (isset($_GET['success']) && $_GET['success'] == 1) {
    echo "<script>
        alert('Order placed successfully!');
        window.location.href = 'orders.php';
    </script>";
    exit;
}

$total = 0;
$qry = $conn->query("SELECT c.*, p.name, p.price, p.image_url FROM cart c INNER JOIN products p ON p.id=c.product_id WHERE c.user_id=$user_id");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout - Flawsome Beauty</title>
    <link rel="stylesheet" href="checkout.css">
</head>
<body>
    <div class="hero-container">
        <div class="hero-image"></div>
    </div>

    <section class="py-5">
        <div class="container">
            <div class="card rounded-0">
                <div class="card-body">
                    <h3><b>Checkout</b></h3>
                    <hr class="border-dark">

                    <table class="table">
                        <thead>
                            <tr>
                                <th>Image</th>
                                <th>Product</th>
                                <th>Price</th>
                                <th>Quantity</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $cart_items = []; // Store cart items to pass later
                            while ($row = $qry->fetch_assoc()): 
                                $cart_items[] = $row;
                            ?>
                            <tr>
                                <td><img src="<?= $row['image_url'] ?>" alt="<?= $row['name'] ?>" width="50"></td>
                                <td><?= $row['name'] ?></td>
                                <td>RS:<?= number_format($row['price'], 2) ?></td>
                                <td><?= $row['quantity'] ?></td>
                                <td>RS:<?= number_format($row['price'] * $row['quantity'], 2) ?></td>
                            </tr>
                            <?php $total += $row['price'] * $row['quantity']; endwhile; ?>
                        </tbody>
                    </table>
                    <h4 class="text-right">Total: RS <?= number_format($total, 2) ?></h4>

                    <form action="checkoutprocess.php" method="POST">
                        <input type="hidden" name="user_id" value="<?= $user_id ?>">
                        <input type="hidden" name="total_amount" value="<?= $total ?>">

                        <?php foreach ($cart_items as $item): ?>
                            <input type="hidden" name="product_ids[]" value="<?= $item['product_id'] ?>">
                            <input type="hidden" name="quantities[]" value="<?= $item['quantity'] ?>">
                        <?php endforeach; ?>

                        <h4>Billing Details</h4>
                        <label>Name:</label>
                        <input type="text" name="name" required>

                        <label>Email:</label>
                        <input type="email" name="email" required>

                        <label>Address Line 1:</label>
                        <input type="text" name="address1" required>

                        <label>Address Line 2 (optional):</label>
                        <input type="text" name="address2">

                        <label>City:</label>
                        <input type="text" name="city" required>

                        <label>Zip Code:</label>
                        <input type="text" name="zipcode" required>

                        <label>Phone 1:</label>
                        <input type="text" name="phone1" required>

                        <label>Phone 2 (optional):</label>
                        <input type="text" name="phone2">

                        <h4>Payment Method</h4>
                        <select name="payment_method" id="payment_method" required onchange="togglePaymentFields()">
                            <option value="COD">Cash on Delivery</option>
                            <option value="Card">Credit/Debit Card</option>
                            <option value="UPI">UPI</option>
                            <option value="GooglePay">Google Pay</option>
                        </select>

                        <div id="card_fields" style="display: none;">
                            <label>Card Number:</label>
                            <input type="text" name="card_number" id="card_number">
                            <label>Expiry Date:</label>
                            <input type="text" name="expiry_date" id="expiry_date">
                            <label>CVV:</label>
                            <input type="text" name="cvv" id="cvv">
                        </div>

                        <button type="submit" class="btn btn-primary">Place Order</button>
                    </form>
                </div>
            </div>
        </div>
    </section>
</body>
</html>
