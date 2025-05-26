<?php
// Database connection settings
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "appointment"; // replace with your actual database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

// Receive POST data
$patient_name = $_POST['name'] ?? '';
$doctor = $_POST['doctor'] ?? '';
$date = $_POST['date'] ?? '';
$time = $_POST['time'] ?? '';

// Simple validation
if ($doctor && $date && $time && $patient_name) {
  $sql = "INSERT INTO appointments (patient_name, doctor, appointment_date, appointment_time)
          VALUES (?, ?, ?, ?)";

  $stmt = $conn->prepare($sql);
  $stmt->bind_param("ssss", $patient_name, $doctor, $date, $time);

  if ($stmt->execute()) {
    echo "success";
  } else {
    echo "error: " . $conn->error;
  }

  $stmt->close();
} else {
  echo "error: Incomplete data.";
}

$conn->close();
?>
