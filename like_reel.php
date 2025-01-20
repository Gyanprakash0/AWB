<?php
session_start();
include('connection.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (!isset($_SESSION['user_id'])) {
        echo json_encode(['success' => false, 'message' => 'User not logged in.']);
        exit;
    }

    $reel_id = intval($_POST['reel_id']);
    $user_id = $_SESSION['user_id']; 

    $check_query = "SELECT * FROM reels_meta_data WHERE reel_id = ? AND user_id = ?";
    $stmt = $conn->prepare($check_query);
    $stmt->bind_param("ii", $reel_id, $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        echo json_encode(['success' => false, 'message' => 'You already liked this reel.']);
    } else {
        $insert_query = "INSERT INTO reels_meta_data (reel_id, user_id, likes) VALUES (?, ?, 1)";
        $stmt = $conn->prepare($insert_query);
        $stmt->bind_param("ii", $reel_id, $user_id);
        
        if ($stmt->execute()) {
            
            $update_likes_query = "UPDATE reels SET likes = likes + 1 WHERE id = ?";
            $update_stmt = $conn->prepare($update_likes_query);
            $update_stmt->bind_param("i", $reel_id);
            $update_stmt->execute();

            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Error liking the reel.']);
        }
    }
    $stmt->close();
    $conn->close();
}
