<?php

declare(strict_types=1);

require __DIR__ . '/room_availability.php';
require __DIR__ . '/transfercode_validation.php';
require __DIR__ . '/transfercode_deposit.php';
require __DIR__ . '/../../vendor/autoload.php';
require __DIR__ . '/dotenv.php';
require __DIR__ . '/json_receipt.php';

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
        transfer_code TEXT NOT NULL,
        total_cost REAL NOT NULL
    )");

    $db->exec("CREATE TABLE IF NOT EXISTS rooms (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        room_type TEXT NOT NULL,
        room_cost REAL NOT NULL
    )");

    $db->exec("CREATE TABLE IF NOT EXISTS features (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        feature_name TEXT NOT NULL,
        feature_cost REAL NOT NULL
    )");

    $db->exec("CREATE TABLE IF NOT EXISTS booking_room_feature (
    booking_id INTEGER NOT NULL,
    room_id INTEGER NOT NULL,
    feature_id INTEGER NOT NULL,
    FOREIGN KEY (booking_id) REFERENCES bookings(id),
    FOREIGN KEY (room_id) REFERENCES rooms(id),
    FOREIGN KEY (feature_id) REFERENCES features(id),
    PRIMARY KEY (booking_id, room_id, feature_id)
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

    $roomCosts = ['Budget Bale' => 1, 'Comfort Stack' => 2, 'Luxury Loft' => 4];
    $featureCosts = ['Sauna' => 3, 'Minibar' => 1, 'Yatzy' => 1];

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

        $totalCost = ($roomCost * ($days + 1)) + $featureCost;

        // Apply 30% discount for bookings of three days or more
        if ($days >= 2) {
            $totalCost *= 0.7;
        }

        // Check if the transfer code is valid
        if (isTransferCodeValid($transferCode, $totalCost)) {
            if (isRoomAvailable($roomType, $arrivalDate, $departureDate, $db)) {
                try {
                    // Insert booking
                    $stmt = $db->prepare("INSERT INTO bookings (arrival_date, departure_date, transfer_code, total_cost) 
                        VALUES (:arrival_date, :departure_date, :transfer_code, :total_cost)");
                    $stmt->bindParam(':arrival_date', $arrivalDate);
                    $stmt->bindParam(':departure_date', $departureDate);
                    $stmt->bindParam(':transfer_code', $transferCode);
                    $stmt->bindParam(':total_cost', $totalCost);
                    $stmt->execute();
                    $bookingId = $db->lastInsertId();

                    // Insert room
                    $stmt = $db->prepare("INSERT INTO rooms (room_type, room_cost) 
                        VALUES (:room_type, :room_cost)");
                    $stmt->bindParam(':room_type', $roomType);
                    $stmt->bindParam(':room_cost', $roomCost);
                    $stmt->execute();
                    $roomId = $db->lastInsertId();

                    // Verify booking and room IDs exist before junction table insert
                    if (!$bookingId || !$roomId) {
                        throw new PDOException("Failed to get valid IDs for junction table insert");
                    }

                    // Insert the initial booking-room relationship without features
                    $junctionStmt = $db->prepare("INSERT INTO booking_room_feature (booking_id, room_id, feature_id) 
                        VALUES (:booking_id, :room_id, 0)"); // Using 0 as a default feature_id
                    $junctionStmt->bindValue(':booking_id', $bookingId);
                    $junctionStmt->bindValue(':room_id', $roomId);
                    $junctionStmt->execute();

                    // If features were selected, add them as well
                    if (!empty($features)) {
                        foreach ($features as $feature) {
                            // Insert feature
                            $featureStmt = $db->prepare("INSERT INTO features (feature_name, feature_cost)
                                VALUES (:feature_name, :feature_cost)");
                            $featureStmt->bindValue(':feature_name', $feature);
                            $featureStmt->bindValue(':feature_cost', $featureCosts[$feature]);
                            $featureStmt->execute();
                            $featureId = $db->lastInsertId();

                            if (!$featureId) {
                                throw new PDOException("Failed to get valid feature ID");
                            }

                            // Insert additional feature relationships into junction table
                            $junctionStmt = $db->prepare("INSERT INTO booking_room_feature (booking_id, room_id, feature_id)
                                VALUES (:booking_id, :room_id, :feature_id)");
                            $junctionStmt->bindValue(':booking_id', $bookingId);
                            $junctionStmt->bindValue(':room_id', $roomId);
                            $junctionStmt->bindValue(':feature_id', $featureId);
                            $junctionStmt->execute();
                        }
                    }

                    //Deposits funds from transfercode into hotel managers account.
                    depositFunds($transferCode, $hotelManager);

                    echo "<p>Booking successfully saved! Total cost: $totalCost</p>";
                    echo "<p>Thank you for choosing $hotel. Here's your receipt:</p>";
                    echo '<pre>' . json_encode($response, JSON_PRETTY_PRINT) . '</pre>';
                } catch (PDOException $e) {
                    echo "<p>Error saving booking: " . $e->getMessage() . "</p>";
                }
            } else {
                echo "<p>Room not available.</p>";
            }
        } else {
            echo "<p>Transfer code not valid.</p>";
        }
    } else {
        echo "<p>Please fill in all fields.</p>";
    }
}
