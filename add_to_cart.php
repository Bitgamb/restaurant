<?php
session_start(); // Start the session to access session variables

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['product_id'])) {
    // Validate and sanitize the input (in this case, the product ID)
    $product_id = $_POST['product_id'];
    $product_id = filter_var($product_id, FILTER_SANITIZE_NUMBER_INT); // Sanitize as integer

    // Check if the user is logged in (i.e., if the user ID is set in the session)
    if (isset($_SESSION['user_id'])) {
        // Retrieve the user ID from the session
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

        // Prepare and execute the SQL query to insert the product into the cart table with the user ID
        $sql = "INSERT INTO cart (user_id, product_id) VALUES (?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ii", $user_id, $product_id); // 'i' indicates integer data type
        $stmt->execute();

        // Check if the insertion was successful
        if ($stmt->affected_rows > 0) {
            // Product added to cart successfully
            echo "Product added to cart successfully.";
            
        } else {
            // Product could not be added to cart
            echo "Failed to add product to cart.";
        }

        // Close database connection
        $stmt->close();
        $conn->close();
    } else {
        // User is not logged in, redirect to login page or show an error message
        echo "User is not logged in. Please log in first.";
    }
} else {
    // If the form was not submitted via POST or 'product_id' was not provided
    echo "Invalid request.";
}
?>
