<?php
session_start();
require_once 'dbconn.php';

// Function to retrieve user data by username
function getUserByUsername($conn, $username) {
    $query = "SELECT * FROM user WHERE username = '$username'";
    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        return $result->fetch_assoc();
    }

    return null;
}

// Get the username of the logged-in user
if (isset($_SESSION['username'])) {
    $username = $_SESSION['username'];
} else {
    header("Location: login.php");
    exit();
}

// Function to get user information by username
function getUserInfo($conn, $username) {
    $query = "SELECT u.username, u.firstName, u.lastName, u.email, u.dob, u.profile, u.gender, mt.typeName
              FROM user u
              LEFT JOIN subscription s ON u.subID = s.subID
              LEFT JOIN membershiptype mt ON s.typeID = mt.typeID
              WHERE u.username = '$username'";
    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        return $result->fetch_assoc();
    }

    return null;
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Handle the form submission to update user information
    $newUsername = $_POST['new_username'];
    $newFirstName = $_POST['new_first_name'];
    $newLastName = $_POST['new_last_name'];
    $newEmail = $_POST['new_email'];

  // Check if a new profile picture is uploaded
  $newProfilePicture = $_FILES['new_profile_picture']['tmp_name'];
  $profilePictureBase64 = '';

  if (!empty($newProfilePicture)) {
      $profilePictureBase64 = base64_encode(file_get_contents($newProfilePicture));
  }

  $updateSql = "UPDATE user SET username = '$newUsername', firstName = '$newFirstName', lastName = '$newLastName', email = '$newEmail', profile = '$profilePictureBase64' WHERE username = '$username'";
  $updateResult = mysqli_query($conn, $updateSql);

  if ($updateResult) {
      // Reload the page after updating the information
      header("Location: view_profile.php?update=success");
      exit();
  } else {
      echo "Error updating user information: " . mysqli_error($conn);
  }
}
// Get user information using the function
$row = getUserInfo($conn, $username);

// Display an error message if the user is not found
if (!$row) {
    echo "User not found in the database.";
    exit();
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit User Information</title>

    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Open+Sans&display=swap">
        <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" rel="stylesheet">


    <style>
        body {
            font-family: 'Open Sans', sans-serif;
            background-color: #f5f5f5; /* Light background color */
            color: #333333; /* Dark text color */
            margin: 0;
            padding: 0;
        }

        .edit-container {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-color: #ffffff; /* White background color */
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            margin: 20px;
            padding: 50px;
            flex-direction: column;
        }

        h1 {
            text-align: center;
            font-size: 28px;
            font-weight: bold;
        }

        label {
            font-size: 18px;
            margin-bottom: 5px;
        }

        input, select {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            font-size: 16px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }

        input[type="submit"] {
            background-color: #ff9900; /* Orange color for submit button */
            color: white;
            padding: 15px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 18px;
            transition: background-color 0.3s;
        }

        input[type="submit"]:hover {
            background-color: #e68a00; /* Darker orange on hover */
        }

        button {
        background-color: #3399ff; /* Blue color for the button */
        color: white;
        padding: 12px;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        font-size: 16px;
        margin-bottom: 15px;
        transition: background-color 0.3s;
    }

    button:hover {
        background-color: #1a66cc; /* Darker blue on hover */
    }
    </style>
    
</head>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <a class="navbar-brand" href="indexuser.php">GameHub</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link" href="mygame.php">My Games</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="subscription_page.php">Subscription</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="view_profile.php">My Profile</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="includes/logout.php">Logout</a>
            </li>
        </ul>
    </div>
    <?php echo '<p class="lead" style="color: white; margin-right: 20px;">Hi ' . $_SESSION['username'] . '! </p>'; ?>
</nav>
<body>
    <div class="edit-container">
        <h1>Edit User Information</h1>
        <form method="post" action="" enctype="multipart/form-data">
            <label for="new_username">Username:</label>
            <input type="text" name="new_username" value="<?php echo $row['username']; ?>" required><br>

            <label for="new_first_name">First Name:</label>
            <input type="text" name="new_first_name" value="<?php echo $row['firstName']; ?>" required><br>

            <label for="new_last_name">Last Name:</label>
            <input type="text" name="new_last_name" value="<?php echo $row['lastName']; ?>" required><br>

            <label for="new_email">Email:</label>
            <input type="text" name="new_email" value="<?php echo $row['email']; ?>" required><br>

            <label for="new_profile_picture">Profile Picture:</label>
            <input type="file" name="new_profile_picture"><br>

            <label for="new_dob">Date of Birth:</label>
            <input type="text" name="new_dob" id="datepicker" value="<?php echo $row['dob']; ?>" required><br>

            <label for="new_gender">Gender:</label>
            <select name="new_gender">
                <option value="Male" <?php echo ($row['gender'] == 'Male') ? 'selected' : ''; ?>>Male</option>
                <option value="Female" <?php echo ($row['gender'] == 'Female') ? 'selected' : ''; ?>>Female</option>
            </select><br>

            <label for="new_subscription">Subscription:</label>
<input type="text" name="new_subscription" value="<?php echo $row['typeName']; ?>" readonly style="background-color: #f5f5f5; cursor: not-allowed;" required><br>
<button type="button" onclick="window.location.href='subscription_page.php'">Manage Subscription</button>

            <input type="submit" value="Update">
        </form>
    </div>
    <script>
        $(function () {
            $("#datepicker").datepicker();
        });
    </script>
</body>
</html>

<?php
mysqli_close($conn);
?>
