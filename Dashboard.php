<?php
session_start();
include('includes/header.php');
include('connection.php');
require 'vendor/autoload.php';  

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Send email function
function send_email($to, $subject, $body) {
    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();  
        $mail->Host = 'smtp.gmail.com';  
        $mail->SMTPAuth = true;
        $mail->Username = 'no-reply@awb.com';  //IF require Create a Universal .envfile and then configure there
        $mail->Password = 'PAssword'; 
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;  

        $mail->setFrom('no-reply@awb.com', 'ADMIN from AWB');
        $mail->addAddress($to);  // Send to the logged-in user's email

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


if (!isset($_SESSION['user_id'])) {
    header('Location: login.php'); 
    exit();
}

$user_id = $_SESSION['user_id'];


$user_details_query = "SELECT id, fullname, username, email FROM tbl_users WHERE id = '$user_id'";
$user_details_result = mysqli_query($conn, $user_details_query);
$user_details = mysqli_fetch_assoc($user_details_result);


$fullname = $user_details['fullname'];
$username = $user_details['username'];
$email = $user_details['email'];


if (!isset($_SESSION['email_sent'])) {
    $_SESSION['email_sent'] = true;  

    // Send email to the logged-in user
    $subject = "Login Notification from AWB Website";
    $body = "
    Hello $fullname,<br><br>
    A new login has been detected on your account at the AWB Website. Here are the details:<br><br>
    <strong>Username:</strong> $username<br>
    <strong>User ID:</strong> $user_id<br>
    <strong>Login Time:</strong> " . date('Y-m-d H:i:s') . "<br>
    <strong>IP Address:</strong> " . $_SERVER['REMOTE_ADDR'] . "<br>
    <strong>Browser/Device:</strong> " . $_SERVER['HTTP_USER_AGENT'] . "<br><br>
    If this was not you, please contact support immediately. Otherwise, you can ignore this message.<br><br>
    Thank you!<br><br>
    Best regards,<br>
    The AWB Team<br>
    hackerworld.net";  

    
    send_email($email, $subject, $body);
}


$meetings_today_query = "SELECT COUNT(*) AS total_meetings_today FROM meetings WHERE user_id = '$user_id' AND DATE(created_at) = CURDATE()";
$meetings_week_query = "SELECT COUNT(*) AS total_meetings_week FROM meetings WHERE user_id = '$user_id' AND YEARWEEK(created_at, 1) = YEARWEEK(CURDATE(), 1)";
$meetings_month_query = "SELECT COUNT(*) AS total_meetings_month FROM meetings WHERE user_id = '$user_id' AND YEAR(created_at) = YEAR(CURDATE()) AND MONTH(created_at) = MONTH(CURDATE())";

$meetings_today_result = mysqli_query($conn, $meetings_today_query);
$meetings_week_result = mysqli_query($conn, $meetings_week_query);
$meetings_month_result = mysqli_query($conn, $meetings_month_query);

$meetings_today = mysqli_fetch_assoc($meetings_today_result)['total_meetings_today'];
$meetings_week = mysqli_fetch_assoc($meetings_week_result)['total_meetings_week'];
$meetings_month = mysqli_fetch_assoc($meetings_month_result)['total_meetings_month'];


$pending_requests_query = "SELECT COUNT(*) AS total_pending_requests FROM forex_requests WHERE user_id = '$user_id' AND status = 'pending'";
$approved_requests_query = "SELECT COUNT(*) AS total_approved_requests FROM forex_requests WHERE user_id = '$user_id' AND status = 'approved'";
$withdrawn_requests_query = "SELECT COUNT(*) AS total_withdrawn_requests FROM forex_requests WHERE user_id = '$user_id' AND status = 'withdrawn'";

$pending_requests_result = mysqli_query($conn, $pending_requests_query);
$approved_requests_result = mysqli_query($conn, $approved_requests_query);
$withdrawn_requests_result = mysqli_query($conn, $withdrawn_requests_query);

$pending_requests = mysqli_fetch_assoc($pending_requests_result)['total_pending_requests'];
$approved_requests = mysqli_fetch_assoc($approved_requests_result)['total_approved_requests'];
$withdrawn_requests = mysqli_fetch_assoc($withdrawn_requests_result)['total_withdrawn_requests'];


$loan_today_query = "SELECT SUM(total_amount) AS total_loan_today FROM loan_requests WHERE user_id = '$user_id' AND DATE(created_at) = CURDATE()";
$loan_30days_query = "SELECT SUM(total_amount) AS total_loan_30days FROM loan_requests WHERE user_id = '$user_id' AND created_at >= CURDATE() - INTERVAL 30 DAY";
$loan_year_query = "SELECT SUM(total_amount) AS total_loan_year FROM loan_requests WHERE user_id = '$user_id' AND YEAR(created_at) = YEAR(CURDATE())";
$loan_status_query = "SELECT status, COUNT(*) AS total_requests FROM loan_requests WHERE user_id = '$user_id' GROUP BY status";

$loan_today_result = mysqli_query($conn, $loan_today_query);
$loan_30days_result = mysqli_query($conn, $loan_30days_query);
$loan_year_result = mysqli_query($conn, $loan_year_query);
$loan_status_result = mysqli_query($conn, $loan_status_query);

$loan_today = mysqli_fetch_assoc($loan_today_result)['total_loan_today'];
$loan_30days = mysqli_fetch_assoc($loan_30days_result)['total_loan_30days'];
$loan_year = mysqli_fetch_assoc($loan_year_result)['total_loan_year'];


$loan_status_data = [];
while ($row = mysqli_fetch_assoc($loan_status_result)) {
    $loan_status_data[$row['status']] = $row['total_requests'];
}

$pending_loans = $loan_status_data['pending'] ?? 0;
$approved_loans = $loan_status_data['approved'] ?? 0;
$cancelled_loans = $loan_status_data['cancelled'] ?? 0;
$withdrawn_loans = $loan_status_data['withdrawn'] ?? 0;


$gallery_10days_query = "SELECT COUNT(*) AS total_folders_10days FROM tbl_folders WHERE user_id = '$user_id' AND created_at >= CURDATE() - INTERVAL 10 DAY";
$gallery_30days_query = "SELECT COUNT(*) AS total_folders_30days FROM tbl_folders WHERE user_id = '$user_id' AND created_at >= CURDATE() - INTERVAL 30 DAY";
$gallery_year_query = "SELECT COUNT(*) AS total_folders_year FROM tbl_folders WHERE user_id = '$user_id' AND YEAR(created_at) = YEAR(CURDATE())";

$gallery_10days_result = mysqli_query($conn, $gallery_10days_query);
$gallery_30days_result = mysqli_query($conn, $gallery_30days_query);
$gallery_year_result = mysqli_query($conn, $gallery_year_query);

$gallery_10days = mysqli_fetch_assoc($gallery_10days_result)['total_folders_10days'];
$gallery_30days = mysqli_fetch_assoc($gallery_30days_result)['total_folders_30days'];
$gallery_year = mysqli_fetch_assoc($gallery_year_result)['total_folders_year'];


$connection_pending_query = "SELECT COUNT(*) AS total_pending_connections FROM tbl_requests WHERE user_id = '$user_id' AND status = 'pending'";
$connection_accepted_query = "SELECT COUNT(*) AS total_accepted_connections FROM tbl_requests WHERE user_id = '$user_id' AND status = 'accepted'";
$connection_not_accepted_query = "SELECT COUNT(*) AS total_not_accepted_connections FROM tbl_requests WHERE user_id = '$user_id' AND status = 'not accepted'";

$connection_pending_result = mysqli_query($conn, $connection_pending_query);
$connection_accepted_result = mysqli_query($conn, $connection_accepted_query);
$connection_not_accepted_result = mysqli_query($conn, $connection_not_accepted_query);

$connection_pending = mysqli_fetch_assoc($connection_pending_result)['total_pending_connections'];
$connection_accepted = mysqli_fetch_assoc($connection_accepted_result)['total_accepted_connections'];
$connection_not_accepted = mysqli_fetch_assoc($connection_not_accepted_result)['total_not_accepted_connections'];


mysqli_close($conn);
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f4f7fa;
        }
        .chart-container {
            margin-bottom: 30px;
        }
    </style>
</head>
<body>

<div class="container mt-5">
    <div class="row">

        
        <div class="col-lg-4 col-md-6 chart-container">
            <div class="card p-3">
                <h5>Total Meetings Created</h5>
                <canvas id="meetingsChart"></canvas>
            </div>
        </div>

        
        <div class="col-lg-4 col-md-6 chart-container">
            <div class="card p-3">
                <h5>Forex Requests</h5>
                <canvas id="forexRequestsChart"></canvas>
            </div>
        </div>

       
        <div class="col-lg-4 col-md-6 chart-container">
            <div class="card p-3">
                <h5>Loan Requests</h5>
                <canvas id="loanRequestsChart"></canvas>
            </div>
        </div>

        
        <div class="col-lg-4 col-md-6 chart-container">
            <div class="card p-3">
                <h5>Travel Gallery Folders</h5>
                <canvas id="travelGalleryChart"></canvas>
            </div>
        </div>

        >
        <div class="col-lg-4 col-md-6 chart-container">
            <div class="card p-3">
                <h5>Connection Requests</h5>
                <canvas id="connectionRequestsChart"></canvas>
            </div>
        </div>

    </div>
</div>

<script>
// Meetings Chart
const meetingsData = {
    labels: ['Today', 'This Week', 'This Month'],
    datasets: [{
        label: 'Meetings Created',
        data: [<?php echo $meetings_today; ?>, <?php echo $meetings_week; ?>, <?php echo $meetings_month; ?>],
        backgroundColor: 'rgba(75, 192, 192, 0.6)',
        borderColor: 'rgba(75, 192, 192, 1)',
        borderWidth: 1
    }]
};
const ctxMeetings = document.getElementById('meetingsChart').getContext('2d');
new Chart(ctxMeetings, { type: 'bar', data: meetingsData });

// Forex Requests Chart
const forexData = {
    labels: ['Pending', 'Approved', 'Withdrawn'],
    datasets: [{
        label: 'Forex Requests',
        data: [<?php echo $pending_requests; ?>, <?php echo $approved_requests; ?>, <?php echo $withdrawn_requests; ?>],
        backgroundColor: 'rgba(255, 99, 132, 0.6)',
        borderColor: 'rgba(255, 99, 132, 1)',
        borderWidth: 1
    }]
};
const ctxForex = document.getElementById('forexRequestsChart').getContext('2d');
new Chart(ctxForex, { type: 'bar', data: forexData });

// Loan Requests Chart
const loanData = {
    labels: ['Today', '30 Days', 'This Year'],
    datasets: [{
        label: 'Loan Requests (Total Amount)',
        data: [<?php echo $loan_today; ?>, <?php echo $loan_30days; ?>, <?php echo $loan_year; ?>],
        backgroundColor: 'rgba(54, 162, 235, 0.6)',
        borderColor: 'rgba(54, 162, 235, 1)',
        borderWidth: 1
    }]
};
const ctxLoan = document.getElementById('loanRequestsChart').getContext('2d');
new Chart(ctxLoan, { type: 'line', data: loanData });


const galleryData = {
    labels: ['Last 10 Days', 'Last 30 Days', 'This Year'],
    datasets: [{
        label: 'Travel Gallery Folders Created',
        data: [<?php echo $gallery_10days; ?>, <?php echo $gallery_30days; ?>, <?php echo $gallery_year; ?>],
        backgroundColor: 'rgba(255, 159, 64, 0.6)',
        borderColor: 'rgba(255, 159, 64, 1)',
        borderWidth: 1
    }]
};
const ctxGallery = document.getElementById('travelGalleryChart').getContext('2d');
new Chart(ctxGallery, { type: 'bar', data: galleryData });


const connectionData = {
    labels: ['Pending', 'Accepted', 'Not Accepted'],
    datasets: [{
        label: 'Connection Requests',
        data: [<?php echo $connection_pending; ?>, <?php echo $connection_accepted; ?>, <?php echo $connection_not_accepted; ?>],
        backgroundColor: 'rgba(153, 102, 255, 0.6)',
        borderColor: 'rgba(153, 102, 255, 1)',
        borderWidth: 1
    }]
};
const ctxConnection = document.getElementById('connectionRequestsChart').getContext('2d');
new Chart(ctxConnection, { type: 'bar', data: connectionData });
</script>

</body>
</html>

<?php include('includes/footer.php'); ?>
