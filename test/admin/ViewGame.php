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


// Function to retrieve game data with genre name
function getGameData() {
    global $conn;
    $sql = "SELECT g.gameID, g.gameName, g.genreID, g.gameDesc, g.logo, g.releaseDate, g.developer, g.publisher, g.price, ge.genreName
            FROM game g
            JOIN genre ge ON g.genreID = ge.genreID";
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
// Retrieve genres from the database
$genreSql = "SELECT * FROM genre";
$genreResult = $conn->query($genreSql);

$genres = array();

if ($genreResult->num_rows > 0) {
    while ($row = $genreResult->fetch_assoc()) {
        $genres[] = $row;
    }
}
$allowedRoleID = 1; // Assuming roleID 1 corresponds to the 'Administrator' role
if ($userRow['roleID'] != $allowedRoleID) {
    // Redirect to a restricted access page or show an error message
    header("Location: restricted_access.php");
    exit();
}
$currentPage = pathinfo($_SERVER['PHP_SELF'], PATHINFO_FILENAME);

// Display the retrieved data in a list of boxed sections
$data = getGameData();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Game Data View</title>
    <link rel="shortcut icon" type="image/x-icon" href="../logo.jpg">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="styleviewgame.css">

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

    <!-- Header Section -->
    <section id="header">
        <h2>Game Data View</h2>
        <a href="addgame.php" class="btn btn-primary mb-3">Add Game</a>
    </section>

    <!-- Game Sections -->
    <section id="game-container">
        <?php $count = 0; ?>
        <?php foreach ($data as $row): ?>
            <section class="game-section" id="game-<?php echo $row['gameID']; ?>">
            <img class="game-section img" src="data:image/jpeg;base64,<?php echo $row['logo']; ?>" alt="Game Logo">
                <h3><?php echo $row['gameName']; ?></h3>
                <p><strong>Genre:</strong> <?php echo $row['genreName']; ?></p>
                <p><strong>Description:</strong> <?php echo $row['gameDesc']; ?></p>
                <p><strong>Release Date:</strong> <?php echo $row['releaseDate']; ?></p>
                <p><strong>Developer:</strong> <?php echo $row['developer']; ?></p>
                <p><strong>Publisher:</strong> <?php echo $row['publisher']; ?></p>
                <p><strong>Price:</strong> <?php echo $row['price']; ?></p>
                <p>
                    <a href="updategame.php?gameID=<?php echo $row['gameID']; ?>">Update</a>
                    <a href="javascript:void(0);" onclick="confirmDelete(<?php echo $row['gameID']; ?>)">Delete</a>
                    <form id="deleteForm<?php echo $row['gameID']; ?>" method="post" action="deletegame.php">
                        <input type="hidden" name="gameID" value="<?php echo $row['gameID']; ?>">
                    </form>
                </p>
            </section>

            <?php
            $count++;
            if ($count % 3 === 0) {
                echo '<div class="clear"></div>'; // Add a clearfix after every 3 games
            }
            ?>
        <?php endforeach; ?>
    </section>

    <!-- JavaScript Section -->
    <section id="javascript">
        <script>
            function confirmDelete(gameID) {
                var confirmDelete = confirm("Are you sure you want to delete this game?");
                
                if (confirmDelete) {
                    document.forms["deleteForm" + gameID].submit();
                }
            }
        </script>
    </section>

    <!-- Add Bootstrap JS and Popper.js (required for Bootstrap dropdowns) -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>

</body>
</html>
