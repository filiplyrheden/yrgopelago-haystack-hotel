<?php

// Preset variables
$island = "Pitchfork Isle";
$hotel = "Haystack Hotel";
$stars = 5;

// Function to determine costs based on room type and features
function getRoomCost($roomType)
{
    switch ($roomType) {
        case "Budget":
            return 1;
        case "Standard":
            return 2;
        case "Luxury":
            return 4;
        default:
            return 0;
    }
}

function getFeatureCost($features)
{
    $costMap = [
        "Sauna" => 2,
        "Minibar" => 1
    ];
    $total = 0;
    foreach ($features as $feature) {
        if (array_key_exists($feature, $costMap)) {
            $total += $costMap[$feature];
        }
    }
    return $total;
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $arrival_date = $_POST['arrival_date'];
    $departure_date = $_POST['departure_date'];
    $room_type = $_POST['room_type'];
    $features = isset($_POST['features']) ? $_POST['features'] : [];
    $transfer_code = $_POST['transfer_code'];

    // Calculate the total cost
    $days = max(1, (strtotime($departure_date) - strtotime($arrival_date)) / 86400);
    $room_cost = getRoomCost($room_type) * $days;
    $feature_cost = getFeatureCost($features);
    $total_cost = $room_cost + $feature_cost;

    // Construct JSON response
    $response = [
        "island" => $island,
        "hotel" => $hotel,
        "arrival_date" => $arrival_date,
        "departure_date" => $departure_date,
        "room_type" => $room_type,
        "features" => $features,
        "total_cost" => $total_cost,
        "stars" => $stars,
        "additional_info" => [
            "greeting" => "Thank you for choosing $hotel",
            "imageUrl" => ""
        ]
    ];

    // Output JSON
    // header('Content-Type: application/json');
    // echo json_encode($response, JSON_PRETTY_PRINT);
}
