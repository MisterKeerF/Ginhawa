<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = ""; // Update if needed
$dbname = "db1";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
