<?php
session_start();
include('Connection.php');

if (!isset($_SESSION['user_id']) || !isset($_POST['receiver_id'])) {
    exit("Invalid request.");
}

$senderId = $_SESSION['user_id'];
$receiverId = $_POST['receiver_id'];

$query = "UPDATE messages SET seen = 1 WHERE sender_id = ? AND receiver_id = ? AND seen = 0";
$stmt = $conn->prepare($query);
$stmt->bind_param("ii", $receiverId, $senderId);
$stmt->execute();
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