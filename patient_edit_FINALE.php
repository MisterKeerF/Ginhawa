<?php
// edit_patient.php

$conn = new mysqli('sql203.infinityfree.com', 'if0_39086009', 'zI7iB22cjtr4w', 'if0_39086009_db1');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if form submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = intval($_POST['id']);
    $firstName = $conn->real_escape_string($_POST['firstName']);
    $lastName = $conn->real_escape_string($_POST['lastName']);
    $phoneNumber = $conn->real_escape_string($_POST['phoneNumber']);
    // Add other fields if needed

    $sql = "UPDATE user SET firstName='$firstName', lastName='$lastName', phoneNumber='$phoneNumber' WHERE id=$id";

    if ($conn->query($sql) === TRUE) {
        header("Location: patients.php?message=updated");
        exit();
    } else {
        echo "Error updating record: " . $conn->error;
    }
}

// Otherwise, show form pre-filled
if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $result = $conn->query("SELECT * FROM user WHERE id=$id");

    if ($result->num_rows === 1) {
        $row = $result->fetch_assoc();
    } else {
        echo "Patient not found.";
        exit();
    }
} else {
    echo "No ID specified.";
    exit();
}

$conn->close();
?>

<!DOCTYPE html>
<html>
<head><title>Edit Patient</title></head>
<body>
<h2>Edit Patient</h2>
<form method="POST" action="edit_patient.php">
    <input type="hidden" name="id" value="<?php echo $row['id']; ?>" />
    <label>First Name:</label><br />
    <input type="text" name="firstName" value="<?php echo htmlspecialchars($row['firstName']); ?>" required /><br />
    <label>Last Name:</label><br />
    <input type="text" name="lastName" value="<?php echo htmlspecialchars($row['lastName']); ?>" required /><br />
    <label>Phone Number:</label><br />
    <input type="text" name="phoneNumber" value="<?php echo htmlspecialchars($row['phoneNumber']); ?>" required /><br />
    <br />
    <input type="submit" value="Update Patient" />
</form>
</body>
</html>
