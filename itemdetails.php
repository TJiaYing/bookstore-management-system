<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Details</title>
    <!-- Link to Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>

<body>
    <div class="container">
        <div class="text-center">
            <!-- Display book details here (as per previous example) -->
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

// Check if the 'id' parameter is provided in the URL
if (isset($_GET['id'])) {
    $item_id = $_GET['id'];

    // Retrieve book details from the database based on the item_id
    $sql = "SELECT * FROM tbl_items WHERE item_id = $item_id";
    $result = $conn->query($sql);

    if ($result->num_rows === 1) {
        $row = $result->fetch_assoc();
        // Display book details
        echo '<h1>Book Details</h1>';
        // Display the book image (if available)
        if (isset($row["bookimage"])) {
            echo '<img src="bookimages/' . $row["bookimage"] . '" alt="Book Image">';
        } else {
            echo '<img src="bookimages/default_image.png" alt="Default Image">';
        }
        echo '<h2>' . $row['item_name'] . '</h2>';
        echo '<p>Book Type: ' . $row['item_type'] . '</p>';
        echo '<p>Quantity: ' . $row['item_quantity'] . '</p>';
        echo '<p>Description: ' . $row['item_description'] . '</p>';
        echo '<p>Price: RM ' . $row['item_price'] . '</p>';
        echo '<p>Category: ' . $row['item_category'] . '</p>';
    } else {
        echo "Book not found.";
    }
} else {
    echo "Invalid request. Please provide a valid book ID.";
}

$conn->close();
?>
        </div>

        <!-- Back button -->
        <div class="mt-4 text-center">
            <a href="javascript:history.back()" class="btn btn-primary">Back</a>
        </div>
    </div><br>

    <!-- Link to Bootstrap JS and jQuery (required for some Bootstrap features) -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.1/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>

