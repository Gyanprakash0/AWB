<?php
session_start();
include('connection.php'); 

if (!isset($_SESSION['user_id'])) {
    echo "You need to be logged in to save a reel.";
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['reel_id'])) {
    $user_id = $_SESSION['user_id'];
    $reel_id = $_POST['reel_id'];

   
    $query = "INSERT INTO saved_reels (user_id, reel_id) VALUES (?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ii", $user_id, $reel_id);
    
    if ($stmt->execute()) {
        echo "Reel saved successfully!";
    } else {
        echo "Error saving reel.";
    }
} else {
    echo "Invalid request.";
}
?>
