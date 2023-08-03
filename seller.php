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
    $itemname = $_POST['itemname'];
    $itemtype = $_POST['itemtype'];
    $itemquantity = $_POST['itemquantity'];
    $itemdescription = $_POST['itemdescription'];
    $itemprice = $_POST['itemprice'];
    $itemcategory = $_POST['itemcategory'];
    $email = $_SESSION['email'];

    // Fetch the user ID from the database based on the logged-in user's email
    $sql_user_id = "SELECT user_id FROM tbl_users WHERE user_email='$email'";
    $result_user_id = $conn->query($sql_user_id);

    if ($result_user_id->num_rows === 1) {
        $row_user_id = $result_user_id->fetch_assoc();
        $userid = $row_user_id['user_id'];

    // Handle file upload
    if (isset($_FILES['bookimage']) && $_FILES['bookimage']['error'] === UPLOAD_ERR_OK) {
      $targetDir = "bookimages/";
      $imageFileType = strtolower(pathinfo($_FILES["bookimage"]["name"], PATHINFO_EXTENSION));
    
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
  // Generate a unique name for the uploaded image using item_id
  $newFileName = uniqid() . '.png';  // Assuming you want to save it with a .png extension
  // Move the uploaded file to the target directory with the new name
  if (!move_uploaded_file($_FILES["bookimage"]["tmp_name"], $targetDir . $newFileName)) {
      echo "Sorry, there was an error uploading your file.";
      exit();
  }
} else {
  echo "Image not uploaded.";
  exit();
}
    // Insert the book data into the database with the user ID
        $sql = "INSERT INTO tbl_items (item_name, item_type, item_quantity, item_description, item_price, item_category, user_id, bookimage) 
                VALUES ('$itemname', '$itemtype', '$itemquantity', '$itemdescription', '$itemprice', '$itemcategory','$userid', '$newFileName')";

        if ($conn->query($sql) === TRUE) {
            // Book added successfully, redirect to the homepage
            header("Location: homepage.php");
            exit();
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    } else {
        echo "User not found or multiple users with the same email.";
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
                <li><a href="seller.php">SELL</a></li>
                <li><a href="profile.php">PROFILE</a></li>
            </ul>
        </div>
        <i class="fa fa-bars" onclick="showMenu()"></i>
    </nav>
    <div class="text-box">
        <h1>SELL NEW BOOK</h1>
    </div>
    </section>
<section class="pengenalan">
<div class="container mt-5">
<form method="post" action="">
<div class="form-group">
        <label for="amount">Amount MYR (Malaysia):</label>
        <input type="number" step="0.01" name="amount" required>
        </div>
            <div class="form-group">
        <label for="currency">Target Currency:</label>
        <select class="form-control" name="currency" required>
            <option value="USD">USD (US)</option>
            <option value="GBP">GBP (UK)</option>
            <option value="JPY">JPY (Japan)</option>
            <option value="EUR">EUR (European Union)</option>
            <option value="AUD">AUD (Australia)</option>
            <option value="CNY">CNY (China)</option>
            <option value="SDG">SDG (Sudan)</option>
            <option value="SGD">SGD (Singapore)</option>
            <option value="THB">THB (Thailand)</option>
            <option value="AED">AED (United Arad Emirates)</option>
        </select>
</div>
<div class="mb-3">
        <button type="submit" class="btn btn-primary">Calculate</button>
</div>
    </form>
    <?php
function get_exchange_rates($api_key, $base_currency) {
    $url = "https://v6.exchangerate-api.com/v6/d2d530d5f497a25b9fb9b207/latest/{$base_currency}";
    $response = file_get_contents($url);
    
    if ($response !== false) {
        $data = json_decode($response, true);
        return $data['conversion_rates'];
    } else {
        echo "Failed to retrieve exchange rates.";
        return null;
    }
}

function perform_money_exchange($amount, $exchange_rates, $target_currency) {
    if (array_key_exists($target_currency, $exchange_rates)) {
        $rate = $exchange_rates[$target_currency];
        $exchanged_amount = $amount * $rate;
        return array($exchanged_amount, $rate);
    } else {
        echo "Exchange rate for {$target_currency} not available.";
        return array(null, null);
    }
}

// Replace 'YOUR_API_KEY' with your actual API key
$api_key = "d2d530d5f497a25b9fb9b207";
$base_currency = "MYR";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the user input from the form
    $amount_myr = floatval($_POST["amount"]);
    $target_currency = $_POST["currency"];

// Step 1: Get exchange rates
$exchange_rates = get_exchange_rates($api_key, $base_currency);

if ($exchange_rates !== null) {
    // Step 2: Perform the money exchange
    list($exchanged_amount, $rate) = perform_money_exchange($amount_myr, $exchange_rates, $target_currency);
    if ($exchanged_amount !== null) {
        $exchanged_amount_formatted = number_format($exchanged_amount, 2);
        echo "{$amount_myr} MYR is equivalent to {$exchanged_amount_formatted} {$target_currency} (Exchange rate: {$rate})";
    }
}
}
?>
</div>
<br><hr>
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
                <input type="text" class="form-control" id="bookname" name="itemname" required>
              </div>
              <div class="form-group">
                <label for="booktype">Book Type:</label>
                <select class="form-control" id="booktype" name="itemtype" required>
                  <option value="">Select a book type</option>
                  <option value="fiction">Fiction</option>
                  <option value="non-fiction">Non-Fiction</option>
                  <option value="mystery">Mystery</option>
                  <option value="fantasy">Fantasy</option>
                </select>
              </div>
              <div class="form-group">
                <label for="bookquantity">Book Quantity:</label>
                <input type="number" class="form-control" id="bookquantity" name="itemquantity" required>
              </div>
              <div class="form-group">
                <label for="bookdescription">Book Description:</label>
                <textarea class="form-control" id="bookdescription" rows="3" name="itemdescription" required></textarea>
              </div>              
              <div class="form-group">
                <label for="bookprice">Book Price:</label>
                <div class="input-group">
                  <div class="input-group-prepend">
                    <span class="input-group-text">RM</span>
                  </div>
                  <input type="text" class="form-control" id="bookprice" name="itemprice" placeholder="Enter the book price" required>
                </div>
              </div>
              <div class="form-group">
                <label for="bookcategory">Book Category:</label>
                <select class="form-control" id="bookcategory" name="itemcategory" required>
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
