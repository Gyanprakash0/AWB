<?php
session_start();
include('connection.php'); 
include('includes/header.php'); 

if (!isset($_SESSION["username"])) {
    header('Location: login.php'); 
    exit(); 
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
   
    $user_id = $_SESSION['user_id'];  
    $emergency_type = $_POST['emergency_type'];
    $severity = $_POST['severity'];
    $description = $_POST['description'];
    $location = $_POST['location'];
    $date_occurred = $_POST['date_occurred'];

    // Insert into the database
    $sql = "INSERT INTO emergency_reports (user_id, emergency_type, severity, description, location, date_occurred) 
            VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("isssss", $user_id, $emergency_type, $severity, $description, $location, $date_occurred);
    
    if ($stmt->execute()) {
        $message = "Emergency report submitted successfully We will connect you within a hour.";
    } else {
        $message = "Error submitting emergency report.";
    }
    
    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Emergency Report Form</title>
    <link rel="stylesheet" type="text/css" href="css/style.css">
</head>
<body class="bg-light">
    <div class="container">
        <h2 class="mt-5">Emergency Report Form</h2>
        <?php if (isset($message)) { echo "<div class='alert alert-info'>$message</div>"; } ?>
        
        <form action="" method="POST" class="mt-4">
            <!-- Type of Emergency -->
            <div class="form-group">
                <label for="emergency_type">Type of Emergency</label>
                <select id="emergency_type" name="emergency_type" class="form-control" required>
                    <option value="Medical">Medical</option>
                    <option value="Financial">Financial</option>
                    <option value="Trip">Trip</option>
                    <option value="Other">Other</option>
                </select>
            </div>

            <!-- Severity Level -->
            <div class="form-group">
                <label for="severity">Severity</label>
                <select id="severity" name="severity" class="form-control" required>
                    <option value="High">High Level</option>
                    <option value="Minimal">Minimal</option>
                </select>
            </div>

            <!-- Description of Emergency -->
            <div class="form-group">
                <label for="description">Description</label>
                <textarea id="description" name="description" class="form-control" rows="4" required></textarea>
            </div>

            <!-- Location -->
            <div class="form-group">
                <label for="location">Location (Optional)</label>
                <input type="text" id="location" name="location" class="form-control" placeholder="Enter location" />
            </div>

            <!-- Date of Occurrence -->
            <div class="form-group">
                <label for="date_occurred">Date of Occurrence</label>
                <input type="datetime-local" id="date_occurred" name="date_occurred" class="form-control" required />
            </div>

            <button type="submit" class="btn btn-primary">Submit Report</button>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.4.1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php include('includes/footer.php'); ?>
