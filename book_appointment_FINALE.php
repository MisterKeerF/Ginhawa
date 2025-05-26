<?php
session_start(); // REQUIRED to access session variables
header('Content-Type: application/json');

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'User not logged in']);
    exit;
}

$user_id = $_SESSION['user_id'];

// Connect to the database
$conn = new mysqli('localhost', 'root', '', 'db1');

if ($conn->connect_error) {
    echo json_encode(['success' => false, 'message' => 'DB connection failed']);
    exit;
}

// Get user's name from the user table
$user_query = $conn->prepare("SELECT firstName FROM user WHERE patient_id = ?");
$user_query->bind_param("i", $user_id);
$user_query->execute();
$user_result = $user_query->get_result();

if ($user_result->num_rows === 0) {
    echo json_encode(['success' => false, 'message' => 'User not found']);
    exit;
}

$user_data = $user_result->fetch_assoc();
$name = $user_data['firstName'];

// Get the rest of the data from JSON
$data = json_decode(file_get_contents("php://input"), true);

if (!isset($data['doctor'], $data['date'], $data['time'])) {
    echo json_encode(['success' => false, 'message' => 'Missing fields']);
    exit;
}

$doctor = $data['doctor'];
$date = $data['date'];
$time = $data['time'];

// Save appointment
$stmt = $conn->prepare("INSERT INTO appointment (name, doctor, date, time) VALUES (?, ?, ?, ?)");
$stmt->bind_param("ssss", $name, $doctor, $date, $time);

if ($stmt->execute()) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'message' => 'Database error']);
}

$stmt->close();
$conn->close();
?>
