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
    $car_type = $_POST['car_type']; 
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];
    $rental_start_date = $_POST['rental_start_date'];
    $rental_end_date = $_POST['rental_end_date'];

    
    $aadhar_card = $pan_card = $driving_license = '';
    $documents_uploaded = 0;

    if (isset($_FILES['aadhar_card']) && $_FILES['aadhar_card']['error'] == 0) {
        $aadhar_card = uploadDocument($_FILES['aadhar_card']);
    }

    if (isset($_FILES['pancard']) && $_FILES['pancard']['error'] == 0) {
        $pan_card = uploadDocument($_FILES['pancard']);
    }

    if (isset($_FILES['driving_license']) && $_FILES['driving_license']['error'] == 0) {
        $driving_license = uploadDocument($_FILES['driving_license']);
    }

    
    if ($aadhar_card && $pan_card && $driving_license) {
        $documents_uploaded = 1; 
    }

    
    $sql = "INSERT INTO car_rental_requests (user_id, car_type, name, email, phone, address, aadhar_card, pancard, driving_license, documents_uploaded, rental_start_date, rental_end_date, status, created_at)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'pending', NOW())";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("isssssssssss", $user_id, $car_type, $name, $email, $phone, $address, $aadhar_card, $pan_card, $driving_license, $documents_uploaded, $rental_start_date, $rental_end_date);
    
    if ($stmt->execute()) {
        $success_message = "Your rental request has been successfully submitted.";
       
        $user_request = [
            'car_type' => $car_type,
            'name' => $name,
            'email' => $email,
            'phone' => $phone,
            'address' => $address,
            'rental_start_date' => $rental_start_date,
            'rental_end_date' => $rental_end_date,
            'documents_uploaded' => $documents_uploaded ? 'Yes' : 'No'
        ];
    } else {
        $error_message = "Error submitting your request. Please try again.";
    }

    $stmt->close();
}


function uploadDocument($file) {
    $target_dir = "docs/"; 
    $target_file = $target_dir . basename($file["name"]);
    $fileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    
    if (!in_array($fileType, ['jpg', 'png', 'pdf', 'jpeg'])) {
        return '';
    }

    if (move_uploaded_file($file["tmp_name"], $target_file)) {
        return $target_file;
    } else {
        return '';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Car/Bike Rental Request</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        
        .form-container {
            width: 60%;
            margin: 0 auto;
            padding: 20px;
            background-color: #f9f9f9;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        
        input, select, textarea {
            width: 100%;
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }

        input[type="file"] {
            padding: 5px;
        }

        .submit-button {
            width: 100%;
            padding: 10px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        .submit-button:hover {
            background-color: #45a049;
        }

        .error-message {
            color: red;
        }

        .success-message {
            color: green;
        }

        
        .modal {
            display: none;
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            padding-top: 100px;
            transition: all 0.3s ease-in-out;
        }

        .modal-content {
            background-color: white;
            margin: 5% auto;
            padding: 30px;
            border-radius: 10px;
            width: 60%;
            animation: slideIn 0.5s ease-out;
        }

        .modal h3 {
            margin-top: 0;
            font-size: 24px;
            color: #4CAF50;
        }

        .modal p {
            font-size: 18px;
            color: #555;
        }

        .close {
            color: #aaa;
            font-size: 28px;
            font-weight: bold;
            float: right;
            cursor: pointer;
        }

        .close:hover,
        .close:focus {
            color: black;
        }

        
        @keyframes slideIn {
            from {
                transform: translateY(-50%);
                opacity: 0;
            }
            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        
        footer {
            text-align: center;
            background-color: #333;
            color: white;
            padding: 10px;
            position: fixed;
            bottom: 0;
            width: 100%;
        }

        footer a {
            color: #4CAF50;
            text-decoration: none;
        }
    </style>
</head>
<body>


<div class="form-container">
    <h2>Request Car or Bike Rental</h2>

    
    <?php if (isset($error_message)) { echo "<p class='error-message'>$error_message</p>"; } ?>
    <?php if (isset($success_message)) { echo "<p class='success-message'>$success_message</p>"; } ?>

   
    <form action="" method="post" enctype="multipart/form-data">
        
        <label for="car_type">Select Vehicle Type:</label>
        <select name="car_type" required>
            <option value="4_seater_suv">4 Seater SUV</option>
            <option value="7_seater_suv">7 Seater SUV</option>
            <option value="sedan">Sedan</option>
            <option value="hatchback">Hatchback</option>
            <option value="luxury">Luxury</option>
            <option value="bike">Bike</option>
        </select>

        
        <label for="name">Full Name:</label>
        <input type="text" name="name" required>
        
        <label for="email">Email Address:</label>
        <input type="email" name="email" required>
        
        <label for="phone">Phone Number:</label>
        <input type="tel" name="phone" required>
        
        <label for="address">Address:</label>
        <textarea name="address" required></textarea>
        
        
        <label for="aadhar_card">Upload Aadhar Card:</label>
        <input type="file" name="aadhar_card" required>
        
        <label for="pancard">Upload PAN Card:</label>
        <input type="file" name="pancard" required>
        
        <label for="driving_license">Upload Driving License:</label>
        <input type="file" name="driving_license" required>

       
        <label for="rental_start_date">Rental Start Date:</label>
        <input type="date" name="rental_start_date" required>
        
        <label for="rental_end_date">Rental End Date:</label>
        <input type="date" name="rental_end_date" required>

        
        <button type="submit" class="submit-button">Submit Request</button>
    </form>

    <?php if (isset($user_request)): ?>
        <div class="user-request-details">
            <h3>Your Rental Request Details:</h3>
            <p><strong>Vehicle Type:</strong> <?php echo ucfirst(str_replace('_', ' ', $user_request['car_type'])); ?></p>
            <p><strong>Name:</strong> <?php echo $user_request['name']; ?></p>
            <p><strong>Email:</strong> <?php echo $user_request['email']; ?></p>
            <p><strong>Phone:</strong> <?php echo $user_request['phone']; ?></p>
            <p><strong>Address:</strong> <?php echo nl2br($user_request['address']); ?></p>
            <p><strong>Rental Period:</strong> <?php echo $user_request['rental_start_date']; ?> to <?php echo $user_request['rental_end_date']; ?></p>
            <p><strong>Documents Uploaded:</strong> <?php echo $user_request['documents_uploaded']; ?></p>
        </div>
    <?php endif; ?>
</div>


<div id="successModal" class="modal">
    <div class="modal-content">
        <span class="close">&times;</span>
        <h3>Your request has been submitted successfully!</h3>
        <p>We will contact you soon for further processing.</p>
    </div>
</div>


<script>

var modal = document.getElementById("successModal");
var closeModal = document.getElementsByClassName("close")[0];

<?php if (isset($success_message)): ?>
    modal.style.display = "block";
<?php endif; ?>


closeModal.onclick = function() {
    modal.style.display = "none";
}

window.onclick = function(event) {
    if (event.target == modal) {
        modal.style.display = "none";
    }
}
</script>

<?php
include('includes/footer.php'); 
?>

</body>
</html>
