<?php
session_start();
include('includes/header.php'); 
include('connection.php'); 
require 'vendor/autoload.php'; 

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;


if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['latitude']) && isset($_POST['longitude'])) {
    $latitude = $_POST['latitude'];
    $longitude = $_POST['longitude'];

    
    $user_id = $_SESSION['user_id'];

    
    $stmtUser = $conn->prepare("SELECT fullname, email FROM tbl_users WHERE id = ?");
    $stmtUser->bind_param("i", $user_id);
    $stmtUser->execute();
    $result = $stmtUser->get_result();
    $user = $result->fetch_assoc();

    
    $sql = "INSERT INTO sos_locations (user_id, latitude, longitude, timestamp) VALUES (?, ?, ?, NOW())";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("idd", $user_id, $latitude, $longitude);
    
    if ($stmt->execute()) {
        
        sendSOSNotificationToAdmin($user, $latitude, $longitude);
        $location_message = "Your location has been shared successfully.";
    } else {
        $location_message = "Error sharing location.";
    }

    $stmt->close();
    $conn->close();
}


function sendSOSNotificationToAdmin($user, $latitude, $longitude) {
    $admin_email = 'admin@hackerworld.net';  // Admin email to send notification
    $subject = "Emergency SOS Alert Reply Fast also Share to nearest help & Support Team!";
    $body = "
    <p><strong>Emergency SOS Alert</strong></p>
    <p><strong>User Full Name:</strong> {$user['fullname']}</p>
    <p><strong>Email:</strong> {$user['email']}</p>
    <p><strong>Location:</strong> Latitude: {$latitude}, Longitude: {$longitude}</p>
    <p><strong>Timestamp:</strong> " . date('Y-m-d H:i:s') . "</p>
    <p>To AWB SOS Emergency Team By Web </p>
    <p><strong>Emergency Alert:</strong> In case of an emergency, please use the following contact numbers for immediate assistance:</p>

<p><strong>Police Emergency Number:</strong> 100</p>
<p><strong>Ambulance Service Number:</strong> 102 (Ambulance), 108 (Emergency Services)</p>
<p><strong>AWB Emergency Support Team:</strong> +91-9835066985</p>

<p>For immediate help, please call the relevant emergency service number or reach out to our support team. If this alert was triggered by mistake, please ignore this message.</p>

<p>Stay safe, and remember to provide your exact location to the authorities. Our team is here to assist you in any way possible.</p>

    ";

    
    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';  
        $mail->SMTPAuth = true;
        $mail->Username = 'mail id'; 
        $mail->Password = 'password';  
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        $mail->setFrom('MAIL ID (mentioned above)', 'SOS Service - AWB Response Fast');
        $mail->addAddress($admin_email);  

        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body = $body;

        
        if ($mail->send()) {
            
        } else {
            echo "Error sending email: " . $mail->ErrorInfo;
        }
    } catch (Exception $e) {
        echo "Error sending email: {$mail->ErrorInfo}";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Emergency SOS Service</title>
    <style>
        h1 {
            margin-top: 20px;
            color: #333;
        }

        
        .sos-button {
            width: 120px;
            height: 120px;
            background-color: red;
            color: white;
            font-size: 30px;
            font-weight: bold;
            border-radius: 50%;
            border: none;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.3);
            transition: transform 0.3s ease;
            margin-top: 30px; 
        }

        .sos-button:hover {
            transform: scale(1.1);
        }

        
        .message {
            font-size: 18px;
            margin-top: 20px;
            color: #333;
        }

    </style>
</head>
<body>

   
    <div style="text-align:center;">
        <h1>Emergency SOS Service</h1>

       
        <button style="margin-left:600px;" class="sos-button" onclick="getLocation()">SOS</button>

        
        <div id="location-message" class="message">
            <?php if (isset($location_message)) { echo "<p>$location_message</p>"; } ?>
        </div>
    </div>

    <script>
        
        function getLocation() {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(sendLocation, showError);
            } else {
                alert("Geolocation is not supported by this browser.");
            }
        }

        
        function sendLocation(position) {
            var lat = position.coords.latitude;
            var lon = position.coords.longitude;

            
            var xhr = new XMLHttpRequest();
            xhr.open("POST", "", true);
            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
            xhr.onload = function () {
                if (xhr.status == 200) {
                    alert("Your location has been shared successfully.");
                }
            };
            xhr.send("latitude=" + lat + "&longitude=" + lon);
        }

      
        function showError(error) {
            switch(error.code) {
                case error.PERMISSION_DENIED:
                    alert("User denied the request for Geolocation.");
                    break;
                case error.POSITION_UNAVAILABLE:
                    alert("Location information is unavailable.");
                    break;
                case error.TIMEOUT:
                    alert("The request to get user location timed out.");
                    break;
                case error.UNKNOWN_ERROR:
                    alert("An unknown error occurred.");
                    break;
            }
        }
    </script>

</body>
</html>

<?php include('includes/footer.php');
