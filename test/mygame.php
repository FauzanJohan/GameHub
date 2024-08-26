<?php
session_start();
include 'dbconn.php';

// Function to retrieve user data by username
function getUserByUsername($conn, $username) {
    $query = "SELECT * FROM user WHERE username = '$username'";
    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        return $result->fetch_assoc();
    }

    return null;
}

// Function to retrieve purchased games by the user
function getPurchasedGames($conn, $userID) {
    $purchaseSql = "SELECT p.purchaseID, g.gameName, g.logo, p.date, p.amount
                    FROM purchase p
                    INNER JOIN game g ON p.gameID = g.gameID
                    WHERE p.userID = $userID";

    $purchaseResult = $conn->query($purchaseSql);

    $games = array();

    if ($purchaseResult->num_rows > 0) {
        while ($row = $purchaseResult->fetch_assoc()) {
            $games[] = $row;
        }
    }

    return $games;
}

// Check if the user is logged in
if (isset($_SESSION['username'])) {
    $username = $_SESSION['username'];

    // Fetch user details using the username
    $user = getUserByUsername($conn, $username);

    if ($user) {
        // Retrieve the games purchased by the user
        $userID = $user['userID'];
        $purchasedGames = getPurchasedGames($conn, $userID);
    } else {
        echo '<p>Error: User not found!</p>';
    }
} else {
    // Redirect to login page if the user is not logged in
    header("Location: login.php");
    exit();
}

// Retrieve subscription details
$userSubscriptionSql = "SELECT u.username, s.subID, s.typeID, s.subStatus
 FROM user u
 INNER JOIN subscription s ON u.subID = s.subID
 WHERE u.username = '{$_SESSION['username']}'";

$userSubscriptionResult = mysqli_query($conn, $userSubscriptionSql) or die(mysqli_error($conn));

$userSubscriptionRow = mysqli_fetch_assoc($userSubscriptionResult);

mysqli_free_result($userSubscriptionResult);

// If the user has a subscription, fetch the subscription type information
if ($userSubscriptionRow) {
    $subscriptionTypeID = $userSubscriptionRow['typeID'];
    $subStatus = $userSubscriptionRow['subStatus'];

    $subscriptionTypeSql = "SELECT typeName FROM membershiptype WHERE typeID = $subscriptionTypeID";
    $subscriptionTypeResult = mysqli_query($conn, $subscriptionTypeSql) or die(mysqli_error($conn));

    $subscriptionTypeRow = mysqli_fetch_assoc($subscriptionTypeResult);

    // Close the subscription type query
    mysqli_free_result($subscriptionTypeResult);

    // Assign the value to $membershipType
    $membershipType = $subscriptionTypeRow['typeName'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Purchased Games</title>
    <link rel="shortcut icon" type="image/x-icon" href="logo.jpg">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="styleindexuser.css">
</head>
<body class="a">

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
                <a class="nav-link" href="view_profile.php">My Profile</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="includes/logout.php">Logout</a>
            </li>

            <?php
            if (isset($_SESSION['username'])) {
                if (isset($membershipType) && $membershipType == 'Premium' && $subStatus == 'Active') {
                    echo '<li class="nav-item">';
                    echo '<a class="nav-link" href="event.php">Event</a>';
                    echo '</li>';
                }
            }
            ?>
        </ul>
    </div>
    <?php echo '<p class="lead" style="color: white; margin-right: 20px;">Hi ' . $_SESSION['username'] . '! </p>'; ?>
</nav>
<div class="headerpage">
        My Games
    </div>

<div class="container mt-4">
    <?php
    // Display the purchased games in HTML
    if (!empty($purchasedGames)) {
        echo '<h2>Your Purchased Games</h2>';
        echo '<div class="row">';
        foreach ($purchasedGames as $row) {
            echo '<div class="col-md-6">';
            echo '<div class="card">';
            echo '<img src="data:image/jpeg;base64,' . $row['logo'] . '" class="card-img-top" alt="Game Logo">';
            echo '<div class="card-body">';
            echo '<h5 class="card-title">' . $row['gameName'] . '</h5>';
            echo '<p class="card-text">Purchase Date: ' . $row['date'] . '</p>';
            echo '<p class="card-text">Amount: RM ' . number_format($row['amount'], 2) . '</p>';
            
            // Hidden input field for purchaseID
           // Hidden input field for purchaseID
           echo '<form method="post" action="loaderpage.php" target="_blank">';
           echo '<input type="hidden" name="purchaseID" value="' . $row['purchaseID'] . '">';
           // Submit button to view game details
           echo '<button type="submit" class="btn btn-link" name="viewGame">Play the Game</button>';
           echo '</form>';
           echo '</div>';
           echo '</div>';
           echo '</div>';
        }
        echo '</div>';
    } else {
        echo '<p>You haven\'t purchased any games yet.</p>';
    }
    ?>
</div>

<!-- Add Bootstrap JS and Popper.js (required for Bootstrap dropdowns) -->
<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>

</body>
</html>

<footer>
    <div class="footer-box">
        <p>&copy; 2023 GameHub. All rights reserved. 🎮</p>
    </div>
</footer>
