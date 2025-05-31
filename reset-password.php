<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();

if (!isset($_SESSION['reset_phone'])) {
  echo "<script>alert('Session expired. Try again.'); window.location.href='forgot-password.html';</script>";
  exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $phone = $_SESSION['reset_phone'];
  $new_password = $_POST['new_password'];
  $confirm_password = $_POST['confirm_password'];

  if ($new_password !== $confirm_password) {
    echo "<script>alert('Passwords do not match.'); window.history.back();</script>";
    exit();
  }

  $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

  $conn = new mysqli('sql203.infinityfree.com', 'if0_39086009', 'zI7iB22cjtr4w', 'if0_39086009_db1');
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  $sql = "UPDATE user SET password = ? WHERE phone = ?";
  $stmt = $conn->prepare("UPDATE user SET password = ? WHERE phoneNumber = ?");
  $stmt->bind_param("ss", $hashed_password, $phone);
  $stmt->execute();

  $stmt->close();
  $conn->close();
  session_destroy();

  echo "<script>alert('Password successfully reset!'); window.location.href='login.html';</script>";
}
?>
