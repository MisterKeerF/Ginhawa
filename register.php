<?php
session_start(); // ✅ REQUIRED to use sessions

$host = "localhost";
$dbname = "db1";
$dbUser = "root";
$dbPass = "";

$firstName = $_POST['firstName'] ?? '';
$lastName = $_POST['lastName'] ?? '';
$age = $_POST['age'] ?? '';
$sex = $_POST['sex'] ?? '';
$phoneNumber = $_POST['phoneNumber'] ?? '';
$password_raw = $_POST['password'] ?? '';

if (empty($firstName) || empty($lastName) || empty($age) || empty($sex) || empty($phoneNumber) || empty($password_raw)) {
    die("Please fill out all required fields.");
}

$password_hashed = password_hash($password_raw, PASSWORD_DEFAULT);

// Connect to database
$conn = new mysqli($host, $dbUser, $dbPass, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Prepare and execute statement
$stmt = $conn->prepare("INSERT INTO user (firstName, lastName, age, sex, phoneNumber, password) VALUES (?, ?, ?, ?, ?, ?)");
$stmt->bind_param("ssisss", $firstName, $lastName, $age, $sex, $phoneNumber, $password_hashed);

if ($stmt->execute()) {
    // ✅ Get the ID of the newly inserted user
    $newUserId = $stmt->insert_id;

    // ✅ Set session variable
    $_SESSION['user_id'] = $newUserId;

    // ✅ You can optionally store full name
    $_SESSION['user_name'] = $firstName . ' ' . $lastName;

    // Redirect to homepage or appointment page
    header("Location: login.html"); // or wherever you want
    exit();
} else {
    echo "Error: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>
