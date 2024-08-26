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

// Check for the update query parameter
$updateMessage = '';
if (isset($_GET['update']) && $_GET['update'] === 'success') {
    $updateMessage = 'Update Successful';
}

// Set the initial display state based on the update message
$showUpdateMessage = !empty($updateMessage);
echo '<style>';
echo '.update-message { display: ' . ($showUpdateMessage ? 'block' : 'none') . '; }';
echo '</style>';

// Get user data using the function
$userData = getUserByUsername($conn, $username);

// Check if the user is found
if ($userData !== null) {
    // Extract data from the user array

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
    <title>Gaming User Profile</title>
    <link rel="shortcut icon" type="image/x-icon" href="logo.jpg">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Open+Sans&display=swap">
    <link rel="stylesheet" href="styleindexuser.css">
    <link rel="stylesheet" href="animation.css">

    <style>
        
    </style>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            var updateMessage = document.querySelector(".update-message");
            if (updateMessage) {
                setTimeout(function () {
                    updateMessage.style.display = "none";
                }, 3000);
            }
        });
    </script>
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
                        if (isset($_SESSION['username'])) {

                            // Check if $membershipType is set before using it
                            if (isset($membershipType) && $membershipType == 'Premium' && $subStatus == 'Active') {
                                echo '<li class="nav-item">';
                                echo '<a class="nav-link" href="event.php">Event</a>';
                                echo '</li>';
                            }
                        }?>
                </ul>
            </div>
            <?php echo '<p class="lead" style="color: white; margin-right: 20px;">Hi ' . $_SESSION['username'] . '! </p>'; ?>
        </nav>
<body class="b">
    <div class="headerpage">
        Gaming Profile
    </div>
    <!-- Display the pop-up message -->
    <div class="update-message"><?php echo $updateMessage; ?></div>

    <div class="profile-container">
        <div class="user-info">
            <?php
            // Check if profile picture path is available
            if (!empty($userData['profile'])) {
                echo '<img class="profile-picture" src="data:image/jpeg;base64,' . $userData['profile'] . '" alt="Profile Picture">';
            } else {
                echo '<img class="profile-picture" src="default_profile_pic.jpg" alt="Default Profile Picture">';
            }
            ?>

            <div class="name">
                <?php echo $userData['firstName'] . ' ' . $userData['lastName']; ?>
            </div>

            <div class="user-details">
                <div>
                    <div class="icon">&#128101;</div>
                    <div><?php echo $userData['username']; ?></div>
                </div>
                <div>
                    <div class="icon">&#9993;</div>
                    <div><?php echo $userData['email']; ?></div>
                </div>
                <div>
                    <div class="icon">&#128197;</div>
                    <div><?php echo $userData['dob']; ?></div>
                </div>
                <div>
                    <div class="icon">&#9794;</div>
                    <div><?php echo $userData['gender']; ?></div>
                </div>
            </div>

            <?php
            // Check if subscription-related information is available
            if (isset($userData['typeName'], $userData['subStatus'])) {
                $typeName = $userData['typeName'];
                $subStatus = $userData['subStatus'];
                ?>
        <div class="user-container">
            <div class="membership-type">
                Membership Type :
                <div class="type-info <?php echo $typeName; ?>">
                    <div class="typeName-box"><?php echo $typeName; ?></div>
                </div>
            </div>
    
            <div class="user-status">
                Subscription Status :
                <div class="status-info <?php echo $subStatus; ?>">
                    <div class="status-box"><?php echo $subStatus; ?></div>
                </div>
            </div>
        </div>

        <!-- Keep the "Edit" button here -->
    <button class="edit-button" onclick="window.location.href='update_profile.php'">Edit</button>
                <?php
            }
            ?>
        </div>
    </div>

    <footer>
    <div class="footer-box">
        <p>&copy; 2023 GameHub. All rights reserved. 🎮</p>
    </div>
</footer>
</body>
</html>


    <?php
} else {
    echo "User not found in the database.";
}

mysqli_close($conn);
?>
