<?php
include('../connections.php');

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);

    $delete_query = "DELETE FROM users WHERE id = ?";
    $stmt = $conn->prepare($delete_query);
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        echo "<script>
                alert('User deleted successfully');
                window.location.href = 'http://localhost/flawsomebeauty/admin_area/admin.php';
              </script>";
    } else {
        echo "<script>
                alert('Failed to delete user');
                window.location.href = 'http://localhost/flawsomebeauty/admin_area/admin.php';
              </script>";
    }

    $stmt->close();
} else {
    echo "<script>
            alert('Invalid request');
            window.location.href = 'http://localhost/flawsomebeauty/admin_area/admin.php';
          </script>";
}
$conn->close();
?>
