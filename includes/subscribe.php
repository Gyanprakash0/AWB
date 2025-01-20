<?php
/*
  ************************************************************
  *                        AWB                                 *
  *                       Copyright                              *
  *   Gyan Prakash Pandey   *   Ansh Shukla   *   Shiwan Tripathi   *
  *   Code is open source, but please do not remove this credit. *
  ************************************************************
*/?>


<?php
include('connection.php'); 


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);

    
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "<script>alert('Invalid email format.');</script>";
    } else {
        
        $query = "SELECT * FROM subscriptions WHERE email = ?";
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, "s", $email);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if (mysqli_num_rows($result) > 0) {
            echo "<script>alert('This email is already subscribed.');</script>";
        } else {
            
            $insertQuery = "INSERT INTO subscriptions (email) VALUES (?)";
            $insertStmt = mysqli_prepare($conn, $insertQuery);
            mysqli_stmt_bind_param($insertStmt, "s", $email);
            
            if (mysqli_stmt_execute($insertStmt)) {
                echo "<script>alert('Thank you for subscribing!');</script>";
            } else {
                echo "<script>alert('There was an error. Please try again.');</script>";
            }
        }

        mysqli_stmt_close($stmt);
        mysqli_stmt_close($insertStmt);
    }
}
?><section class="newsletter">

<div class="content">
    <h1 class="heading">Subscribe Now</h1>
    <p>Join our mailing list for updates!</p>
    <form id="subscribeForm" action="" method="POST">
        <input type="email" name="email" placeholder="Enter your email" required class="email" id="emailInput">
        <div id="errorMessage" style="color: red; display: none;"></div>
        <input type="submit" value="Subscribe" class="btn">
    </form>
</div>

</section>

<script>
document.getElementById('subscribeForm').onsubmit = function(event) {
var emailInput = document.getElementById('emailInput').value;
var errorMessage = document.getElementById('errorMessage');


var emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

if (!emailPattern.test(emailInput)) {
    event.preventDefault(); 
    errorMessage.textContent = 'Please enter a valid email address.';
    errorMessage.style.display = 'block'; 
} else {
    errorMessage.style.display = 'none';
}
};
</script>



<?php
/*
  ************************************************************
  *                        AWB                                 *
  *                       Copyright                              *
  *   Gyan Prakash Pandey   *   Ansh Shukla   *   Shiwan Tripathi   *
  *   Code is open source, but please do not remove this credit. *
  ************************************************************
*/?>
