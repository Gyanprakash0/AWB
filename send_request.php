<?php
session_start();
include('connection.php'); // Include your database connection

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $userId = $_SESSION['user_id']; // Ensure user ID is fetched from session
    $requestedUserId = $_POST['requested_user_id']; // Make sure this is being sent correctly
    $adharNo = $_POST['adhar_no'];
    $panNo = $_POST['pan_no'];
    $contactNo = $_POST['contact_no'];

    
    var_dump($userId, $requestedUserId, $adharNo, $panNo, $contactNo);

    
    if (empty($requestedUserId)) {
        echo "Requested User ID is required.";
        exit; 
    }

   
    $stmt = $conn->prepare("INSERT INTO tbl_requests (user_id, requested_user_id, adhar_no, pan_no, contact_no, status, created_date) 
                             VALUES (?, ?, ?, ?, ?, 'Pending', NOW())");

   
    if (!$stmt) {
        die("Preparation failed: " . $conn->error);
    }

  
    $stmt->bind_param("iisss", $userId, $requestedUserId, $adharNo, $panNo, $contactNo);

   
    if ($stmt->execute()) {
        
        header("Location: open_to_trip.php?message=Request sent successfully");
    } else {
        
        echo "Error: " . $stmt->error;
    }

    
    $stmt->close();
}

mysqli_close($conn); 
?>
