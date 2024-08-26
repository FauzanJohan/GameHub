<?php
session_start();
include 'dbconn.php';

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

// Fetch user details, including role, from the database
$username = $_SESSION['username'];
$userSql = "SELECT roleID FROM user WHERE username = '$username'";
$userResult = mysqli_query($conn, $userSql) or die(mysqli_error($conn));
$userRow = mysqli_fetch_assoc($userResult);

// Check user role and restrict access if necessary
$allowedRoleID = 1; // Assuming roleID 1 corresponds to the 'Administrator' role
if ($userRow['roleID'] != $allowedRoleID) {
    // Redirect to a restricted access page or show an error message
    header("Location: restricted_access.php");
    exit();
}

// Fetch membership types from the database
$sql = "SELECT * FROM membershiptype";
$result = mysqli_query($conn, $sql) or die(mysqli_error($conn));
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GameHub Admin Dashboard</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" rel="stylesheet">
    <link rel="shortcut icon" type="image/x-icon" href="logo.jpg">
    <link rel="stylesheet" href="styleadmin.css">
    <link rel="stylesheet" href="animation.css">
</head>
<body>

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

<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <a class="navbar-brand" href="indexadmin.php">GameHub Admin</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" href="../test/admin/viewgame.php">Games</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="../test/admin/viewgenre.php">Genres</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="../test/admin/viewmembership.php">Membership Type</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="../test/admin/viewsubscription.php">Subscription</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="../test/admin/viewpurchase.php">Purchase</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="../test/admin/viewuser.php">User</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="../test/admin/viewadmin.php">Admin</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="../test/includes/logout.php">Log Out</a>
                </li>
            </ul>
        </div>
</nav>

<!-- Main Content -->
<div class="container mt-4">
    <div class="jumbotron">
        <h1 class="display-4">Welcome to GameHub Admin Dashboard</h1>
        <p class="lead">Manage your games, genres, and membership types with ease.</p>
    </div>

    <!-- Subscription Status Section -->
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Active Subscriptions</h5>
                    <p class="card-text">Total number of users with an active subscription:</p>
                    <?php
                    // Include your database connection file
                    include 'dbconn.php';

                    // Query to get the count of users with an active subscription
                    $activeSubscriptionSql = "SELECT COUNT(*) as count FROM subscription WHERE subStatus = 'Active'";
                    $activeSubscriptionResult = $conn->query($activeSubscriptionSql);

                    if ($activeSubscriptionResult && $activeSubscriptionResult->num_rows > 0) {
                        $activeSubscriptionCount = $activeSubscriptionResult->fetch_assoc()['count'];
                        echo '<p class="lead">' . $activeSubscriptionCount . ' users have an active subscription.</p>';
                    } else {
                        echo '<p class="lead">Error retrieving active subscription count.</p>';
                    }

                    // Close the database connection
                    $conn->close();
                    ?>
                </div>
            </div>
        </div>
    </div>


    <!-- Quick Links -->
<div class="row">
    <div class="col-md-4">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">View Games</h5>
                <p class="card-text">Browse and manage the list of games.</p>
                <a href="viewgame.php" class="btn btn-primary">Go to Games</a>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">View Genres</h5>
                <p class="card-text">Explore and manage game genres.</p>
                <a href="viewgenre.php" class="btn btn-primary">Go to Genres</a>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">View Membership Types</h5>
                <p class="card-text">Check and update membership types.</p>
                <a href="viewmembership.php" class="btn btn-primary">Go to Membership Types</a>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">View User Subscriptions</h5>
                <p class="card-text">Check User Subscriptions Details.</p>
                <a href="viewsubscription.php" class="btn btn-primary">Go to User Subscriptions</a>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">View User Purchase</h5>
                <p class="card-text">Check User Purchase Details.</p>
                <a href="viewpurchase.php" class="btn btn-primary">Go to User Purchase</a>
            </div>
        </div>
    </div>
</div>

</div> 

<!-- Add Bootstrap JS and Popper.js (required for Bootstrap dropdowns) -->
<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>

</body>
</html>
