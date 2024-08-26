<?php
session_start();
require_once 'dbconn.php';

// Check if the user has an active subscription
if (isset($_SESSION['username'])) {
    $username = $_SESSION['username'];

    // Query to get user's subscription details
    $subscriptionQuery = "SELECT s.*, m.typeName
                    FROM subscription s
                    INNER JOIN membershiptype m ON s.typeID = m.typeID
                    INNER JOIN user u ON s.userID = u.userID
                    WHERE u.username = '$username' AND s.subStatus = 'Active'";

    $subscriptionResult = $conn->query($subscriptionQuery);

    if ($subscriptionResult->num_rows > 0) {
        $subscriptionDetails = $subscriptionResult->fetch_assoc();
        ?>
        <!DOCTYPE html>
        <html lang="en">
        <head>
            <title>Subscription Receipt</title>
            <link rel="shortcut icon" type="image/x-icon" href="logo.jpg">
            <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" rel="stylesheet">
            <link rel="stylesheet" href="styleindexuser.css">
            
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
        <div class="receipt-container">
            <div class="receipt-header">
                <h2>Subscription Receipt</h2>
            </div>
            <div class="receipt-details">
                <p><strong>Username:</strong> <?php echo $username; ?></p>
                <p><strong>Subscription Type:</strong> <?php echo $subscriptionDetails['typeName']; ?></p>
                <p><strong>Start Date:</strong> <?php echo $subscriptionDetails['startDate']; ?></p>
                <p><strong>End Date:</strong> <?php echo $subscriptionDetails['endDate']; ?></p>
                <p><strong>Payment Method:</strong> <?php echo $subscriptionDetails['paymentMethod']; ?></p>
                <p><strong>Amount Paid:</strong> RM <?php echo $subscriptionDetails['amount']; ?></p>
            </div>
            <div class="receipt-footer text-right">
                <p>Thank you for subscribing!</p>
                <form method="post" action="">
                <button type="submit" name="backtosub" class="backtosub-button" formaction="subscription_page.php">Back to Subscription Page</button>
                <button type="submit" name="print" class="print-button" onclick="window.print()">Print</button>
                </form>
            
            </div>
        </div>

        <!-- Add Bootstrap JS and Popper.js (required for Bootstrap dropdowns) -->
        <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
        </body>
        </html>
        <?php
    } else {
        // Redirect to subscription_page.php if the user doesn't have an active subscription
        header("Location: subscription_page.php");
        exit();
    }
} else {
    // Redirect to login page if the user is not logged in
    header("Location: login.php");
    exit();
}
?>
