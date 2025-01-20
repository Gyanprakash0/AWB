<?php

include('connection.php');

if (isset($_GET['id'])) {
    $tripId = $_GET['id'];

    
    $query = "SELECT * FROM awb_trip_details WHERE id = $tripId";
    $result = mysqli_query($conn, $query);

    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);

        
        $response = [
            'id' => $row['id'],
            'trip_name' => $row['trip_name'],
            'duration' => $row['duration'],
            'activities_included' => $row['activities_included'],
            'start_location' => $row['start_location'],
            'destination' => $row['destination'],
            'price' => $row['price'],
            'discount' => $row['discount'],
            'accommodation' => $row['accommodation'],
            'transportation' => $row['transportation'],
            'food' => $row['food'],
            'itinerary' => $row['itinerary'],
            'terms_conditions' => $row['terms_conditions'],
            'cancellation_policy' => $row['cancellation_policy'],
            'inclusions' => $row['inclusions'],
            'exclusions' => $row['exclusions'],
            'contact_details' => $row['contact_details'],
            'booking_policy' => $row['booking_policy'],
            'refund_policy' => $row['refund_policy'],
            'additional_info' => $row['additional_info'],
        ];

        
        echo json_encode($response);
    } else {
        echo json_encode(['error' => 'Trip not found']);
    }
} else {
    echo json_encode(['error' => 'No trip ID provided']);
}
?>
