<?php

require __DIR__ . '/database.php';

// Query to get booked dates in January for the Budget room type
$budgetResult = $db->query("SELECT
    arrival_date,
    departure_date
FROM bookings
INNER JOIN booking_room_feature 
    ON booking_room_feature.booking_id = bookings.id
INNER JOIN rooms
    ON rooms.id = booking_room_feature.room_id
WHERE 
    (
        arrival_date BETWEEN '2025-01-01' AND '2025-01-31'
        OR departure_date BETWEEN '2025-01-01' AND '2025-01-31'
    )
    AND rooms.room_type = 'Budget';");

$budgetBookedDates = [];
while ($row = $budgetResult->fetch(PDO::FETCH_ASSOC)) {
    $arrivalDate = new DateTime($row['arrival_date']);
    $departureDate = new DateTime($row['departure_date']);
    $interval = new DateInterval('P1D');
    $dateRange = new DatePeriod($arrivalDate, $interval, $departureDate->add($interval));

    foreach ($dateRange as $date) {
        $budgetBookedDates[] = (int)$date->format('j');
    }
}

// Remove duplicate dates
$budgetBookedDates = array_unique($budgetBookedDates);

// Debugging: Log the booked dates
error_log(json_encode($budgetBookedDates));

// Return the booked dates as a JSON response
header('Content-Type: application/json');
echo json_encode(array_values($budgetBookedDates)); // Ensure the JSON response is an array