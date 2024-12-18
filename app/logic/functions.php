<?php
function isRoomAvailable($room_type, $arrival_date, $departure_date, $pdo)
{
    // Check if any booking for the same room type overlaps with the requested dates
    $query = "
        SELECT COUNT(*) AS count 
        FROM bookings 
        WHERE room_type = :room_type 
        AND (
            (arrival_date <= :departure_date AND departure_date >= :arrival_date)
        )";

    $stmt = $pdo->prepare($query);
    $stmt->execute([
        'room_type' => $room_type,
        'arrival_date' => $arrival_date,
        'departure_date' => $departure_date
    ]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    return $result['count'] == 0; // True if no overlapping booking exists
}
