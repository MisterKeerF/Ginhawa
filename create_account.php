<?php
session_start();

// Database config
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "db1";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    $_SESSION['notif_type'] = "error";
    $_SESSION['notif_message'] = "Connection failed: " . $conn->connect_error;
    header("Location: admindashboard.php");
    exit();
}

$user_name = $_POST['user_name'];
$fullName = $_POST['fullName'];
$specialization = $_POST['specialization'];
$raw_password = $_POST['password'];

$hashed_password = password_hash($raw_password, PASSWORD_DEFAULT);

$stmt = $conn->prepare("INSERT INTO doctor (user_name, fullName, specialization, password) VALUES (?, ?, ?, ?)");
$stmt->bind_param("ssss", $user_name, $fullName, $specialization, $hashed_password);

if ($stmt->execute()) {
    $_SESSION['notif_type'] = "success";
    $_SESSION['notif_message'] = "Account created successfully!";
} else {
    $_SESSION['notif_type'] = "error";
    $_SESSION['notif_message'] = "Error: " . $stmt->error;
}

$stmt->close();
$conn->close();

header("Location: admindashboard.php");
exit();
?>
