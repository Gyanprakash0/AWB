<?php
session_start(); 
include('connection.php'); 
include('includes/header.php'); 


require 'vendor/autoload.php'; 

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;


function storeMeetingDetails($conn, $userId, $meetingName) {
   
    $stmt = $conn->prepare("INSERT INTO meetings (user_id, meeting_name, created_at) VALUES (?, ?, NOW())");
    $stmt->bind_param("is", $userId, $meetingName);
    $stmt->execute();

    
    $stmtUser = $conn->prepare("SELECT fullname, email FROM tbl_users WHERE id = ?");
    $stmtUser->bind_param("i", $userId);
    $stmtUser->execute();
    $result = $stmtUser->get_result();
    $user = $result->fetch_assoc();
    $url='jitsi meet public domain url';

   
    $subject = "Meeting Created Successfully!";
    $body = "
    Hello {$user['fullname']},<br><br>
    Your meeting has been successfully created!<br><br>
    Meeting Name: {$meetingName}<br>
    Meeting URL: <a href='{$url}/{$meetingName}'>Join Meeting</a><br><br>
    Thank you for using our service!<br>
    Best regards,<br>
    The AWB Team";

  
    sendEmail($user['email'], $subject, $body);
}


function sendEmail($to, $subject, $body) {
    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();  
        $mail->Host = 'smtp.gmail.com';  
        $mail->SMTPAuth = true;
        $mail->Username = 'email';  
        $mail->Password = 'password';  
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;  

        $mail->setFrom('no-reply@hackerworld.net', 'AWB Team');
        $mail->addAddress($to);  

        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body = $body;

        if (!$mail->send()) {
            throw new Exception("Message could not be sent. Mailer Error: {$mail->ErrorInfo}");
        }
    } catch (Exception $e) {
        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['meetingName'])) {
    storeMeetingDetails($conn, $_SESSION['user_id'], $_POST['meetingName']);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jitsi Meet Integration</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <script src="http://awbmeet.duckdns.org/external_api.js"></script>
<!--List Your self hosted or public jitsi script here-->
    <style>
        body {
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            height: 200vh; 
        }

        .container {
            display: flex;
            flex: 1; 
            width: 100%;
            height: 50%;
        }

        .controls {
            width: 30%;
            padding: 100px 50px 50px 50px;
            background-color: #f0f0f0;
            box-shadow: 2px 0 5px rgba(0, 0, 0, 0.1);
            overflow-y: auto; 
        }

        .video-container {
            padding: 20px 0px 0px 0px;
            width: 70%;
            height: 100%; 
            position: relative; 
        }

        button {
            background-color: #4CAF50; 
            border: none;
            color: white;
            padding: 10px 15px;
            text-align: center;
            text-decoration: none;
            display: inline-block;
            font-size: 16px;
            margin: 4px 2px;
            cursor: pointer;
            border-radius: 5px;
            transition: background-color 0.3s;
        }

        button:hover {
            background-color: #45a049; 
        }

        input[type="text"] {
            width: calc(100% - 24px); 
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        h3 {
            margin-top: 20px;
        }

        ul {
            list-style-type: none;
            padding: 0;
        }

        .icon {
            cursor: pointer;
            margin-left: 10px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="controls">
            <h2>Meeting Controls</h2>
            <form method="POST">
                <div>
                    <input type="text" id="meetingName" name="meetingName" placeholder="Enter Meeting Name" required>
                    <button id="createMeeting" type="submit">Create Meeting</button>
                </div>
            </form>
            <div>
                <button id="joinMeeting">Join Meeting</button>
            </div>
            <h3>Recent Meetings</h3>
            <ul id="recentMeetings">
                <?php
                
                $stmt = $conn->prepare("SELECT meeting_name, created_at FROM meetings WHERE user_id = ? ORDER BY created_at DESC LIMIT 5");
                $stmt->bind_param("i", $_SESSION['user_id']);
                $stmt->execute();
                $result = $stmt->get_result();

                while ($row = $result->fetch_assoc()) {
                    echo "<li>{$row['meeting_name']} - {$row['created_at']}</li>";
                }
                ?>
            </ul>
            <h3>Share Meeting</h3>
            <div id="shareOptions" style="display: none;">
                <i class="fas fa-envelope icon" id="shareEmail" title="Share via Email"></i>
                <i class="fas fa-copy icon" id="copyToClipboard" title="Copy to Clipboard"></i>
            </div>
        </div>
        <div class="video-container" id="videoContainer"></div>
    </div>

    <script>
    const domain = 'awbmeet.duckdns.org'; 
    let api;
    let currentMeetingName = '';

    document.getElementById('createMeeting').onclick = () => {
        const meetingName = document.getElementById('meetingName').value;
        if (meetingName) {
            startMeeting(meetingName);
        } else {
            alert('Please enter a meeting name.');
        }
    };

    document.getElementById('joinMeeting').onclick = () => {
        const meetingName = document.getElementById('meetingName').value;
        if (meetingName) {
            startMeeting(meetingName);
        } else {
            alert('Please enter a meeting name.');
        }
    };

    function startMeeting(meetingName) {
        currentMeetingName = meetingName; 
        const options = {
            roomName: meetingName,
            width: '100%',
            height: '100%', 
            parentNode: document.querySelector('#videoContainer'),
            interfaceConfigOverwrite: {
                filmStripOnly: false
            },
            configOverwrite: {
                prejoinPageEnabled: false,
            },
        };

        if (api) {
            api.dispose();
        }

        api = new JitsiMeetExternalAPI(domain, options);

        
        document.getElementById('shareOptions').style.display = 'block';

        
        api.addEventListener('videoConferenceLeft', () => {
            // Clear the video container
            document.getElementById('videoContainer').innerHTML = '';
            // Hide share options
            document.getElementById('shareOptions').style.display = 'none';
        });
    }

    document.getElementById('shareEmail').onclick = () => {
        const meetingUrl = `https://${domain}/${currentMeetingName}`;
        const subject = "Join my meeting";
        const body = `Please join my meeting at: ${meetingUrl}`;
        window.location.href = `mailto:?subject=${encodeURIComponent(subject)}&body=${encodeURIComponent(body)}`;
    };

    document.getElementById('copyToClipboard').onclick = () => {
        const meetingUrl = `https://${domain}/${currentMeetingName}`;
        navigator.clipboard.writeText(meetingUrl).then(() => {
            alert('Meeting link copied to clipboard!');
        }, (err) => {
            alert('Failed to copy: ', err);
        });
    };
    </script>
</body>

<?php include('includes/footer.php'); ?>
</html>
