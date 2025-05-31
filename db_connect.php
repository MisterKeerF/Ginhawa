<?php

$conn = new mysqli('sql203.infinityfree.com', 'if0_39086009', 'zI7iB22cjtr4w', 'if0_39086009_db1');

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
