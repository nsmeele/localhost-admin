<?php
// Include connection
include_once('connection.php');

// Create database
$sql = "CREATE DATABASE $dbName";
if ($conn->query($sql) === TRUE) {
    echo "New database created: <strong>".$dbName."</strong>";
} else {
    echo $conn->error;
}