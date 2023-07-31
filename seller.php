<?php
session_start(); // Start the session

// Check if the user is logged in or registered
if (!isset($_SESSION['email'])) {
    // Redirect to login page if the user is not logged in
    header("Location: login.php");
    exit();
}

// Check if the form is submitted
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
    $userid = $_POST['userid'];
    $itemname = $_POST['itemname'];
    $itemtype = $_POST['itemtype'];
    $itemquantity = $_POST['itemquantity'];
    $itemdescription = $_POST['itemdescription'];
    $itemprice = $_POST['itemprice'];
    $itemcategory = $_POST['itemcategory'];
    $email = $_SESSION['email'];

    // Handle file upload
    if (isset($_FILES['bookimage']) && $_FILES['bookimage']['error'] === UPLOAD_ERR_OK) {
      $targetDir = "bookimages/";
      $targetFile = $targetDir . basename($_FILES["bookimage"]["name"]);
      $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));
  
      // Check if the file is an image
      $check = getimagesize($_FILES["bookimage"]["tmp_name"]);
      if ($check === false) {
          echo "File is not an image.";
          exit();
        }

        // Check if the file is a PNG image
        if ($imageFileType !== "png") {
            echo "Only PNG images are allowed.";
            exit();
        }
    
        // Check file size
        if ($_FILES["bookimage"]["size"] > 5000000) { // 5MB
            echo "Sorry, your file is too large.";
            exit();
        }
        // Move the uploaded file to the target directory
        if (!move_uploaded_file($_FILES["bookimage"]["tmp_name"], $targetFile)) {
            echo "Sorry, there was an error uploading your file.";
            exit();
        }
    } else {
        echo "Image not uploaded.";
        exit();
    }

    // Insert the book data into the database
    $sql = "INSERT INTO tbl_items (item_name, item_type, item_quantity, item_description, item_price, item_category, user_id) 
            VALUES ('$itemname', '$itemtype', '$itemquantity', '$itemdescription', '$itemprice', '$itemcategory','$userid')";

    if ($conn->query($sql) === TRUE) {
        // Book added successfully, redirect to the homepage
        header("Location: homepage.php");
        exit();
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    $conn->close();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Sell Book Page</title>
    <link rel="stylesheet" href="home_page.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Shantell+Sans:wght@300;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@6.2.1/css/fontawesome.min.css">
     
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
                <li><a href="seller.php">SELL</a></li>
                <li><a href="feedback.php">FEEDBACK</a></li>
            </ul>
        </div>
        <i class="fa fa-bars" onclick="showMenu()"></i>
    </nav>
    <div class="text-box">
        <h1>SELL NEW BOOK</h1>
    </div>
    </section>
<section class="pengenalan">
    <div class="container">
        <div class="row">
          <div class="col-md-6 offset-md-3">
            <h1 class="text-center mb-4">Add New Book</h1>
            <form action="seller.php" method="post" onsubmit="sell(); return false;" enctype="multipart/form-data">
            <div class="form-group">
                <label for="bookimage">Book Image:</label>
                <input type="file" class="form-control" id="bookimage" name="bookimage" required>
              </div>
              <div class="form-group">
                <label for="bookname">Book Name:</label>
                <input type="text" class="form-control" id="bookname" required>
              </div>
              <div class="form-group">
                <label for="booktype">Book Type:</label>
                <select class="form-control" id="booktype" required>
                  <option value="">Select a book type</option>
                  <option value="fiction">Fiction</option>
                  <option value="non-fiction">Non-Fiction</option>
                  <option value="mystery">Mystery</option>
                  <option value="fantasy">Fantasy</option>
                </select>
              </div>
              <div class="form-group">
                <label for="bookquantity">Book Quantity:</label>
                <input type="number" class="form-control" id="bookquantity" required>
              </div>
              <div class="form-group">
                <label for="bookdescription">Book Description:</label>
                <textarea class="form-control" id="bookdescription" rows="3" required></textarea>
              </div>              
              <div class="form-group">
                <label for="bookprice">Book Price:</label>
                <div class="input-group">
                  <div class="input-group-prepend">
                    <span class="input-group-text">RM</span>
                  </div>
                  <input type="text" class="form-control" id="bookprice" placeholder="Enter the book price" required>
                </div>
              </div>
              <div class="form-group">
                <label for="bookcategory">Book Category:</label>
                <select class="form-control" id="bookcategory" required>
                  <option value="">Select book category</option>
                  <option value="new">New</option>
                  <option value="secondhand">Second Hand</option>
                </select>
              </div>
              <div class="text-center">
                <input type="submit" class="btn btn-primary" value="Add">
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

</body>
</html>
