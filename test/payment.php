<?php
session_start();
require_once 'dbconn.php';

function getSubscriptionDetails($conn, $subID) {
    $query = "SELECT * FROM subscription WHERE subID = '$subID'";
    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        return $result->fetch_assoc();
    }

    return null;
}

$startDate = $endDate = $amount = $paymentMethod = '';

if (isset($_GET['subID'])) {
    $subID = $_GET['subID'];
    $subscriptionDetails = getSubscriptionDetails($conn, $subID);

    if ($subscriptionDetails) {
        $startDate = $subscriptionDetails['startDate'];
        $endDate = $subscriptionDetails['endDate'];
        $amount = $subscriptionDetails['amount'];
        $paymentMethod = $subscriptionDetails['paymentMethod'];
    } else {
        echo "Invalid subscription ID.";
        exit();
    }
} else {
    echo "Subscription ID is missing.";
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['confirm_payment'])) {
    $updateQuery = "UPDATE subscription SET subStatus = 'Active' WHERE subID = '$subID'";
    if ($conn->query($updateQuery) === TRUE) {
        $latestSubIDQuery = "SELECT MAX(subID) AS latestSubID FROM subscription";
        $latestSubIDResult = $conn->query($latestSubIDQuery);
        $latestSubIDRow = $latestSubIDResult->fetch_assoc();
        $latestSubID = $latestSubIDRow['latestSubID'];

        $username = $_SESSION['username'];
        $updateUserQuery = "UPDATE user SET subID = '$latestSubID' WHERE username = '$username'";
        $conn->query($updateUserQuery);

        header("Location: subscription_page.php");
        exit();
    } else {
        $confirmationMessage = "Error updating subscription status.";
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['cancel_payment'])) {
    $deleteQuery = "DELETE FROM subscription WHERE subID = '$subID'";
    $conn->query($deleteQuery);

    header("Location: subscription_page.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Confirmation</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="styleindexuser.css">

</head>
<body class="b">

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

<section class="payment-container">
    <h2 class="animate_animated animate_fadeIn">Payment Confirmation</h2>

    <?php
    if (isset($confirmationMessage)) {
        echo "<div class='alert alert-info animate_animated animate_fadeIn'>$confirmationMessage</div>";
    }
    ?>

    <form method="post" action="" class="animate_animated animate_fadeIn">
        <p><strong>Subscription Start Date:</strong> <?php echo $startDate; ?></p>
        <p><strong>Subscription End Date:</strong> <?php echo $endDate; ?></p>
        <p><strong>Amount:</strong> RM <?php echo $amount; ?></p>
        <p><strong>Payment Method:</strong> <?php echo $paymentMethod; ?></p>

        <button type="submit" name="confirm_payment" class="btn btn-success">Confirm Payment</button>
        <button type="submit" name="cancel_payment" class="btn btn-danger">Cancel Payment</button>
    </form>
</section>

<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"></script>

</body>
</html>