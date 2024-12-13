<?php

declare(strict_types=1);

require __DIR__ . '/booking-form.php';

$database = __DIR__ . '/../database/haystack.db';

$pdo = new PDO("sqlite:" . $database);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $arrivalDate = $_POST['arrival_date'] ?? null;
    $departureDate = $_POST['departure_date'] ?? null;
    $roomId = "Luxury";
    $transferCode = $_POST['transfer_code'];
    $totalCost = 1000;

    // Validate required fields
    if ($arrivalDate && $departureDate && $roomId && $transferCode && $totalCost) {
        try {
            // Prepare SQL query
            $query = "INSERT INTO bookings (transfer_code, room_id, arrival_date, departure_date, total_cost)
                      VALUES (:transfer_code, :room_id, :arrival, :departure, :total_cost)";

            $stmt = $pdo->prepare($query);

            // Bind parameters
            $stmt->bindParam(':transfer_code', $transferCode);
            $stmt->bindParam(':room_id', $roomId);
            $stmt->bindParam(':arrival', $arrivalDate);
            $stmt->bindParam(':departure', $departureDate);
            $stmt->bindParam(':total_cost', $totalCost);

            // Execute the query
            $stmt->execute();

            echo "Booking successfully added!";
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    } else {
        echo "Please fill in all fields.";
    }
}
