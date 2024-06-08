<?php
session_start();

// Check if total price is stored in the session
if (isset($_SESSION['totalPrice'])) {
    $totalPrice = $_SESSION['totalPrice'];
} else {
    // If total price is not found in the session, handle accordingly
    $totalPrice = 0; // Set default value or handle as needed
}

// Database connection parameters
$servername = "localhost";
$username = "root";
$password = ""; // Assuming empty password
$database = "restaurant";

// Create connection
$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // If the address form is submitted, insert new address into the database
    // If the address form is submitted, insert new address into the database
if (isset($_POST['address']) && isset($_POST['city']) && isset($_POST['postal_code']) && isset($_POST['country']) && isset($_POST['contact_number'])) {
    $address = $_POST['address'];
    $city = $_POST['city'];
    $postal_code = $_POST['postal_code'];
    $country = $_POST['country'];
    $contact_number = $_POST['contact_number'];
    
    // Insert the new address into the database
    $user_id = $_SESSION["user_id"];
    $sql = "INSERT INTO address (user_id, address, city, postal_code, country, contact_number) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("isssss", $user_id, $address, $city, $postal_code, $country, $contact_number);
    $stmt->execute();

    // Redirect to the same page to avoid form resubmission
    header("Location: ".$_SERVER['PHP_SELF']);
    exit;
}

}

// Fetch addresses for the user
$user_id = $_SESSION["user_id"];
$sql = "SELECT * FROM address WHERE user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout</title>
    <link rel="stylesheet" href="res/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>

<body>

    <!-- Nav -->
    <?php require 'res/_nav.php' ?>

    <div class="container mt-5">
        <h2>Checkout</h2>
        <p>Total Price: ₹<?php echo $totalPrice; ?></p>
        <h3>Select Address</h3>
        <form action="process_checkout.php" method="post">
            <select name="address_id" class="form-select mb-3">
                <option value="" selected disabled>Select Address</option>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <option value="<?php echo $row['id']; ?>"><?php echo $row['address'] . ', ' . $row['city'] . ', ' . $row['postal_code'] . ', ' . $row['country']; ?></option>
                <?php endwhile; ?>
            </select>
            <button type="submit" class="btn btn-primary">Proceed to Payment</button>
        </form>
        <hr>
        <h3>Add New Address</h3>
<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
    <div class="mb-3">
        <label for="address" class="form-label">Address</label>
        <input type="text" name="address" class="form-control" required>
    </div>
    <div class="mb-3">
        <label for="city" class="form-label">City</label>
        <input type="text" name="city" class="form-control" required>
    </div>
    <div class="mb-3">
        <label for="postal_code" class="form-label">Postal Code</label>
        <input type="text" name="postal_code" class="form-control" required>
    </div>
    <div class="mb-3">
        <label for="country" class="form-label">Country</label>
        <input type="text" name="country" class="form-control" required>
    </div>
    <div class="mb-3">
        <label for="contact_number" class="form-label">Contact Number</label>
        <input type="text" name="contact_number" class="form-control" required>
    </div>
    <button type="submit" class="btn btn-primary">Save Address & Proceed to Payment</button>
</form>

    </div>

    <!-- Footer -->
    <?php require 'res/_footer.php' ?>

    <!-- Razorpay Integration -->
    <script src="https://checkout.razorpay.com/v1/checkout.js"></script>
    <script>
        var options = {
            "key": "rzp_test_5uPzGreHV3wiCs",
            "amount": <?php echo $totalPrice * 100; ?>, // Amount is in currency subunits. Here it's ₹totalPrice in paisa
            "currency": "INR",
            "name": "Restaurant",
            "description": "Payment for food order",
            "image": "path_to_your_logo",
            "handler": function (response){
                // Send payment details to save_payment.php
                var xhr = new XMLHttpRequest();
                xhr.open("POST", "save_payment.php", true);
                xhr.setRequestHeader("Content-Type", "application/json");
                xhr.onreadystatechange = function () {
                    if (xhr.readyState === 4 && xhr.status === 200) {
                        console.log(xhr.responseText);
                        // Redirect to confirmation page after successful payment
                        window.location.href = 'booked.php';
                    }
                };
                xhr.send(JSON.stringify(response));
            }
        };
        var rzp1 = new Razorpay(options);
        document.querySelector('button[type="submit"]').onclick = function(e){
            rzp1.open();
            e.preventDefault();
        }
    </script>

</body>

</html>
