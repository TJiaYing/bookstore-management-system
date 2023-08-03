<?php
session_start();
?>
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
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.1/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</head>
<body>

<section class="header">
    <nav>
        <div class="logo-container">
            <a href="homepage.php"><img src="images/logo.png"></a>
        </div>
        <div class="nav-links">
            <i class="fa fa-times" onclick="hideMenu()"></i>
            <ul>
                <?php
                // Check if the user is logged in or registered
                if (isset($_SESSION['username']) && isset($_SESSION['email'])) {
                    // User is logged in, display the navigation bar with user information
                    $name = $_SESSION['username'];
                    $email = $_SESSION['email'];
                    echo '<li><a href="homepage.php">HOME</a></li>';
                    echo '<li><a href="seller.php">SELL</a></li>';
                    echo '<li><a href="profile.php">Welcome, ' . $name . '!</a></li>';
                } else {
                    // User is not logged in, display the navigation bar without user information
                    echo '<li><a href="homepage.php">HOME</a></li>';
                    echo '<li><a href="login.php">LOGIN</a></li>';
                    echo '<li><a href="register.php">REGISTER</a></li>';
                }
                ?>
            </ul>
        </div>
        <i class="fa fa-bars" onclick="showMenu()"></i>
    </nav><br>
    <div class="text-box">
        <h1>BOOK STORE</h1>
        <?php
        // Check if the user is logged in or registered
        if (isset($_SESSION['username']) && isset($_SESSION['email'])) {
            $name = $_SESSION['username'];
            $email = $_SESSION['email'];
            // Display the user's information
            echo "<p>Welcome, $name!</p>";
            echo "<p>Email: $email</p>";
            // Add the logout button
            echo '<div class="text-center">';
            echo '    <form action="homepage.php" method="post">';
            echo '        <input type="hidden" name="logout" value="true">';
            echo '        <input type="submit" class="btn btn-primary" value="Logout">';
            echo '    </form>';
            echo '</div>';
        } else {
            // If the user is not logged in or registered, show an empty message
            echo "<p>Please login or register to see your information.</p>";
        }
        // Handle logout request
        if (isset($_POST['logout']) && $_POST['logout'] === 'true') {
            // Clear all session variables
            $_SESSION = array();

            // Destroy the session
            session_destroy();

            // Redirect the user to the homepage or login page
            header("Location: homepage.php"); // Replace 'index.php' with your homepage URL
            exit();
        }
        ?>
    </div>
</section>

  <!------pengenalan------>
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
<div class="col-12 text-center">
    <h1 class="mb-4">LIST OF BOOK</h1>
</div>
<div class="col-12 text-center mt-4 d-flex justify-content-center">
    <form action="" method="get" class="form-inline">
    <div class="form-group mr-2">
            <input type="text" name="item_name" class="form-control" placeholder="Search Item Name">
        </div>
        <button type="submit" class="btn btn-primary mt-2">Search</button>
    </form>
</div>

<br>
    <div class="container book-cards-container">
        <div class="row">
    <?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "bookstore";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Scan the "bookimages/" directory for image files
$imagesDir = "bookimages/";
$images = glob($imagesDir . "*.png");

$search_item_name = isset($_GET['item_name']) ? $_GET['item_name'] : '';

// Retrieve book information from the database
$sql = "SELECT * FROM tbl_items"; // Change 'books' to your actual table name
if (!empty($search_item_name)) {
    // If search query is provided, add a WHERE clause to filter results based on item_name
    $sql .= " WHERE item_name LIKE '%$search_item_name%'";
}

$result = $conn->query($sql);

// Display book information and images as individual cards
if ($result->num_rows > 0) {
    $count = 0;
    while ($row = $result->fetch_assoc()) {
        if ($count % 3 === 0) {
            echo '<div class="row justify-content-center">';
        }

        echo '<div class="col-12 col-md-6 col-lg-4">';
        echo '<div class="container mb-4 book-card">';
        echo '  <div class="border rounded p-3">';
        echo '    <a href="itemdetails.php?id=' . $row["item_id"] . '">';
        echo '      <div class="text-center">';
        if (isset($row["bookimage"])) {
            $imagePath = "bookimages/" . $row["bookimage"];
            if (file_exists($imagePath)) {
                echo '<div class="book-image-wrapper">';
                echo '  <img src="' . $imagePath . '" class="card-img-top book-image" alt="Book Image">';
            } else {
                echo '<div class="book-image-wrapper">';
                echo '  <img src="bookimages/default_image.png" class="card-img-top book-image" alt="Default Image">';
            }
        } else {
            echo '<div class="book-image-wrapper">';
            echo '  <img src="bookimages/default_image.png" class="card-img-top book-image" alt="Default Image">';
        }
        echo '    </div>'; // Close the book-image-wrapper
        echo '   <h5>' . $row["item_name"] . '</h5>';
        echo '   <p class="mb-0">Book Price: RM ' . $row["item_price"] . '</p>';
        echo '  </div>';
        echo '</a>';
        echo '</div>';
        echo '</div>';
        echo '</div>';

        $count++; 

        if ($count % 3 === 0) {
            echo '</div>';
        }
    }

    // If the last row is not complete (less than three items), close the row
    if ($count % 3 !== 0) {
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
