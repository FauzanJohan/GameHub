<?php
include '../dbconn.php';

// Check if genreID is provided in the URL (GET request)
if (isset($_GET['genreID'])) {
    $genreID = $_GET['genreID'];
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Delete Genre</title>
        <link rel="shortcut icon" type="image/x-icon" href="../logo.jpg">
        <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" rel="stylesheet">
        <script>
            function confirmDelete() {
                return confirm("Are you sure you want to delete this genre?");
            }

            function showAlert(message) {
                alert(message);
            }
        </script>
    </head>
    <body>
        <div class="container mt-4">
            <h2>Delete Genre</h2>
            <form method="post" onsubmit="return confirmDelete();">
                <input type="hidden" name="genreID" value="<?php echo $genreID; ?>">
                <button type="submit" class="btn btn-danger">Delete Genre</button>
            </form>
        </div>
        <!-- Include Bootstrap JS and Popper.js (required for Bootstrap dropdowns) -->
        <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
    </body>
    </html>
    <?php
}

// Check if the form is submitted (POST request)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $genreID = $_POST['genreID'];

    $sql = "DELETE FROM genre WHERE genreID = $genreID";

    if ($conn->query($sql) === TRUE) {
        // Genre deleted successfully, show alert and reload the current page
        echo '<script type="text/javascript">showAlert("Genre deleted successfully!");</script>';
        echo '<script type="text/javascript">window.location.replace("viewgenre.php");</script>';
        exit();
    } else {
        echo "Error deleting genre: " . $conn->error;
    }
}
?>
