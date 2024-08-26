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

// Function to retrieve genre data
function getGenreData() {
    global $conn;
    $sql = "SELECT * FROM genre";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $data = array();
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
        return $data;
    } else {
        return array();
    }
}

$allowedRoleID = 1; // Assuming roleID 1 corresponds to the 'Administrator' role
if ($userRow['roleID'] != $allowedRoleID) {
    // Redirect to a restricted access page or show an error message
    header("Location: restricted_access.php");
    exit();
}

// Display the retrieved data in a list of boxed sections
$genreData = getGenreData();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Genre Data</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <a class="navbar-brand" href="../indexadmin.php">GameHub Admin</a>
    <link rel="shortcut icon" type="image/x-icon" href="../logo.jpg">
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
<div class="container mt-4">
    <h2>Genre Data</h2>
    <a href="addgenre.php" class="btn btn-primary mb-3">Add Genre</a>
    <table class="table">
        <thead class="thead-dark">
            <tr>
                <th scope="col">Genre ID</th>
                <th scope="col">Genre Name</th>
                <th scope="col">Genre Description</th>
                <th scope="col">Actions</th>
            </tr>
        </thead>
        <tbody>
            <!-- Replace the following with actual PHP logic to fetch and display data from your database -->
            <?php foreach ($genreData as $genre): ?>
                <tr>
                    <th scope="row"><?php echo $genre['genreID']; ?></th>
                    <td><?php echo $genre['genreName']; ?></td>
                    <td><?php echo $genre['genreDesc']; ?></td>
                    <td>
                        <a href="updategenre.php?genreID=<?php echo $genre['genreID']; ?>">Edit</a>
                        <a href="deletegenre.php?genreID=<?php echo $genre['genreID']; ?>">Delete</a>
                    </td>
                </tr>
            <?php endforeach; ?>
            <!-- End of dynamic data -->
        </tbody>
    </table>
</div>

<!-- Add Bootstrap JS and Popper.js (required for Bootstrap dropdowns) -->
<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>

</body>
</html>
