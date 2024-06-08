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
    <style>
        .card img {
            width: 100%; /* Set the width of the image to 100% of its container */
            height: auto; /* Maintain the aspect ratio of the image */
        }

        .box1 {
            position: relative;
            height: 100vh;
            color: white;
            font-weight: 900;
            font-family: 'Times New Roman', Times, serif;
            text-transform: capitalize;
            overflow: hidden; /* Hide overflowing content */
        }

        .video-container {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            overflow: hidden;
            z-index: -1; /* Move the video behind other content */
        }

        .bg-video {
            min-width: 100%;
            min-height: 100%;
            width: auto;
            height: auto;
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
        }

        .content {
            position: relative;
            z-index: 1;
            padding: 4cm 2cm 0 2cm; /* Adjust padding as needed */
        }

        /* Additional styling for content */
        .content h1,
        .content p {
            margin: 0;
        }

        .content p {
            margin-top: 1em;
        }

        .order-btn {
            background-color: #ffffff;
            color: #000000;
            border: none;
            padding: 10px 20px;
            font-size: 16px;
            cursor: pointer;
        }
    </style>
</head>

<body>

    <!-- Nav -->
    <?php require 'res/_nav.php' ?>


    <div class="box1">
        <div class="video-container">
            <video autoplay muted loop class="bg-video">
                <source src="video/rest.mp4" type="video/mp4">
                Your browser does not support the video tag.
            </video>
        </div>

        <div class="content">
            <h1><b>Best quality</b></h1>
            <h1><b>food</b></h1>
            <p id="para"><b>Start aligned text on all viewport Lorem, ipsum dolor sit amet consectetur adipisicing elit.</b></p>
            <a class="order-btn" href="order_now.php">Order now</a>

        </div>
    </div>


    <!-- box2 -->
    <?php require 'res/_galary.php' ?>

    <!-- box3 -->
    <?php require 'res/_about.php' ?>

    <!-- box4 -->
    <?php require 'res/_menu.php' ?>

    <!-- box5 -->
    <?php require 'res/_contact.php' ?>

    <!-- footer -->
    <?php require 'res/_footer.php' ?>
    <?php if (isset($_SESSION['user_id'])): ?>
        <!-- Display the welcome message if the user is logged in -->
        <div class="welcome-message">
            <p>Welcome, <?php echo $_SESSION['username']; ?>!</p>
        </div>
    <?php endif; ?>
</body>

</html>
