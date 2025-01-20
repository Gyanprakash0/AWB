<?php
session_start();
include('connection.php');

// Ensure the user is logged in
if (!isset($_SESSION['user_id'])) {
    die("Error: User not logged in.");
}

$sessionUserId = $_SESSION['user_id']; // User ID from session
$tripName = isset($_GET['trip_name']) ? $_GET['trip_name'] : '';
$location = isset($_GET['location']) ? $_GET['location'] : '';
$description = isset($_GET['description']) ? $_GET['description'] : '';
$pricePerPerson = isset($_GET['price']) ? floatval($_GET['price']) : 0;
$discount = isset($_GET['discount']) ? floatval($_GET['discount']) : 0;
$newPrice = isset($_GET['new_price']) ? floatval($_GET['new_price']) : 0;

// Check if 'user_name' field is set in the GET data
if (isset($_GET['user_name']) && is_array($_GET['user_name'])) {
    $peopleCount = count($_GET['user_name']); // Get total number of people
} else {
    die("Error: No user names provided.");
}

// Calculate prices
$totalPrice = $newPrice * $peopleCount;
$discountAmount = ($totalPrice * $discount) / 100;
$priceAfterDiscount = $totalPrice - $discountAmount;
$gstTax = $priceAfterDiscount * 0.1;  // 10% GST
$serviceCharge = 150 * $peopleCount; // Service charge per person
$platformFee = 100 * $peopleCount;   // Platform fee per person
$travelInsurance = 99 * $peopleCount; // Travel insurance per person
$finalAmount = $priceAfterDiscount + $gstTax + $serviceCharge + $platformFee + $travelInsurance;

// Insert booking into the database
$sqlBooking = "INSERT INTO awb_bookings 
                (user_id, trip_name, trip_location, trip_description, total_persons, total_price, discount_amount, price_after_discount, gst_tax, final_amount, created_at, updated_at)
               VALUES 
                ('$sessionUserId', '$tripName', '$location', '$description', '$peopleCount', '$totalPrice', '$discountAmount', '$priceAfterDiscount', '$gstTax', '$finalAmount', NOW(), NOW())";

if (mysqli_query($conn, $sqlBooking)) {
    $bookingId = mysqli_insert_id($conn);  // Get the ID of the inserted booking

    // Insert each user's details into the database
    $userNames = $_GET['user_name'];
    $userEmails = $_GET['user_email'];
    $userPhones = $_GET['user_phone'];
    $userIds = $_FILES['user_id']; // File upload array

    foreach ($userNames as $index => $userName) {
        $userEmail = $userEmails[$index];
        $userPhone = $userPhones[$index];
        $userIdFile = $userIds['name'][$index]; // The file name for the ID proof

        // Handle file upload
        $uploadDir = 'uploads/';
        $uploadFilePath = $uploadDir . basename($userIdFile);
        
        if (move_uploaded_file($userIds['tmp_name'][$index], $uploadFilePath)) {
            // Insert user information into the database
            $sqlUser = "INSERT INTO awb_users (booking_id, user_name, user_email, user_phone, id_proof)
                        VALUES ('$bookingId', '$userName', '$userEmail', '$userPhone', '$userIdFile')";
            if (mysqli_query($conn, $sqlUser)) {
                echo "User $userName booked successfully.<br>";
            } else {
                echo "Error inserting user data: " . mysqli_error($conn) . "<br>";
            }
        } else {
            echo "Error uploading ID proof for $userName.<br>";
        }
    }

    // Successfully placed the booking
    echo "Booking successfully placed!";
} else {
    echo "Error: " . mysqli_error($conn);
}

// Close the database connection
mysqli_close($conn);
?>
