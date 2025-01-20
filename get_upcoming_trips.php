<?php
session_start();
include('database_connection.php'); 

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    $userId = $_GET['user_id'];

    $stmt = $conn->prepare("SELECT user_name, price FROM bookings WHERE user_id = ?");
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $result = $stmt->get_result();

    $trips = [];
    while ($row = $result->fetch_assoc()) {
        $trips[] = $row;
    }

    echo json_encode($trips);
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