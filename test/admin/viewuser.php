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
    <title>User List - GameHubb Admin</title>
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
            <h1>User List</h1>
            <div class="search-form">
                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="GET">
                    <input type="text" name="search" placeholder="Search Username, Name, or Email">
                    <button type="submit" class="btn btn-primary">Search</button>
                </form>
            </div>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Username</th>
                        <th>First Name</th>
                        <th>Last Name</th>
                        <th>Email</th>
                        <th>Password</th>
                        <th>Role</th>
                        <th>Date of Birth</th>
                        <th>Gender</th>
                        <th>Action</th>
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

                    // Prepare the query with the search term and JOIN with the 'role' table
                    $query = "SELECT user.*, role.roleName FROM user
                            LEFT JOIN role ON user.roleID = role.roleID
                            WHERE (user.username LIKE '%$searchTerm%' OR user.firstName LIKE '%$searchTerm%' OR user.email LIKE '%$searchTerm%') AND user.roleID = 2"; //

                    // Get the search results
                    $result = mysqli_query($conn, $query);

                    // Loop through the results and display them in the table
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo "<tr>";
                        echo "<td>" . $row['username'] . "</td>";
                        echo "<td>" . $row['firstName'] . "</td>";
                        echo "<td>" . $row['lastName'] . "</td>";
                        echo "<td>" . $row['email'] . "</td>";
                        echo "<td>" . $row['password'] . "</td>";
                        echo "<td>" . $row['roleName'] . "</td>"; // Display role name instead of role ID
                        echo "<td>" . $row['dob'] . "</td>";
                    
                        echo "<td>" . $row['gender'] . "</td>";
                                       
                        // Add Delete button with a link to deleteadmin.php
                        echo "<td><a href='deleteadmin.php?id=" . $row['userID'] . "' class='btn btn-danger' onclick='return confirm(\"Are you sure you want to delete this admin?\");'>Delete</a></td>";
                    
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
