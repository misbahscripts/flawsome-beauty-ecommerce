<?php
// Include the database connection
include("connections.php");

ini_set('display_errors', 1);
error_reporting(E_ALL);

// Start session to track user data
session_start();

// Sign-up functionality
if (isset($_POST['signup'])) {
    // Capture form data
    $username = $_POST['username'];
    $email = $_POST['email'];
    $mobile = $_POST['mobileNumber'];
    $password = $_POST['pswd'];

    // Validate mobile number (must be exactly 10 digits)
    if (!preg_match("/^[0-9]{10}$/", $mobile)) {
        echo "<script>alert('Invalid mobile number! Please enter exactly 10 digits.');</script>";
        exit();
    }

    // Validate email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "<script>alert('Invalid email format! Please enter a valid email address.');</script>";
        exit();
    }

    // Assign role based on the email
    $role = (strpos($email, "admin") !== false) ? 'admin' : 'user';

    // Sanitize inputs
    $username = mysqli_real_escape_string($conn, $username);
    $email = mysqli_real_escape_string($conn, $email);
    $mobile = mysqli_real_escape_string($conn, $mobile);
    $password = mysqli_real_escape_string($conn, $password);

    // Hash the password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Check for existing user
    $checkQuery = "SELECT * FROM users WHERE email = '$email' OR mobilenumber = '$mobile'";
    $result = mysqli_query($conn, $checkQuery);

    if (mysqli_num_rows($result) > 0) {
        echo "<script>alert('User already exists. Please log in.');</script>";
    } else {
        // Insert new user into the database with role
        $sql = "INSERT INTO users (username, email, mobilenumber, password, role)
                VALUES ('$username', '$email', '$mobile', '$hashed_password', '$role')";

        if (mysqli_query($conn, $sql)) {
            header("Location: index.php");
            exit();
        } else {
            echo "Error: " . mysqli_error($conn);
        }
    }
}

// Login functionality
if (isset($_POST['login'])) {
    $email = $_POST['email'];
    $password = $_POST['pswd'];

    // Sanitize inputs
    $email = mysqli_real_escape_string($conn, $email);
    $password = mysqli_real_escape_string($conn, $password);

    // Query to fetch user data
    $sql = "SELECT * FROM users WHERE email='$email'";
    $result = mysqli_query($conn, $sql);

    if ($result && mysqli_num_rows($result) > 0) {
        $user = mysqli_fetch_assoc($result);

        // Verify the password
        if (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role'];

            // Redirect based on role
            if ($user['role'] == 'admin') {
                header("Location: ../flawsomebeauty/admin_area/admin.php");
            } else {
                header("Location: index.php");
            }
            exit();
        } else {
            echo "<script>alert('Invalid password!');</script>";
        }
    } else {
        echo "<script>alert('No user found with this email!');</script>";
    }
}

// Close the connection
mysqli_close($conn);
?>

<!DOCTYPE html>
<html>
<head>
  <title>Flawsome Login/Signup</title>
  <link rel="stylesheet" type="text/css" href="login.css">
  <link href="https://fonts.googleapis.com/css2?family=Jost:wght@500&display=swap" rel="stylesheet">
  <script>
    // JS to limit mobile number input to 10 digits live
    function validateMobileInput(e) {
      const input = e.target;
      // Remove non-digit characters
      input.value = input.value.replace(/\D/g, '');
      // Limit length to 10
      if (input.value.length > 10) {
        input.value = input.value.slice(0, 10);
      }
    }
    window.addEventListener('DOMContentLoaded', () => {
      const mobileInput = document.querySelector('input[name="mobileNumber"]');
      if (mobileInput) {
        mobileInput.addEventListener('input', validateMobileInput);
      }
    });
  </script>
</head>
<body>

<div class="main">
    <input type="checkbox" id="chk" aria-hidden="true">

    <!-- Sign-up form -->
    <div class="signup">
        <form method="POST" novalidate>
            <label for="chk" aria-hidden="true">Sign up</label>
            <input 
                type="text" 
                name="username" 
                placeholder="User name" 
                required 
                autocomplete="off"
            />
            <input 
                type="email" 
                name="email" 
                placeholder="Email" 
                required 
                autocomplete="off"
            />
            <input 
                type="text" 
                name="mobileNumber" 
                placeholder="Mobile Number" 
                required 
                pattern="\d{10}" 
                maxlength="10" 
                title="Please enter exactly 10 digits"
                autocomplete="off"
            />
            <input 
                type="password" 
                name="pswd" 
                placeholder="Password" 
                required
                autocomplete="off"
            />
            <button type="submit" name="signup"> Sign Up</button>
        </form>
    </div>

    <!-- Login form -->
    <div class="login">
        <form method="POST" novalidate>
            <label for="chk" aria-hidden="true">Login</label>
            <input 
                type="email" 
                name="email" 
                placeholder="Email" 
                required
                autocomplete="off"
            />
            <input 
                type="password" 
                name="pswd" 
                placeholder="Password" 
                required
                autocomplete="off"
            />
            <button type="submit" name="login">Login</button>
        </form>
    </div>
</div>

<script src="login.js?v=1" defer></script>

</body>
</html>
