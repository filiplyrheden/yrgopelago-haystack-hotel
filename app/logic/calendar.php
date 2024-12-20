<?php

require __DIR__ . '/database.php';

// Query to get booked dates in January
$result = $db->query("SELECT arrival_date, departure_date FROM bookings WHERE arrival_date BETWEEN '2025-01-01' AND '2025-01-31' OR departure_date BETWEEN '2025-01-01' AND '2025-01-31'");

$bookedDates = [];
while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
    $arrivalDate = new DateTime($row['arrival_date']);
    $departureDate = new DateTime($row['departure_date']);
    $interval = new DateInterval('P1D');
    $dateRange = new DatePeriod($arrivalDate, $interval, $departureDate->add($interval));

    foreach ($dateRange as $date) {
        $bookedDates[] = (int)$date->format('j');
    }
}

// Remove duplicate dates
$bookedDates = array_unique($bookedDates);

// Debugging: Log the booked dates
error_log(json_encode($bookedDates));

// Return the booked dates as a JSON response
header('Content-Type: application/json');
echo json_encode(array_values($bookedDates)); // Ensure the JSON response is an array
