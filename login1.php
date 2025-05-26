<?php
$host = "localhost";
$dbname = "db1";
$username = "root";
$password = "";

// Connect to DB
$conn = new mysqli($host, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}



// Get login input
$identifier = $_POST['identifier'] ?? '';
$password_raw = $_POST['password'] ?? '';

if (empty($identifier) || empty($password_raw)) {
    die("Please fill in all fields.");
}

// Function to check user in a table
function checkUser($conn, $table, $idField, $identifier, $password_raw, $redirectPage) {
    $stmt = $conn->prepare("SELECT * FROM $table WHERE $idField = ?");
    $stmt->bind_param("s", $identifier);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($row = $result->fetch_assoc()) {
        if (password_verify($password_raw, $row['password'])) {
            // Login successful
            session_start();
            $_SESSION['user_id'] = $row['id'] ?? null;
            $_SESSION['role'] = $table;
            header("Location: $redirectPage");
            exit();
        }
    }
    return false;
}

// Check patient using phone number
if (checkUser($conn, "user", "phoneNumber", $identifier, $password_raw, "patientdashboard.html")) {
    exit();
}

// Check doctor using username
if (checkUser($conn, "doctor", "user_name", $identifier, $password_raw, "doctor-interface.html")) {
    exit();
}

// Check admin using username
if (checkUser($conn, "admin", "userName", $identifier, $password_raw, "admindashboard.html")) {
    exit();
}

// If no match found
echo "<script>alert('Invalid credentials. Please try again.'); window.location.href = 'login.html';</script>";
exit();
?>

