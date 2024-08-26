<?php
require_once 'dbconn.php';

// Create SQL statement and run the query for games
$sql = "SELECT gameID, gameName, gameDesc, logo, releaseDate, developer, publisher, price FROM game";
$result = mysqli_query($conn, $sql) or die(mysqli_error($conn));

// Close the database connection (optional, depends on your implementation)
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome to GameHub</title>
    <link rel="shortcut icon" type="image/x-icon" href="logo.jpg">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="styleindexuser.css">
    <link rel="stylesheet" href="animation.css">
</head>
<body class ="a">

<header>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
            <a class="navbar-brand" href="index.php">GameHub</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" href="https://www.communitygaming.io/" target="_blank">Community</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="contactus.html">Contact Us</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="aboutus.html">About</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
</header>

<div class="container-animation">
            <div class = "bubbles">
                <span style="--i:11;"></span>
                <span style="--i:12;"></span>
                <span style="--i:24;"></span>
                <span style="--i:10;"></span>
                <span style="--i:14;"></span>
                <span style="--i:23;"></span>
                <span style="--i:18;"></span>
                <span style="--i:16;"></span>
                <span style="--i:19;"></span>
                <span style="--i:20;"></span>
                <span style="--i:22;"></span>
                <span style="--i:25;"></span>
                <span style="--i:18;"></span>
                <span style="--i:21;"></span>
                <span style="--i:15;"></span>
                <span style="--i:13;"></span>
                <span style="--i:26;"></span>
                <span style="--i:17;"></span>
                <span style="--i:13;"></span>
                <span style="--i:28;"></span>
                <span style="--i:11;"></span>
                <span style="--i:12;"></span>
                <span style="--i:24;"></span>
                <span style="--i:10;"></span>
                <span style="--i:14;"></span>
                <span style="--i:23;"></span>
                <span style="--i:18;"></span>
                <span style="--i:16;"></span>
                <span style="--i:19;"></span>
                <span style="--i:20;"></span>
                <span style="--i:22;"></span>
                <span style="--i:25;"></span>
                <span style="--i:18;"></span>
                <span style="--i:21;"></span>
                <span style="--i:15;"></span>
                <span style="--i:13;"></span>
                <span style="--i:26;"></span>
                <span style="--i:17;"></span>
                <span style="--i:13;"></span>
                <span style="--i:28;"></span>
                
            </div>
        </div>

        <div class="header-box text-center py-5">
            <h1 class="display-4 font-weight-bold">Welcome to <span class="highlight text-warning">GameHub</span></h1>
            <p class="lead">Your ultimate destination for online gaming! 🎮</p>
        </div>

<div class ="container-with-edges">
<section class="call-to-action">
    <p> Ready to join the <span class="highlight" style="color: orange;">Gaming Community</span>?</p>
    <a href="login.php" class="btn">Login</a>
    <a href="register.php" class="btn">Register</a>
</section>
</div>
<br><br><br>

<div class="slider-frame">
    <h2>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Recommendations</h2>
    <br>
    <div class="slide-images">
        <div class="img-container">
            <img src="left.jpg" alt="Game Recommendation 1" style="width: 750px; height: 750px;">
        </div>
        <div class="img-container">
            <img src="pes.jpg" alt="Game Recommendation 2" style="width: 750px; height: 750px;">
        </div>
        <div class="img-container">
            <img src="left.jpg" alt="Game Recommendation 3" style="width: 750px; height: 750px;">
        </div>
    </div>
</div>

<br>
<br>
<div>
    <h2>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Featured Games</h2>
</div>
<div class="card-container">
    <?php
    // Loop through the fetched data and generate cards
    while ($row = mysqli_fetch_assoc($result)) {
        echo '<div class="card">';
        echo '<img src="data:image/jpeg;base64,' . $row['logo'] . '" alt="Game Logo">';
        echo '<div class="card-content">';
        echo '<h3>' . $row['gameName'] . '</h3>';
        echo '<p>' . $row['gameDesc'] . '</p>';
        echo "<p>RM " . $row['price'] . "</p>";
        echo "&nbsp;&nbsp;";
        echo '<a href="login.php" class="btnbuy">Buy</a>';
        echo '</div></div>';
    }
    ?>
</div>
<br><br><br>

<div class="membership">
    <h1>Try our membership</h1>
<section class="membership-types">
    <div class="membership-card-basic">
        <h2>Basic Membership</h2>
        <p>Access to essential features</p>
        <ul>
            <li>Basic games library</li>
            <li>Community forums</li>
            <li>Regular updates</li>
        </ul>
        <a href="login.php" class="btn">Subscribe</a>
    </div>

    <div class="membership-card-premium">
        <h2>Premium Membership</h2>
        <p>Unlock the full gaming experience</p>
        <ul>
            <li>Exclusive game titles</li>
            <li>Early access to new releases</li>
            <li>Premium support</li>
        </ul>
        <a href="login.php" class="btn">Subscribe</a>
    </div>
</section>
</div>

<footer>
    <div class="footer-box">
        <p>&copy; 2023 GameHub. All rights reserved. 🎮</p>
    </div>
</footer>

</body>
</html>
