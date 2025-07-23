<?php
include 'connections.php'; // Include your database connection file
session_start();

// Initialize $user_id from session if available
$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;




// Initialize cart if not already present in session
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Handle add to cart action
if (isset($_POST['addToCart'])) {
    $productId = $_POST['productId'];
    $productName = $_POST['productName'];
    $productPrice = $_POST['productPrice'];
    $productImage = $_POST['productImage'];
    $quantity = $_POST['quantity'];

    // Check if the product is already in the cart
    $productFound = false;
    foreach ($_SESSION['cart'] as &$cartItem) {
        if ($cartItem['id'] == $productId) {
            $cartItem['quantity'] += $quantity; // Increase quantity if product is already in cart
            $productFound = true;
            break;
        }
    }

    // If product not found, add a new product to the cart
    if (!$productFound) {
        $_SESSION['cart'][] = [
            'id' => $productId,
            'name' => $productName,
            'price' => $productPrice,
            'image' => $productImage,
            'quantity' => $quantity,
        ];
    }
    echo "<script>alert('Product added to cart!');</script>";
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Flawsome Beauty Products</title>
    <link rel="stylesheet" href="styless.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>
<body>
   
    <!-- Main Content -->
    <div class="main-content">

        <div class="hero-container">
            <div class="hero-image"></div>
            <div class="hero-image"></div>
            <div class="hero-image"></div>
        </div>
      
        <div class="tab-navigation">
  <a href="#products" class="tab-link" title="Home">🏠</a>
  <a href="#about-section" class="tab-link" title="About">💡</a>
  <a href="#contact-section" class="tab-link" title="Contact">✉️</a>
  <a href="checkout.php" class="tab-link" title="Checkout">🚚</a>
  <a href="orders.php" class="tab-link" title="Orders">📦</a>
  <a href="cart.php" class="tab-link" title="Cart">🛒</a>

  <!-- Session check for login/logout -->
  <?php if (isset($_SESSION['user_id'])) { ?>
    <a href="logout.php" class="tab-link logout-tab" title="Logout">🚪 Logout</a>
  <?php } else { ?>
    <a href="login.php" class="tab-link" title="Login">🤳🏾 Login</a>
  <?php } ?>
</div>


        <div id="notification-area" class="notification-area d-flex flex-column align-items-center"></div>
       
        <section id="products">
            <h2>Our Products</h2>
            <div class="product-grid">
                <?php
               // Fetch products from the database
$query = "SELECT * FROM products";
$result = $conn->query($query);

if ($result->num_rows > 0) {
    // Loop through the products and display them
    while ($row = $result->fetch_assoc()) {
        echo '<div class="product-card">
                <img src="' . $row['image_url'] . '" alt="' . $row['name'] . '">
                <h3>' . $row['name'] . '</h3>
                <p>Price: Rs ' . $row['price'] . '</p>';
        
        // Add to Cart Form
        echo '<form action="add-to-cart.php" method="POST" onsubmit="return checkLogin()">
        <input type="hidden" name="product_id" value="' . $row["id"] . '">
        <input type="hidden" name="product_name" value="' . $row["name"] . '">
        <input type="hidden" name="product_price" value="' . $row["price"] . '">
        <input type="hidden" name="product_image" value="' . $row["image_url"] . '">
        <input type="number" name="quantity" value="1" min="1" max="10">
        <button type="submit" name="addToCart">Add to Cart</button>
    </form>';



        echo '</div>'; // End of product card
    }
} else {
    echo "No products found.";
}
?>
            </div>
        </section>
    </div>

    <script>
        function checkLogin() {
            <?php if (!$user_id): ?>
                alert("User not logged in! Please log in to add products.");
                return false; // Prevent the form submission
            <?php else: ?>
                return true; // Allow the form submission if logged in
            <?php endif; ?>
        }
    </script>
 



        <footer class="site-footer">
            <div class="footer-content">
                <div id="about-section" class="about-section">
                    <h2>About Us</h2>
                    <p>Flawsome Beauty, where flaws are awesome...</p>
                </div>
                <div id="contact-section" class="contact-section">
                    <h2>Contact Us</h2>
                    <p>Email: contact@flawsomebeauty.com</p>
                </div>
            </div>
        </footer>

    </div>

    <!-- Chatbot Integration -->
    <div id="chatbot-button">
        <button onclick="toggleChatbot()">Chat with us!</button>
    </div>
    <div id="landbot-container" style="display: none;"></div>
    <script>
        window.addEventListener('mouseover', initLandbot, { once: true });
        window.addEventListener('click', initLandbot, { once: true });
        var myLandbot;
        function initLandbot() {
            if (!myLandbot) {
                var s = document.createElement('script'); s.type = 'text/javascript'; s.async = true;
                s.addEventListener('load', function() {
                    myLandbot = new Landbot.Livechat({
                        configUrl: 'https://storage.googleapis.com/landbot.online/v3/H-2658412-IIB8VD6W79983FDQ/index.json',
                    });
                });
                s.src = 'https://cdn.landbot.io/landbot-3/landbot-3.0.0.js';
                var x = document.getElementsByTagName('script')[0];
                x.parentNode.insertBefore(s, x);
            }
        }
        function toggleChatbotPopup() {
            var popup = document.getElementById('chatbot-popup');
            popup.style.display = (popup.style.display === 'block') ? 'none' : 'block';
        }

        function toggleChatbot() {
            var landbot = document.getElementById('landbot-container');
            landbot.style.display = (landbot.style.display === 'none') ? 'block' : 'none';
            toggleChatbotPopup(); // Hide popup when chat is open
        }
    </script>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script src="script.js"></script>
</body>
</html>
