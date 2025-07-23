<?php
include('../connections.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $price = $_POST['price'];
    $stock = $_POST['stock'];
    $description = $_POST['description'];
    $image_url = '';

    // Handle image upload
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $target_dir = "images/"; // Changed from uploads/ to images/
        $target_file = $target_dir . basename($_FILES['image']['name']);

        if (move_uploaded_file($_FILES['image']['tmp_name'], "../" . $target_file)) { // Save in /images/
            $image_url = $target_file; // Save only relative path
        }
    }

    // Insert product into database
    $insert_query = "INSERT INTO products (name, price, stock, description, image_url) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($insert_query);
    $stmt->bind_param("sdiss", $name, $price, $stock, $description, $image_url);

    if ($stmt->execute()) {
        echo "<script>
                alert('Product added successfully');
                window.location.href = 'admin.php?page=products';
              </script>";
    } else {
        echo "<script>
                alert('Failed to add product');
                window.location.href = 'admin.php?page=products';
              </script>";
    }
    
    $stmt->close();
    $conn->close();
}
?>

<style>
    
</style>
<!-- Button to open modal -->
<button onclick="document.getElementById('addProductModal').style.display='block'">Add New Product</button>

<!-- Modal -->
<div id="addProductModal" class="modal" style="display:none; position: fixed; top: 50%; left: 50%; transform: translate(-50%, -50%); background: white; padding: 20px; border: 1px solid black; box-shadow: 0px 0px 10px gray;">
    <h2>Add Product</h2>
    <form method="POST" enctype="multipart/form-data">
        <label>Name:</label>
        <input type="text" name="name" required>
        <label>Price:</label>
        <input type="text" name="price" required>
        <label>Stock:</label>
        <input type="text" name="stock" required>
        <label>Description:</label>
        <textarea name="description" required></textarea>
        <label>Product Image:</label>
        <input type="file" name="image" required>
        <button type="submit">Add Product</button>
        <button type="button" onclick="document.getElementById('addProductModal').style.display='none'">Cancel</button>
    </form>
</div>
