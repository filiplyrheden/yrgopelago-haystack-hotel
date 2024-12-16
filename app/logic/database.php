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

// Retrieve data from $_POST
$transferCode = $_POST['transfer_code'] ?? null;
$arrivalDate = $_POST['arrival_date'] ?? null;
$departureDate = $_POST['departure_date'] ?? null;

// Validate input
if (empty($transferCode) || empty($arrivalDate) || empty($departureDate)) {
    die("Required fields are missing!");
}

// Calculate total cost
$totalCost = 0;
$roomNames = []; // Array to hold room names

// Handle room costs
if (isset($_POST['room'])) {
    foreach ($_POST['room'] as $room) {
        // Add room cost if it exists
        if (!empty($room['cost'])) {
            $totalCost += (int)$room['cost'];
        }
        // Add room name if it exists
        if (!empty($room['name'])) {
            $roomNames[] = $room['name'];
        }
    }
}

// Handle feature costs
if (isset($_POST['features'])) {
    foreach ($_POST['features'] as $feature) {
        if (!empty($feature['cost'])) {
            $totalCost += (int)$feature['cost'];
        }
    }
}

// Combine room names into a single string
$room = implode(', ', $roomNames); // Example: "Standard, Deluxe"

// Insert data into the bookings table
try {
    // Prepare the SQL statement
    $stmt = $pdo->prepare('
        INSERT INTO bookings (transfer_code, room_id, arrival_date, departure_date, total_cost) 
        VALUES (:transfer_code, :room_id, :arrival_date, :departure_date, :total_cost)
    ');

    // Bind values to the placeholders
    $stmt->bindValue(':transfer_code', $transferCode, PDO::PARAM_STR);
    $stmt->bindValue(':room_id', $room, PDO::PARAM_STR);
    $stmt->bindValue(':arrival_date', $arrivalDate, PDO::PARAM_STR);
    $stmt->bindValue(':departure_date', $departureDate, PDO::PARAM_STR);
    $stmt->bindValue(':total_cost', $totalCost, PDO::PARAM_INT);

    // Execute the statement
    $stmt->execute();

    echo "Booking successfully saved!";
} catch (PDOException $e) {
    echo "Error inserting data: " . $e->getMessage();
}
