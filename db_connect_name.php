<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = ""; // Update if needed
$dbname = "db1";

$conn = new mysqli('sql203.infinityfree.com', 'if0_39086009', 'zI7iB22cjtr4w', 'if0_39086009_db1');

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
