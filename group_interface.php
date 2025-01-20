<?php
ob_start(); 
session_start();
include('connection.php'); 
include('includes/header.php');

if (isset($_SESSION['message'])) {
    echo "<div class='alert success'>{$_SESSION['message']}</div>";
    unset($_SESSION['message']); 
}

$userId = $_SESSION['user_id'];
$groupId = $_GET['group_id'];


$stmt = $conn->prepare("SELECT group_name FROM tbl_group WHERE group_id = ?");
$stmt->bind_param("i", $groupId);
$stmt->execute();
$groupResult = $stmt->get_result();
$groupRow = $groupResult->fetch_assoc();
$groupName = $groupRow['group_name'];


$stmt = $conn->prepare("SELECT user_id FROM tbl_group_members WHERE group_id = ?");
$stmt->bind_param("i", $groupId);
$stmt->execute();
$result = $stmt->get_result();
$members = [];
while ($row = $result->fetch_assoc()) {
    $members[] = $row['user_id'];
}


$userDetails = [];
foreach ($members as $member) {
    $stmt = $conn->prepare("SELECT username, email FROM tbl_users WHERE id = ?");
    $stmt->bind_param("i", $member);
    $stmt->execute();
    $userResult = $stmt->get_result();
    if ($userRow = $userResult->fetch_assoc()) {
        $userDetails[] = $userRow;
    }
}


$stmt = $conn->prepare("SELECT user_id, message, created_at FROM tbl_messages WHERE group_id = ? ORDER BY created_at");
$stmt->bind_param("i", $groupId);
$stmt->execute();
$messageResult = $stmt->get_result();
$messages = $messageResult->fetch_all(MYSQLI_ASSOC);


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_FILES['fileToUpload']) && $_FILES['fileToUpload']['error'] === UPLOAD_ERR_OK) {
       
        $targetDir = "Group_chat/";
        $targetFile = $targetDir . basename($_FILES["fileToUpload"]["name"]);
        $fileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

        
        if (in_array($fileType, ['jpg', 'png', 'jpeg', 'mp4', 'pdf', 'mp3'])) {
            if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $targetFile)) {
                $stmt = $conn->prepare("INSERT INTO tbl_messages (group_id, user_id, message, created_at) VALUES (?, ?, ?, NOW())");
                $stmt->bind_param("iis", $groupId, $userId, $targetFile);
                $stmt->execute();
            } else {
                echo "Sorry, there was an error uploading your file.";
            }
        } else {
            echo "Sorry, only JPG, JPEG, PNG, MP4, PDF, & MP3 files are allowed.";
        }
    } elseif (isset($_POST['message']) && !empty(trim($_POST['message']))) {
        
        $message = $_POST['message'];
        $stmt = $conn->prepare("INSERT INTO tbl_messages (group_id, user_id, message, created_at) VALUES (?, ?, ?, NOW())");
        $stmt->bind_param("iis", $groupId, $userId, $message);
        $stmt->execute();
    }

    
    header("Location: group_interface.php?group_id=" . $groupId);
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Group Interface</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css"> <!-- Font Awesome -->
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            background-color: #f8f9fa;
        }

        .container {
            display: flex;
            padding: 20px;
            gap: 20px;
        }

        .member-container {
            flex: 1;
            background-color: #ffffff;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            padding: 20px;
            display: flex;
            flex-direction: column;
        }

        h2, h3 {
            color: #343a40;
            margin: 0; /* Remove default margin */
        }

        #memberItems {
            list-style-type: none;
            padding: 0;
            max-height: 200px; 
            overflow-y: auto; 
            flex-grow: 1; 
            margin-top: 10px; 
        }

        .member-item {
            padding: 10px;
            border-bottom: 1px solid #e9ecef;
            margin-bottom: 5px; 
            transition: background-color 0.2s;
            display: flex;
            align-items: center; 
        }

        .member-item i {
            margin-right: 10px; 
            color: #007bff; 
        }

        .member-item:hover {
            background-color: #e9ecef;
        }

        .chat-box {
            flex: 2;
            background-color: #ffffff;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            padding: 20px;
        }

        .chat-message {
            margin-bottom: 10px;
        }

        #chatMessages {
            height: 400px; 
            overflow-y: auto; 
            border: 1px solid #ced4da;
            padding: 10px;
            background-color: #fdfdfd;
            border-radius: 4px;
        }

        .input-container {
            display: flex;
            align-items: center;
            margin-top: 10px;
        }

        .send-button {
            background: none;
            border: none;
            cursor: pointer;
            color: #007bff;
            font-size: 24px;
            margin-left: 10px;
        }

        input[type="text"] {
            flex-grow: 1;
            padding: 10px;
            border: 1px solid #ced4da;
            border-radius: 4px;
            margin-right: 10px;
        }

        img {
            max-width: 100%;
            border-radius: 4px;
        }

        button {
            padding: 10px 15px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s;
            margin-top: 10px; 
        }

        button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="member-container">
            <h2 style="margin-top: 50px;">Add Member</h2>
            <form method="POST" action="add_member.php">
                <input type="text" name="username" placeholder="Enter username or email to add" required>
                <input type="hidden" name="group_id" value="<?php echo $groupId; ?>">
                <button type="submit">Add Member</button>
            </form>
            <h3 style="padding:50px 0px 0px 0px ;">Group Members (<?php echo count($userDetails); ?>)</h3>
            <ul id="memberItems">
                <?php foreach ($userDetails as $user): ?>
                    <li class="member-item">
                        <i class="fas fa-user"></i> <!-- User icon -->
                        <span><?php echo htmlspecialchars($user['username']); ?> (<?php echo htmlspecialchars($user['email']); ?>)</span>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>

        <div class="chat-box">
            <h3><?php echo htmlspecialchars($groupName); ?> Chat</h3> <!-- Display the group name -->
            <div id="chatMessages">
                <?php foreach ($messages as $msg): ?>
                    <?php
                        
                        $stmt = $conn->prepare("SELECT username FROM tbl_users WHERE id = ?");
                        $stmt->bind_param("i", $msg['user_id']);
                        $stmt->execute();
                        $userResult = $stmt->get_result();
                        $userRow = $userResult->fetch_assoc();
                    ?>
                    <div class="chat-message">
                        <strong><?php echo htmlspecialchars($userRow['username']); ?>:</strong>
                        <?php if (preg_match('/\.(jpg|jpeg|png)$/i', $msg['message'])): ?>
                            <img src="<?php echo htmlspecialchars($msg['message']); ?>" alt="Image sent">
                        <?php else: ?>
                            <?php echo htmlspecialchars($msg['message']); ?>
                        <?php endif; ?>
                        <em>(<?php echo $msg['created_at']; ?>)</em>
                    </div>
                <?php endforeach; ?>
            </div>

            <div class="input-container">
                <form method="POST" enctype="multipart/form-data">
                    <input type="text" name="message" placeholder="Type your message here...">
                    <input type="file" name="fileToUpload" accept=".jpg,.jpeg,.png,.mp4,.pdf,.mp3" style="display: none;" id="fileInput">
                    <label for="fileInput" class="send-button">
                        <i class="fas fa-paperclip"></i>
                    </label>
                    <button type="submit" class="send-button">
                        <i class="fas fa-paper-plane"></i>
                    </button>
                </form>
            </div>
        </div>
    </div>

    <?php include('includes/footer.php'); ?>
</body>

</html>

<?php ob_end_flush();  ?>
