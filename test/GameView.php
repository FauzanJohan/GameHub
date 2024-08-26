<!-- gameview.php -->

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Game List</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>

<?php

class GameView {
    
    public function displayGames($games) {
        // Debugging: Output the content of $games
        // var_dump($games);

        if (!empty($games)) {
            echo '<table>';
            echo '<tr>';
            echo '<th>Game ID</th>';
            echo '<th>Game Name</th>';
            echo '<th>Genre ID</th>';
            echo '<th>Action</th>';
            echo '</tr>';

            foreach ($games as $game) {
                echo '<tr>';
                echo '<td>' . $game['gameID'] . '</td>';
                echo '<td>' . $game['gameName'] . '</td>';
                echo '<td>' . $game['genreID'] . '</td>';
                echo '<td><a href="editgame.php?gameID=' . $game['gameID'] . '">Edit</a> | <a href="deletegame.php?gameID=' . $game['gameID'] . '">Delete</a></td>';
                echo '</tr>';
            }

            echo '</table>';
        } else {
            echo '<p>No games found.</p>';
        }
    }
}

?>

</body>
</html>
