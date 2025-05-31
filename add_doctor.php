<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $conn = new mysqli('sql203.infinityfree.com', 'if0_39086009', 'zI7iB22cjtr4w', 'if0_39086009_db1');

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $fullName = $conn->real_escape_string($_POST['fullName']);
    $specialization = $conn->real_escape_string($_POST['specialization']);
    $username = $conn->real_escape_string($_POST['username']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // hash for security

    $sql = "INSERT INTO doctor (fullName, specialization, username, password) VALUES ('$fullName', '$specialization', '$username', '$password')";

    if ($conn->query($sql) === TRUE) {
        header("Location: admindashboard.php?success=1");
        exit();
    } else {
        echo "Error: " . $conn->error;
    }

    $conn->close();
}
?>
