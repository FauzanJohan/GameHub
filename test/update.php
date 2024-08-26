
<?php
// Connect to the database
$conn = mysqli_connect("localhost", "root", "", "test");
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve the updated values from the form
    $username = $_POST["username"];
    $name = $_POST["name"];
    $email = $_POST["email"];
    $password = $_POST["password"];
    $ic = $_POST["ic"];
    $address = $_POST["address"]; 
    $tel_no = $_POST["tel_no"];

    // Update the staff record in the database
    $sql = "UPDATE staff SET name='$name', email='$email', password='$password', ic='$ic', address='$address', tel_no='$tel_no' WHERE username='$username'";
    if (mysqli_query($conn, $sql)) {
        // Redirect to the staff database page after successful update
        header("Location: staff.php");
        exit();
    } else {
        echo "Error updating record: " . mysqli_error($conn);
    }
}

// Retrieve the staff record to be updated based on the provided username in the URL parameter
$username = $_GET["id"];
$sql = "SELECT * FROM staff WHERE username='$username'";
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);

// Close the connection
mysqli_close($conn);
?>

<!DOCTYPE html>
<html>
	
	
<head>
    <meta charset="UTF-8">
    <title>Edit Staff</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css">
</head>
<body>
    <h1>Edit Staff</h1>
    <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        <input type="hidden" name="username" value="<?php echo $row['username']; ?>">
        <div class="mb-3">
            <label for="name" class="form-label">Name</label>
            <input type="text" class="form-control" id="name" name="name" value="<?php echo $row['name']; ?>">
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
            <label for="ic" class="form-label">IC</label>
            <input type="text" class="form-control" id="ic" name="ic" value="<?php echo $row['ic']; ?>">
        </div>
        <div class="mb-3">
            <label for="address" class="form-label">Address</label>
            <input type="text" class="form-control" id="address" name="address" value="<?php echo $row['address']; ?>">
        </div>
        <div class="mb-3">
            <label for="tel_no" class="form-label">Tel No</label>
            <input type="text" class="form-control" id="tel_no" name="tel_no" value="<?php echo $row['tel_no']; ?>">
        </div>
        <button type="submit" class="btn btn-primary">Update</button>
    </form>
</body>
</html>
