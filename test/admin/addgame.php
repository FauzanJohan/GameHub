<?php
include '../dbconn.php';

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Handle form submission
    $gameName = $_POST['gameName'];
    $genreID = $_POST['genreID'];
    $gameDesc = $_POST['gameDesc'];
    $releaseDate = $_POST['releaseDate'];
    $developer = $_POST['developer'];
    $publisher = $_POST['publisher'];
    $price = $_POST['price'];

    // Specify the target directory for uploaded files
    $uploadsDirectory = 'uploads/';

    // Check if the target directory exists, create it if not
    if (!file_exists($uploadsDirectory) && !is_dir($uploadsDirectory)) {
        mkdir($uploadsDirectory, 0755, true);
    }

    // Check if a file is uploaded
    if ($_FILES['logo']['error'] == 0) {
        $uploadedFilePath = $uploadsDirectory . basename($_FILES['logo']['name']);

        // Move the uploaded file to the specified directory
        if (move_uploaded_file($_FILES['logo']['tmp_name'], $uploadedFilePath)) {
            // File upload successful, insert new game into the database
            $sql = "INSERT INTO game (gameName, genreID, gameDesc, logo, releaseDate, developer, publisher, price) 
                    VALUES ('$gameName', '$genreID', '$gameDesc', '$uploadedFilePath', '$releaseDate', '$developer', '$publisher', '$price')";

            if ($conn->query($sql) === TRUE) {
                echo "Game added successfully!";
                header("Location: viewgame.php");
        exit();
            } else {
                echo "Error adding game: " . $conn->error;
            }
        } else {
            echo "Error uploading file.";
        }
    } else {
        echo "No file uploaded.";
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
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Game</title>
    <link rel="shortcut icon" type="image/x-icon" href="../logo.jpg">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">

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
<body class="addgame">

    <h2>Add Game</h2>

    <form method="post" enctype="multipart/form-data" class="container">
        <div class="form-group">
            <label for="gameName">Game Name:</label>
            <input type="text" name="gameName" class="form-control" required>
        </div>

        <div class="form-group">
            <label for="genreID">Genre:</label>
            <select name="genreID" class="form-control" required>
                <?php foreach ($genres as $genre): ?>
                    <option value="<?php echo $genre['genreID']; ?>"><?php echo $genre['genreName']; ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="form-group">
            <label for="gameDesc">Description:</label>
            <textarea name="gameDesc" class="form-control" required></textarea>
        </div>

        <div class="form-group">
            <label for="logo">Logo:</label>
            <input type="file" name="logo" class="form-control-file" required>
        </div>

        <div class="form-group">
            <label for="releaseDate">Release Date:</label>
            <input type="date" name="releaseDate" class="form-control" required>
        </div>

        <div class="form-group">
            <label for="developer">Developer:</label>
            <input type="text" name="developer" class="form-control" required>
        </div>

        <div class="form-group">
            <label for="publisher">Publisher:</label>
            <input type="text" name="publisher" class="form-control" required>
        </div>

        <div class="form-group">
            <label for="price">Price:</label>
            <input type="text" name="price" class="form-control" required>
        </div>

        <button type="submit" class="btn btn-primary">Add Game</button>
    </form>

    <!-- Include Bootstrap JS and Popper.js (required for Bootstrap dropdowns) -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>

</body>
</html>

