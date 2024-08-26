<?php
session_start();
include '../dbconn.php';
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

// Check if the user has the required role for accessing this page
$allowedRoleID = 1; // Assuming roleID 1 corresponds to the 'Administrator' role
if ($userRow['roleID'] != $allowedRoleID) {
    // Redirect to a restricted access page or show an error message
    header("Location: restricted_access.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Purchase List - GameHubb Admin</title>
    <link rel="shortcut icon" type="image/x-icon" href="../logo.jpg">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="style.css">
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <a class="navbar-brand" href="../indexadmin.php">GameHub Admin</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" href="../admin/viewgame.php">Games</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="../admin/viewgenre.php">Genres</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="../admin/viewmembership.php">Membership Type</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="../admin/viewsubscription.php">Subscription</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="../admin/viewpurchase.php">Purchase</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="../admin/viewuser.php">User</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="../admin/viewadmin.php">Admin</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="../includes/logout.php">Log Out</a>
                </li>
            </ul>
        </div>
</nav>
<br>
    <div class="container">
        <div class="table-container">
            <h1>Purchase List</h1>
            <div class="search-form">
                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="GET">
                    <input type="text" name="search" placeholder="Search User ID, Payment Method, or Date">
                    <button type="submit" class="btn btn-primary">Search</button>
                </form>
            </div>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Purchase ID</th>
                        <th>User ID</th>
                        <th>Payment Method</th>
                        <th>Date</th>
                        <th>Amount</th>
                        <th>Game Name</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Connect to the database
                    $conn = mysqli_connect("localhost", "root", "", "gamehubb");
                    if (!$conn) {
                        die("Connection failed: " . mysqli_connect_error());
                    }

                    // Set the search term
                    $searchTerm = isset($_GET['search']) ? $_GET['search'] : '';

                    // Prepare the query with the search term and join with the 'game' table
                    $query = "SELECT purchase.*, game.gameName FROM purchase
                            LEFT JOIN game ON purchase.gameID = game.gameID
                            WHERE (purchase.userID LIKE '%$searchTerm%' OR purchase.paymentMethod LIKE '%$searchTerm%' OR purchase.date LIKE '%$searchTerm%')";

                    // Get the search results
                    $result = mysqli_query($conn, $query);

                    // Loop through the results and display them in the table
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo "<tr>";
                        echo "<td>" . $row['purchaseID'] . "</td>";
                        echo "<td>" . $row['userID'] . "</td>";
                        echo "<td>" . $row['paymentMethod'] . "</td>";
                        echo "<td>" . $row['date'] . "</td>";
                        echo "<td>" . $row['amount'] . "</td>";
                        echo "<td>" . $row['gameName'] . "</td>";

                        echo "</tr>";
                    }

                    // Close the connection
                    mysqli_close($conn);
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
