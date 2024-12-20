<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hotel Booking</title>
    <link rel="stylesheet" href="/app//view/style.css">
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
            <option value="Budget" data-cost="1">Budget (Cost: 1)</option>
            <option value="Standard" data-cost="2">Standard (Cost: 2)</option>
            <option value="Luxury" data-cost="4">Luxury (Cost: 4)</option>
        </select><br><br>

        <label>Features:</label><br>
        <input type="checkbox" id="sauna" name="features[]" value="Sauna" data-cost="2">
        <label for="sauna">Sauna (Cost: 2)</label><br>
        <input type="checkbox" id="minibar" name="features[]" value="Minibar" data-cost="1">
        <label for="minibar">Minibar (Cost: 1)</label><br>
        <input type="checkbox" id="yatzy" name="features[]" value="Yatzy" data-cost="1">
        <label for="yatzy">Yatzy (Cost: 1)</label><br><br>

        <label for="transfer_code">Transfer Code:</label>
        <input type="text" id="transfer_code" name="transfer_code" required><br><br>

        <div>
            <span>Total cost: </span><span id="total_cost"></span>
            <p id="discount_text"></p>
        </div>
        <br>

        <button type="submit">Book Now</button>
    </form>

    <h2>Available Dates in January</h2>
    <div class="calendar" id="calendar"></div>

    <script src="/app/view/script.js"></script>
</body>

</html>