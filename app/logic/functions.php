<?php
function isRoomAvailable(string $room_type, string $arrival_date, string $departure_date, PDO $pdo): bool
{
    // We check if there is ANY existing booking that:
    // 1. References the same room type (via the rooms table)
    // 2. Overlaps with the requested arrival/departure date range
    $query = "
        SELECT COUNT(*) AS count
        FROM bookings b
        JOIN bookings_rooms_features brf ON b.id = brf.booking_id
        JOIN rooms r ON r.id = brf.rooms_id
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
