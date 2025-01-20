<?php
session_start();
include('Connection.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $post_id = $_POST['post_id'];

    
    $query = "DELETE FROM posts WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $post_id);
    $stmt->execute();

    
    $_SESSION['message'] = 'Post successfully deleted!';
    header("Location: my_posts.php"); 
    exit;
}
?>
