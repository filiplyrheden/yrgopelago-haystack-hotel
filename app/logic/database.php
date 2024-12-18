<?php

declare(strict_types=1);

require __DIR__ . '/functions.php';
require __DIR__ . '/transfercode_validation.php';
require __DIR__ . '/transfercode_deposit.php';
require __DIR__ . '/../../vendor/autoload.php';
require __DIR__ . '/dotenv.php';

$database = 'sqlite:' . __DIR__ . '/../database/haystack.db';

// Create SQLite database and table
try {
    $db = new PDO($database);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Create or update table schema
    $db->exec("CREATE TABLE IF NOT EXISTS bookings (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        arrival_date TEXT NOT NULL,
        departure_date TEXT NOT NULL,
        room_type TEXT NOT NULL,
        transfer_code TEXT NOT NULL,
        total_cost REAL NOT NULL
    )");
} catch (PDOException $e) {
    die("Database error: " . $e->getMessage());
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $arrivalDate = $_POST['arrival_date'] ?? '';
    $departureDate = $_POST['departure_date'] ?? '';
    $roomType = $_POST['room_type'] ?? '';
    $transferCode = $_POST['transfer_code'] ?? '';
    $features = $_POST['features'] ?? [];

    $roomCosts = ['Budget' => 1, 'Standard' => 2, 'Luxury' => 4];
    $featureCosts = ['Sauna' => 2, 'Minibar' => 1];

    if ($arrivalDate && $departureDate && $roomType && $transferCode) {
        // Calculate total cost
        $roomCost = $roomCosts[$roomType] ?? 0;
        $featureCost = 0;
        foreach ($features as $feature) {
            $featureCost += $featureCosts[$feature] ?? 0;
        }

        $arrivalDateObj = new DateTime($arrivalDate);
        $departureDateObj = new DateTime($departureDate);
        $days = $departureDateObj->diff($arrivalDateObj)->days;

        $totalCost = ($roomCost * $days) + $featureCost;

        // Check if the transfer code is valid
        if (isTransferCodeValid($transferCode, $totalCost)) {
            // Call depositFunds function to deposit funds to the central bank
            $depositResponse = depositFunds($transferCode, $days);

            // Check the response from depositFunds
            if (is_array($depositResponse) && isset($depositResponse['status']) && $depositResponse['status'] === 'success') {
                // Deposit was successful, proceed with booking
                if (isRoomAvailable($roomType, $arrivalDate, $departureDate, $db)) {
                    try {
                        $stmt = $db->prepare("INSERT INTO bookings (arrival_date, departure_date, room_type, transfer_code, total_cost) 
                                  VALUES (:arrival_date, :departure_date, :room_type, :transfer_code, :total_cost)");
                        $stmt->bindParam(':arrival_date', $arrivalDate);
                        $stmt->bindParam(':departure_date', $departureDate);
                        $stmt->bindParam(':room_type', $roomType);
                        $stmt->bindParam(':transfer_code', $transferCode);
                        $stmt->bindParam(':total_cost', $totalCost);
                        $stmt->execute();

                        echo "<p>Booking successfully saved! Total cost: $totalCost</p>";
                    } catch (PDOException $e) {
                        echo "<p>Error saving booking: " . $e->getMessage() . "</p>";
                    }
                } else {
                    echo "<p>Room not available.</p>";
                }
            } else {
                // Deposit failed, display the error message
                $errorMessage = is_array($depositResponse) ? ($depositResponse['message'] ?? 'Unknown error') : $depositResponse;
                echo "<p>Deposit failed: $errorMessage</p>";
            }
        } else {
            echo "<p>Transfer code not valid.</p>";
        }
    } else {
        echo "<p>Please fill in all fields.</p>";
    }
}
