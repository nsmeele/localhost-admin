<?php
// Create database
$sql = "CREATE DATABASE local_admin";
if ($conn->query($sql) === TRUE) {
    echo "Database created successfully";
} else {
    echo "Error creating database: " . $conn->error;
}