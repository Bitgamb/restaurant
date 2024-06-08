<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="res/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
    </script>
     <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        // Function to handle adding to cart via AJAX
        function addToCart(product_id) {
            $.ajax({
                type: "POST",
                url: "add_to_cart.php",
                data: { product_id: product_id },
                success: function(response) {
                    alert(response); // Show response message
                },
                error: function(xhr, status, error) {
                    console.error(xhr.responseText); // Log error message
                }
            });
        }
    </script>
    
  <script>
    // Function to handle smooth scrolling when a navigation link is clicked
    $(document).ready(function() {
        $("a.nav-link").on('click', function(event) {
            event.preventDefault();
            var target = $(this).attr('href');
            $('.section').hide(); // Hide all sections
            $(target).fadeIn(); // Show the target section
            
        });

        // Show the veg section by default
        $('#veg').show();
    });
</script>
</head>
<body>

<nav class="navbar navbar-expand-lg bg-body-tertiary sticky-top">
    <div class="container-fluid">
        <a class="navbar-brand" id="res" href="zipzup/project/index.php"><b>@Restaurant</b></a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent"
            aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item" id="bar">
                    <a class="nav-link active" aria-current="page" href="#veg">Veg</a>
                </li>

                <li class="nav-item" id="bar">
                    <a class="nav-link active" aria-current="page" href="#non_veg">Non-veg</a>
                </li>

                <li class="nav-item" id="bar">
                    <a class="nav-link active" aria-current="page" href="#beverage">Beverage</a>
                </li>

                <li class="nav-item" id="bar">
                    <a class="nav-link active" aria-current="page" href="#dessert">Desert</a>
                </li>

                <li class="nav-item" id="bar">
    <a class="nav-link active" aria-current="page" href="#" onclick="redirectToCart()"> 
        <img src="assets/cart.svg" id="cart" alt="Cart">
    </a>
</li>

            </ul>
        </div>
    </div>
</nav>

<?php
require_once 'config.php';

$sql = "SELECT * FROM menu";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    echo '<div class="veg section" id="veg" style="display: none;">';
    echo '<h1 id="menu1"><b><i>Veg</i></b></h1>';
    echo '<div class="container text-center">';
    echo '<div class="row">';

    while ($row = $result->fetch_assoc()) {
        if ($row['category'] == 'Veg') {
            echo '<div class="col">';
            echo '<div class="card" style="width: 18rem">';
            echo '<img src="' . $row['image_path'] . '" class="card-img-top" alt="..." height="200rem">';
            echo '<div class="card-body" >';
            echo '<h5 class="card-title">' . $row['item_name'] . '</h5>';
            echo '<p class="card-text" id="box-4-price">' . $row['price'] . '</p>';
            // Check if the quantity is greater than 0
        if ($row['quantity'] > 0) {
            // Allow adding to cart
            echo '<button class="btn btn-danger" onclick="addToCart(' . $row['id'] . ')">Add To Cart</button>';
        } else {
            // Display "Out of Stock" and disable the button
            echo '<button class="btn btn-secondary" disabled>Out of Stock</button>';
        }
        echo '</div>';
            echo '</div>';
            echo '</div>';
        }
    }

    echo '</div>';
    echo '</div>';
    echo '</div>';
} else {
    echo "0 results";
}

// SQL query to select data from database
$sql = "SELECT * FROM menu WHERE category = 'Non Veg' ORDER BY id DESC";
$result = $conn->query($sql);

// Display the items in an HTML table
if ($result->num_rows > 0) {
    echo '<div class="non-veg section" id="non_veg" style="display: none;">';
    echo '<h1 id="menu1"><b><i>Non-veg</i></b></h1>';
    echo '<div class="container text-center">';
    echo '<div class="row">';

    while ($row = $result->fetch_assoc()) {
        echo '<div class="col">';
        echo '<div class="card" style="width: 18rem">';
        echo '<img src="' . $row['image_path'] . '" class="card-img-top" alt="..." height="200rem">';
        echo '<div class="card-body" >';
        echo '<h5 class="card-title">' . $row['item_name'] . '</h5>';
        echo '<p class="card-text" id="box-4-price">' . $row['price'] . '</p>';
        // Check if the quantity is greater than 0
        if ($row['quantity'] > 0) {
            // Allow adding to cart
            echo '<button class="btn btn-danger" onclick="addToCart(' . $row['id'] . ')">Add To Cart</button>';
        } else {
            // Display "Out of Stock" and disable the button
            echo '<button class="btn btn-secondary" disabled>Out of Stock</button>';
        }
        echo '</div>';
        echo '</div>';
        echo '</div>';
    }

    echo '</div>';
    echo '</div>';
    echo '</div>';
} else {
    echo "0 results";
}
$sql = "SELECT * FROM menu WHERE category = 'Beverage' ORDER BY id DESC";
$result = $conn->query($sql);

// Display the items in an HTML table
if ($result->num_rows > 0) {
    echo '<div class="beverage section" id="beverage" style="display: none;">';
    echo '<h1 id="menu1"><b><i>Beverages</i></b></h1>';
    echo '<div class="container text-center">';
    echo '<div class="row">';

    while ($row = $result->fetch_assoc()) {
        echo '<div class="col">';
        echo '<div class="card" style="width: 18rem">';
        echo '<img src="' . $row['image_path'] . '" class="card-img-top" alt="..." height="200rem">';
        echo '<div class="card-body" >';
        echo '<h5 class="card-title">' . $row['item_name'] . '</h5>';
        echo '<p class="card-text" id="box-4-price">' . $row['price'] . '</p>';
        // Check if the quantity is greater than 0
        if ($row['quantity'] > 0) {
            // Allow adding to cart
            echo '<button class="btn btn-danger" onclick="addToCart(' . $row['id'] . ')">Add To Cart</button>';
        } else {
            // Display "Out of Stock" and disable the button
            echo '<button class="btn btn-secondary" disabled>Out of Stock</button>';
        }
        echo '</div>';
        echo '</div>';
        echo '</div>';
    }

    echo '</div>';
    echo '</div>';
    echo '</div>';
} else {
    echo "0 results";
}

$sql = "SELECT * FROM menu WHERE category = 'Dessert' ORDER BY id DESC";
$result = $conn->query($sql);
// Display the items in an HTML table
if ($result->num_rows > 0) {
    echo '<div class="dessert section" id="dessert" style="display: none;">';
    echo '<h1 id="menu1"><b><i>Desserts</i></b></h1>';
    echo '<div class="container text-center">';
    echo '<div class="row">';

    while ($row = $result->fetch_assoc()) {
        echo '<div class="col">';
        echo '<div class="card" style="width: 18rem">';
        echo '<img src="' . $row['image_path'] . '" class="card-img-top" alt="..." height="200rem">';
        echo '<div class="card-body" >';
        echo '<h5 class="card-title">' . $row['item_name'] . '</h5>';
        echo '<p class="card-text" id="box-4-price">' . $row['price'] . '</p>';
        // Check if the quantity is greater than 0
        if ($row['quantity'] > 0) {
            // Allow adding to cart
            echo '<button class="btn btn-danger" onclick="addToCart(' . $row['id'] . ')">Add To Cart</button>';
        } else {
            // Display "Out of Stock" and disable the button
            echo '<button class="btn btn-secondary" disabled>Out of Stock</button>';
        }
        echo '</div>';
        echo '</div>';
        echo '</div>';
    }

    echo '</div>';
    echo '</div>';
    echo '</div>';
} else {
    echo "0 results";
}
$conn->close();
?>

<hr>

<!-- Non-veg, Beverage, and Dessert sections -->

<!-- footer -->
<?php require 'res/_footer.php' ?>

</body>
</html>
<script>
    function redirectToCart() {
        window.location.href = 'cart.php';
    }
</script>