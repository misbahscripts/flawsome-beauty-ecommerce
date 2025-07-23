<?php
include('../connections.php');

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);

    // Fetch order details
    $query = "SELECT * FROM orders WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $order = $result->fetch_assoc();

    if (!$order) {
        echo "<p>Order not found.</p>";
        exit;
    }
} else {
    echo "<p>Invalid request.</p>";
    exit;
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $status = $_POST['status'];
    $address1 = $_POST['address1'];
    $address2 = $_POST['address2'];
    $city = $_POST['city'];
    $zipcode = $_POST['zipcode'];
    $payment_method = $_POST['payment_method'];

    $update_query = "UPDATE orders SET status = ?, address1 = ?, address2 = ?, city = ?, zipcode = ?, payment_method = ? WHERE id = ?";
    $stmt = $conn->prepare($update_query);
    $stmt->bind_param("ssssssi", $status, $address1, $address2, $city, $zipcode, $payment_method, $id);

    if ($stmt->execute()) {
        header("Location: admin_panel.php?page=orders&message=Order updated successfully");
        exit;
    } else {
        echo "<p>Error updating order.</p>";
    }

    $stmt->close();
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Order</title>
</head>
<body>
    <h2>Edit Order #<?php echo htmlspecialchars($order['id']); ?></h2>
    <form method="POST">
        <label>Status:</label>
        <select name="status">
            <option value="Pending" <?php echo ($order['status'] == 'Pending') ? 'selected' : ''; ?>>Pending</option>
            <option value="Processing" <?php echo ($order['status'] == 'Processing') ? 'selected' : ''; ?>>Processing</option>
            <option value="Shipped" <?php echo ($order['status'] == 'Shipped') ? 'selected' : ''; ?>>Shipped</option>
            <option value="Delivered" <?php echo ($order['status'] == 'Delivered') ? 'selected' : ''; ?>>Delivered</option>
            <option value="Cancelled" <?php echo ($order['status'] == 'Cancelled') ? 'selected' : ''; ?>>Cancelled</option>
        </select>
        <br>

        <label>Address Line 1:</label>
        <input type="text" name="address1" value="<?php echo htmlspecialchars($order['address1']); ?>" required>
        <br>

        <label>Address Line 2:</label>
        <input type="text" name="address2" value="<?php echo htmlspecialchars($order['address2']); ?>">
        <br>

        <label>City:</label>
        <input type="text" name="city" value="<?php echo htmlspecialchars($order['city']); ?>" required>
        <br>

        <label>Zip Code:</label>
        <input type="text" name="zipcode" value="<?php echo htmlspecialchars($order['zipcode']); ?>" required>
        <br>

        <label>Payment Method:</label>
        <select name="payment_method">
            <option value="COD" <?php echo ($order['payment_method'] == 'COD') ? 'selected' : ''; ?>>Cash on Delivery</option>
            <option value="Online" <?php echo ($order['payment_method'] == 'Online') ? 'selected' : ''; ?>>Online Payment</option>
        </select>
        <br>

        <button type="submit">Update Order</button>
        <a href="admin_panel.php?page=orders">Cancel</a>
    </form>
</body>
</html>
