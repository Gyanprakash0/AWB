<?php
session_start();
include('connection.php'); 
include('includes/header.php'); 

if (!isset($_SESSION['user_id'])) {
   
    header('Location: login.php');
    exit();
}

$user_id = $_SESSION['user_id']; 


if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $support_type = $_POST['support_type'];
    $contact_info = $_POST['contact_info'];
    $destination = $_POST['destination'];
    $budget = $_POST['budget'];
    $journey_date = $_POST['journey_date'];


    $current_date = new DateTime();
    $journey_date_obj = new DateTime($journey_date);

    if ($journey_date_obj <= $current_date->add(new DateInterval('P3D'))) {
        $error_message = "The journey date must be at least 3 days in the future.";
    } else {
       
        if (!is_numeric($budget) || $budget < 0) {
            $error_message = "Please enter a valid budget (positive number).";
        } else {
            
            $sql = "INSERT INTO travel_support_requests (user_id, support_type, contact_info, destination, budget, journey_date, status) 
                    VALUES (?, ?, ?, ?, ?, ?, 'pending')";
            $stmt = $conn->prepare($sql);

            
            $stmt->bind_param("issdss", $user_id, $support_type, $contact_info, $budget, $destination, $journey_date);

            
            if ($stmt->execute()) {
                $success_message = "Your request has been successfully submitted.";
            } else {
                $error_message = "Error submitting your request. Please try again.";
            }

            $stmt->close();
        }
    }
}


$sql = "SELECT * FROM travel_support_requests WHERE user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$requests = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Travel Support Request</title>
    <style>
       
        .form-container {
            width: 50%;
            margin: 0 auto;
            padding: 20px;
            background-color: 
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            font-family: Arial, sans-serif;
        }

        .form-container h2 {
            text-align: center;
            color: 
        }

        
        label {
            font-size: 16px;
            margin-bottom: 8px;
            display: block;
        }

        input, select {
            width: 100%;
            padding: 10px;
            margin: 10px 0 20px;
            border: 1px solid 
            border-radius: 4px;
            font-size: 14px;
        }

        input[type="submit"] {
            background-color: 
            color: white;
            border: none;
            cursor: pointer;
            font-size: 16px;
            padding: 15px;
            width: 100%;
            border-radius: 4px;
        }

        input[type="submit"]:hover {
            background-color: 
        }

       
        .modal {
            display: none; 
            position: fixed;
            z-index: 1; 
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgb(0, 0, 0); 
            background-color: rgba(0, 0, 0, 0.4);
            padding-top: 60px;
        }

        .modal-content {
            background-color: 
            margin: 5% auto;
            padding: 20px;
            border: 1px solid 
            width: 80%;
            max-width: 500px;
            text-align: center;
            border-radius: 8px;
        }

        .close-btn {
            color: 
            float: right;
            font-size: 28px;
            font-weight: bold;
        }

        .close-btn:hover,
        .close-btn:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }

        .modal h2 {
            color: 
        }

        .requests-table {
            width: 80%;
            margin: 40px auto;
            border-collapse: collapse;
        }

        .requests-table th, .requests-table td {
            padding: 12px;
            text-align: left;
            border: 1px solid 
        }

        .requests-table th {
            background-color: 
        }

        .status-pending {
            color: orange;
        }

        .status-confirmed {
            color: green;
        }

        .status-rejected {
            color: red;
        }
    </style>
</head>
<body>


<div class="form-container">
    <h2>Request Travel Support</h2>
    <?php
    if (isset($error_message)) {
        echo "<p style='color: red;'>$error_message</p>";
    }
    if (isset($success_message)) {
        echo "<p style='color: green;'>$success_message</p>";
    }
    ?>
    <form action="" method="post">
        
        <label for="support_type">Type of Support:</label>
        <select id="support_type" name="support_type" required>
            <option value="guide">Guide for Trip</option>
            <option value="transportation">Transportation Assistance</option>
            <option value="accommodation">Accommodation Help</option>
            <option value="itinerary">Itinerary Planning</option>
            <option value="other">Other</option>
        </select>
        <br><br>

        
        <label for="contact_info">Enter Your Contact Information:</label>
        <input type="text" id="contact_info" name="contact_info" required placeholder="Phone, Email, etc.">
        <br><br>

        
        <label for="destination">Enter Your Destination:</label>
        <input type="text" id="destination" name="destination" required placeholder="Destination place">
        <br><br>

        
        <label for="budget">Enter Your Budget (USD):</label>
        <input type="number" id="budget" name="budget" required step="0.01" min="1" placeholder="Your budget in USD">
        <br><br>

        
        <label for="journey_date">Date of Journey (Must be at least 3 days ahead):</label>
        <input type="date" id="journey_date" name="journey_date" required>
        <br><br>

        
        <input type="submit" value="Submit Request">
    </form>
</div>


<div id="successModal" class="modal">
    <div class="modal-content">
        <span class="close-btn" onclick="closeModal()">&times;</span>
        <h2>Your Request Has Been Successfully Submitted!</h2>
        <p>We will get back to you shortly. Thank you for using our services.</p>
    </div>
</div>

<script>

function openModal() {
    document.getElementById('successModal').style.display = "block";
}


function closeModal() {
    document.getElementById('successModal').style.display = "none";
}


<?php
if (isset($success_message)) {
    echo "openModal();";
}
?>
</script>


<h3 style="text-align: center;">Your Submitted Travel Requests</h3>
<table class="requests-table">
    <thead>
        <tr>
            <th>Support Type</th>
            <th>Contact Info</th>
            <th>Destination</th>
            <th>Budget</th>
            <th>Journey Date</th>
            <th>Status</th>
        </tr>
    </thead>
    <tbody>
        <?php
        foreach ($requests as $request) {
            $status_class = '';
            switch ($request['status']) {
                case 'pending':
                    $status_class = 'status-pending';
                    break;
                case 'confirmed':
                    $status_class = 'status-confirmed';
                    break;
                case 'rejected':
                    $status_class = 'status-rejected';
                    break;
            }
            echo "<tr>
                    <td>{$request['support_type']}</td>
                    <td>{$request['contact_info']}</td>
                    <td>{$request['destination']}</td>
                    <td>\${$request['budget']}</td>
                    <td>{$request['journey_date']}</td>
                    <td class='{$status_class}'>{$request['status']}</td>
                  </tr>";
        }
        ?>
    </tbody>
</table>

<?php
include('includes/footer.php'); ?>

</body>
</html>
