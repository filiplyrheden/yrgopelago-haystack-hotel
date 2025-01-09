<?php

declare(strict_types=1);

require __DIR__ . '/database.php';

// Query to get booked dates in January for the Standard room type
$standardResult = $db->query("SELECT
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
    AND rooms.room_type = 'Comfort Stack';");

$standardBookedDates = [];
while ($row = $standardResult->fetch(PDO::FETCH_ASSOC)) {
    $arrivalDate = new DateTime($row['arrival_date']);
    $departureDate = new DateTime($row['departure_date']);
    $interval = new DateInterval('P1D');
    $dateRange = new DatePeriod($arrivalDate, $interval, $departureDate->add($interval));

    foreach ($dateRange as $date) {
        $standardBookedDates[] = (int)$date->format('j');
    }
}

// Remove duplicate dates
$standardBookedDates = array_unique($standardBookedDates);

// Debugging: Log the booked dates
error_log(json_encode($standardBookedDates));

// Return the booked dates as a JSON response
header('Content-Type: application/json');
echo json_encode(array_values($standardBookedDates)); // Ensure the JSON response is an array
