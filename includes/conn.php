<?php
// Database configuration
$servername = "localhost:4306";
$username = "root"; // Default XAMPP username
$password = ""; // Default XAMPP password
$dbname = "jobportal";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>