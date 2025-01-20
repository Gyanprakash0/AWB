<?php
session_start();
include('connection.php'); 

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['id'])) {
    $reel_id = $_POST['id'];

    
    $query = "SELECT file_name FROM reels WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $reel_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $file_name = $row['file_name'];

        
        $delete_query = "DELETE FROM reels WHERE id = ?";
        $delete_stmt = $conn->prepare($delete_query);
        $delete_stmt->bind_param("i", $reel_id);

        if ($delete_stmt->execute()) {
            
            $file_path = "travel-clips/" . $file_name;

            
            if (file_exists($file_path)) {
                unlink($file_path);
            }

            echo "Success"; 
        } else {
            echo "Error deleting reel from database"; 
        }
    } else {
        echo "Reel not found"; 
    }
} else {
    echo "Invalid request"; // Optionally, send an error message back
}
?>
