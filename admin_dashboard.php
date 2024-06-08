<?php
session_start();

// Authentication check
if (!isset($_SESSION['admin_authenticated']) || $_SESSION['admin_authenticated'] !== true) {
    header("Location: admin_login.php");
    exit();
}

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

// Fetch orders from the database
$sql = "SELECT orders.*, address.address, address.contact_number
        FROM orders
        LEFT JOIN address ON orders.user_id = address.user_id"; // Assuming the table name is `order`
$result = $conn->query($sql);

// Check if any orders exist
if ($result->num_rows > 0) {
    echo "<div class='container-fluid'>";
    echo "<h2>Orders</h2>";
    echo "<table class='table'>";
    echo "<thead>";
    echo "<tr>";
    echo "<th>Order ID</th>";
    echo "<th>User ID</th>";
    echo "<th>Contact Number</th>"; // New column for contact number
    echo "<th>Address</th>"; // New column for address
    
    echo "<th>Product Details</th>";
    echo "<th>Total Price</th>";
    echo "</tr>";
    echo "</thead>";
    echo "<tbody>";
    // Output data of each row
    while ($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . $row["id"] . "</td>";
        echo "<td>" . $row["user_id"] . "</td>";
        echo "<td>" . $row["contact_number"] . "</td>"; // Display contact number
        echo "<td>" . $row["address"] . "</td>"; // Display address
       
        echo "<td>";
        // Fetch product details
        $product_details = explode(',', $row["product_details"]);
        $product_names = array();
        foreach ($product_details as $product_detail) {
            list($product_id, $quantity) = explode(':', $product_detail);
            $product_sql = "SELECT item_name FROM menu WHERE id = ?";
            $product_stmt = $conn->prepare($product_sql);
            $product_stmt->bind_param("i", $product_id);
            $product_stmt->execute();
            $product_result = $product_stmt->get_result();
            if ($product_result->num_rows > 0) {
                $product_data = $product_result->fetch_assoc();
                $product_name = $product_data['item_name'];
                $product_names[] = $product_name . " (Quantity: " . $quantity . ")";
            }
        }
        echo implode(", ", $product_names);
        echo "</td>";
        echo "<td>" . $row["amount"] . "</td>";
        echo "</tr>";
    }
    echo "</tbody>";
    echo "</table>";
    echo "</div>";
} else {
    echo "No orders found.";
}

// Close connection

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <!-- Custom styles -->
    <style>
        .container-fluid {
            padding-top: 20px;
        }
        .table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .table th, .table td {
            border: 1px solid #dee2e6;
            padding: 8px;
            text-align: left;
        }
        .table th {
            background-color: #f8f9fa;
            font-weight: bold;
        }
        .card-img-top {
        max-height: 150px; /* Adjust the max-height as needed */
        object-fit: contain; /* Keep the aspect ratio */
    }
    </style>
</head>
<body>

<!-- Navigation bar -->
<nav class="navbar navbar-expand-lg bg-body-tertiary sticky-top">
    <!-- Navbar content -->
</nav>

<!-- Admin Dashboard Content -->
<div class="container-fluid">
    <!-- Product Management Section -->
    <div class="row">
        <div class="col-md-6">
            <h3>Product Management</h3>
            <!-- Product Form -->
            <form action="product_management.php" method="post" enctype="multipart/form-data">
                <div class="mb-3">
                    <label for="item_name" class="form-label">Item Name</label>
                    <input type="text" class="form-control" id="item_name" name="item_name" required>
                </div>
                <div class="mb-3">
                <label for="category" class="form-label">Category</label>
            <!-- Replace the text input with a dropdown select element -->
            <select class="form-select" id="category" name="category" required>
                <option value="">Select Category</option>
                <?php
                // Fetch and display unique category options
                $categories_sql = "SELECT DISTINCT category FROM menu";
                $categories_result = $conn->query($categories_sql);
                if ($categories_result->num_rows > 0) {
                    while ($category_row = $categories_result->fetch_assoc()) {
                        echo "<option value='" . $category_row['category'] . "'>" . $category_row['category'] . "</option>";
                    }
                }
                ?>
            </select>
        </div>
                <div class="mb-3">
                    <label for="price" class="form-label">Price</label>
                    <input type="number" class="form-control" id="price" name="price" required>
                </div>
                <div class="mb-3">
                    <label for="image" class="form-label">Image</label>
                    <input type="file" class="form-control" id="image" name="image" accept="image/*" required>
                </div>
                <button type="submit" class="btn btn-primary">Add Product</button>
            </form>
        </div>
        <div class="col-md-6">
            <!-- Product List with Filter -->
            <h3>Product List</h3>
            <!-- Category Filter Dropdown -->
            <div class="mb-3">
                <label for="categoryFilter" class="form-label">Filter by Category:</label>
                <select id="categoryFilter" class="form-select" onchange="filterProducts(this.value)">
                    <option value="">All</option>
                    <?php
                    // Fetch and display unique category options
                    $categories_sql = "SELECT DISTINCT category FROM menu";
                    $categories_result = $conn->query($categories_sql);
                    if ($categories_result->num_rows > 0) {
                        while ($category_row = $categories_result->fetch_assoc()) {
                            echo "<option value='" . $category_row['category'] . "'>" . $category_row['category'] . "</option>";
                        }
                    }
                    ?>
                </select>
            </div>
            <!-- Product Cards -->
<div class="row row-cols-1 row-cols-md-2 g-4" id="productList">
    <?php
    // Fetch and display product data in cards
    $menu_sql = "SELECT * FROM menu";
    $menu_result = $conn->query($menu_sql);
    if ($menu_result->num_rows > 0) {
        while ($row = $menu_result->fetch_assoc()) {
            echo "<div class='col' data-category='" . $row['category'] . "'>";
            echo "<div class='card' style='max-width: 300px;'>"; // Added max-width for card
            echo "<img src='" . $row['image_path'] . "' class='card-img-top' alt='" . $row['item_name'] . "'>";
            echo "<div class='card-body'>";
            echo "<h5 class='card-title'>" . $row['item_name'] . "</h5>";
            echo "<p class='card-text'>Category: " . $row['category'] . "</p>";
            echo "<p class='card-text'>Price: ₹" . $row['price'] . "</p>";
            echo "</div>";
            echo "<div class='card-footer'>";
            echo "<button class='btn btn-primary' onclick='editProduct(" . $row['id'] . ", " . $row['price'] . ")'>Edit</button>"; // Edit button
            echo "<button class='btn btn-danger ms-2' onclick='deleteProduct(" . $row['id'] . ")'>Delete</button>"; // Delete button
            echo "</div>";
            echo "</div>";
            echo "</div>";
        }
    } else {
        echo "<p>No products found.</p>";
    }
    ?>
</div>

        </div>
    </div>
</div>




<script>
    // JavaScript function to filter products by category
    function filterProducts(category) {
        const productList = document.getElementById("productList");
        const products = productList.getElementsByClassName("col");
        for (let i = 0; i < products.length; i++) {
            const productCategory = products[i].getAttribute("data-category");
            if (category === "" || productCategory === category) {
                products[i].style.display = "block";
            } else {
                products[i].style.display = "none";
            }
        }
    }
</script>



<!-- Bootstrap JS and additional scripts -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-QB6PRuhUacn5B9cixBgtJvYsrJ8tI7IP2hkcYL7jvO1gjRzFJzYqPkk9aBDJfl2x" crossorigin="anonymous"></script>
<!-- Additional scripts -->
<script>
    // Add your JavaScript code here
</script>
</body>
</html>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-QB6PRuhUacn5B9cixBgtJvYsrJ8tI7IP2hkcYL7jvO1gjRzFJzYqPkk9aBDJfl2x" crossorigin="anonymous"></script>
<!-- Additional scripts -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script>
   // JavaScript function to populate form fields and open the edit modal
   function editProduct(productId) {
    // Redirect to edit product details page with product ID as query parameter
    window.location.href = 'edit_product_details.php?id=' + productId;
}

// Submit edit form
$('#editForm').submit(function(e) {
    e.preventDefault();
    var formData = $(this).serialize();
    $.ajax({
        url: 'update_product.php', // Replace with the actual URL to update product details
        type: 'POST',
        data: formData,
        success: function(response) {
            // Handle success, close modal or update UI
            $('#editModal').modal('hide');
            // You may need to update the product list or UI dynamically
            location.reload(); // Reload the page for simplicity
        },
        error: function(xhr, status, error) {
            console.error(xhr.responseText);
        }
    });
});


    // Function to handle deleting a product
    function deleteProduct(productId) {
        // Confirm deletion
        if (confirm("Are you sure you want to delete this product?")) {
            // Send AJAX request to delete the product
            $.ajax({
                url: 'delete_product.php', // Replace with the actual URL to delete product
                type: 'POST',
                data: { id: productId },
                success: function(response) {
                    // Handle success, refresh the product list or update UI
                    location.reload(); // Reload the page for simplicity
                },
                error: function(xhr, status, error) {
                    console.error(xhr.responseText);
                }
            });
        }
    }
</script>