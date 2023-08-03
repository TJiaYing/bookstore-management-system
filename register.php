<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  // Establish database connection
  $servername = "localhost";
  $username = "root";
  $password = "";
  $dbname = "bookstore";

  $conn = new mysqli($servername, $username, $password, $dbname);
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  // Retrieve form data
$name = $_POST['username'];
$email = $_POST['email'];
$phonenumber = $_POST['phonenumber'];
$address = $_POST['address'];
$password = $_POST['password'];

// Insert user data into the database
$sqlins = "INSERT INTO tbl_users (user_name, user_email, user_phone, user_address, user_password) VALUES ('$name', '$email', '$phonenumber', '$address', '$password')";

if ($conn->query($sqlins) === TRUE) {
    // Register successful
    $_SESSION['email'] = $email; // Store the user's email in the session
    header("Location: login.php"); // Redirect to the homepage
    exit();
} else {
    echo "Register Failed: " . $conn->error;
}
}
?>


<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Register Page</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="stylesheet" href="home_page.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Shantell+Sans:wght@300;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@6.2.1/css/fontawesome.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
     
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</head>
<body>

  <section class="header">
  <nav>
        <a href="homepage.php"><img src="images/logo.png"></a>
        <div class="nav-links">
            <i class="fa fa-tiems" onclick="hideMenu()"></i>
            <ul>
                <li><a href="homepage.php">HOME</a></li>
                <li><a href="login.php">LOGIN</a></li>
                <li><a href="register.php">REGISTER</a></li>
            </ul>
        </div>
        <i class="fa fa-bars" onclick="showMenu()"></i>
    </nav>
    <div class="text-box">
        <h1>BOOK STORE REGISTER</h1>
    </div>
    </section>
<section class="pengenalan">
    <div class="container">
        <div class="row">
          <div class="col-md-6 offset-md-3">
            <h1 class="text-center mb-4">Register</h1>
            <form action="register.php" method="POST">
              <div class="form-group">
                <label for="username">Username:</label>
                <input type="text" class="form-control" id="username" name="username" required placeholder="username">
              </div>
              <div class="form-group">
                <label for="email">Email:</label>
                <input type="text" class="form-control" id="email" name="email" required placeholder="email">
              </div>
              <div class="form-group">
                <label for="phone">Phone Number:</label>
                <input type="text" class="form-control" id="phonenumber" name="phonenumber" required placeholder="phone number">
              </div>
              <div class="form-group">
                <label for="address">Address:</label>
                <input type="text" class="form-control" id="address" name="address" required placeholder="address">
              </div>
              <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" class="form-control" id="password" name="password" required placeholder="password">
              </div>
              <div class="text-center">
                <input type="submit" class="btn btn-primary" value="Register">
              </div>
              <br>
            </form>
          </div>
        </div>
      </div>
</section>

<script>
    var navLinks = document.getElementById("navLinks");
    function showMenu(){
        navLinks.style.right = "0";
    }
    function hideMenu(){
        navLinks.style.right = "-200";
    }
  </script>
<script src="js/bootstrap.min.js"></script>
</body>
</html>