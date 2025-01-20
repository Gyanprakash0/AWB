<?php
session_start();
include('connection.php'); 


if (!isset($_POST['group_id']) || !isset($_POST['username'])) {
    echo "Required data not found.";
    exit;
}

$userId = $_SESSION['user_id'];
$groupId = $_POST['group_id'];
$usernameOrEmail = $_POST['username'];


$stmt = $conn->prepare("SELECT id FROM tbl_users WHERE username = ? OR email = ?");
$stmt->bind_param("ss", $usernameOrEmail, $usernameOrEmail);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
    $userIdToAdd = $user['id'];

    
    $stmt->close(); 
    $stmt = $conn->prepare("SELECT * FROM tbl_group_members WHERE group_id = ? AND user_id = ?");
    $stmt->bind_param("ii", $groupId, $userIdToAdd);
    $stmt->execute();
    $checkResult = $stmt->get_result(); 

    if ($checkResult->num_rows === 0) {
        
        $stmt->close(); 
        $stmt = $conn->prepare("INSERT INTO tbl_group_members (group_id, user_id) VALUES (?, ?)");
        $stmt->bind_param("ii", $groupId, $userIdToAdd);
        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            $_SESSION['message'] = "Member added successfully."; 
            header("Location: group_interface.php"); 
            exit();
        } else {
            $_SESSION['message'] = "Failed to add member."; 
            header("Location: group_interface.php");
            exit();
        }
    } else {
        $_SESSION['message'] = "User is already a member of this group."; 
        header("Location: group_interface.php");
        exit();
    }
} else {
    $_SESSION['message'] = "User not found."; 
    header("Location: group_interface.php");
    exit();
}

$stmt->close(); 
$conn->close(); 
?>
