<?php
session_start();

// Check if the request is a POST request
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if product_id and quantity are set in the POST data
    if (isset($_POST['product_id']) && isset($_POST['quantity'])) {
        // Retrieve product ID and quantity from POST data
        $product_id = $_POST['product_id'];
        $quantity = $_POST['quantity'];

        // Database connection
        $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "restaurant";

        $conn = new mysqli($servername, $username, $password, $dbname);

        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // Prepare and execute SQL query to update quantity
        $stmt = $conn->prepare("UPDATE cart SET quantity = ? WHERE product_id = ?");
        $stmt->bind_param("ii", $quantity, $product_id);
        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            // Quantity updated successfully
            echo "Quantity updated successfully";
        } else {
            // No rows affected, handle error
            echo "Error updating quantity";
        }

        // Close statement and database connection
        $stmt->close();
        $conn->close();
    } else {
        // If product_id or quantity is not set in the POST data
        echo "Invalid request";
    }
} else {
    // If request method is not POST
    echo "Invalid request method";
}
?>
