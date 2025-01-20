<?php
session_start();
include('connection.php');
include('includes/header.php'); 
include('includes/requirements.php'); 

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    echo "Please log in first!";
    exit;
}

$user_id = $_SESSION['user_id'];  // Get user ID from session

// Retrieve trip details from the URL or set defaults
$trip_name = isset($_GET['name']) ? $_GET['name'] : 'Trip Name';
$location = isset($_GET['location']) ? $_GET['location'] : '';
$description = isset($_GET['description']) ? $_GET['description'] : '';
$price_string = isset($_GET['price']) ? $_GET['price'] : '15000.00';


$price_per_person = floatval(str_replace(',', '', $price_string));


$discount = isset($_GET['discount']) ? floatval($_GET['discount']) : 10;
$service_charge = 150; 
$platform_fee = 100;   
$travel_insurance = 99; 

// Calculations
$total_price = $price_per_person; 
$discount_amount = ($total_price * $discount) / 100; 
$price_after_discount = $total_price - $discount_amount; 
$gst_tax = $price_after_discount * 0.1;
$total_amount = $price_after_discount + $gst_tax + $platform_fee + $service_charge + $travel_insurance; // Final price including charges
?>

<div style="margin-top:120px;" class="booking-container">
    <div class="booking-summary">
        <h3>Booking Summary: <?php echo $trip_name; ?></h3>

        <div class="price-breakdown">
            <p><strong>Price per person:</strong> ₹<?php echo number_format($price_per_person, 2); ?></p>
            <p><strong>Total Price (for 1 person):</strong> ₹<?php echo number_format($total_price, 2); ?></p>
            <p><strong>Discount Applied:</strong> ₹<?php echo number_format($discount_amount, 2); ?> (<?php echo $discount; ?>%)</p>
            <p><strong>GST + Tax (10%):</strong> ₹<?php echo number_format($gst_tax, 2); ?></p>
            <p><strong>Platform Fee:</strong> ₹<?php echo number_format($platform_fee, 2); ?></p>
            <p><strong>Service Charge:</strong> ₹<?php echo number_format($service_charge, 2); ?></p>
            <p><strong>Travel Insurance:</strong> ₹<?php echo number_format($travel_insurance, 2); ?></p>
            <div class="total">
                <p><strong>Total Amount (for 1 person):</strong> ₹<?php echo number_format($total_amount, 2); ?></p>
            </div>
        </div>
    </div>

    <section class="right-side">
        <h3>Enter User Details</h3>
        <form id="user-form" action="trip_booking.php" method="POST" enctype="multipart/form-data">
            <!-- Hidden trip information -->
            <input type="hidden" name="trip_name" value="<?php echo htmlspecialchars($trip_name); ?>" />
            <input type="hidden" name="price" value="<?php echo htmlspecialchars($price_per_person); ?>" />
            <input type="hidden" name="discount" value="<?php echo htmlspecialchars($discount); ?>" />
            <input type="hidden" name="new_price" value="<?php echo htmlspecialchars($price_after_discount); ?>" />
            <input type="hidden" name="gst_tax" value="<?php echo htmlspecialchars($gst_tax); ?>" />
            <input type="hidden" name="platform_fee" value="<?php echo htmlspecialchars($platform_fee); ?>" />
            <input type="hidden" name="service_charge" value="<?php echo htmlspecialchars($service_charge); ?>" />
            <input type="hidden" name="travel_insurance" value="<?php echo htmlspecialchars($travel_insurance); ?>" />

            <div id="user-details">
                <div class="user-entry">
                    <label for="user_name">Your Name:</label>
                    <input type="text" name="user_name[]" required />
                    <label for="user_email">Your Email:</label>
                    <input type="email" name="user_email[]" required />
                    <label for="user_phone">Your Phone:</label>
                    <input type="text" name="user_phone[]" required />
                    <label for="user_id">ID Proof:</label>
                    <input type="file" name="user_id[]" required />
                </div>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-primary">Confirm Booking</button>
            </div>
        </form>
    </section>
</div>

<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve form data
    $user_name = $_POST['user_name'][0];
    $user_email = $_POST['user_email'][0];
    $user_phone = $_POST['user_phone'][0];

    // File upload for ID proof
    if (isset($_FILES['user_id']['tmp_name'][0]) && $_FILES['user_id']['error'][0] === UPLOAD_ERR_OK) {
        $file_tmp_name = $_FILES['user_id']['tmp_name'][0];
        $file_name = $_FILES['user_id']['name'][0];
        $upload_dir = 'uploads/';
        $file_path = $upload_dir . basename($file_name);

        // Move the uploaded file to the desired directory
        if (move_uploaded_file($file_tmp_name, $file_path)) {
            $id_proof = $file_path;
        } else {
            echo "Failed to upload the file.";
            exit;
        }
    } else {
        // No file uploaded
        echo "Please upload your ID proof.";
        exit;
    }

    // Generate a unique booking ID
    $booking_id = 'booking_' . uniqid();

    // Get current date and time
    $booking_time = date('Y-m-d H:i:s'); // Current Date and Time
    
    // Prepare the SQL query to insert the data into the database
    $sql = "INSERT INTO awb_users (user_id, booking_id, user_name, user_email, user_phone, id_proof, price, booking_time, trip_name, discount, gst_tax, platform_fee, service_charge, travel_insurance) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    // Prepare the statement
    $stmt = $conn->prepare($sql);

    // Correct bind_param statement
    $stmt->bind_param('ssssssssssssdd', 
        $user_id,          
        $booking_id,        
        $user_name,         
        $user_email,       
        $user_phone,        
        $id_proof,          
        $total_amount,      
        $booking_time,      
        $trip_name,        
        $discount,          
        $gst_tax,           
        $platform_fee,      
        $service_charge,    // service_charge (double)
        $travel_insurance   // travel_insurance (double)
    );

    // Execute the query
    if ($stmt->execute()) {
        echo "Booking confirmed successfully!";
    } else {
        echo "Error: " . $stmt->error;
    }

    // Close the prepared statement
    $stmt->close();
}

// Fetch all bookings made by the logged-in user
$sql = "SELECT * FROM awb_users WHERE user_id = ? ORDER BY booking_time DESC";
$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $user_id);  // Correctly bind the user_id from the session
$stmt->execute();
$result = $stmt->get_result();

echo '<h3>Your Bookings:</h3>';
echo '<table class="booking-table">';
echo '<thead><tr><th>Booking ID</th><th>Trip Name</th><th>Price</th><th>Booking Time</th><th>Status</th><th>Action</th></tr></thead>';
echo '<tbody>';

while ($row = $result->fetch_assoc()) {
    echo '<tr>';
    echo '<td>' . $row['booking_id'] . '</td>';
    echo '<td>' . $row['trip_name'] . '</td>';
    echo '<td>₹' . number_format($row['price'], 2) . '</td>';
    echo '<td>' . date('Y-m-d H:i:s', strtotime($row['booking_time'])) . '</td>';
    echo '<td>' . ($row['price'] > 0 ? 'Pending Payment' : 'Paid') . '</td>';
    echo '<td>';
    if ($row['price'] > 0) {
        echo '<form method="POST" action="https://razorpay.com/payment-button/pl_PLAaos6NcUSgEN/view/?utm_source=payment_button&utm_medium=button&utm_campaign=payment_button"><button type="submit" name="booking_id" value="' . $row['booking_id'] . '">Pay Now</button></form>';
    }
    echo '</td>';
    echo '</tr>';
}

echo '</tbody></table>';

$stmt->close();
?>

<!-- CSS for improving UI -->
<style>
    .booking-container {
        display: flex;
        flex-wrap: wrap;
        justify-content: space-between;
        padding: 20px;
        background-color: #f7f7f7;
    }

    .booking-summary, .right-side {
        background-color: #fff;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        padding: 20px;
        border-radius: 8px;
        flex: 1 1 48%;
        margin: 10px;
    }

    h3 {
        color: #333;
    }

    .price-breakdown p {
        font-size: 14px;
        color: #555;
        margin-bottom: 8px;
    }

    .price-breakdown .total {
        font-weight: bold;
        font-size: 16px;
        margin-top: 15px;
    }

    .form-actions {
        text-align: center;
    }

    .form-actions .btn {
        background-color: #007bff;
        color: white;
        padding: 10px 20px;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        font-size: 16px;
    }

    .form-actions .btn:hover {
        background-color: #0056b3;
    }

    .user-entry {
        margin-bottom: 20px;
    }

    .user-entry label {
        font-weight: bold;
        margin-bottom: 5px;
        display: block;
    }

    .user-entry input {
        width: 100%;
        padding: 10px;
        margin-bottom: 10px;
        border-radius: 5px;
        border: 1px solid #ccc;
    }

    .user-entry input[type="file"] {
        padding: 5px;
    }

    .booking-table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 20px;
    }

    .booking-table th, .booking-table td {
        padding: 12px;
        text-align: center;
        border: 1px solid #ccc;
    }

    .booking-table th {
        background-color: #f2f2f2;
    }

    .booking-table td {
        background-color: #fff;
    }

    .booking-table button {
        background-color: #28a745;
        color: white;
        padding: 8px 15px;
        border: none;
        border-radius: 5px;
        cursor: pointer;
    }

    .booking-table button:hover {
        background-color: #218838;
    }
</style>

<?php
include('includes/footer.php');?>