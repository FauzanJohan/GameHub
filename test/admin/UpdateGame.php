<?php
include '../dbconn.php';

// Assuming you have a gameID from somewhere, for example, from a URL parameter
$gameID = isset($_GET['gameID']) ? $_GET['gameID'] : 0;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Handle form submission
    $newGameName = $_POST['newGameName'];
    $newGenreID = $_POST['newGenreID'];
    $newGameDesc = $_POST['newGameDesc'];
    $newReleaseDate = $_POST['newReleaseDate'];
    $newDeveloper = $_POST['newDeveloper'];
    $newPublisher = $_POST['newPublisher'];
    $newPrice = $_POST['newPrice'];

    // Check if a file is uploaded
    if ($_FILES['newLogo']['error'] == 0) {
        // Read the uploaded file content
        $newLogoContent = file_get_contents($_FILES['newLogo']['tmp_name']);

        // Convert the binary content to base64 for database storage
        $newLogoBase64 = base64_encode($newLogoContent);

        // File upload successful, update the database with the new file content
        $sql = "UPDATE game SET 
                gameName = ?,
                genreID = ?,
                gameDesc = ?,
                logo = ?,  -- Store base64-encoded content in the database
                releaseDate = ?,
                developer = ?,
                publisher = ?,
                price = ?
                WHERE gameID = ?";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssssssssi", $newGameName, $newGenreID, $newGameDesc, $newLogoBase64, $newReleaseDate, $newDeveloper, $newPublisher, $newPrice, $gameID);
    } else {
        // No file uploaded, update other fields without changing the existing image
        $sql = "UPDATE game SET 
                gameName = ?,
                genreID = ?,
                gameDesc = ?,
                releaseDate = ?,    
                developer = ?,
                publisher = ?,
                price = ?
                WHERE gameID = ?";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssssssi", $newGameName, $newGenreID, $newGameDesc, $newReleaseDate, $newDeveloper, $newPublisher, $newPrice, $gameID);
    }

    // Attempt to execute the prepared statement
    $result = $stmt->execute();     

    if ($result === false) {
        // Query execution failed, log the error
        error_log("Error updating game: " . $stmt->error);

        // Close the statement and connection
        $stmt->close();
        $conn->close();

        exit("Error updating game. Please try again later.");
    }

    // Close the statement
    $stmt->close();

    // Game updated successfully
    header("Location: viewgame.php");
    exit();
}

// Retrieve existing game data for the form
$sql = "SELECT * FROM game WHERE gameID = $gameID";
$result = $conn->query($sql);

if ($result === false) {
    // Query execution failed, log the error
    error_log("Error retrieving existing game data: " . $conn->error);
    exit("Error retrieving existing game data. Please try again later.");
}

$existingGameData = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Game</title>
    <link rel="shortcut icon" type="image/x-icon" href="../logo.jpg">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" rel="stylesheet">
</head>

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
<body class="updategame">
    <div class="container mt-4">
        <div class="row justify-content-center">
            <div class="col-md-4" id="photoContainer">
                <!-- Display existing logo if available -->
                <?php if (!empty($existingGameData['logo'])): ?>
                    <img id="existingLogo" src="data:image/jpeg;base64,<?php echo $existingGameData['logo']; ?>" alt="Existing Logo">
                <?php endif; ?>
            </div>
            <div class="col-md-8">
                <form method="post" enctype="multipart/form-data">
                    <h2 class="mb-4">Update Game</h2>

                    <input type="hidden" name="gameID" value="<?php echo $gameID; ?>">

                    <div class="form-group">
                        <label for="newGameName">Game Name:</label>
                        <input type="text" class="form-control" name="newGameName" value="<?php echo $existingGameData['gameName']; ?>" required>
                    </div>

                    <div class="form-group">
                        <label for="newGenreID">Genre ID:</label>
                        <input type="text" class="form-control" name="newGenreID" value="<?php echo $existingGameData['genreID']; ?>" required>
                    </div>

                    <div class="form-group">
                        <label for="newGameDesc">Description:</label>
                        <textarea class="form-control" name="newGameDesc" required><?php echo $existingGameData['gameDesc']; ?></textarea>
                    </div>

                    <div class="form-group">
                        <label for="newLogo">Upload New Logo:</label>
                        <input type="file" class="form-control" name="newLogo">
                    </div>

                    <div class="form-group">
                        <label for="newReleaseDate">Release Date:</label>
                        <input type="date" class="form-control" name="newReleaseDate" value="<?php echo $existingGameData['releaseDate']; ?>" required>
                    </div>

                    <div class="form-group">
                        <label for="newDeveloper">Developer:</label>
                        <input type="text" class="form-control" name="newDeveloper" value="<?php echo $existingGameData['developer']; ?>" required>
                    </div>

                    <div class="form-group">
                        <label for="newPublisher">Publisher:</label>
                        <input type="text" class="form-control" name="newPublisher" value="<?php echo $existingGameData['publisher']; ?>" required>
                    </div>

                    <div class="form-group">
                        <label for="newPrice">Price:</label>
                        <input type="text" class="form-control" name="newPrice" value="<?php echo $existingGameData['price']; ?>" required>
                    </div>

                    <button type="submit" class="btn btn-primary">Update Game</button>
                </form>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS and Popper.js (required for Bootstrap components) -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
</body>
</html>






