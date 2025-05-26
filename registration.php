<?php

$firstName = $_POST['firstName'];
$lastName = $_POST['lastName'];
$age = $_POST['age'];
$sex = $_POST['sex'];
$phoneNumber = $_POST['phoneNumber'];
$password = $_POST['password'];

$conn = new mysqli ('localhost', 'root','','register');

if($conn->connect_error){
    die('Connection Failed  : '.$conn->connect_error);
}else{
    $stmt = $conn->prepare("insert into registration(firstName, lastName, age, sex, phoneNumber, password) 
    values(?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssisis", $firstName, $lastName, $age, $sex, $phoneNumber, $password);
    $stmt->execute();
    echo "registrated successfully...";
    $stmt->close();
    $conn->close();
}

?>