<?php
session_start();
include 'dbconn.php';


function getUserByUsername($conn, $username) {
    $query = "SELECT * FROM user WHERE username = '$username'";
    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        return $result->fetch_assoc();
    }

    return null;
}

// Check if the user is logged in
if (isset($_SESSION['username'])) {
    $username = $_SESSION['username'];

    // Fetch user details using the username
    $user = getUserByUsername($conn, $username);

    // Initialize $membershipType to avoid undefined variable warning
    $membershipType = '';

    if ($user) {
        // Retrieve the games purchased by the user from the 'purchase' table
        $userID = $user['userID'];
        $purchaseSql = "SELECT gameID FROM purchase WHERE userID = $userID";
        $purchasedGamesResult = $conn->query($purchaseSql);

        // Store the purchased game IDs in an array
        $purchasedGames = [];
        while ($purchaseRow = $purchasedGamesResult->fetch_assoc()) {
            $purchasedGames[] = $purchaseRow['gameID'];
        }

        // If the user has purchased games, exclude them from the main query
        if (!empty($purchasedGames)) {
            $excludeGames = implode(',', $purchasedGames);
            $sql = "SELECT gameID, gameName, gameDesc, logo, releaseDate, developer, publisher, price FROM game WHERE gameID NOT IN ($excludeGames)";
        } else {
            $sql = "SELECT gameID, gameName, gameDesc, logo, releaseDate, developer, publisher, price FROM game";
        }

        $result = mysqli_query($conn, $sql) or die(mysqli_error($conn));

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

        // Display the retrieved data in a list of boxed sections
        ?>

        <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Game Data View</title>
            <link rel="shortcut icon" type="image/x-icon" href="logo.jpg">
            <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" rel="stylesheet">
            <link rel="stylesheet" href="styleindexuser.css">
            <link rel="stylesheet" href="animation.css">
        </head>
        <body class="a">

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
                        <a class="nav-link" href="view_profile.php">My Profile</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="includes/logout.php">Logout</a>
                    </li>

                    <?php
                    // Add a new button if the user has a 'Premium' membership with an 'Active' subscription
                    if ($membershipType == 'Premium' && $subStatus == 'Active') {
                        echo '<li class="nav-item">';
                        echo '<a class="nav-link" href="event.php">Event</a>';
                        echo '</li>';
                    }
                    ?>

                </ul>
            </div>
            <?php echo '<p class="lead" style="color: white; margin-right: 20px;">Hi ' . $_SESSION['username'] . '! </p>'; ?>
        </nav>
    </header>

        <div class="container-animation">
            <div class = "bubbles">
                <span style="--i:11;"></span>
                <span style="--i:12;"></span>
                <span style="--i:24;"></span>
                <span style="--i:10;"></span>
                <span style="--i:14;"></span>
                <span style="--i:23;"></span>
                <span style="--i:18;"></span>
                <span style="--i:16;"></span>
                <span style="--i:19;"></span>
                <span style="--i:20;"></span>
                <span style="--i:22;"></span>
                <span style="--i:25;"></span>
                <span style="--i:18;"></span>
                <span style="--i:21;"></span>
                <span style="--i:15;"></span>
                <span style="--i:13;"></span>
                <span style="--i:26;"></span>
                <span style="--i:17;"></span>
                <span style="--i:13;"></span>
                <span style="--i:28;"></span>
                <span style="--i:11;"></span>
                <span style="--i:12;"></span>
                <span style="--i:24;"></span>
                <span style="--i:10;"></span>
                <span style="--i:14;"></span>
                <span style="--i:23;"></span>
                <span style="--i:18;"></span>
                <span style="--i:16;"></span>
                <span style="--i:19;"></span>
                <span style="--i:20;"></span>
                <span style="--i:22;"></span>
                <span style="--i:25;"></span>
                <span style="--i:18;"></span>
                <span style="--i:21;"></span>
                <span style="--i:15;"></span>
                <span style="--i:13;"></span>
                <span style="--i:26;"></span>
                <span style="--i:17;"></span>
                <span style="--i:13;"></span>
                <span style="--i:28;"></span>
                
            </div>
        </div>



        <div class="header-box text-center py-5">
            <h1 class="display-4 font-weight-bold">Welcome to <span class="highlight text-warning">GameHub</span></h1>
            <p class="lead">Your ultimate destination for online gaming! 🎮</p>
        </div>

        <section class="container mt-4 text-center">
            <?php
            echo '<div class="user-greeting text-center">';
            echo '<p class="display-4 text-white mb-4">Greetings, <span class="text-primary">' . $_SESSION['username'] . '</span>!</p>';
            echo '</div>';
            ?>
        </section>

        <div class="card-container">
            <?php
            // Loop through the fetched data and generate cards
            while ($row = mysqli_fetch_assoc($result)) {
                echo '<div class="card">';
                echo '<img src="data:image/jpeg;base64,' . $row['logo'] . '" alt="Game Logo">';
                echo '<div class="card-content">';
                echo '<h3>' . $row['gameName'] . '</h3>';
                echo '<p>' . $row['gameDesc'] . '</p>';
                echo "<p>RM " . $row['price'] . "</p>";

                // Check if the game is already purchased
                if (in_array($row['gameID'], $purchasedGames)) {
                    echo '<p style="color: green;">Already Purchased</p>';
                } else {
                    echo '<a href="gameinfouser.php?id=' . $row['gameID'] . '" class="btnbuy">Buy</a>';
                }

                echo '</div></div>';
            }
            ?>
        </div>

        <footer>
    <div class="footer-box">
        <p>&copy; 2023 GameHub. All rights reserved. 🎮</p>
    </div>

        <!-- Add Bootstrap JS and Popper.js (required for Bootstrap dropdowns) -->
        <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>

        </body>
        </html>

        <?php
    } else {
        echo '<p>Error: User not found!</p>';
    }
} else {
    // Redirect to login page if the user is not logged in
    header("Location: login.php");
    exit();
}
?>
