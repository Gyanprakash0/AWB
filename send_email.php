<!-- For Testing Purpose -->


<?php

require 'vendor/autoload.php';  

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

function send_email($to, $subject, $body) {
    $mail = new PHPMailer(true);  

    try {
        
        $mail->isSMTP();  
        $mail->Host = 'smtp.gmail.com';  
        $mail->SMTPAuth = true;  
        $mail->Username = 'yourmail.com';  
        $mail->Password = '';  
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;  
        $mail->Port = 587;  

        
        $mail->SMTPDebug = 2;  
        $mail->Debugoutput = 'html';  
        
        
        $mail->setFrom('MAil ID', 'NAmE');  
        $mail->addAddress($to);  

     
        $mail->isHTML(true);  
        $mail->Subject = $subject;  
        $mail->Body = $body;  

       
        if ($mail->send()) {
            echo 'Message has been sent successfully!';
        }
    } catch (Exception $e) {
        
        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
}


send_email('Test Mail Tamplete (your mail to test only)', 'Test Email Subject', 'Hello, this is a test email body.');
?>
