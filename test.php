<?php

$url = "https://api.makcorps.com/booking?country=us&hotelid=the-lenox&checkin=2024-12-05&checkout=2024-12-11&currency=USD&kids=0&adults=2&rooms=1&api_key=67322efde8e3a107f2dff6bc";

$ch = curl_init();

curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

$response = curl_exec($ch);

if (curl_errno($ch)) {
    echo 'Request Error:' . curl_error($ch);
} else {
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    if ($http_code == 200) {
        $response_data = json_decode($response, true);
        print_r($response_data);
    } else {
        echo "Request failed with status code " . $http_code;
    }
}

curl_close($ch);

?>
