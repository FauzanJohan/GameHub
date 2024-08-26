<?php
        session_start();
        require_once 'dbconn.php';
        require_once 'membershiptype.php';

        // Function to get user details by username
        function getUserByUsername($conn, $username) {
            $query = "SELECT * FROM user WHERE username = '$username'";
            $result = $conn->query($query);

            if ($result->num_rows > 0) {
                return $result->fetch_assoc();
            }

            return null;
        }

        // Function to get user's subscription details and membership type
        function getUserSubscription($conn, $username) {
            $query = "SELECT s.*, m.typeName
                    FROM subscription s
                    INNER JOIN membershiptype m ON s.typeID = m.typeID
                    INNER JOIN user u ON s.userID = u.userID
                    WHERE u.username = '$username' AND s.subStatus = 'Active'";
            $result = $conn->query($query);

            if ($result->num_rows > 0) {
                return $result->fetch_assoc();
            }

            return null;
        }

        $basicSubscriptionDetails = getMembershipTypeById(1);
        $premiumSubscriptionDetails = getMembershipTypeById(2);

        $userSubscription = getUserSubscription($conn, $_SESSION['username']);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (isset($_POST['subscribe_basic'])) {
                handleSubscription(1, $basicSubscriptionDetails);
            } elseif (isset($_POST['subscribe_premium'])) {
                handleSubscription(2, $premiumSubscriptionDetails);
            } elseif (isset($_POST['cancel_subscription'])) {
                cancelSubscription($userSubscription['subID']);
            }
        }

        function handleSubscription($typeID, $subscriptionDetails) {
            global $conn;

            if ($subscriptionDetails) {
                $username = isset($_SESSION['username']) ? $_SESSION['username'] : '';
                $userDetails = getUserByUsername($conn, $username);

                if ($userDetails) {
                    $userID = $userDetails['userID'];
                    $startDate = date('Y-m-d'); // Current date as the start date
                    $endDate = date('Y-m-d', strtotime("+1 month")); // Example: Subscription valid for one month

                    // You may want to validate payment here before proceeding

                    // Add subscription to the database
                    $query = "INSERT INTO subscription (userID, startDate, endDate, subStatus, typeID, amount, paymentMethod) 
                            VALUES ('$userID', '$startDate', '$endDate', 'Active', '$typeID', '{$subscriptionDetails['price']}', 'Online Payment')";

                    if ($conn->query($query) === TRUE) {
                        // Get the last inserted ID (subID)
                        $lastInsertedID = $conn->insert_id;

                        // Redirect to payment.php with subID parameter
                        header("Location: payment.php?subID=$lastInsertedID");
                        exit();
                    } else {
                        $message = "Error: " . $query . "<br>" . $conn->error;
                    }
                } else {
                    $message = "Invalid username. User does not exist.";
                }
            } else {
                $message = "Invalid subscription type!";
            }

            if (isset($message)) {
                echo "<div class='alert alert-info'>$message</div>";
            }
        }

        function cancelSubscription($subID) {
            global $conn;
        
            // Check if the subscription ID exists
            $subID = intval($subID); // Ensure $subID is an integer
            $query = "SELECT * FROM subscription WHERE subID = ? LIMIT 1";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("i", $subID);
            $stmt->execute();
            $result = $stmt->get_result();
        
            if ($result->num_rows > 0) {
                // Update subscription status to 'Cancelled'
                $updateQuery = "UPDATE subscription SET subStatus = 'Cancelled' WHERE subID = ?";
                $updateStmt = $conn->prepare($updateQuery);
                $updateStmt->bind_param("i", $subID);
        
                if ($updateStmt->execute()) {
                    // Update user table to set subID to null
                    $nullSubIDQuery = "UPDATE user SET subID = NULL WHERE subID = ?";
                    $nullSubIDStmt = $conn->prepare($nullSubIDQuery);
                    $nullSubIDStmt->bind_param("i", $subID);
        
                    if ($nullSubIDStmt->execute()) {
                        echo "<div class='alert alert-success'>Subscription cancelled successfully.</div>";
                        // Redirect to subscription_page.php
                        header("Location: http://localhost/gamehub/test/subscription_page.php");
                        exit();
                    } else {
                        echo "<div class='alert alert-danger'>Error updating user table: " . $nullSubIDStmt->error . "</div>";
                    }

                    $nullSubIDStmt->close();
                } else {
                    echo "<div class='alert alert-danger'>Error cancelling subscription: " . $updateStmt->error . "</div>";
                }
        
                $updateStmt->close();
            } else {
                echo "<div class='alert alert-warning'>Subscription not found.</div>";
            }
        
            $stmt->close();
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

// Display the retrieved data in a list of boxed sections

        ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Subscription Page</title>
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

        <div class="headerpage">
        Subscription Plans
    </div>

<section class="container mt-4">

    <?php if (!$userSubscription) : ?>
        <div class="subscription-box">
            <!-- Basic Subscription Section -->
            <section class="subscription-section">
                <h3>Basic Subscription</h3>
                <p><strong>Description:</strong> <?php echo $basicSubscriptionDetails['typeDesc']; ?></p>
                <p><strong>Price:</strong> RM <?php echo $basicSubscriptionDetails['price']; ?></p>
                <p><strong>Discount:</strong> <?php echo $basicSubscriptionDetails['discount']; ?>%</p>

                <!-- Add a JavaScript function for subscription confirmation -->
                <form method="post" action="" onsubmit="return confirmSubscription('Basic')">
                    <button type="submit" name="subscribe_basic">Subscribe to Basic</button>
                </form>
            </section>

            <!-- Premium Subscription Section -->
            <section class="subscription-section">
                <h3>Premium Subscription</h3>
                <p><strong>Description:</strong> <?php echo $premiumSubscriptionDetails['typeDesc']; ?></p>
                <p><strong>Price:</strong> RM <?php echo $premiumSubscriptionDetails['price']; ?></p>
                <p><strong>Discount:</strong> <?php echo $premiumSubscriptionDetails['discount']; ?>%</p>

                <!-- Add a JavaScript function for subscription confirmation -->
                <form method="post" action="" onsubmit="return confirmSubscription('Premium')">
                    <button type="submit" name="subscribe_premium">Subscribe to Premium</button>
                </form>
            </section>
        </div>
    <?php endif; ?>

    <?php if ($userSubscription) : ?>
        <div class="container mt-4 subscription-details">
            <h2 class="text-center mb-4">Your Subscription Details</h2>
            <p><strong>Start Date:</strong> <?php echo $userSubscription['startDate']; ?></p>
            <p><strong>End Date:</strong> <?php echo $userSubscription['endDate']; ?></p>
            <p><strong>Status:</strong> <?php echo $userSubscription['subStatus']; ?></p>
            <p><strong>Your subscription:</strong> <?php echo $userSubscription['typeName']; ?></p>

             <!-- Button to cancel subscription and view receipt -->
        <form method="post" action="">
            <button type="submit" name="cancel_subscription" class="cancel-button">Cancel Subscription</button>
            <button type="submit" name="receipt" class="receipt-button" formaction="receipt.php">Receipt</button>
        </form>
        </div>
    <?php endif; ?>
</section>

<footer>
<div class="footer-box">
        © 2024 Gaming Profile. All Rights Reserved.
    </div>
    </footer>

<!-- Add a script for the confirmation popup -->
<script>
    function confirmSubscription(subscriptionType) {
        // Display a confirmation popup
        var confirmation = confirm("Are you sure you want to subscribe to " + subscriptionType + " subscription?");

        // Return true if the user clicked OK, false otherwise
        return confirmation;
    }
</script>

<!-- Add Bootstrap JS and Popper.js (required for Bootstrap dropdowns) -->
<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>

</body>
</html>