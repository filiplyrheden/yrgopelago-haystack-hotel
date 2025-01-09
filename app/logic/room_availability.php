<?php

declare(strict_types=1);

// Check if a room is available for booking
function isRoomAvailable(string $room_type, string $arrival_date, string $departure_date, PDO $pdo): bool
{
    $query = "
        SELECT COUNT(*) AS count
        FROM bookings b
        JOIN booking_room_feature brf ON b.id = brf.booking_id
        JOIN rooms r ON r.id = brf.room_id
        WHERE r.room_type = :room_type
          AND (
               b.arrival_date <= :departure_date 
               AND b.departure_date >= :arrival_date
          )
    ";

    $stmt = $pdo->prepare($query);
    $stmt->execute([
        'room_type'       => $room_type,
        'arrival_date'    => $arrival_date,
        'departure_date'  => $departure_date
    ]);

    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    return ($result['count'] == 0);
}
