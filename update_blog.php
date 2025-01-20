<?php
session_start();
include('Connection.php'); 

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id'];
    $title = $_POST['title'];
    $theme = $_POST['theme'];
    $description = $_POST['description'];
    $image = $_FILES['image']['name'];

   
    if (!empty($image)) {
        $target = "uploads/" . basename($image);
        move_uploaded_file($_FILES['image']['tmp_name'], $target);
    } else {
        
        $query = "SELECT image FROM blogs WHERE id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $image = $row['image'];
    }

    // Update blog in the database
    $query = "UPDATE blogs SET title = ?, theme = ?, description = ?, image = ? WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ssssi", $title, $theme, $description, $image, $id);

    if ($stmt->execute()) {
        $_SESSION['success_message'] = "Blog updated successfully!";
    } else {
        $_SESSION['error_message'] = "Failed to update blog.";
    }

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