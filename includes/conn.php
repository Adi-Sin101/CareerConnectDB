<?php
include 'query_logger.php';

// Database configuration
$servername = "localhost:4306";
$username = "root"; // Default XAMPP username
$password = ""; // Default XAMPP password
$dbname = "jobportal";

// Create connection using LoggedMysqli
$conn = new LoggedMysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>