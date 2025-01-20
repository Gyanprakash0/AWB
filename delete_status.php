<?php
session_start();
include('connection.php'); 

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}


if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $user_id = $_SESSION['user_id'];

 
    $stmt = $conn->prepare("DELETE FROM user_status WHERE id = ? AND user_id = ?");
    if ($stmt) {
        $stmt->bind_param("ii", $id, $user_id);
        if ($stmt->execute()) {
            $_SESSION['success_message'] = "Status deleted successfully.";
        } else {
            $_SESSION['error_message'] = "Error deleting status: " . $stmt->error;
        }
        $stmt->close();
    } else {
        $_SESSION['error_message'] = "Failed to prepare statement: " . $conn->error;
    }
} else {
    $_SESSION['error_message'] = "No ID provided.";
}

header('Location: connect+.php'); // Redirect back to the user status page
exit();
?>
