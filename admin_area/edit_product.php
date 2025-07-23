<?php
include('../connections.php');

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Fetch product data
    $product_query = "SELECT * FROM products WHERE id = $id";
    $product_result = $conn->query($product_query);
    $product = $product_result->fetch_assoc();

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        // Update product data
        $name = $_POST['name'];
        $price = $_POST['price'];
        $stock = $_POST['stock'];
        $description = $_POST['description'];

        // Optional: Handle image upload if necessary

        $update_query = "UPDATE products SET name = '$name', price = '$price', stock = '$stock', description = '$description' WHERE id = $id";
        $conn->query($update_query);
        header('Location: admin_panel.php?page=products');
        exit;
    }
}

?>

<form method="POST" enctype="multipart/form-data">
    <label>Name:</label>
    <input type="text" name="name" value="<?php echo $product['name']; ?>" required>
    <label>Price:</label>
    <input type="text" name="price" value="<?php echo $product['price']; ?>" required>
    <label>Stock:</label>
    <input type="text" name="stock" value="<?php echo $product['stock']; ?>" required>
    <label>Description:</label>
    <textarea name="description" required><?php echo $product['description']; ?></textarea>
    <!-- Optional: Add image upload field -->
    <button type="submit">Update</button>
</form>
