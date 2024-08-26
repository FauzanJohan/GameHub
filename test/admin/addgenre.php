<?php
include '../dbconn.php';

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Handle form submission
    $genreName = $_POST['genreName'];
    $genreDesc = $_POST['genreDesc'];

    // Insert new genre into the database
    $sql = "INSERT INTO genre (genreName, genreDesc) 
            VALUES ('$genreName', '$genreDesc')";

    if ($conn->query($sql) === TRUE) {
        echo "Genre added successfully!";
        // Redirect to viewgenre.php after successful insertion
        header("Location: viewgenre.php");
        exit();
    } else {
        echo "Error adding genre: " . $conn->error;
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Genre</title>
    <link rel="shortcut icon" type="image/x-icon" href="../logo.jpg">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" rel="stylesheet">
</head>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <a class="navbar-brand" href="indexadmin.php">GameHub Admin</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" href="admin/viewgame.php">Games</a>
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
                    <a class="nav-link" href="../admin/logout.php">Log Out</a>
                </li>
            </ul>
        </div>
</nav>

<body>

    <h2>Add Genre</h2>

    <form method="post" class="container">
        <div class="form-group">
            <label for="genreName">Genre Name:</label>
            <input type="text" name="genreName" class="form-control" required>
        </div>

        <div class="form-group">
            <label for="genreDesc">Genre Description:</label>
            <textarea name="genreDesc" class="form-control" required></textarea>
        </div>

        <button type="submit" class="btn btn-primary">Add Genre</button>
    </form>

    <!-- Include Bootstrap JS and Popper.js (required for Bootstrap dropdowns) -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>

</body>
</html>
