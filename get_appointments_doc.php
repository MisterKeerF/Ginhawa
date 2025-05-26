<?php
$host = "localhost";
$user = "root";
$password = "";
$db = "db1"; // CHANGE THIS

$conn = new mysqli($host, $user, $password, $db);
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

$date = $_GET['date'];

$sql = "SELECT name, time FROM appointment WHERE date = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $date);
$stmt->execute();
$result = $stmt->get_result();

$appointment = [];
while ($row = $result->fetch_assoc()) {
  $appointment[] = $row;
}

echo json_encode($appointment);
?>