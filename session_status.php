<?php
session_start();

$response = ['message' => '', 'type' => ''];

if (isset($_SESSION['notif_message']) && isset($_SESSION['notif_type'])) {
    $response['message'] = $_SESSION['notif_message'];
    $response['type'] = $_SESSION['notif_type'];
    unset($_SESSION['notif_message'], $_SESSION['notif_type']);
}

header('Content-Type: application/json');
echo json_encode($response);
?>
