<?php
// Check if product ID is set and not empty
if(isset($_POST['id']) && !empty($_POST['id'])) {
    // Database connection parameters
    $servername = "localhost";
    $db_username = "root";
    $db_password = ""; // Assuming empty password
    $database = "restaurant";

    // Create connection
    $conn = new mysqli($servername, $db_username, $db_password, $database);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Prepare a delete statement
    $sql = "DELETE FROM menu WHERE id = ?";

    if($stmt = $conn->prepare($sql)){
        // Bind variables to the prepared statement as parameters
        $stmt->bind_param("i", $param_id);

        // Set parameters
        $param_id = $_POST['id'];

        // Attempt to execute the prepared statement
        if($stmt->execute()){
            // Product deleted successfully
            echo "Product deleted successfully.";
        } else{
            // If execution fails
            echo "Oops! Something went wrong. Please try again later.";
        }
    }

    // Close statement
    $stmt->close();

    // Close connection
    $conn->close();
} else {
    // If product ID is not set or empty
    echo "Invalid product ID.";
}
?>
