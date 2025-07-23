<?php
session_start();

// // Ensure admin is logged in
// // Uncomment this when login functionality is in use
// if (!isset($_SESSION['admin_username'])) {
//     header('Location: ../login.php');
//     exit();
// }

include('../connections.php');

// Fetch statistics
$total_users_query = "SELECT COUNT(*) AS total_users FROM users";
$total_users_result = $conn->query($total_users_query);
$total_users = $total_users_result->fetch_assoc()['total_users'] ?? 0;

$total_products_query = "SELECT COUNT(*) AS total_products FROM products";
$total_products_result = $conn->query($total_products_query);
$total_products = $total_products_result->fetch_assoc()['total_products'] ?? 0;

$total_orders_query = "SELECT COUNT(*) AS total_orders FROM orders";
$total_orders_result = $conn->query($total_orders_query);
$total_orders = $total_orders_result->fetch_assoc()['total_orders'] ?? 0;

// Fetch total stock from the stock column
$total_stock_query = "SELECT SUM(stock) AS total_stock FROM products";
$total_stock_result = $conn->query($total_stock_query);
$total_stock = $total_stock_result->fetch_assoc()['total_stock'] ?? 0;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="admin-style.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> <!-- Adding jQuery for simplicity -->
</head>
<body>

    <!-- Sidebar -->
    <div class="sidebar">
        <div class="logo">
            <h2>Admin Panel</h2>
        </div>
        <nav>
            <ul>
                <li><a href="#" onclick="showPage('dashboard')">Dashboard</a></li>
                <li><a href="#" onclick="showPage('products')">Products</a></li>
                <li><a href="#" onclick="showPage('orders')">Orders</a></li>
                <li><a href="#" onclick="showPage('users')">Users</a></li>
                <li><a href="../login.php">Logout</a></li>
            </ul>
        </nav>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <!-- Top Navbar -->
        <div class="navbar">
            <span>Welcome, <?php echo isset($_SESSION['admin_username']) ? $_SESSION['admin_username'] : 'Admin'; ?>!</span>
        </div>

        <!-- Dashboard Stats -->
        <div class="dashboard-stats">
            <div>Total Users: <?php echo $total_users; ?></div>
            <div>Total Products: <?php echo $total_products; ?></div>
            <div>Total Orders: <?php echo $total_orders; ?></div>
            <div>Total Stock: <?php echo $total_stock; ?></div>
        </div>

        <!-- Dynamic Content Section -->
        <div id="content">
            <!-- Default content can go here or a placeholder -->
            <h2>Welcome to Flawsome Beauty  Admin Dashboard</h2>
            <p></p>
        </div>
    </div>

    <script>
        function showPage(page) {
            // Handle dynamic content based on which section is clicked
            $.ajax({
                url: 'fetch_data.php', // PHP script to fetch data
                type: 'POST',
                data: { page: page },
                success: function(response) {
                    $('#content').html(response);
                }
            });
        }
    </script>

</body>
</html>
