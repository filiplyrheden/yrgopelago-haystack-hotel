<?php

//Preset variables
$island = "Pitchfork Isle";
$hotel = "Haystack Hotel";
$stars = 5;


if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $arrival_date = $_POST['arrival_date'];
    $departure_date = $_POST['departure_date'];
    $room = isset($_POST['room']) ? $_POST['room'] : [];
    $features = array_filter($_POST['features'], function ($feature) {
        return isset($feature['name']);
    });
    $transferCode = $_POST['transfer_code'];

    // Calculate the total cost
    //Converts arrival date into Unix timestamp. Subtracts timestamps to get total number of seconds between the arrival and departure dates. 
    //Divides result by 86400 (the number of seconds in a day) to convert the difference from seconds to days. 
    $days = max(1, (strtotime($departure_date) - strtotime($arrival_date)) / 86400);
    $room_cost = array_reduce($_POST['room'], function ($sum, $room) {
        if (isset($room['name'])) {
            return $sum + (int)$room['cost'];
        }
        return $sum;
    }, 0);
    $feature_cost = array_reduce($features, function ($sum, $feature) {
        if (isset($feature['name'])) {
            return $sum + (int)$feature['cost'];
        }
        return $sum;
    }, 0);
    $total_cost = $room_cost * $days + $feature_cost;

    // Construct JSON response
    $response = [
        "island" => $island,
        "hotel" => $hotel,
        "arrival_date" => $arrival_date,
        "departure_date" => $departure_date,
        "total_cost" => $total_cost,
        "stars" => $stars,
        "features" => array_values($features),
        "additional_info" => [
            "greeting" => "Thank you for choosing $hotel",
            "imageUrl" => ""
        ]
    ];

    // Output JSON
    // header('Content-Type: application/json');
    // $jsonResponse = json_encode($response, JSON_PRETTY_PRINT);
}

// var_dump($_POST);
