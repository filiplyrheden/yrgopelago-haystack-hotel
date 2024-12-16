<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hotel Booking</title>
</head>

<body>
    <h1>Hotel Booking</h1>
    <form method="POST" action="/app/logic/database.php">
        <label for="arrival_date">Arrival Date:</label>
        <input type="date" id="arrival_date" name="arrival_date" required min="2025-01-01" max="2025-01-31"><br><br>

        <label for="departure_date">Departure Date:</label>
        <input type="date" id="departure_date" name="departure_date" required min="2025-01-01" max="2025-01-31"><br><br>

        <label for="room_type">Room Type:</label>
        <select id="room_type" name="room_type" required>
            <option value="Budget">Budget (Cost: 1)</option>
            <option value="Standard">Standard (Cost: 2)</option>
            <option value="Luxury">Luxury (Cost: 4)</option>
        </select><br><br>

        <label>Features:</label><br>
        <input type="checkbox" id="sauna" name="features[]" value="Sauna">
        <label for="sauna">Sauna (Cost: 2)</label><br>
        <input type="checkbox" id="minibar" name="features[]" value="Minibar">
        <label for="minibar">Minibar (Cost: 1)</label><br><br>

        <label for="transfer_code">Transfer Code:</label>
        <input type="text" id="transfer_code" name="transfer_code" required><br><br>

        <button type="submit">Book Now</button>
    </form>
</body>