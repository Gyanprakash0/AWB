<?php
session_start();
include('Connection.php');

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $id = $_POST['id'];
    $name = $_POST['name'];
    $mobile_number = $_POST['mobile_number'];
    $trip_start_date = $_POST['trip_start_date'];
    $trip_duration = $_POST['trip_duration'];
    $gender = $_POST['gender'];
    $preference = $_POST['preference'];
    $status = $_POST['status'];
    $other_status = $_POST['other_status'] ?? null;

    
    $stmt = $conn->prepare("UPDATE user_status SET name = ?, mobile_number = ?, trip_start_date = ?, trip_duration = ?, gender = ?, preference = ?, status = ?, other_status = ? WHERE id = ? AND user_id = ?");
    $stmt->bind_param("ssissssssi", $name, $mobile_number, $trip_start_date, $trip_duration, $gender, $preference, $status, $other_status, $id, $_SESSION['user_id']);
    $stmt->execute();
    $stmt->close();

    $_SESSION['success_message'] = "Status updated successfully.";
    header("Location: status.php");
    exit();
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