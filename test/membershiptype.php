<?php
require_once 'dbconn.php';

function getMembershipTypes() {
    global $conn;
    
    $query = "SELECT * FROM membershiptype";
    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        $types = array();
        while ($row = $result->fetch_assoc()) {
            $types[] = $row;
        }
        return $types;
    } else {
        return array();
    }
}

function getMembershipTypeById($typeID) {
    global $conn;
    
    $query = "SELECT * FROM membershiptype WHERE typeID = $typeID";
    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        return $result->fetch_assoc();
    } else {
        return null;
    }
}
?>
        