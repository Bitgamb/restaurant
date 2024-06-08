<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="res/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <!-- Include jQuery library -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
</head>

<body>

    <!-- Nav -->
    <?php require 'res/_nav.php' ?>

    <div class="cart">
    <?php
session_start(); // Start the session to access session variables

// Initialize total price variable
$total_price_all = 0;

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    // Redirect the user to the login page or display an error message
    header("Location: login.php");
    exit(); // Stop further execution
}

// Get the user ID from the session
$user_id = $_SESSION['user_id'];

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "restaurant";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// SQL query to retrieve cart items with product details, image path, and quantity
$sql = "SELECT cart.product_id, menu.item_name, menu.price, menu.image_path, cart.quantity
        FROM cart
        JOIN menu ON cart.product_id = menu.id
        WHERE cart.user_id = $user_id";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // Display cart items
    while ($row = $result->fetch_assoc()) {
        // Calculate total price for each item
        $total_price_item = $row['price'] * $row['quantity'];
        $total_price_all += $total_price_item; // Add to total price of all items

        echo '<div class="card mb-3" style="max-width: 540px;" data-product-id="' . $row['product_id'] . '">
                <div class="row g-0">
                    <div class="col-md-4">
                        <img src="' . $row['image_path'] . '" class="img-fluid rounded-start" alt="...">
                    </div>
                    <div class="col-md-8">
                        <div class="card-body">
                            <h5 class="card-title">' . $row['item_name'] . '</h5>
                            <p class="card-text">Rs.' . $total_price_item . '</p> <!-- Display total price -->
                            <div class="counter-container">
                                <button class="counter-btn decrementBtn">-</button>
                                <span class="quantity">' . $row['quantity'] . '</span>
                                <button class="counter-btn incrementBtn">+</button>
                            </div>
                            <button class="btn btn-danger deleteBtn">Delete</button>
                        </div>
                    </div>
                </div>
            </div>';
    }

    // Store total price in session
    $_SESSION['totalPrice'] = $total_price_all;

    // Display total price of all items
    echo '<p>Total Price: Rs.' . $total_price_all . '</p>';
} else {
    echo "Your cart is empty.";
}

$conn->close();
?>


        <div class="d-grid gap-2 col-6 mx-auto">
            <a href="checkout.php"><button class="btn btn-primary" type="button" id="checkout_btn">Checkout</button></a>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            $('.incrementBtn').click(function() {
                var $button = $(this);
                var $quantityElement = $button.siblings('.quantity');
                var oldValue = parseInt($quantityElement.text()); // Parse as integer
                var priceElement = $button.closest('.card').find('.card-text');
                var unitPrice = parseFloat(priceElement.text().replace('Rs.', ''));
                var newValue = oldValue + 1;
                var totalPrice = unitPrice * newValue;

                $quantityElement.text(newValue); // Update quantity
                updateQuantity($button, newValue);
            });

            $('.decrementBtn').click(function() {
                var $button = $(this);
                var $quantityElement = $button.siblings('.quantity');
                var oldValue = parseInt($quantityElement.text()); // Parse as integer
                if (oldValue > 1) {
                    var priceElement = $button.closest('.card').find('.card-text');
                    var unitPrice = parseFloat(priceElement.text().replace('Rs.', ''));
                    var newValue = oldValue - 1;
                    var totalPrice = unitPrice * newValue;

                    $quantityElement.text(newValue); // Update quantity
                    updateQuantity($button, newValue);
                }
            });

            function updateQuantity(button, newQuantity) {
                var productId = button.closest('.card').data('product-id');
                // Send an AJAX request to update the quantity in the database
                $.ajax({
                    url: 'update_quantity.php',
                    method: 'POST',
                    data: {
                        product_id: productId,
                        quantity: newQuantity
                    },
                    success: function(response) {
                        console.log(response); // Log response for debugging
                    },
                    error: function(xhr, status, error) {
                        console.error(error); // Log error for debugging
                    }
                });
            }
        });

        $(document).ready(function() {
    // Increment and decrement functions

    // Delete button click event
    $('.deleteBtn').click(function() {
        var $button = $(this);
        var productId = $button.closest('.card').data('product-id');
        // Send an AJAX request to delete the product from the cart
        $.ajax({
            url: 'delete_from_cart.php',
            method: 'POST',
            data: {
                product_id: productId
            },
            success: function(response) {
                // Remove the card from the DOM
                $button.closest('.card').remove();
                console.log(response); // Log response for debugging
            },
            error: function(xhr, status, error) {
                console.error(error); // Log error for debugging
            }
        });
    });
});

    </script>

    <!-- footer -->
    <?php require 'res/_footer.php' ?>

</body>

</html>
