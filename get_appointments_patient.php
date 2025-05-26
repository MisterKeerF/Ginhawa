<?php
session_start();
require 'db_connection.php'; // your DB connection file

// Get patient ID from session
if (!isset($_SESSION['id'])) {
  echo json_encode(['error' => 'Not logged in']);
  exit;
}

$patientId = $_SESSION['id'];

$sql = "SELECT doctor, date, time, status FROM appointment WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $patientId);
$stmt->execute();
$result = $stmt->get_result();

$appointment = [];

while ($row = $result->fetch_assoc()) {
  $appointment[] = [
    'doctor' => $row['doctor'],
    'date' => $row['date'],
    'time' => $row['time'],
    'status' => $row['status']
  ];
}

echo json_encode($appointment);
?>
