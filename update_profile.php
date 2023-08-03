<?php
session_start(); // Start the session

// Check if the user is logged in
if (isset($_SESSION['email'])) {
    // Establish database connection
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "bookstore";

    $conn = new mysqli($servername, $username, $password, $dbname);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Retrieve user data from the form submission
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phonenumber = $_POST['phonenumber'];
    $address = $_POST['address'];
    $password = $_POST['password'];

    // Update user information in the database
    $sql = "UPDATE tbl_users SET user_name='$name', user_email='$email', user_phone='$phonenumber', user_address='$address', user_password='$password' WHERE user_email='{$_SESSION['email']}'";

    if ($conn->query($sql) === TRUE) {
        // Update successful, redirect back to the profile page
        $_SESSION['email'] = $email; // Update the email in the session
        header("Location: profile.php");
        exit();
    } else {
        echo "Error updating record: " . $conn->error;
    }

    $conn->close();
} else {
    // If the user is not logged in, redirect to the login page or homepage
    header("Location: login.php"); // Replace 'login.php' with your login page URL
    exit();
}
?>
