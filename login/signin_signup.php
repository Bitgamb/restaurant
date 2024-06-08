<?php
session_start();
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "restaurant";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the form is submitted

// Handle form submission for registration
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['reg'])) {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Prepare a select statement to check if the email already exists
    $sql = "SELECT * FROM user WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        // Email doesn't exist, insert new user
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        $sql = "INSERT INTO user (username, email, password) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sss", $username, $email, $hashed_password);
        $stmt->execute();

        // Redirect user after successful registration
        header("location: /project/index.php");
    } else {
        // Email already exists, show an error message
        echo "<script>alert('Email already exists.'); </script>";
    }
}
// Handle form submission for login
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['login'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Prepare a select statement to check if the email and password match
    $sql = "SELECT * FROM user WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['password'])) {
            // Password matches, set session variables and redirect user
            $_SESSION['loggedin'] = true;
            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['email'] = $user['email'];
            header("location: /project/index.php");
        } else {
            // Incorrect password, show an error message
            echo "<script>alert('Incorrect password.'); </script>";
        }
    } else {
        // User not found, show an error message
        echo "<script>alert('User not found.'); </script>";
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>sign in & sign up form</title>
    <link rel="stylesheet" href="singin/signin_signup.css">
    <script src="https://kit.fontawesome.com/64d58efce2.js" crossorigin="anonymous"></script>
</head>

<body>
    <div class="container">
        <div class="forms-container">
            <div class="signin-signup">

                <!-- sign in -->
                <form method="POST" class="sign-in-form">
                    <h2 class="title">sign in</h2>
                    <div class="input-field">
                        <i class="fas fa-envelope"></i>
                        <input required name="email" type="text" placeholder="Email"></input>
                    </div>

                    <div class="input-field">
                        <i class="fas fa-lock"></i>
                        <input name="password" type="password" placeholder="password"></input>
                    </div>
                    <button type="submit" name="login" value="login" class="btn solid">Login</button>

                    <p class="social-text"> Or sign in with social platform</p>
                    <div class="social-media">
                        <a href="#" class="social-icon">
                            <i class="fab fa-facebook"></i>
                        </a>
                        <a href="#" class="social-icon">
                            <i class="fab fa-twitter"></i>
                        </a>
                        <a href="#" class="social-icon">
                            <i class="fab fa-google"></i>
                        </a>
                        <a href="#" class="social-icon">
                            <i class="fab fa-linkedin-in"></i>
                        </a>
                    </div>
                </form>

                <!-- sign up -->
                <form method="POST" class="sign-up-form">
                    <h2 class="title">sign up</h2>
                    <div class="input-field">
                        <i class="fas fa-user"></i>
                        <input required name="username" type="text" placeholder="Username"></input>
                    </div>
                    <div class="input-field">
                        <i class="fas fa-envelope"></i>
                        <input required name="email" type="text" placeholder="Email"></input>
                    </div>
                    <div class="input-field">
                        <i class="fas fa-lock"></i>
                        <input name="password" type="password" placeholder="password"></input>
                    </div>
                    <button type="submit" name="reg" value="reg" class="btn solid">Sign up</button>

                    <p class="social-text"> Or sign in\Sign up with social platform</p>
                    <div class="social-media">
                        <a href="#" class="social-icon">
                            <i class="fab fa-facebook"></i>
                        </a>
                        <a href="#" class="social-icon">
                            <i class="fab fa-twitter"></i>
                        </a>
                        <a href="#" class="social-icon">
                            <i class="fab fa-google"></i>
                        </a>
                        <a href="#" class="social-icon">
                            <i class="fab fa-linkedin-in"></i>
                        </a>
                    </div>
                </form>
            </div>
        </div>
        <div class="panels-container">
            <div class="panel left-panel">
                <div class="content">
                    <h3>New here ?</h3>
                    <p>Lorem ipsum, dolor sit amet consectetur adipisicing elit. Consequuntur, aperiam. Doloremque, animi.</p>
                    <button class="btn transparent" id="sign-up-btn">Sign up</button>
                </div>
                <img src="signin/table.svg" class="image" alt="">
            </div>

            <div class="panel right-panel">
                <div class="content">
                    <h3>One of us ?</h3>
                    <p>Lorem ipsum, dolor sit amet consectetur adipisicing elit. Consequuntur, aperiam. Doloremque, animi.</p>
                    <button class="btn transparent" id="sign-in-btn">Sign in</button>
                </div>
                <img src="singin/register.svg" class="image" alt="">
            </div>
        </div>
    </div>

    <script src="singin/signin-signup.js"></script>
</body>

</html>