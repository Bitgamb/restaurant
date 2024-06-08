<?php
session_start(); // Resume the existing session

// Time in seconds before the session expires. 2 hours = 7200 seconds
$expireAfter = 7200;

// Check if the 'last_activity' session variable is set
if (isset($_SESSION['last_activity'])) {
    // Calculate the time since the last activity
    $timeSinceLastActivity = time() - $_SESSION['last_activity'];
    
    // If the time since the last activity exceeds the expiration time
    if ($timeSinceLastActivity > $expireAfter) {
        // Unset and destroy the session
        session_unset();
        session_destroy();
        // Redirect to login page with a message (optional)
        header("location: signin_signup.php?session_expired=1");
        exit;
    }
}else {
     // Unset and destroy the session
     session_unset();
     session_destroy();
     // Redirect to login page with a message (optional)
     header("location: signin_signup.php?session_expired=1");
     exit;
}
// echo "<script>alert('".$_SESSION['id']."');</script>";

