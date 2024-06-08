<style>
    .card-img-top {
        object-fit: cover; /* Scale the image to fill the entire container while maintaining aspect ratio */
    }
</style>
<div class="box4" id="box4">
    <h1 id="menu1"><b><i>Menu</i></b></h1>
    <div class="container text-center">
        <div class="row">
            <?php
            $servername = "localhost";
            $username = "root";
            $password = "";
            $dbname = "restaurant";

            $conn = new mysqli($servername, $username, $password, $dbname);

            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }

            // Fetch food items from menu table
            $sql = "SELECT * FROM menu";
            $result = $conn->query($sql);

            $categories = [];

            if ($result->num_rows > 0) {
                // Display two food items from each category
                while ($row = $result->fetch_assoc()) {
                    $category = $row["category"];

                    if (!in_array($category, $categories)) {
                        $categories[] = $category;
                    }

                    if (count($categories) > 4) {
                        break;
                    }
                }

                foreach ($categories as $category) {
                    $sql = "SELECT * FROM menu WHERE category = '$category' LIMIT 2";
                    $result = $conn->query($sql);

                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            echo '<div class="col">
                            <div class="card" style="width: 18rem">
                            <img src="' . $row["image_path"] . '" class="card-img-top" alt="...">
                                <div class="card-body">
                                    <h5 class="card-title">' . $row["item_name"] . '</h5>
                                    <p class="card-text" id="box-4-price">' . $row["price"] . '</p>
                                    <button type="button" onclick="addToCart(' . $row["id"] . ')" class="btn btn-danger">Add To Cart</button>
                                </div>
                            </div>
                        </div>';
                        }
                    }
                }
            } else {
                echo "0 results";
            }

            $conn->close();
            ?>
        </div>
    </div>
</div>
<script>
    function addToCart(productId) {
        var xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                alert(this.responseText); // Display response from add_to_cart.php
            }
        };
        xhttp.open("POST", "add_to_cart.php", true);
        xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xhttp.send("product_id=" + productId);
    }
</script>

