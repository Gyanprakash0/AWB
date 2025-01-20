<?php
session_start();
include('Connection.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  
    $title = $_POST['title'];
    $description = $_POST['description'];
    $location = $_POST['location'];
    $date_time = $_POST['date_time'];
    $post_type = $_POST['post_type'];
    $media_type = $_POST['media_type'];
    
    
    $media_url = null; 
    if (!empty($_FILES['media_file']['name'])) {
      
        $target_dir = 'media/';
    
        if (!file_exists($target_dir)) {
            mkdir($target_dir, 0777, true);
        }
        
       
        $media_url = $target_dir . basename($_FILES['media_file']['name']);
        
        
        if (!move_uploaded_file($_FILES['media_file']['tmp_name'], $media_url)) {
            echo "Error uploading file.";
            exit();
        }
    }

 
    if (!isset($_SESSION['user_id'])) {
        echo "User not logged in.";
        exit();
    }
    $user_id = $_SESSION['user_id'];

    
    $stmt = $conn->prepare("INSERT INTO posts (title, description, location, date_time, post_type, media_type, media_url, user_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssssss", $title, $description, $location, $date_time, $post_type, $media_type, $media_url, $user_id);

    
    if ($stmt->execute()) {
        
        header("Location: user_post_blade.php");
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }

    
    $stmt->close();
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