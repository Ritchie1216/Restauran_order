<?php

session_start();

$servername = 'localhost'; // Your database host
$username = 'root'; // Your database username
$password = '1234'; // Your database password
$dbname = 'restaurant2'; // Your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>