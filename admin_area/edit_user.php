<?php
include('../connections.php');

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Fetch user data
    $user_query = "SELECT * FROM users WHERE id = $id";
    $user_result = $conn->query($user_query);
    $user = $user_result->fetch_assoc();

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        // Update user data
        $username = $_POST['username'];
        $email = $_POST['email'];
        $mobilenumber = $_POST['mobilenumber'];

        $update_query = "UPDATE users SET username = '$username', email = '$email', mobilenumber = '$mobilenumber' WHERE id = $id";
        $conn->query($update_query);
        header('Location: admin_panel.php?page=users');
        exit;
    }
}

?>

<form method="POST">
    <label>Username:</label>
    <input type="text" name="username" value="<?php echo $user['username']; ?>" required>
    <label>Email:</label>
    <input type="email" name="email" value="<?php echo $user['email']; ?>" required>
    <label>Phone:</label>
    <input type="text" name="mobilenumber" value="<?php echo $user['mobilenumber']; ?>" required>
    <button type="submit">Update</button>
</form>
