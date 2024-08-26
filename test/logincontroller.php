<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];

    // Connect to the MySQL database
    $conn = new mysqli("localhost", "root", "", "test");

    // Check if the connection was successful
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Prepare the SQL statement with placeholders
    $sql = "SELECT * FROM staff WHERE username=? AND password=?";
    $stmt = $conn->prepare($sql);

    // Bind the parameters and execute the query
    $stmt->bind_param("ss", $username, $password);
    $stmt->execute();

    // Store the result
    $result = $stmt->get_result();

    // Check if the user exists
    if ($result->num_rows > 0) {
        // Check if the user is an administrator
        $row = $result->fetch_assoc();
        if ($row['username'] === "admin") {
            $_SESSION["status"] = "loggedin";
            $_SESSION["role"] = "admin";
            $_SESSION["success"] = "You've successfully logged in.";
            header("Location: index.php");
            exit();
        }
    }

    // Close the statement and the connection to the MySQL database
    $stmt->close();
    $conn->close();

    $_SESSION["danger"] = "Login failed... Please re-login.";
    header("Location: login.php");
    exit();
}
?>
