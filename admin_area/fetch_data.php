<?php
include('../connections.php');

// Check if 'page' is set in POST
if (!isset($_POST['page'])) {
    die("<p>Error: Missing page parameter.</p>");
}

$page = $_POST['page'];

if ($page == 'dashboard') {
    // Fetch statistics with error checking
    $total_users_query = "SELECT COUNT(*) AS total_users FROM users";
    $total_users_result = $conn->query($total_users_query) or die("Error: " . $conn->error);
    $total_users = $total_users_result->fetch_assoc()['total_users'] ?? 0;

    $total_products_query = "SELECT COUNT(*) AS total_products FROM products";
    $total_products_result = $conn->query($total_products_query) or die("Error: " . $conn->error);
    $total_products = $total_products_result->fetch_assoc()['total_products'] ?? 0;

    $total_orders_query = "SELECT COUNT(*) AS total_orders FROM orders";
    $total_orders_result = $conn->query($total_orders_query) or die("Error: " . $conn->error);
    $total_orders = $total_orders_result->fetch_assoc()['total_orders'] ?? 0;

    $total_stock_query = "SELECT SUM(stock) AS total_stock FROM products";
    $total_stock_result = $conn->query($total_stock_query) or die("Error: " . $conn->error);
    $total_stock = $total_stock_result->fetch_assoc()['total_stock'] ?? 0;

    echo "
    <h2>Dashboard</h2>
    <div class='dashboard-stats'>
        <div>Total Users: $total_users</div>
        <div>Total Products: $total_products</div>
        <div>Total Orders: $total_orders</div>
        <div>Total Stock: $total_stock</div>
    </div>
    ";
} elseif ($page == 'products') {
    $products_query = "SELECT id, name, image_url, price, stock, description FROM products";
    $products_result = $conn->query($products_query) or die("Error: " . $conn->error);

    echo "<h2>Products</h2>";

    // ADD NEW PRODUCT BUTTON
    echo "<a href='add_products.php' class='btn btn-primary' style='display: inline-block; margin-bottom: 10px; padding: 8px 12px; background-color: #007bff; color: white; text-decoration: none; border-radius: 4px;'>Add New Product</a>";

    echo "<table border='1'>";
    echo "<thead><tr><th>ID</th><th>Name</th><th>Image</th><th>Price</th><th>Stock</th><th>Description</th><th>Actions</th></tr></thead><tbody>";

    while ($product = $products_result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>{$product['id']}</td>";
        echo "<td>{$product['name']}</td>";
        echo "<td><img src='../{$product['image_url']}' alt='Product Image' width='80'></td>";
        echo "<td>{$product['price']}</td>";
        echo "<td>{$product['stock']}</td>";
        echo "<td>{$product['description']}</td>";
        echo "<td>
                <a href='edit_product.php?id={$product['id']}'>Edit</a> | 
                <a href='delete_product.php?id={$product['id']}'>Delete</a>
              </td>";
        echo "</tr>";
    }
    echo "</tbody></table>";
} elseif ($page == 'orders') {
    $orders_query = "
        SELECT orders.id, orders.user_id, orders.product_id, orders.status,
               users.username, users.email, orders.phone1, orders.phone2, 
               orders.address1, orders.address2, orders.city, orders.zipcode, 
               orders.payment_method, orders.order_date
        FROM orders
        LEFT JOIN users ON orders.user_id = users.id
    ";
    $orders_result = $conn->query($orders_query) or die("Error: " . $conn->error);

    echo "<h2>Orders</h2>";
    echo "<table border='1'>";
    echo "<thead><tr><th>ID</th><th>Username</th><th>Email</th><th>Phone1</th><th>Phone2</th><th>Address</th><th>Payment Method</th><th>Order Date</th><th>Status</th><th>Actions</th></tr></thead><tbody>";

    while ($order = $orders_result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>{$order['id']}</td>";
        echo "<td>{$order['username']}</td>";
        echo "<td>{$order['email']}</td>";
        echo "<td>{$order['phone1']}</td>";
        echo "<td>{$order['phone2']}</td>";
        echo "<td>{$order['address1']}, {$order['address2']}, {$order['city']}, {$order['zipcode']}</td>";
        echo "<td>{$order['payment_method']}</td>";
        echo "<td>{$order['order_date']}</td>";
        echo "<td>{$order['status']}</td>";
        echo "<td>
                <a href='edit_orders.php?id={$order['id']}'>Edit</a> | 
                <a href='delete_orders.php?id={$order['id']}'>Delete</a>
              </td>";
        echo "</tr>";
    }
    echo "</tbody></table>";
} elseif ($page == 'users') {
    $users_query = "SELECT id, username, email, mobilenumber FROM users";
    $users_result = $conn->query($users_query) or die("Error: " . $conn->error);

    echo "<h2>Users</h2>";
    echo "<table border='1'>";
    echo "<thead><tr><th>ID</th><th>Username</th><th>Email</th><th>Phone</th><th>Actions</th></tr></thead><tbody>";

    while ($user = $users_result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>{$user['id']}</td>";
        echo "<td>{$user['username']}</td>";
        echo "<td>{$user['email']}</td>";
        echo "<td>{$user['mobilenumber']}</td>";
        echo "<td>
                <a href='edit_user.php?id={$user['id']}'>Edit</a> | 
                <a href='delete_user.php?id={$user['id']}'>Delete</a>
              </td>";
        echo "</tr>";
    }
    echo "</tbody></table>";
}

$conn->close();
?>
