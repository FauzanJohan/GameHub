<?php
// Connect to the database
$conn = mysqli_connect("localhost", "root", "", "gamehubb");
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve the updated values from the form
    $id = $_POST["id"];
    $username = $_POST["username"];
    $firstName = $_POST["firstName"];
    $lastName = $_POST["lastName"];
    $email = $_POST["email"];
    $password = $_POST["password"];
    $roleID = $_POST["roleID"];
    $dob = $_POST["dob"];
    $gender = $_POST["gender"];

    // Fetch $userID from the form
    $userID = $_POST["id"];

    // Check if a new profile image is uploaded
    if ($_FILES['profile']['error'] == 0) {
        // Read the uploaded file content
        $newProfileContent = file_get_contents($_FILES['profile']['tmp_name']);

        // Convert the binary content to base64 for database storage
        $newProfileBase64 = base64_encode($newProfileContent);

        // File upload successful, update the database with the new file content
        $sql = "UPDATE user SET 
                username = ?,
                firstName = ?,
                lastName = ?,
                email = ?,
                password = ?,
                roleID = ?,
                dob = ?,
                profile = ?,  -- Store base64-encoded content in the database
                gender = ?
                WHERE userID = ?";

        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "sssssssssi", $username, $firstName, $lastName, $email, $password, $roleID, $dob, $newProfileBase64, $gender, $userID);
    } else {
        // No file uploaded, update other fields without changing the existing profile
        $sql = "UPDATE user SET 
                username = ?,
                firstName = ?,
                lastName = ?,
                email = ?,
                password = ?,
                roleID = ?,
                dob = ?,
                gender = ?
                WHERE userID = ?";

        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "ssssssssi", $username, $firstName, $lastName, $email, $password, $roleID, $dob, $gender, $userID);
    }

    // Attempt to execute the prepared statement
    $result = mysqli_stmt_execute($stmt);

    if ($result === false) {
        // Query execution failed, log the error
        error_log("Error updating user: " . mysqli_stmt_error($stmt));

        // Close the statement and connection
        mysqli_stmt_close($stmt);
        mysqli_close($conn);

        exit("Error updating user. Please try again later.");
    }

    // Redirect to the admin list page after successful update
    header("Location: viewadmin.php");
    exit();
}

// Retrieve the user record to be updated based on the provided ID in the URL parameter
$userID = $_GET["id"];
$sql = "SELECT * FROM user WHERE userID='$userID'";
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);

// Close the connection
mysqli_close($conn);
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Admin</title>
    <link rel="shortcut icon" type="image/x-icon" href="../logo.jpg">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css">
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
<body>
    <div class="container mt-5">
        <h1 class="mb-4">Edit Admin</h1>
        <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" enctype="multipart/form-data">

            <input type="hidden" name="id" value="<?php echo $row['userID']; ?>">
            <input type="hidden" name="old_profile" value="<?php echo $row['profile']; ?>">

            <div class="mb-3">
                <label for="username" class="form-label">Username</label>
                <input type="text" class="form-control" id="username" name="username" value="<?php echo $row['username']; ?>">
            </div>

            <div class="mb-3">
                <label for="firstName" class="form-label">First Name</label>
                <input type="text" class="form-control" id="firstName" name="firstName" value="<?php echo $row['firstName']; ?>">
            </div>

            <div class="mb-3">
                <label for="lastName" class="form-label">Last Name</label>
                <input type="text" class="form-control" id="lastName" name="lastName" value="<?php echo $row['lastName']; ?>">
            </div>

            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" id="email" name="email" value="<?php echo $row['email']; ?>">
            </div>

            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" class="form-control" id="password" name="password" value="<?php echo $row['password']; ?>">
            </div>

            <div class="mb-3">
                <label for="roleID" class="form-label">Role ID</label>
                <input type="number" class="form-control" id="roleID" name="roleID" value="<?php echo $row['roleID']; ?>">
            </div>

            <div class="mb-3">
                <label for="dob" class="form-label">Date of Birth</label>
                <input type="date" class="form-control" id="dob" name="dob" value="<?php echo $row['dob']; ?>">
            </div>

            <div class="mb-3">
                <label for="gender" class="form-label">Gender</label>
                <select class="form-select" id="gender" name="gender">
                    <option value="male" <?php echo ($row['gender'] === 'male') ? 'selected' : ''; ?>>Male</option>
                    <option value="female" <?php echo ($row['gender'] === 'female') ? 'selected' : ''; ?>>Female</option>
                </select>
            </div>

            <div class="mb-3">
                <label for="profile" class="form-label">Profile Image</label>
                <input type="file" class="form-control" id="profile" name="profile">
            </div>

            <button type="submit" class="btn btn-primary">Update</button>
        </form>
    </div>

    <!-- Bootstrap JS and Popper.js (required for Bootstrap components) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
