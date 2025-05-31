<?php
session_start();
header('Content-Type: application/json');

// Database setup
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "db1";

$conn = new mysqli('sql203.infinityfree.com', 'if0_39086009', 'zI7iB22cjtr4w', 'if0_39086009_db1');
if ($conn->connect_error) {
  die(json_encode(["error" => "Connection failed: " . $conn->connect_error]));
}

// Check if logged in
if (!isset($_SESSION['user_id'])) {
  http_response_code(403);
  echo json_encode(["error" => "User not logged in."]);
  exit;
}

// Get user ID
$patient_id = $_SESSION['user_id'];

// Get POST data (assumes you use POST to book an appointment)
$data = json_decode(file_get_contents("php://input"), true);
$name = $data['name'] ?? '';
$doctor = $data['doctor'] ?? '';
$status = $data['status'] ?? 'Pending'; // default
$time = $data['time'] ?? '';

// Validate
if (!$name || !$doctor || !$time) {
  http_response_code(400);
  echo json_encode(["error" => "Missing required fields."]);
  exit;
}

// Insert appointment
$sql = "INSERT INTO appointment (name, doctor, status, time, patient_id) VALUES (?, ?, ?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ssssi", $name, $doctor, $status, $time, $patient_id);

if ($stmt->execute()) {
  echo json_encode(["success" => true, "message" => "Appointment booked successfully."]);
} else {
  echo json_encode(["error" => "Error: " . $conn->error]);
}

$conn->close();
?>
