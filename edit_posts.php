<?php
session_start();
include('Connection.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $post_id = $_POST['post_id'];
    $title = $_POST['post_title'];
    $description = $_POST['post_description'];
    $location = $_POST['post_location'];

   
    $media_url = null;
    if (isset($_FILES['post_media']) && $_FILES['post_media']['error'] == UPLOAD_ERR_OK) {
        $file_tmp = $_FILES['post_media']['tmp_name'];
        $file_name = basename($_FILES['post_media']['name']);
        $upload_dir = 'uploads/'; 
        $media_url = $upload_dir . uniqid() . '-' . $file_name;
        move_uploaded_file($file_tmp, $media_url);
    }

    
    $query = "UPDATE posts SET title = ?, description = ?, location = ?" . ($media_url ? ", media_url = ? " : "") . " WHERE id = ?";
    $stmt = $conn->prepare($query);
    
    if ($media_url) {
        $stmt->bind_param("ssssi", $title, $description, $location, $media_url, $post_id);
    } else {
        $stmt->bind_param("sssi", $title, $description, $location, $post_id);
    }
    
    $stmt->execute();
    
   
    $_SESSION['message'] = 'Post successfully Edited!';
    header("Location: my_posts.php");
    exit;
}
?>
