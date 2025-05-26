<?php
// getAppointments.php
date_default_timezone_set('Asia/Manila'); // Set your timezone

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "db1";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

$today = date('Y-m-d');
$sql = "SELECT name, status, time FROM appointment WHERE DATE(date) = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $today);
$stmt->execute();
$result = $stmt->get_result();

$appointment = array();
while ($row = $result->fetch_assoc()) {
  $appointment[] = $row;
}

echo json_encode($appointment);
$conn->close();
?>
