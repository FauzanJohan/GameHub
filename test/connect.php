<?php
$name = $_POST['name'];
$email = $_POST['email'];
$username = $_POST['username'];
$password = $_POST['password'];
$ic = $_POST['ic'];
$address = $_POST['address'];
$tel_no = $_POST['tel_no'];

// Database connection
$conn = new mysqli('localhost', 'root', '', 'test');
if ($conn->connect_error) {
    die("Connection Failed: " . $conn->connect_error);
} else {
    $stmt = $conn->prepare("INSERT INTO staff (name, email, username, password, ic, address, tel_no) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssss", $name, $email, $username, $password, $ic, $address, $tel_no);
    $execval = $stmt->execute();
    if ($execval) {
        echo "Registration successful.";
        // Add a success message on the registration page
        echo '<p>Thank you for registering. You can now proceed to <a href="login.html">login</a>.</p>';
    } else {
        echo "Error: " . $stmt->error;
    }
    $stmt->close();
    $conn->close();
}
?>
