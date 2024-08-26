<?php
require_once 'dbconn.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve user input
    $username = isset($_POST['username']) ? $_POST['username'] : '';
    $firstName = isset($_POST['firstName']) ? $_POST['firstName'] : '';
    $lastName = isset($_POST['lastName']) ? $_POST['lastName'] : '';
    $email = isset($_POST['email']) ? $_POST['email'] : '';
    $password = isset($_POST['password']) ? $_POST['password'] : ''; // Hash the password
    $dob = isset($_POST['dob']) ? $_POST['dob'] : '';
    $gender = isset($_POST['gender']) ? $_POST['gender'] : '';

    // File upload handling for profile picture
    $profilePicture = '';

    if (isset($_FILES['profilePicture']) && $_FILES['profilePicture']['error'] == 0) {
        $profilePictureData = file_get_contents($_FILES['profilePicture']['tmp_name']);
        $profilePicture = base64_encode($profilePictureData);
    }

    // Assuming you have a default role ID for regular users (adjust this based on your actual roles)
    $defaultRoleID = 2; // Change this value according to your role table

    // Insert data into the database with a valid role ID and profile picture
    $sql = "INSERT INTO user (username, firstName, lastName, email, password, dob, gender, profile, roleID) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssssssi", $username, $firstName, $lastName, $email, $password, $dob, $gender, $profilePicture, $defaultRoleID);

    if ($stmt->execute()) {
        echo "Registration successful!";

        // Redirect to the login page
        header("Location: http://localhost/gamehub/test/login.php");
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }

    // Close the statement
    $stmt->close();

    // Close the database connection
    $conn->close();
}
?>

<?php
// ... (Your PHP code remains unchanged)
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Registration</title>
    <link rel="stylesheet" href="style.css">
</head>

<body class="register">
    <div class="container">
        <h2>User Registration</h2>
        <form id="registrationForm" action="register.php" method="post" enctype="multipart/form-data">
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" required>

            <label for="firstName">First Name:</label>
            <input type="text" id="firstName" name="firstName" required>

            <label for="lastName">Last Name:</label>
            <input type="text" id="lastName" name="lastName" required>

            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>

            <label for="password">Password:</label>
            <div class="password-toggle">
                <input type="password" id="password" name="password" required>
                <button type="button" onclick="togglePassword()">Show</button>
            </div>

            <label for="dob">Date of Birth:</label>
            <input type="date" id="dob" name="dob" required>

            <label for="gender">Gender:</label>
            <select id="gender" name="gender" required>
                <option value="male">Male</option>
                <option value="female">Female</option>
                <option value="other">Other</option>
            </select>

            <label for="profilePicture">Profile Picture:</label>
            <input type="file" id="profilePicture" name="profilePicture" accept="image/*">

            <button type="submit">Register</button>

            <a href="login.php" class="back-button">Back</a>
        </form>
    </div>

    <script>
        function validatePassword() {
            var passwordField = document.getElementById("password");
            var password = passwordField.value;

            // Regular expressions for password validation
            var alphanumericRegex = /^(?=.*[a-zA-Z0-9])/;
            var symbolRegex = /^(?=.*[!@#$%^&*()_+{}\[\]:;<>,.?~\\/-])/;
            var minLength = 8;

            // Check if the password meets the criteria
            if (alphanumericRegex.test(password) && symbolRegex.test(password) && password.length >= minLength) {
                return true;
            } else {
                alert("Password must contain at least one alphanumeric character, one special symbol, and be at least 8 characters long.");
                return false;
            }
        }

        function togglePassword() {
            var passwordField = document.getElementById("password");
            var passwordButton = document.querySelector(".password-toggle button");

            if (passwordField.type === "password") {
                passwordField.type = "text";
                passwordButton.textContent = "Hide";
            } else {
                passwordField.type = "password";
                passwordButton.textContent = "Show";
            }
        }

        // Attach the validatePassword function to the form's onsubmit event
        document.getElementById("registrationForm").onsubmit = function() {
            return validatePassword();
        };
    </script>
</body>
</html>
