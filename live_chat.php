<?php
session_start();
include('connection.php'); // Include your database connection

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php"); // Redirect to login if not logged in
    exit;
}

// Handle message sending
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userId = $_SESSION['user_id']; // Get the logged-in user's ID
    $message = $_POST['message'] ?? '';

    // Handle file uploads
    if (isset($_FILES['files'])) {
        foreach ($_FILES['files']['tmp_name'] as $key => $tmp_name) {
            $fileName = $_FILES['files']['name'][$key];
            $targetPath = "uploads/" . basename($fileName);
            move_uploaded_file($tmp_name, $targetPath);
            $filePath = $targetPath;

            // Save message with file
            $query = "INSERT INTO tbl_live_chat (user_id, message, file_path) VALUES ('$userId', '$message', '$filePath')";
            mysqli_query($conn, $query);
        }
    } else {
        // Save message without file
        $query = "INSERT INTO tbl_live_chat (user_id, message) VALUES ('$userId', '$message')";
        mysqli_query($conn, $query);
    }

    header("Location: live_chat.php"); // Redirect to the same page to avoid resubmission
    exit;
}

// Count users
$queryCount = "SELECT COUNT(*) as user_count FROM tbl_users";
$userCountResult = mysqli_query($conn, $queryCount);
$userCount = mysqli_fetch_assoc($userCountResult)['user_count'];

// Fetch chat messages
$queryChat = "SELECT u.fullname, lc.message, lc.file_path, lc.timestamp 
              FROM tbl_live_chat lc 
              JOIN tbl_users u ON lc.user_id = u.id 
              ORDER BY lc.timestamp ASC";
$chatMessages = mysqli_query($conn, $queryChat);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Live Chat</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <style>
        body {
            background-color: #f8f9fa;
        }
        #chat-box {
            height: 400px;
            overflow-y: scroll;
            background-color: #fff;
            border-radius: 5px;
            padding: 15px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
        }
        .chat-message {
            margin-bottom: 15px;
            animation: fadeIn 0.5s;
        }
        .chat-message img {
            max-width: 100%;
            border-radius: 5px;
        }
        .chat-message video {
            max-width: 100%;
            border-radius: 5px;
        }
        .rules {
            background-color: #e9ecef;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
    </style>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
<?php include('includes/header.php'); ?>

<div class="container mt-5">
    <h1 class="display-4">Welcome, <?php echo htmlspecialchars($_SESSION['fullname']); ?>!</h1>
    <hr>

    <div class="row">
        <div class="col-md-4">
            <h3><i class="fas fa-users"></i> User Count: <?php echo $userCount; ?></h3>
            <div class="rules">
                <h4>Rules and Regulations:</h4>
                <ol>
                    <li>Live chat is monitored by admin.</li>
                    <li>Do not share personal information.</li>
                    <li>No offensive language.</li>
                    <li>Respect others in the chat.</li>
                    <li>No spamming or flooding the chat.</li>
                    <li>Do not share illegal content.</li>
                    <li>Keep discussions relevant to the chat topic.</li>
                    <li>Be courteous and helpful.</li>
                    <li>Do not impersonate others.</li>
                    <li>Follow company policies at all times.</li>
                </ol>
            </div>
        </div>
        <div class="col-md-8">
            <h3>Chat</h3>
            <div id="chat-box">
                <?php while ($row = mysqli_fetch_assoc($chatMessages)) : ?>
                    <div class="chat-message">
                        <strong><?php echo htmlspecialchars($row['fullname']); ?>:</strong>
                        <p><?php echo nl2br(htmlspecialchars($row['message'])); ?></p>
                        <?php if ($row['file_path']) : ?>
                            <div>
                                <?php
                                $fileExtension = pathinfo($row['file_path'], PATHINFO_EXTENSION);
                                if (in_array($fileExtension, ['jpg', 'jpeg', 'png', 'gif'])) {
                                    echo '<img src="' . htmlspecialchars($row['file_path']) . '" alt="Uploaded Image">';
                                } elseif (in_array($fileExtension, ['mp4', 'mov'])) {
                                    echo '<video controls><source src="' . htmlspecialchars($row['file_path']) . '" type="video/mp4">Your browser does not support the video tag.</video>';
                                } elseif (in_array($fileExtension, ['pdf'])) {
                                    echo '<iframe src="' . htmlspecialchars($row['file_path']) . '" style="width: 100%; height: 400px;" frameborder="0"></iframe>';
                                }
                                ?>
                            </div>
                        <?php endif; ?>
                        <small class="text-muted"> (<?php echo htmlspecialchars($row['timestamp']); ?>)</small>
                    </div>
                <?php endwhile; ?>
            </div>
            <form method="post" enctype="multipart/form-data" class="mt-2">
                <textarea name="message" class="form-control" rows="3" placeholder="Type your message here..."></textarea>
                <input type="file" name="files[]" class="mt-2" accept=".jpg,.jpeg,.png,.mp3,.mp4,.pdf" multiple>
                <button type="submit" class="btn btn-primary mt-2">Send</button>
            </form>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    // Auto-scroll to the bottom of the chat box
    $('#chat-box').scrollTop($('#chat-box')[0].scrollHeight);
});
</script>

<?php include('includes/footer.php'); ?>
</body>
</html>
<?php
/*
  ************************************************************
  *                        AWB                                 *
  *                       Copyright                              *
  *   Gyan Prakash Pandey   *   Ansh Shukla   *   Shiwan Tripathi   *
  *   Code is open source, but please do not remove this credit. *
  ************************************************************
*/?>