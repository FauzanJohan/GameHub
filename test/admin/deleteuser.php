<?php include 'header.php'; ?>
<?php
// Connect to the database
$conn = mysqli_connect("localhost", "root", "", "gamehubb");
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Check if the delete confirmation form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve the user ID to be deleted
    $userId = $_POST["id"];

    // Delete the user record from the database
    $sql = "DELETE FROM user WHERE userID='$userId'";
    if (mysqli_query($conn, $sql)) {
        // Redirect to the user listing page after successful deletion
        header("Location: viewuser.php");
        exit();
    } else {
        echo "Error deleting record: " . mysqli_error($conn);
    }
}

// Retrieve the user record to be deleted based on the provided ID in the URL parameter
$userId = $_GET["id"];
$sql = "SELECT * FROM user WHERE userID='$userId'";
$result = mysqli_query($conn, $sql);

if (!$result) {
    echo "Error retrieving record: " . mysqli_error($conn);
} else {
    $rowCount = mysqli_num_rows($result);

    if ($rowCount > 0) {
        $row = mysqli_fetch_assoc($result);
        // Display the user information and delete form
        ?>

        <!DOCTYPE html>
        <html>
        <head>
            <meta charset="UTF-8">
            <title>Delete User</title>
            <link rel="shortcut icon" type="image/x-icon" href="../logo.jpg">
            <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css">
        </head>
        <body>
            <h1>Delete User</h1>
            <p>Are you sure you want to delete the following user record?</p>
            <table class="table">
                <tr>
                    <th>User ID:</th>
                    <td><?php echo $row['userID']; ?></td>
                </tr>
                <tr>
                    <th>Username:</th>
                    <td><?php echo $row['username']; ?></td>
                </tr>
                <tr>
                    <th>Email:</th>
                    <td><?php echo $row['email']; ?></td>
                </tr>
                <tr>
                    <th>Role ID:</th>
                    <td><?php echo $row['roleID']; ?></td>
                </tr>
            </table>
            <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                <input type="hidden" name="id" value="<?php echo $row['userID']; ?>">
                <p class="text-danger">This action cannot be undone. Are you sure you want to proceed?</p>
                <button type="submit" class="btn btn-danger">Delete</button>
                <a href="viewuser.php" class="btn btn-secondary">Cancel</a>
            </form>
        </body>
        </html>

        <?php
    } else {
        echo "User not found.";
    }
}

// Close the connection
mysqli_close($conn);
?>
