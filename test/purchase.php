<?php
session_start();
include 'dbconn.php';

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

// Check if the gameID is provided in the URL
if (isset($_GET['gameID'])) {
    $gameID = $_GET['gameID'];

    // Fetch game details from the database based on the gameID
    $gameSql = "SELECT * FROM game WHERE gameID = ?";
    $stmt = $conn->prepare($gameSql);
    $stmt->bind_param("i", $gameID);
    $stmt->execute();
    $gameResult = $stmt->get_result();

    if ($gameResult->num_rows > 0) {
        $game = $gameResult->fetch_assoc();

        // Fetch user subscription details using the username
        $username = $_SESSION['username']; // Assuming you have 'username' in your session
        $user = getUserByUsername($conn, $username);

        if ($user) {
            $discount = 0; // Default discount when no subscription
            $subscriptionSql = "SELECT s.subID, s.typeID, t.discount
                                FROM subscription s
                                INNER JOIN membershiptype t ON s.typeID = t.typeID
                                WHERE s.subID = ?";
            $stmt = $conn->prepare($subscriptionSql);
            $stmt->bind_param("i", $user['subID']);
            $stmt->execute();
            $subscriptionResult = $stmt->get_result();

            if ($subscriptionResult->num_rows > 0) {
                $subscription = $subscriptionResult->fetch_assoc();
                $discount = $subscription['discount'];
            }

            // Calculate the discounted price
            $discountedPrice = $game['price'] * (1 - ($discount / 100));

            // Check if the confirmation form is submitted
            if (isset($_POST['confirmPurchase'])) {
                // Insert purchase details into the 'purchase' table
                $userID = $user['userID'];
                $paymentMethod = $_POST['paymentMethod'];
                $date = date("Y-m-d H:i:s"); // Current date and time
                $amount = $discountedPrice;

                $insertPurchaseSql = "INSERT INTO purchase (userID, paymentMethod, date, amount, gameID)
                                      VALUES (?, ?, ?, ?, ?)";
                $stmt = $conn->prepare($insertPurchaseSql);
                $stmt->bind_param("ssssi", $userID, $paymentMethod, $date, $amount, $gameID);

                if ($stmt->execute()) {
                    header("Location: http://localhost/gamehub/test/mygame.php");
                    exit();
                } else {
                    $purchaseError = true;
                }
            }

            // Check if the cancel form is submitted
            if (isset($_POST['cancelPurchase'])) {
                // Delete the pending purchase entry
                $deletePurchaseSql = "DELETE FROM purchase WHERE userID = ? AND gameID = ? AND paymentMethod IS NULL";
                $stmt = $conn->prepare($deletePurchaseSql);
                $stmt->bind_param("ii", $userID, $gameID);
                $stmt->execute();

                header("Location: http://localhost/gamehub/test/mygame.php");
                exit();
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Purchase Confirmation</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .container {
            margin-top: 50px;
        }
        .card {
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>
<body>

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
        </ul>
    </div>
    <?php echo '<p class="lead" style="color: white; margin-right: 20px;">Hi ' . $_SESSION['username'] . '! </p>'; ?>
</nav>

<div class="container">
    <?php if (isset($purchaseError)): ?>
        <div class="card p-4">
            <h2 class="text-danger">Error processing your purchase!</h2>
            <p>There was an issue completing your purchase. Please try again later.</p>
            <p><a href="http://localhost/gamehub/test/mygame.php" class="btn btn-primary">Back to My Games</a></p>
        </div>
    <?php elseif (isset($_POST['confirmPurchase'])): ?>
        <div class="card p-4">
            <h2 class="text-success">Thank you for your purchase!</h2>
            <p>Your payment has been confirmed.</p>
            <p><a href="http://localhost/gamehub/test/mygame.php" class="btn btn-primary">Back to My Games</a></p>
        </div>
    <?php else: ?>
        <div class="card p-4">
            <h2>Confirm Purchase</h2>
            <p>Game details:</p>
            <ul>
                <li><strong>Game:</strong> <?php echo $game['gameName']; ?></li>
                <li><strong>Original Price:</strong> $<?php echo $game['price']; ?></li>
                <li><strong>Discount Percentage:</strong> <?php echo $discount; ?>%</li>
                <li><strong>Discounted Price:</strong> $<?php echo number_format($discountedPrice, 2); ?></li>
            </ul>
            <p>Amount to pay: $<?php echo number_format($discountedPrice, 2); ?></p>

            <!-- Confirmation form -->
            <form method="post" action="">
                <div class="form-group">
                    <label for="paymentMethod">Select Payment Method:</label>
                    <select class="form-control" name="paymentMethod" id="paymentMethod" required>
                        <option value="Credit Card">Credit Card</option>
                        <option value="PayPal">PayPal</option>
                    </select>
                </div>
                <button type="submit" name="confirmPurchase" class="btn btn-success">Confirm Purchase</button>

                <!-- Cancel form -->
                <button type="submit" name="cancelPurchase" class="btn btn-danger">Cancel Purchase</button>
            </form>
        </div>
    <?php endif; ?>
</div>

<!-- Add Bootstrap JS and Popper.js (required for Bootstrap dropdowns) -->
<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>

</body>
</html>
