<?php
session_start();
include('connection.php'); 


if (!isset($_GET['user_id'])) {
    http_response_code(400); 
    echo json_encode(['error' => 'User ID is required']);
    exit;
}

$user_id = $_GET['user_id'];


$stmt = $conn->prepare("SELECT type, payment_method, proof FROM tbl_expenses WHERE user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

$expenses = [];
while ($row = $result->fetch_assoc()) {
    $expenses[] = $row;
}

header('Content-Type: application/json');
echo json_encode($expenses);
