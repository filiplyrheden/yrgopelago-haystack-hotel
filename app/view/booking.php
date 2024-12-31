<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hotel Booking</title>
    <link rel="stylesheet" href="/app//view/style.css">
</head>

<body>
    <header>
        <div class="header-content">
            <a href="#" class="logo">Haystack Hotel</a>
            <nav>
                <ul>
                    <li><a href="#rooms">Rooms</a></li>
                    <li><a href="#booking">Booking</a></li>
                    <li><a href="#about">About</a></li>
                    <li><a href="#contact">Contact</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <section class="hero">
        <div class="hero-content">
            <h1>Welcome to Haystack Hotel</h1>
            <p>Experience rustic luxury in the heart of the countryside</p>
        </div>
    </section>

    <h2 id="rooms-heading">Room Types</h2>
    <section class="room-cards">
        <div class="room-card">
            <img src="/assets/budget-bale.png" alt="Budget Room">
            <div class="room-card-content">
                <h3>Budget Bale</h3>
                <p>Perfect for solo travelers. A simple haystack experience.</p>
            </div>
        </div>
        <div class="room-card">
            <img src="/assets/comfort-stack.png" alt="Standard Room">
            <div class="room-card-content">
                <h3>Comfort stack</h3>
                <p>A cozy setup with soft blankets and rustic charm.</p>
            </div>
        </div>
        <div class="room-card">
            <img src="/assets/luxury-loft.png" alt="Luxury Room">
            <div class="room-card-content">
                <h3>Luxury Loft</h3>
                <p>An elevated experience with premium bedding on a haystack.</p>
            </div>
        </div>
    </section>

    <h2>Hotel Booking</h2>
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

        <div class="cost-display">
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