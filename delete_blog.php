<?php
session_start();
include('Connection.php'); 

if (isset($_GET['id'])) {
    $blog_id = $_GET['id'];
    $user_id = $_SESSION['user_id']; 

  
    $query = "DELETE FROM blogs WHERE id = ? AND user_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ii", $blog_id, $user_id);

    if ($stmt->execute()) {
        $_SESSION['success_message'] = "Blog has been deleted successfully.";
    } else {
        $_SESSION['error_message'] = "Error deleting blog. Please try again.";
    }

    $stmt->close();
    header('Location: user-blog.php');
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