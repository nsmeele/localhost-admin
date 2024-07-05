<?php
// sql to create table
$tableName = 'project_details';
$sql       = "CREATE TABLE $tableName (
id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
name VARCHAR(50) NOT NULL,
folder VARCHAR(50),
description VARCHAR(255),
type VARCHAR(50),
username VARCHAR(50),
password VARCHAR(50),
reg_date TIMESTAMP
)";

if ($conn->query($sql) === true) {
    echo "Table ".$tableName." created successfully";
} else {
    echo "Error creating table: ".$conn->error;
}