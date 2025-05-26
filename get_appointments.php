<?php
session_start();
header('Content-Type: application/json');
include 'db_connection.php'; // your DB connection file

if (!isset($_SESSION['doctor'])) {
    echo json_encode(['error' => 'Not logged in']);
    exit;
}

$doctor_id = $_SESSION['doctor'];

$sql = "SELECT name, date, time FROM appointment WHERE doctor = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $doctor_id);
$stmt->execute();
$result = $stmt->get_result();

$appointments = [];
while ($row = $result->fetch_assoc()) {
    $appointments[] = $row;
}

echo json_encode($appointments);
?>
