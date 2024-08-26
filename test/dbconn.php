<?php
//set database connection configuration
$servername = "localhost";
$dbusername = "root";
$dbpassword = "";
$dbname = "gamehubb";
//create connection
$conn = mysqli_connect($servername, $dbusername, $dbpassword, $dbname) or die('Error connecting to database: ' . mysqli_connect_error());
?>
