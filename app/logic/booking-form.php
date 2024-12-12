<?php

//Preset variables
$island = "Pitchfork Isle";
$hotel = "Haystack Hotel";
$stars = 5;


if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $arrival_date = $_POST['arrival_date'];
    $departure_date = $_POST['departure_date'];
    $room = isset($_POST['room']) ? $_POST['room'] : [];
    $features = isset($_POST['features']) ? $_POST['features'] : [];

    // Calculate the total cost
    //Converts arrival date into Unix timestamp. Subtracts timestamps to get total number of seconds between the arrival and departure dates. 
    //Divides result by 86400 (the number of seconds in a day) to convert the difference from seconds to days. 
    $days = (strtotime($departure_date) - strtotime($arrival_date)) / 86400;
    $room_cost = isset($room['cost']) ? $room['cost'] : 0;
    $feature_cost = array_reduce($features, function ($sum, $feature) {
        return $sum + (isset($feature['cost']) ? $feature['cost'] : 0);
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
        "features" => $features,
        "additional_info" => [
            "greeting" => "Thank you for choosing $hotel",
            "imageUrl" => ""
        ]
    ];

    // Output JSON
    header('Content-Type: application/json');
    echo json_encode($response, JSON_PRETTY_PRINT);
    exit;
}
