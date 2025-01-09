<?php

declare(strict_types=1);

// Check if the form was submitted
if (isset($_POST['reset']) && $_POST['reset'] === 'true') {
    echo "Database reset triggered!<br>";

    try {
        // Connect to the database
        $database = 'sqlite:' . __DIR__ . '/../../app/database/haystack.db';
        $db = new PDO($database);
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Array of tables to reset
        $tables = ['rooms', 'bookings', 'features', 'booking_room_feature'];

        // Loop through tables and execute DELETE queries
        foreach ($tables as $table) {
            $query = "DELETE FROM $table"; // SQL to delete all rows
            $db->exec($query); // Execute the query
            echo "Cleared table: $table<br>";
        }

        echo "Database reset successfully!";
    } catch (PDOException $e) {
        echo "Error resetting the database: " . $e->getMessage();
    }
}
