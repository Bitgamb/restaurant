<nav class="navbar navbar-expand-lg bg-body-tertiary sticky-top">
    <div class="container-fluid">
        <a class="navbar-brand" id="res" href="#"><b>@Restaurant</b></a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent"
            aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item" id="bar">
                    <a class="nav-link active" aria-current="page" href="zipzup/project/index.php">Home</a>
                </li>

                <li class="nav-item" id="bar">
                    <a class="nav-link active" aria-current="page" href="#box2">Gallery</a>
                </li>

                <li class="nav-item" id="bar">
                    <a class="nav-link active" aria-current="page" href="#box3">About us</a>
                </li>

                <li class="nav-item" id="bar">
                    <a class="nav-link active" aria-current="page" href="order_now.php"> Order now</a>
                </li>

                <li class="nav-item" id="bar">
                    <a class="nav-link active" aria-current="page" href="#box4">Menu</a>
                </li>

                <li class="nav-item" id="bar">
                    <a class="nav-link active" aria-current="page" href="#box5">contact us</a>
                </li>

                <li class="nav-item" id="bar">
                    <a class="nav-link active" aria-current="page" href="cart.php"> <img src="assets/cart.svg" id="cart"></a>
                </li>
            </ul>

            <form class="d-flex" role="search">
                <?php if (!isset($_SESSION['user_id'])): ?>
                    <!-- Display the login button if the user session is not active -->
                    <a class="btn btn-outline-success me-2" href="/project/login/signin_signup.php">Log in</a>
                <?php endif; ?>

                <!-- Admin login button -->
                <a class="btn btn-outline-primary" href="admin_login.php">Admin Login</a>
            </form>
        </div>
    </div>
</nav>
