<?php
session_start();
require_once 'dbconn.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Login</title>
    <link rel="shortcut icon" type="image/x-icon" href="logo.jpg">
    <link rel="stylesheet" href="style.css">
</head>
<body class="login">
    <h1>Login Page</h1>

    <?php
    // Define variables and set empty values
    $usernameErr = $passwordErr = '';
    $username = $password = '';

    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['login'])) {
        // Validate username
        if (empty($_POST['username'])) {
            $usernameErr = "Username is required";
        } else {
            $username = $_POST['username'];
            $usernameErr = "";
        }

        // Validate password
        if (empty($_POST['password'])) {
            $passwordErr = "Password is required";
        } else {
            $password = $_POST['password'];

            // Password validation criteria
            $alphanumericRegex = '/^(?=.*[a-zA-Z0-9])/';
            $symbolRegex = '/^(?=.*[!@#$%^&*()_+{}\[\]:;<>,.?~\\/-])/';
            $minLength = 8;

            // Check if the password meets the criteria
            if (!preg_match($alphanumericRegex, $password) || !preg_match($symbolRegex, $password) || strlen($password) < $minLength) {
                $passwordErr = "Password must contain at least one alphanumeric character, one special symbol, and be at least 8 characters long.";
            } else {
                $passwordErr = "";
            }
        }

        // If there are no errors, proceed to SQL with prepared statements
        if (empty($passwordErr) && empty($usernameErr)) {
            $sql = "SELECT r.roleName FROM role r, user u WHERE u.roleID = r.roleID AND u.username = ? AND u.password = ?";
        
            if (mysqli_query($conn, "DESCRIBE `role`")) {
                $stmt = mysqli_prepare($conn, $sql);
                mysqli_stmt_bind_param($stmt, "ss", $username, $password);
                mysqli_stmt_execute($stmt);
                $result = mysqli_stmt_get_result($stmt);
        
                if (mysqli_num_rows($result) == 1) {
                    $row = mysqli_fetch_assoc($result);
                    $_SESSION['username'] = $username;
                    $_SESSION['role'] = $row["roleName"];
                    setcookie('username', $username);
        
                    if ($_SESSION['role'] == 'Administrator') {
                        header("Location: indexadmin.php");
                        exit();
                    } elseif ($_SESSION['role'] == 'User') {
                        header("Location: indexuser.php");
                        exit();
                    }
                } else {
                    echo "<span style='color: red'>Incorrect username and/or password. Please login again.</span><br>";
                }
        
                mysqli_stmt_close($stmt);
            } else {
                echo "<span style='color: red'>Error: 'role' table doesn't exist.</span><br>";
            }
        }
    }
    ?>

    <br>

    <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        <label for="username"><b>Username</b></label>
        <input type="text" name="username"><br>
        <span><?php echo $usernameErr; ?></span>

        <label for="password"><b>Password</b></label>
        <input type="password" name="password" id="passwordInput">
        <button type="button" class="password-toggle" onclick="togglePassword()">Show Password</button><br>
        <span><?php echo $passwordErr; ?></span>

        <input type="submit" name="login" value="Login">

        <a href="index.php" class="back-button">Back</a>
    </form>

    <a href="register.php">Register</a>

    <?php
    if (!empty($_SESSION['username'])) {
        echo "<a href='includes/logout.php'>Click to log out</a>";
    }
    ?>

    <script>
        function togglePassword() {
            var passwordInput = document.getElementById("passwordInput");

            if (passwordInput.type === "password") {
                passwordInput.type = "text";
                document.querySelector(".password-toggle").textContent = "Hide Password";
            } else {
                passwordInput.type = "password";
                document.querySelector(".password-toggle").textContent = "Show Password";
            }
        }
    </script>
</body>
</html>
