<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hotel Booking</title>
</head>

<body>
    <form method="POST" action="app/logic/booking-form.php">

        <label for="arrival_date">Arrival Date:</label>
        <input type="date" id="arrival_date" name="arrival_date" required min="2025-01-01" max="2025-01-31"><br>

        <label for="departure_date">Departure Date:</label>
        <input type="date" id="departure_date" name="departure_date" required min="2025-01-01" max="2025-01-31"><br>

        <fieldset>
            <legend>Rooms:</legend>
            <label>
                <input type="checkbox" name="room[0][name]" value="Budget">
                Budget (Cost: 1)
                <input type="hidden" name="room[0][cost]" value="1">
            </label><br>
            <label>
                <input type="checkbox" name="room[1][name]" value="Standard">
                Standard (Cost: 2)
                <input type="hidden" name="room[1][cost]" value="2">
            </label><br>
            <label>
                <input type="checkbox" name="room[2][name]" value="Luxury">
                Luxury (Cost: 4)
                <input type="hidden" name="room[2][cost]" value="4">
            </label><br>
        </fieldset>

        <fieldset>
            <legend>Features:</legend>
            <label>
                <input type="checkbox" name="features[0][name]" value="sauna">
                Sauna (Cost: 2)
                <input type="hidden" name="features[0][cost]" value="2">
            </label><br>
        </fieldset>

        <fieldset>
            <label>
                Transfer code:
                <input type="text" name="transfer_code">
            </label><br>
        </fieldset>


        <button type="submit">Book Now</button>
    </form>
</body>