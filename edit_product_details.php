
<?php
// edit_product_details.php

// Include database connection code or any necessary functions
// Example: include 'db_connection.php';

// Check if product ID is provided in the URL
if(isset($_GET['id'])) {
    $productId = $_GET['id'];

    // Establish database connection
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "restaurant";

    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Fetch product details from the database using $productId
    $sql = "SELECT * FROM menu WHERE id = $productId";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $product = $result->fetch_assoc();
    } else {
        echo "Product not found.";
        exit();
    }

    // Close database connection
    $conn->close();

    // Check if the form is submitted for updating product details
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Retrieve form data
        $newItemName = $_POST['item_name'];
        $newCategory = $_POST['category'];
        $newPrice = $_POST['price'];
        $newQuantity = $_POST['quantity'];

        // Establish database connection
        $conn = new mysqli($servername, $username, $password, $dbname);

        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // Update product details in the database
        $updateSql = "UPDATE menu SET item_name = '$newItemName', category = '$newCategory', price = $newPrice, quantity = $newQuantity WHERE id = $productId";

        if ($conn->query($updateSql) === TRUE) {
            $successMessage = "Product details updated successfully!";
            // Refresh product data after update
            $product['item_name'] = $newItemName;
            $product['category'] = $newCategory;
            $product['price'] = $newPrice;
            $product['quantity'] = $newQuantity;
        } else {
            echo "Error updating product details: " . $conn->error;
        }

        // Close database connection
        $conn->close();
    }
} else {
    // If product ID is not provided, redirect back to the product list page or display an error message
    header("Location: product_list.php"); // Redirect to product list page
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Product Details</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>
<body>
<div class="container">
    <h2>Edit Product</h2>
    <?php if(isset($successMessage)) { ?>
        <div class="alert alert-success" role="alert">
            <?php echo $successMessage; ?>
        </div>
    <?php } ?>
    <form method="post" enctype="multipart/form-data">
        <div class="mb-3">
            <label for="item_name" class="form-label">Item Name</label>
            <input type="text" class="form-control" id="item_name" name="item_name" value="<?php echo $product['item_name']; ?>" required>
        </div>
        <div class="mb-3">
            <label for="category" class="form-label">Category</label>
            <input type="text" class="form-control" id="category" name="category" value="<?php echo $product['category']; ?>" required>
        </div>
        <div class="mb-3">
            <label for="price" class="form-label">Price</label>
            <input type="number" class="form-control" id="price" name="price" value="<?php echo $product['price']; ?>" required>
        </div>
        <div class="mb-3">
            <label for="quantity" class="form-label">Quantity</label>
            <input type="number" class="form-control" id="quantity" name="quantity" value="<?php echo $product['quantity']; ?>" required>
        </div>
        <button type="submit" class="btn btn-primary">Update Product</button>
    </form>
</div>
</body>
</html>
