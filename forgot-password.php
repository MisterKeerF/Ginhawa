<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $phone = $_POST['phone'];

  // Connect to database
  $conn = new mysqli('sql203.infinityfree.com', 'if0_39086009', 'zI7iB22cjtr4w', 'if0_39086009_db1');

  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  // Use correct column name: phoneNumber
  $stmt = $conn->prepare("SELECT * FROM user WHERE phoneNumber = ?");
  $stmt->bind_param("s", $phone);
  $stmt->execute();
  $result = $stmt->get_result();

  if ($result->num_rows > 0) {
    session_start();
    $_SESSION['reset_phone'] = $phone;
    header("Location: reset-password.html");
    exit();
  } else {
    echo "<script>alert('Phone number not found.'); window.history.back();</script>";
  }

  $stmt->close();
  $conn->close();
}
?>
