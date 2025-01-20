<?php
session_start();
include('connection.php'); 

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
 
  $user_id = $_SESSION['user_id']; 
  $name = $_POST['name'];
  $email = $_POST['email'];
  $contact = $_POST['contact'];
  $bank_name = $_POST['bank_name'];
  $ifsc = $_POST['ifsc'];
  $loan_amount = $_POST['loan_amount'];
  $loan_type = $_POST['loan_type'];
  $repayment_type = $_POST['repayment_type'];
  $emi_duration = isset($_POST['emi_duration']) ? $_POST['emi_duration'] : null;
  $reason = $_POST['reason'];

  $extra_charges = $loan_amount * 0.03; 
  $total_amount = $loan_amount + $extra_charges;

 
  $query = "INSERT INTO loan_requests (user_id, name, email, contact, bank_name, ifsc, loan_amount, loan_type, repayment_type, emi_duration, reason, extra_charges, total_amount) 
            VALUES ('$user_id', '$name', '$email', '$contact', '$bank_name', '$ifsc', '$loan_amount', '$loan_type', '$repayment_type', '$emi_duration', '$reason', '$extra_charges', '$total_amount')";

  if (mysqli_query($conn, $query)) {
    
    $message = "Loan request submitted successfully!";
    $redirect_url = "financial_emerergency.php";
    echo "
      <div id='successModal' class='modal'>
        <div class='modal-content'>
          <span class='close'>&times;</span>
          <h3>Success!</h3>
          <p>Your loan request has been submitted successfully.</p>
          <img src='https://banner2.cleanpng.com/20180403/ljw/avhuhfcm7.webp' alt='Success' class='checkmark'>
        </div>
      </div>
      <script>
        // Show the modal
        document.getElementById('successModal').style.display = 'block';

        // Redirect after 3 seconds
        setTimeout(function() {
          window.location.href = '$redirect_url';
        }, 3000);

        // Close modal when the user clicks on the close button
        document.querySelector('.close').onclick = function() {
          document.getElementById('successModal').style.display = 'none';
        }

        // Close the modal if the user clicks outside of the modal content
        window.onclick = function(event) {
          if (event.target == document.getElementById('successModal')) {
            document.getElementById('successModal').style.display = 'none';
          }
        }
      </script>
    ";
  } else {
    echo "Error: " . mysqli_error($conn);
  }
}
?>


<style>

  .modal {
    display: none; 
    position: fixed; 
    z-index: 1; 
    left: 0;
    top: 0;
    width: 100%; 
    height: 100%; 
    overflow: auto; 
    background-color: rgb(0,0,0); 
    background-color: rgba(0,0,0,0.4); 
    padding-top: 60px;
  }

  /* Modal Content */
  .modal-content {
    background-color: #fefefe;
    margin: 5% auto;
    padding: 20px;
    border: 1px solid #888;
    width: 80%;
    text-align: center;
    border-radius: 10px;
  }

  /* The Close Button */
  .close {
    color: #aaa;
    float: right;
    font-size: 28px;
    font-weight: bold;
  }

  .close:hover,
  .close:focus {
    color: black;
    text-decoration: none;
    cursor: pointer;
  }

  .checkmark {
    width: 50px;
    height: 50px;
    margin: 10px 0;
  }

  h3 {
    font-size: 24px;
    color: #28a745;
  }

  p {
    font-size: 18px;
    color: #333;
  }
</style>


