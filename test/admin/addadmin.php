<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Admin - GameHubb Admin</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css">
    <link rel="shortcut icon" type="image/x-icon" href="../logo.jpg">
    <link rel="stylesheet" href="style.css">
    <script>
        // JavaScript function to validate age before submitting the form
        function validateAge() {
            var dobInput = document.getElementById("dob");
            var dob = new Date(dobInput.value);
            var currentDate = new Date();

            // Calculate age
            var age = currentDate.getFullYear() - dob.getFullYear();

            if (currentDate.getMonth() < dob.getMonth() || (currentDate.getMonth() === dob.getMonth() && currentDate.getDate() < dob.getDate())) {
                age--;
            }

            // Check if age is below 18
            if (age < 18) {
                alert("Sorry, admin below 18 years old are not allowed to register.");
                return false; // Prevent form submission
            }

            return true; // Allow form submission
        }
    </script>
</head>
<body>
    <h1>Add Admin</h1>
    <div class="container">
        <div class="form-container">
        <?php
        // Check if the form is submitted
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            // Get the form data
            $username = $_POST["username"];
            $firstName = $_POST["firstName"];
            $lastName = $_POST["lastName"];
            $email = $_POST["email"];
            $password = $_POST["password"];
            $roleID = 1; // Default roleID
            $dob = $_POST["dob"];
            $gender = $_POST["gender"]; // Added gender field

            // Calculate age based on date of birth
            $dobDate = new DateTime($dob);
            $currentDate = new DateTime();
            $age = $dobDate->diff($currentDate)->y;

            // Check if age is below 18
            if ($age < 18) {
                echo '<script>alert("Sorry, admin below 18 years old are not allowed to register.");</script>';
            } else {
                // Assuming 'profile' is the name attribute for the file input field
                $profile = $_FILES['profile']['name'];
                $profile_temp = $_FILES['profile']['tmp_name'];

                // Move uploaded file to a directory
                move_uploaded_file($profile_temp, "profile_images/$profile");

                // Connect to the MySQL database
                $conn = new mysqli("localhost", "root", "", "gamehubb");

                // Check if the connection was successful
                if ($conn->connect_error) {
                    die("Connection failed: " . $conn->connect_error);
                }

                // Prepare the SQL statement
                $stmt = $conn->prepare("INSERT INTO user (username, firstName, lastName, email, password, roleID, dob, profile, gender) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
                $stmt->bind_param("sssssssss", $username, $firstName, $lastName, $email, $password, $roleID, $dob, $profile, $gender);

                // Execute the SQL statement
                $execval = $stmt->execute();

                if ($execval) {
                    echo "New admin successfully added.";
                    // Redirect to viewadmin.php after successful addition
                    echo '<script>window.location.href = "viewadmin.php";</script>';
                } else {
                    echo "Error adding admin: " . $stmt->error;
                }

                // Close the database connection
                $stmt->close();
                $conn->close();
            }
        }
        ?>
            <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" enctype="multipart/form-data" onsubmit="return validateAge();">
                <label for="username">Username:</label>
                <input type="text" name="username" id="username" required><br>

                <label for="firstName">First Name:</label>
                <input type="text" name="firstName" id="firstName" required><br>

                <label for="lastName">Last Name:</label>
                <input type="text" name="lastName" id="lastName" required><br>

                <label for="email">Email:</label>
                <input type="email" name="email" id="email" required><br>

                <label for="password">Password:</label>
                <input type="password" name="password" id="password" required><br>

                <label for="dob">Date of Birth:</label>
                <input type="date" name="dob" id="dob" required><br>

                <label for="gender">Gender:</label>
                <select name="gender" id="gender" required>
                    <option value="male">Male</option>
                    <option value="female">Female</option>
                </select><br>

                <label for="profile">Profile Picture:</label>
                <input type="file" name="profile" id="profile" accept="image/*" required><br>

                <input type="submit" value="Add admin">
                <button onclick="location.href='viewadmin.php';" type="button">Cancel</button>
            </form>
        </div>
    </div>
</body>
</html>
