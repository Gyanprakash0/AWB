<?php
session_start();
include('connection.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $reel_id = intval($_POST['reel_id']);
    $comment = trim($_POST['comment']);
    $user_id = $_SESSION['user_id']; 
    
    if (!empty($comment)) {
        
        $query = "SELECT comments FROM reels_meta_data WHERE reel_id = ? AND user_id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ii", $reel_id, $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
           
            $row = $result->fetch_assoc();
            $existing_comments = $row['comments'];
            $new_comments = $existing_comments . '|' . htmlspecialchars($comment);
            $query = "UPDATE reels_meta_data SET comments = ? WHERE reel_id = ? AND user_id = ?";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("sii", $new_comments, $reel_id, $user_id);
            $stmt->execute();
        } else {
            
            $query = "INSERT INTO reels_meta_data (reel_id, user_id, comments) VALUES (?, ?, ?)";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("iis", $reel_id, $user_id, htmlspecialchars($comment));
            $stmt->execute();
        }

        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Comment cannot be empty.']);
    }
}
?>
