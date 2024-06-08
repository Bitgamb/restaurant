<?php
session_start();

// Database connection parameters
$servername = "localhost";
$username = "root";
$password = "";
$database = "restaurant";

// Create connection
$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if session variables are set and not empty
if (!isset($_SESSION['totalPrice']) || !isset($_SESSION['user_id']) || empty($_SESSION['totalPrice']) || empty($_SESSION['user_id'])) {
    echo "Session variables are not set or empty.";
    exit();
}

// Get total price and user ID from the session
$total_price = $_SESSION['totalPrice'];
$user_id = $_SESSION['user_id'];

// Get payment response from Razorpay
$response = file_get_contents('php://input');
$payment_response = json_decode($response);

// Check if payment is successful
if (isset($payment_response->razorpay_payment_id) && !empty($payment_response->razorpay_payment_id)) {
    // Payment successful
    $payment_id = $payment_response->razorpay_payment_id;
    $status = 'success';

    // Get product details for the user
    $product_details = array();
    $cart_sql = "SELECT product_id, quantity FROM cart WHERE user_id = ?";
    $cart_stmt = $conn->prepare($cart_sql);
    $cart_stmt->bind_param("i", $user_id);
    $cart_stmt->execute();
    $cart_result = $cart_stmt->get_result();

    // Check if cart items exist for the user
if ($cart_result->num_rows > 0) {
    while ($row = $cart_result->fetch_assoc()) {
        $product_id = $row['product_id'];
        $quantity = $row['quantity'];

        // Reduce the quantity of the product in the menu table
        $reduce_quantity_sql = "UPDATE menu SET quantity = quantity - ? WHERE id = ?";
        $reduce_quantity_stmt = $conn->prepare($reduce_quantity_sql);
        $reduce_quantity_stmt->bind_param("ii", $quantity, $product_id);

        if ($reduce_quantity_stmt->execute()) {
            // Quantity reduced successfully
            echo "Quantity reduced for product ID: " . $product_id . "<br>";
        } else {
            // Error reducing quantity
            echo "Error reducing quantity for product ID: " . $product_id . "<br>";
        }

        $product_details[] = $product_id . ':' . $quantity; // Concatenate product_id and quantity
    }

    // Convert product details array to comma-separated string
    $product_details_string = implode(',', $product_details);

    // Insert order details into the database
    $order_sql = "INSERT INTO orders (user_id, payment_id, amount, product_details, created_at) VALUES (?, ?, ?, ?, NOW())";
    $order_stmt = $conn->prepare($order_sql);
    $order_stmt->bind_param("isds", $user_id, $payment_id, $total_price, $product_details_string);

    // Execute the insert query
    if ($order_stmt->execute()) {
        // Order details saved successfully
        echo "Order details saved successfully";

        // Delete cart items based on user ID
        $delete_cart_sql = "DELETE FROM cart WHERE user_id = ?";
        $delete_cart_stmt = $conn->prepare($delete_cart_sql);
        $delete_cart_stmt->bind_param("i", $user_id);

        if ($delete_cart_stmt->execute()) {
            // Cart items deleted successfully
            echo "Cart items deleted successfully";
        } else {
            // Error deleting cart items
            echo "Error deleting cart items: " . $delete_cart_stmt->error;
        }

        // Redirect to booked.php
        header("Location: booked.php");
        exit();
    } else {
        // Error saving order details
        echo "Error saving order details: " . $order_stmt->error;
    }
}
 else {
        echo "No items in the cart.";
    }
} else {
    // Payment failed
    // Handle payment failure scenario here
    header("Location: ../pages/payment_failed.php");
    exit();
}
?>
