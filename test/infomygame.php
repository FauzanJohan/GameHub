<?php
session_start();
require_once 'dbconn.php';

// Check if session variables are set
if (!isset($_SESSION['userid']) || !isset($_SESSION['username'])) {
    // Redirect to index.php if not logged in
    header("Location: index.php");
    exit();
}
// Function to retrieve user data by username
function getUserByUsername($conn, $username) {
    $query = "SELECT * FROM user WHERE username = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        return $result->fetch_assoc();
    }

    return null;
}

// Check if 'id' is set in the URL
if (isset($_GET['gameID'])) {
    // Get the game ID from the URL
    $gameID = mysqli_real_escape_string($conn, $_GET['gameID']);

    // Create SQL statement and run the query for the selected game
    $sql = "SELECT gameID, gameName, gameDesc, logo, releaseDate, developer, publisher, price FROM game WHERE gameID = $gameID";
    $result = mysqli_query($conn, $sql) or die(mysqli_error($conn));

    // Fetch the game details
    $gameDetails = mysqli_fetch_assoc($result);

    // Close the database connection
    $conn->close();
} else {
    // Redirect the user if 'id' is not set in the URL
    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome to GameHub</title>
    <link rel="shortcut icon" type="x-icon" href="GameHub logo.png">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="styleindexuser.css">
</head>
<body>

<header>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <a class="navbar-brand" href="indexuser.php">GameHub</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" href="mygame.php">My Games</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="subscription_page.php">Subscription</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="viewmembership.php">My Profile</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="includes/logout.php">Logout</a>
                </li>
            </ul>
        </div>
    </nav>
</header>

<div class="breadcrumb-container">
    <div class="breadcrumb">
        <a href="index.php">Home</a>
        <span class="divider">/</span>
        <a href="index.php">Game</a>
        <span class="divider">/</span>
        <span class="active"><?php echo $gameDetails['gameName']; ?></span>
    </div>
</div>

<section class="game-details">
    <img src="data:image/jpeg;base64,<?php echo $gameDetails['logo']; ?>" alt="Game Logo">
    <div class="card-content">
        <h3><?php echo $gameDetails['gameName']; ?></h3>
        <p>Game Description: <?php echo $gameDetails['gameDesc']; ?></p>
        <p>Release Date: <?php echo $gameDetails['releaseDate']; ?></p>
        <p>Developer: <?php echo $gameDetails['developer']; ?></p>
        <p>Publisher: <?php echo $gameDetails['publisher']; ?></p>
        <h4>RM <?php echo $gameDetails['price']; ?></h4>
        &nbsp;&nbsp
        <a href="purchase.php?gameID=<?php echo $gameDetails['gameID']; ?>" class="btnbuy">Buy</a>
    </div>
</section>

<footer>
    <div class="footer-box">
        <p>&copy; 2023 GameHub. All rights reserved. 🎮</p>
    </div>
</footer>

</body>
</html>
