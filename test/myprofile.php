<?php
session_start();
include 'dbconn.php';

function getUserByUsername($conn, $username) {
    $query = "SELECT * FROM user WHERE username = '$username'";
    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        return $result->fetch_assoc();
    }

    return null;
}

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: login.php"); // Redirect to the login page if not logged in
    exit();
}

// Get the username from the session
$username = $_SESSION['username'];

// Get user data using the function
$userData = getUserByUsername($conn, $username);

// Check if the user is found
if ($userData !== null) {
    // Extract data from the user array
    $profile = $userData['profile'];
    $firstName = $userData['firstName'];
    $lastName = $userData['lastName'];
    $email = $userData['email'];
    $dob = $userData['dob'];
    $gender = $userData['gender'];

    // You can customize this section based on your actual database schema
    $subID = $userData['subID'];
    $typeName = ''; // Assuming you have a membership type field in the user table
    $subStatus = ''; // Assuming you have a subscription status field in the user table
} else {
    // Handle the case where no user is found with the given username
    die("User not found!");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gaming User Profile</title>

    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Open+Sans&display=swap">

    <style>
        body {
            font-family: 'Open Sans', sans-serif;
            background-color: #f5f5f5;
            color: #333333;
            margin: 0;
            padding: 0;
        }

        .header {
            background-color: #1a1a1a;
            color: #ffffff;
            padding: 20px;
            text-align: center;
            font-size: 28px;
            font-weight: bold;
        }

        .update-message {
            display: <?php echo $updateMessage ? 'block' : 'none'; ?>;
            position: fixed;
            top: 10px;
            left: 50%;
            transform: translateX(-50%);
            background-color: #4CAF50;
            color: white;
            padding: 15px;
            border-radius: 5px;
            z-index: 1;
            animation: fadeInOut 3s ease-in-out;
        }

        .profile-container {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin: 20px;
        }

        .user-info {
            flex: 0 70%;
            background-color: #ffffff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            margin-right: 10px;
        }

        .name {
            font-weight: bold;
            font-size: 20px;
            text-transform: uppercase;
        }

        .user-details {
            margin-top: 10px;
        }

        .profile-picture {
            border-radius: 50%;
            width: 150px;
            height: 150px;
            object-fit: cover;
            border: 5px solid #ff9900;
            margin-bottom: 20px;
        }

        .user-info div {
            font-size: 18px;
            margin-bottom: 15px;
        }

        .type-info, .status-info {
            font-size: 18px;
            margin-top: 10px;
            text-align: center;
            font-weight: bold;
        }

        .type-info {
            background-color: <?php echo $row['typeName'] === 'Premium' ? '#FFD700' : ($row['typeName'] === 'Basic' ? '#808080' : '#ffffff'); ?>;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            padding: 10px;
        }

        .status-info {
            background-color: <?php echo $row['subStatus'] === 'Active' ? '#4CAF50' : '#e74c3c'; ?>;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            padding: 10px;
        }

        .edit-button, .logout-button {
            background-color: #ff9900;
            color: white;
            padding: 15px;
            margin-top: 20px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 18px;
            transition: background-color 0.3s;
            width: 70%;
            margin-left: 15%;
        }

        .edit-button:hover, .logout-button:hover {
            background-color: #e68a00;
        }

        .footer {
            background-color: #1a1a1a;
            color: #ffffff;
            padding: 15px;
            text-align: center;
            font-size: 16px;
            margin-top: 5%;
        }

        @keyframes fadeInOut {
            0%, 100% {
                opacity: 0;
            }
            10%, 90% {
                opacity: 1;
            }
        }
    </style>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            var updateMessage = document.querySelector(".update-message");
            if (updateMessage) {
                setTimeout(function () {
                    updateMessage.style.display = "none";
                }, 3000);
            }
        });
    </script>
</head>
<body>
    <div class="header">
        Gaming Profile
    </div>
    <div class="update-message"><?php echo $updateMessage; ?></div>

    <div class="profile-container">
        <div class="user-info">
            <?php
            if (!empty($userData['profile'])) {
                echo '<img class="profile-picture" src="' . $userData['profile'] . '" alt="Profile Picture">';
            } else {
                echo '<img class="profile-picture" src="default_profile_pic.jpg" alt="Default Profile Picture">';
            }
            ?>
            <div class="name"><?php echo $userData['firstName'] . ' ' . $userData['lastName']; ?></div>
            <div class="user-details">
                <div class="username"><?php echo $userData['username']; ?></div>
                <div class="email"><?php echo $userData['email']; ?></div>
                <div class="dob"><?php echo $userData['dob']; ?></div>
                <div class="gender"><?php echo $userData['gender']; ?></div>
            </div>

            <?php if (isset($userData['typeName'])) : ?>
                <div class="type-info"><?php echo $userData['typeName']; ?></div>
            <?php endif; ?>

            <?php if (isset($userData['subStatus'])) : ?>
                <div class="status-info"><?php echo $userData['subStatus']; ?></div>
            <?php endif; ?>
        </div>
    </div>

    <button class="edit-button" onclick="window.location.href='update_profile.php'">Edit</button>
    <button class="logout-button" onclick="window.location.href='login.php'">Log Out</button>

    <div class="footer">
        © 2024 Gaming Profile. All Rights Reserved.
    </div>
</body>
</html>


