<?php

$database = 'sqlite:' . __DIR__ . '/../../app/database/haystack.db';
$db = new PDO($database);

// Preset variables
$island = "Pitchfork Isle";
$hotel = "Haystack Hotel";

// Fetch current star rating from database
$starRating = $db->query("SELECT star_rating FROM hotel_settings WHERE id = 1")->fetchColumn() ?: 5;

// Functions to determine costs based on room type and features
function getRoomCost($roomType)
{
    switch ($roomType) {
        case "Budget Bale":
            return 1;
        case "Comfort Stack":
            return 2;
        case "Luxury Loft":
            return 4;
        default:
            return 0;
    }
}

function getFeatureCost($features)
{
    $costMap = [
        "Sauna" => 3,
        "Minibar" => 1,
        "Yatzy" => 1
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

    if ($days >= 3) {
        $total_cost *= 0.7;
    }

    // Construct JSON response
    $response = [
        "island" => $island,
        "hotel" => $hotel,
        "arrival_date" => $arrival_date,
        "departure_date" => $departure_date,
        "room_type" => $room_type,
        "features" => $features,
        "total_cost" => $total_cost,
        "stars" => $starRating,
        "additional_info" => [
            "greeting" => "Thank you for choosing $hotel",
            "imageUrl" => "https://i.giphy.com/media/v1.Y2lkPTc5MGI3NjExbHZ3aHk2N3Z4dWl6Z3dydmo2N3VoN2s4OXhqcmdnNTEzZmgxanlwcCZlcD12MV9pbnRlcm5hbF9naWZfYnlfaWQmY3Q9Zw/l0MYC0LajbaPoEADu/giphy.gif"
        ]
    ];
}
