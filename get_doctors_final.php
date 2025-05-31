<?php
// get_doctors_final.php

$host = "localhost";
$user = "root";
$pass = "";
$dbname = "db1";

$conn = new mysqli('sql203.infinityfree.com', 'if0_39086009', 'zI7iB22cjtr4w', 'if0_39086009_db1');

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT fullName FROM doctor";  // adjust column name if needed
$result = $conn->query($sql);

$doctors = [];

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $doctors[] = $row['fullName'];
    }
}

$conn->close();

echo json_encode($doctors);
?>
