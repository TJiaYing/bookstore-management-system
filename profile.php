<!DOCTYPE html>
<html>
<head>
    <meta name="viewport" content="with=device-width, initial-scale=1.0">
  <title>Profile</title>
  <link rel="stylesheet" href="home_page.css">
  <link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Shantell+Sans:wght@300;500;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@6.2.1/css/fontawesome.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
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
        <h1>PROFILE INFO</h1>
    </div>
  </section>
  <div class="profile-container">
        <div class="profile-info">
        <h1 class="mb-4">My Profile</h1>
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

                // Retrieve user data from the database based on email
                $email = $_SESSION['email']; // Retrieve email from the session
                $sql = "SELECT * FROM tbl_users WHERE user_email='$email'";
                $result = $conn->query($sql);

                if ($result->num_rows > 0) {
                    $row = $result->fetch_assoc();
                    $name = $row['user_name'];
                    $phonenumber = $row['user_phone'];
                    $address = $row['user_address'];
                }

                $conn->close();
        ?>
                <p><strong>Name:</strong> <?php echo $name; ?></p>
                <p><strong>Phone Number:</strong> <?php echo $phonenumber; ?></p>
                <p><strong>Address:</strong> <?php echo $address; ?></p>
                <p><strong>Email:</strong> <?php echo $email; ?></p>

        <?php
            } else {
                // If the user is not logged in, display a message
                echo "<p>Please login to view your profile.</p>";
            }
        ?>
        </div>
        </div><br>
      </div><hr>
      <h2>Update Profile Information</h2>
            <form action="update_profile.php" method="post">
            <div class="mb-3">
                    <label for="name" class="form-label">Name:</label>
                    <input type="text" id="name" name="name" class="form-control" value="<?php echo $name; ?>" required>
                </div><br>
                <div class="mb-3">
                    <label for="email" class="form-label">Email:</label>
                    <input type="email" id="email" name="email" class="form-control" value="<?php echo $email; ?>" required>
                </div><br>
                <div class="mb-3">
                    <label for="phonenumber" class="form-label">Phone Number:</label>
                    <input type="tel" id="phonenumber" name="phonenumber" class="form-control" value="<?php echo $phonenumber; ?>" required>
                </div><br>
                <div class="mb-3">
                    <label for="address" class="form-label">Address:</label>
                    <input type="text" id="address" name="address" class="form-control" value="<?php echo $address; ?>" required>
                </div><br>
                <div class="mb-3">
                    <label for="password" class="form-label">Password:</label>
                    <input type="password" id="password" name="password" class="form-control" required>
                </div><br>
                <div class="text-center">
                    <button type="submit" class="btn btn-primary">Update</button>
                </div><br>
            </form>
        </div>
    </div>
</body>
</html>