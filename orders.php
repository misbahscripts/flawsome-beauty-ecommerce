<?php
session_start();
require 'connections.php';
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

if (!isset($_SESSION['user_id'])) {
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>Login Required</title>
        <link rel="stylesheet" href="login.css">
        <style>
            body {
                display: flex;
                justify-content: center;
                align-items: center;
                height: 100vh;
                font-family: 'Jost', sans-serif;
                background: linear-gradient(to right, #c9d6ff, #e2e2e2);
            }
            .login-message {
                background-color: white;
                padding: 3rem;
                border-radius: 20px;
                box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
                text-align: center;
                max-width: 400px;
            }
            .login-message h2 {
                font-size: 1.8rem;
                color: #333;
                margin-bottom: 1rem;
            }
            .login-message p {
                font-size: 1rem;
                color: #555;
            }
            .login-message a {
                display: inline-block;
                margin-top: 1rem;
                padding: 0.7rem 1.5rem;
                background-color: #573b8a;
                color: #fff;
                border-radius: 10px;
                text-decoration: none;
            }
            .login-message a:hover {
                background-color: #6d44b8;
            }
        </style>
    </head>
    <body>
        <div class="login-message">
            <h2>You're not logged in!</h2>
            <p>To view your orders, please log in.</p>
            <a href="login.php">Log In</a>
        </div>
    </body>
    </html>
    <?php
    exit;
}

$user_id = $_SESSION['user_id'];

// ✅ Fetch user name and mobile from `users`
$user_query = "SELECT username, mobilenumber FROM users WHERE id = ?";
$user_stmt = $conn->prepare($user_query);
$user_stmt->bind_param("i", $user_id);
$user_stmt->execute();
$user_result = $user_stmt->get_result();
$user_info = $user_result->fetch_assoc();
$user_stmt->close();

// ✅ Fetch latest order's address from `orders`
$addr_query = "SELECT address1 FROM orders WHERE user_id = ? ORDER BY order_date DESC LIMIT 1";
$addr_stmt = $conn->prepare($addr_query);
$addr_stmt->bind_param("i", $user_id);
$addr_stmt->execute();
$addr_result = $addr_stmt->get_result();
$order_info = $addr_result->fetch_assoc();
$addr_stmt->close();

// ✅ Fetch orders with LEFT JOIN in case products are missing
$order_query = "
    SELECT o.id AS order_id, o.order_date, o.status, o.payment_method, 
           o.quantity, p.name AS product_name, p.image_url
    FROM orders o
    LEFT JOIN products p ON o.product_id = p.id
    WHERE o.user_id = ?
    ORDER BY o.order_date DESC";

$stmt = $conn->prepare($order_query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

$orders = [];
while ($row = $result->fetch_assoc()) {
    $orders[$row['order_id']][] = $row;
}

$stmt->close();
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Orders</title>
    <link rel="stylesheet" href="orders.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css">
</head>
<body class="container">

    <?php if ($user_info): ?>
        <h2 class="text-center mt-4">
            ORDERS PLACED BY <span class="text-primary"><?php echo htmlspecialchars($user_info['username']); ?></span>
        </h2>
        <div class="user-details text-center my-4">
            <p><strong>Address:</strong> <?php echo htmlspecialchars($order_info['address1'] ?? 'Not available'); ?></p>
            <p><strong>Mobile Number:</strong> <?php echo htmlspecialchars($user_info['mobilenumber']); ?></p>
        </div>
    <?php else: ?>
        <h2 class="text-center mt-4 text-danger">User information not found.</h2>
    <?php endif; ?>

    <?php if (!empty($orders)) { ?>
        <table class="table table-bordered table-hover">
            <thead class="table-dark">
                <tr>
                    <th>Order ID</th>
                    <th>Product</th>
                    <th>Image</th>
                    <th>Quantity</th>
                    <th>Order Date</th>
                    <th>Payment Method</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($orders as $order_id => $order_items): ?>
                    <?php foreach ($order_items as $index => $row): ?>
                        <tr>
                            <?php if ($index == 0): ?>
                                <td rowspan="<?php echo count($order_items); ?>" class="align-middle"><?php echo $order_id; ?></td>
                            <?php endif; ?>
                            <td><?php echo htmlspecialchars($row['product_name'] ?? 'N/A'); ?></td>
                            <td>
                                <?php if (!empty($row['image_url'])): ?>
                                    <img src="<?php echo htmlspecialchars($row['image_url']); ?>" width="50" class="img-thumbnail">
                                <?php else: ?>
                                    <span class="text-muted">No image</span>
                                <?php endif; ?>
                            </td>
                            <td><?php echo $row['quantity']; ?></td>
                            <td><?php echo $row['order_date']; ?></td>
                            <td><?php echo $row['payment_method']; ?></td>
                            <td><span class="badge bg-info"><?php echo $row['status']; ?></span></td>
                        </tr>
                    <?php endforeach; ?>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php } else { ?>
        <p class="text-center text-danger fs-5">No orders found.</p>
    <?php } ?>

    <div class="text-center mt-4">
        <a href="index.php" class="btn btn-primary">Go Back to Products</a>
    </div>

</body>
</html>
