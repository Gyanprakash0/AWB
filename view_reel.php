<?php
session_start();
include('connection.php');

if (isset($_POST['reel_id'])) {
    $reel_id = $_POST['reel_id'];

   
    $query = "UPDATE reels_meta_data SET views = views + 1 WHERE reel_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $reel_id);
    $stmt->execute();

  
    echo json_encode(['success' => true]);
}
?>
