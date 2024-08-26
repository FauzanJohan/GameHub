<?php
include '../dbconn.php';

// Assuming you have a typeID from somewhere, for example, from a URL parameter
$typeID = isset($_GET['typeID']) ? $_GET['typeID'] : 0;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Handle form submission
    $newTypeName = $_POST['newTypeName'];
    $newTypeDesc = $_POST['newTypeDesc'];
    $newPrice = $_POST['newPrice'];
    $newDiscount = $_POST['newDiscount'];

    $sql = "UPDATE membershiptype SET 
            typeName = ?,
            typeDesc = ?,
            price = ?,
            discount = ?
            WHERE typeID = ?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssddi", $newTypeName, $newTypeDesc, $newPrice, $newDiscount, $typeID);

    // Attempt to execute the prepared statement
    $result = $stmt->execute();

    if ($result === false) {
        // Query execution failed, log the error
        error_log("Error updating membership type: " . $stmt->error);

        // Close the statement and connection
        $stmt->close();
        $conn->close();

        exit("Error updating membership type. Please try again later.");
    }

    // Close the statement
    $stmt->close();

    // Membership type updated successfully
    header("Location: viewmembership.php");
    exit();
}

// Retrieve existing membership type data for the form
$sql = "SELECT * FROM membershiptype WHERE typeID = $typeID";
$result = $conn->query($sql);

if ($result === false) {
    // Query execution failed, log the error
    error_log("Error retrieving existing membership type data: " . $conn->error);
    exit("Error retrieving existing membership type data. Please try again later.");
}

$existingMembershipTypeData = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Membership Type</title>
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
<body class="updatemembership">

    <section class="container">
        <form method="post">
            <h2 class="mb-4">Update Membership Type</h2>

            <input type="hidden" name="typeID" value="<?php echo $typeID; ?>">

            <div class="form-group">
                <label for="newTypeName">Type Name:</label>
                <input type="text" name="newTypeName" class="form-control" value="<?php echo $existingMembershipTypeData['typeName']; ?>" required>
            </div>

            <div class="form-group">
                <label for="newTypeDesc">Type Description:</label>
                <textarea name="newTypeDesc" class="form-control" required><?php echo $existingMembershipTypeData['typeDesc']; ?></textarea>
            </div>

            <div class="form-group">
                <label for="newPrice">Price:</label>
                <input type="text" name="newPrice" class="form-control" value="<?php echo $existingMembershipTypeData['price']; ?>" required>
            </div>

            <div class="form-group">
                <label for="newDiscount">Discount:</label>
                <input type="text" name="newDiscount" class="form-control" value="<?php echo $existingMembershipTypeData['discount']; ?>" required>
            </div>

            <button type="submit" class="btn btn-primary">Update Membership Type</button>
        </form>
    </section>

    <!-- Add Bootstrap JS and Popper.js (required for Bootstrap dropdowns) -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
</body>

</html>

