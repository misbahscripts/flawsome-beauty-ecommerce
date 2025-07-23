<?php
session_start();
session_unset();
session_destroy();
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title>Logging Out...</title>
<style>
  /* Reset and base */
  * {
    margin: 0; padding: 0; box-sizing: border-box;
  }
  html {
    scroll-behavior: smooth;
  }
  body {
    background: linear-gradient(135deg, #FABFDF, #EBA1B4);
    color: #740E28;
    font-family: sans-serif;
    margin: 10px;
    padding: 10px;
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
  }
  .logout-message {
    background: #FABFDF;
    border: 4px solid #EBA1B4;
    border-radius: 15px;
    padding: 30px 40px;
    max-width: 400px;
    text-align: center;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.3);
    font-weight: bold;
    font-size: 1.6em;
    text-shadow: 2px 2px 3px rgba(0, 0, 0, 0.2);
    font-family: 'Brush Script MT', cursive;
  }
  .logout-message p {
    margin-top: 12px;
    font-size: 1em;
    color: #740E28;
    font-weight: normal;
    text-shadow: none;
  }
</style>
<meta http-equiv="refresh" content="2;url=login.php" />
</head>
<body>
  <div class="logout-message">
    You have successfully logged out.
    <p>Redirecting to login page...</p>
  </div>
</body>
</html>
