<?php
include "config.php";
date_default_timezone_set("Asia/Kolkata");
function tim()
{
    date_default_timezone_set("Asia/Kolkata");
    $time = round(microtime(true) * 1000);
    return $time;
}

function timeconvert($milliseconds, $format)
{
    date_default_timezone_set("Asia/Kolkata");
    $date = new DateTime();
    $date->setTimestamp($milliseconds / 1000); // Convert milliseconds to seconds

    // Format the date as a string d-M-Y
    $formattedDate = $date->format($format);

    echo $formattedDate;
}

function access($email, $password)
{
    global $conn;

    // Prepare SQL statement to avoid SQL injection
    $stmt = $conn->prepare("SELECT * FROM user WHERE email = ?");
    $stmt->bind_param("s", $email);

    // Execute the query
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $row = $result->fetch_assoc();

        // Verify the password
        if (password_verify($password, $row["password"])) {
            return true;
        } else {

            return false;
        }
    }

    // Close statement
    $stmt->close();
}

function getuid($email, $password)
{
    global $conn;

    // Prepare SQL statement to avoid SQL injection
    $stmt = $conn->prepare("SELECT * FROM user WHERE email = ?");
    $stmt->bind_param("s", $email);

    // Execute the query
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $row = $result->fetch_assoc();

        // Verify the password
        if (password_verify($password, $row["password"])) {
            return $row["user_id"];
        } else {

            return false;
        }
    }

    // Close statement
    $stmt->close();
}

function registerUser($name, $email, $password)
{
    global $conn;
    // get bonous


    // Check if the user already exists
    $stmt = $conn->prepare("SELECT user_id FROM user WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // User already exists
        $row = $result->fetch_assoc();
        return true;
    } else {

        // Encrypt the password
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $joined = tim();
        // Prepare SQL statement to insert a new user

        $stmt = $conn->prepare("INSERT INTO user (username,email, password) VALUES (?, ?, ?)");

        $stmt->bind_param("sss", $name, $email, $hashed_password);

        // Execute the query
        if ($stmt->execute()) {
            // Return the new user ID
            return true;
        } else {
            return  "Error: " . $stmt->error;
        }

        // Close statement
        $stmt->close();
    }
}
function loginUser($email, $password)
{
    global $conn;

    // Prepare SQL statement to avoid SQL injection
    $stmt = $conn->prepare("SELECT * FROM user WHERE email = ?");
    $stmt->bind_param("s", $email);

    // Execute the query
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $row = $result->fetch_assoc();

        // Verify the password
        if (password_verify($password, $row["password"])) {
            // if (($password== $row["password"])) {

          return true;
        } else {
            return "Invalid credentials";
        }
    } else {
        return "User not found";
    }

    // Close statement
    $stmt->close();
}
