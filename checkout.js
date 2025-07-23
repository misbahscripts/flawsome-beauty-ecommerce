document.addEventListener('DOMContentLoaded', function() {
    // Retrieve cart from localStorage
    let cart = JSON.parse(localStorage.getItem('cart')) || [];

    // Function to display cart items on the checkout page with images and total price
    function displayCheckoutCart() {
        const checkoutCartList = document.getElementById('checkout-cart-list');
        checkoutCartList.innerHTML = ''; // Ensure checkout-cart-list exists

        if (cart.length === 0) {
            checkoutCartList.innerHTML = '<li>Your cart is empty.</li>';
        } else {
            let total = 0; // Initialize total

            cart.forEach((item, index) => {
                const li = document.createElement('li');
                const productImage = document.createElement('img');
                productImage.src = item.image_url; // Product image source from cart data
                productImage.alt = item.name;
                productImage.style.width = '50px'; // Adjust size as necessary
                productImage.style.marginRight = '10px'; // Add some space between image and text
                
                li.appendChild(productImage);
                li.appendChild(document.createTextNode(`${item.name} - Rs ${item.price}`));

                // Create remove button
                const removeButton = document.createElement('button');
                removeButton.textContent = 'Remove';
                removeButton.addEventListener('click', () => {
                    cart.splice(index, 1); // Remove the item from the cart
                    localStorage.setItem('cart', JSON.stringify(cart)); // Update localStorage
                    displayCheckoutCart(); // Refresh cart display
                });

                li.appendChild(removeButton); // Add remove button to the list item
                checkoutCartList.appendChild(li);

                total += item.price; // Keep the total calculation intact
            });

            // Append total price to the cart
            const totalLi = document.createElement('li');
            totalLi.textContent = `Total: Rs ${total}`;
            totalLi.style.fontWeight = 'bold'; // Make total bold
            checkoutCartList.appendChild(totalLi);
        }
    }

    // Display cart items when the page loads
    displayCheckoutCart();

    // Back to Products Button
    const backToProductsButton = document.getElementById('back-to-products');
    if (backToProductsButton) {
        backToProductsButton.addEventListener('click', function() {
            window.location.href = 'index.php'; // Adjust URL as necessary
        });
    }

    // Place Order Button
    const placeOrderButton = document.getElementById('place-order');
    if (placeOrderButton) {
        placeOrderButton.addEventListener('click', function(event) {
            // Prevent the form from submitting immediately
            event.preventDefault();

            const form = document.getElementById('checkout-form');
            
            if (form) {
                // Check if form is filled out
                if (!form.checkValidity()) {
                    alert('Please complete all required form fields before placing the order.');
                    return; // Prevent order placement if form is incomplete
                }
                
                // Clear the cart if form is valid
                localStorage.removeItem('cart');
                alert('YOUR ORDER HAS BEEN PLACED. THANK YOU!!');
                window.location.href = 'index.php'; // Redirect to a success page
            } else {
                console.error('Form not found');
            }
        });
    }
});
