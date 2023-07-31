<!DOCTYPE html>
<html>
<head>
    <meta name="viewport" content="with=device-width, initial-scale=1.0">
  <title>Book Store</title>
  <link rel="stylesheet" href="home_page.css">
  <link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Shantell+Sans:wght@300;500;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@6.2.1/css/fontawesome.min.css">
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
        <h1>BOOK STORE</h1>
        <?php
    // Start the PHP session (should be at the top of the file)
    session_start();

    // Check if the user is logged in or registered
    if (isset($_SESSION['username']) && isset($_SESSION['email'])) {
        $name = $_SESSION['username'];
        $email = $_SESSION['email'];
        // Display the user's information
        echo "<p>Welcome, $name!</p>";
        echo "<p>Email: $email</p>";
    } else {
        // If the user is not logged in or registered, show an empty message
        echo "<p>Please login or register to see your information.</p>";
    }
    ?>
    </div>
  </section>
  <!------pengenalan------>
<section class="pengenalan">
<form action="" method="post">
        <label for="amount">Amount:</label>
        <input type="text" name="amount" id="amount" required>
        <br>
        <label for="fromCurrency">From Currency:</label>
        <select name="fromCurrency" id="fromCurrency" required>
            <option value="MYR">MYR</option>
            <option value="SGD">SGD</option>
            <option value="CNY">CNY</option>
            <option value="USD">USD</option>
            <option value="EUR">EUR</option>
            <option value="THB">THB</option>
        </select>
        <br>
        <label for="toCurrency">To Currency:</label>
        <select name="toCurrency" id="toCurrency" required>
        <option value="MYR">MYR</option>
            <option value="SGD">SGD</option>
            <option value="CNY">CNY</option>
            <option value="USD">USD</option>
            <option value="EUR">EUR</option>
            <option value="THB">THB</option>
        </select>
        <br>
        <input type="submit" value="Convert">
    </form>
<?php
function convertCurrency($amount, $fromCurrency, $toCurrency) {
    $apiKey = 'fca_live_ZsI2JbPb6HpyaKwQsjDyt9S236nKNI0okT9Usk5J'; // Replace this with your actual API key from freecurrencyapi.com
    $endpoint = "https://api.freecurrencyapi.com/v1/latest?base_currency={$fromCurrency}";

    // Initialize the HTTP client
    $curl = curl_init();

    // Set the cURL options
    curl_setopt($curl, CURLOPT_URL, $endpoint);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

    // Send the API request
    $response = curl_exec($curl);
    curl_close($curl);

    // Parse the response JSON
    $data = json_decode($response, true);

    // Check if the API call was successful
    if (isset($data['data']['rates'][$toCurrency])) {
        $rate = $data['data']['rates'][$toCurrency];
        $convertedAmount = $amount * $rate;
        return number_format($convertedAmount, 2); // Return the converted amount rounded to 2 decimal places
    } else {
        return "Currency conversion error.";
    }
}
// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $amount = $_POST["amount"];
  $fromCurrency = $_POST["fromCurrency"];
  $toCurrency = $_POST["toCurrency"];

  // Call the convertCurrency function with the user-inputted values
$convertedAmount = convertCurrency($amount, $fromCurrency, $toCurrency);
echo "{$amount} {$fromCurrency} is equivalent to {$convertedAmount} {$toCurrency}";
}
?>
<br>
    <h1>LIST OF BOOK</h1>
    <div class="container">
            <div class="row">
    <?php
// Establish database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "bookstore";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Retrieve book information from the database
$sql = "SELECT * FROM tbl_items"; // Change 'books' to your actual table name
$result = $conn->query($sql);

// Display book information
if ($result->num_rows > 0) {
  while ($row = $result->fetch_assoc()) {
      echo '<div class="col-md-4 mb-3">';
      echo '  <div class="card">';
      echo '    <img src="bookimages/' . $row["bookimage"] . '" class="card-img-top" alt="' . $row["tmp_name"] . '">';
      echo '    <div class="card-body">';
      echo '      <h5 class="card-title">' . $row["item_name"] . '</h5>';
      echo '      <p class="card-text">Book Price: RM ' . $row["item_price"] . '</p>';
      echo '    </div>';
      echo '  </div>';
      echo '</div>';
    }
} else {
    echo "<p>No books found.</p>";
}

$conn->close();
?>
</div>
</div>
    <br>
</section>

  <!------JavaScript for Toggle Menu------>
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
