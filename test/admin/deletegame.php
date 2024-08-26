<?php
include '../dbconn.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Handle form submission
    $gameID = $_POST['gameID'];

    $sql = "DELETE FROM game WHERE gameID = $gameID";

    if ($conn->query($sql) === TRUE) {
        // Game deleted successfully, redirect to viewgame.php
        header("Location: viewgame.php");
        exit();
    } else {
        echo "Error deleting game: " . $conn->error;
    }
}
?>
