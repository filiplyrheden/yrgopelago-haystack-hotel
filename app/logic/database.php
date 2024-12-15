<?php

declare(strict_types=1);

$database = 'sqlite:' . __DIR__ . '/../database/haystack.db';

// Database connection
try {
    $pdo = new PDO($database);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Could not connect to the database: " . $e->getMessage());
}

$transferCode = 123;
$arrivalDate = "2025-01-01";
$departureDate = "2025-01-02";
$totalCost = 5;
$roomName = "Luxury";


// Validate inputs
try {
    // Prepare an SQL statement with placeholders
    $stmt = $pdo->prepare('INSERT INTO bookings (transfer_code, room_id, arrival_date, departure_date, total_cost) VALUES (:transfer_code, :room_id, :arrival_date, :departure_date, :total_cost)');

    // Bind values to the placeholders
    $stmt->bindValue(':transfer_code', $transferCode, PDO::PARAM_STR);
    $stmt->bindValue(':arrival_date', $arrivalDate, PDO::PARAM_STR);
    $stmt->bindValue(':departure_date', $departureDate, PDO::PARAM_STR);
    $stmt->bindValue(':total_cost', $totalCost, PDO::PARAM_INT);
    $stmt->bindValue(':room_id', $roomName, PDO::PARAM_STR);

    // Execute the statement
    $stmt->execute();

    echo "Data inserted successfully!";
} catch (PDOException $e) {
    echo "Error inserting data: " . $e->getMessage();
}
