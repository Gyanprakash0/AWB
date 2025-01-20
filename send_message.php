<?php
session_start();
include('Connection.php');


if (!isset($_SESSION['user_id']) || !isset($_POST['receiver_id'])) {
    exit("Invalid request.");
}

$senderId = $_SESSION['user_id'];
$receiverId = $_POST['receiver_id'];
$message = isset($_POST['message']) ? $_POST['message'] : '';
$filePaths = [];
$audioFilePath = '';


if (isset($_FILES['files'])) {
    foreach ($_FILES['files']['tmp_name'] as $key => $tmpName) {
        if ($_FILES['files']['error'][$key] == UPLOAD_ERR_OK) {
            $fileTmpPath = $_FILES['files']['tmp_name'][$key];
            $fileName = $_FILES['files']['name'][$key];
            $fileSize = $_FILES['files']['size'][$key];
            $fileType = pathinfo($fileName, PATHINFO_EXTENSION);

            
            if (in_array($fileType, ['jpg', 'jpeg', 'png', 'mp3', 'mp4', 'wav', 'ogg'])) {
                $targetDir = 'uploads/';
                $filePath = $targetDir . uniqid() . '.' . $fileType;

               
                if (move_uploaded_file($fileTmpPath, $filePath)) {
                    $filePaths[] = $filePath; 
                } else {
                    exit("Error moving the uploaded file.");
                }
            } else {
                exit("Invalid file type. Allowed types: JPG, PNG, MP3, MP4, WAV, OGG.");
            }
        }
    }
}


if (isset($_FILES['audio'])) {
    $audioFile = $_FILES['audio'];
    if ($audioFile['error'] == UPLOAD_ERR_OK) {
        $audioTmpPath = $audioFile['tmp_name'];
        $audioFileName = $audioFile['name'];
        $audioFileType = pathinfo($audioFileName, PATHINFO_EXTENSION);
        
       
        if (in_array($audioFileType, ['mp3', 'wav', 'ogg'])) {
            $audioTargetDir = 'uploads/audio/';
            $audioFilePath = $audioTargetDir . uniqid() . '.' . $audioFileType;
            
            
            if (move_uploaded_file($audioTmpPath, $audioFilePath)) {
                
                $filePaths[] = $audioFilePath;
            } else {
                exit("Error moving the audio file.");
            }
        } else {
            exit("Invalid audio file type. Allowed types: MP3, WAV, OGG.");
        }
    }
}


$query = "INSERT INTO messages (sender_id, receiver_id, message, file_path) VALUES (?, ?, ?, ?)";
$stmt = $conn->prepare($query);


$filePathsString = implode(',', $filePaths); 


$stmt->bind_param("iiss", $senderId, $receiverId, $message, $filePathsString);


$stmt->execute();


echo json_encode(['status' => 'success', 'message' => 'Message sent successfully.']);

?>
