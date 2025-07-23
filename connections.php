<?php
// Set up database connection
$host = 'localhost';  // or your database host
$user = 'root';       // your database username
$password = '';       // your database password
$dbname = "ecom"; // your database name

$conn = new mysqli($host, $user, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

?>
