<?php
session_start();
include('database_connection.php'); 

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $userId = $_POST['user_id'];
    $userName = $_POST['user_name'];
    $idCard = $_POST['user_id_card'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $price = $_POST['price'];

    $stmt = $conn->prepare("INSERT INTO bookings (user_id, user_name, user_id_card, email, phone, price) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("issssd", $userId, $userName, $idCard, $email, $phone, $price);
    $stmt->execute();
    $stmt->close();

    echo json_encode(['status' => 'success']);
}
?>
<?php
/*
  ************************************************************
  *                        AWB                                 *
  *                       Copyright                              *
  *   Gyan Prakash Pandey   *   Ansh Shukla   *   Shiwan Tripathi   *
  *   Code is open source, but please do not remove this credit. *
  ************************************************************
*/?>