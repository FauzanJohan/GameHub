<?php
session_start();
require_once 'dbconn.php';


function getUserByUsername($conn, $username) {
    $query = "SELECT u.profile, u.username, u.firstName, u.lastName, u.email, u.dob, u.gender, mt.typeName, s.subStatus
        FROM user u
        LEFT JOIN subscription s ON u.subID = s.subID
        LEFT JOIN membershiptype mt ON s.typeID = mt.typeID
        WHERE u.username = '$username'";
    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        return $result->fetch_assoc();
    }

    return null;
}

// Get the username of the logged-in user
if (isset($_SESSION['username'])) {
    $username = $_SESSION['username'];
} else {
    header("Location: login.php");
    exit();
}

// Get the username of the logged-in user
$userData = getUserByUsername($conn, $username);

// Query the database for the information of the logged-in user
$sql = "SELECT u.profile, u.username, u.firstName, u.lastName, u.email, u.dob, u.profile, u.gender, mt.typeName, s.subStatus
    FROM user u
    JOIN subscription s ON u.subID = s.subID
    JOIN membershiptype mt ON s.typeID = mt.typeID
    WHERE username = '$username'";
$result = mysqli_query($conn, $sql);

// Check if the user is a premium member with an active subscription
if ($row = mysqli_fetch_assoc($result)) {
    $membershipType = $row['typeName'];
    $subStatus = $row['subStatus'];

    if ($membershipType == 'Premium' && $subStatus == 'Active') {
        // User is a premium member with an active subscription, display game events
        ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Game Events</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="styleindexuser.css">

    <link rel="shortcut icon" type="image/x-icon" href="logo.jpg">
</head>
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
        <div class="headerpage">
        Events
    </div>

        <body class="event">

            <div class="game-container">
                <img src="images/Bronze.jpg" alt="game">
                <div class="game-details">
                    <h2>Fortnight</h2>
                    <h1>Bronze Ranked Cup Solo</h1>
                    <p>Time: 3:00 PM - 7:00 PM </p>
                    <p>Location: KLCC, Level 3, Game Center </p>
                    <p>About: Join us for an afternoon of fun and games. The tournament is open and only for bronze rank player.</p>
                    <div class="game-button">
                        <a href="https://fortnitetracker.com/events">
                            <button>Tap to learn more</button>
                        </a>
                    </div>
                </div>
            </div>

            <div class="game-container">
                <img src="images/Boston.jpg" alt="game">
                <div class="game-details">
                    <h2>Call of Duty Warface</h2>
                    <h1>BOSTON: BREACH MAJOR 1 TOURNAMENT</h1>
                    <p>Date: 25 - 28 January 2024 </p>
                    <p>Time: 3:00 PM - 7:00 PM </p>
                    <p>Location: MGM Music Hall at Fenwey, BOSTON MA</p>
                    <p>About: Join us as a spectator for the major tournament in Boston, Limited tickets available!</p>
                    <div class="game-button">
                        <a href="https://www.callofdutyleague.com/en-us">
                            <button>Tap to learn more</button>
                        </a>
                    </div>
                </div>
            </div>

            <div class="game-container">
                <img src="images/gta.jpg" alt="game">
                <div class="game-details">
                    <h2>GTA 5</h2>
                    <h1>Lobby Style - Free for All (30 players)</h1>
                    <p>Date: 2 February 2024</p>
                    <p>Time: 10:00 PM - 12:00 AM </p>
                    <p>Location: Aman Central, Level 7, Game Arena, Kedah </p>
                    <p>About: Join us for a free-for-all tournament. The tournament is open and only for 30 players.</p>
                    <div class="game-button">
                        <a href="https://www.game.tv/find-tournaments/-gta-5--tournaments">
                            <button>Tap to learn more</button>
                        </a>
                    </div>
                </div>
            </div>

        </body>
        </html>

    <?php
    } else {
        // User is not a premium member with an active subscription
        ?>

        <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Game Events</title>

            <style>
                body {
                    text-align: center;
                    font-family: Arial, sans-serif;
                    margin-top: 50px;
                }

                .alert {
                    padding: 20px;
                    background-color: #f44336;
                    color: white;
                    margin-bottom: 15px;
                }
            </style>
        </head>

        <body>
            <div class="alert">
                <strong>Alert!</strong> This page is only accessible to premium members.
            </div>
        </body>
        </html>

    <?php
    }
} else {
    // User data not found
    echo "User data not found.";
}
?>
