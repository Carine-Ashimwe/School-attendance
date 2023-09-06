<?php

// MySQL database configuration
$host = 'localhost'; 
$username = 'root'; 
$password = ''; 
$database = 'newtrial';

// Create a MySQL connection
$mysqli = new mysqli($host, $username, $password, $database);

// Check connection
if ($mysqli->connect_errno) {
    die("Failed to connect to MySQL: " . $mysqli->connect_error);
}

?>
