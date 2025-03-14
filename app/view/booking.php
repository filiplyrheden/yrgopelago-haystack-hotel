<?php

$database = 'sqlite:' . __DIR__ . '/../../app/database/haystack.db';
$db = new PDO($database);

// Fetch current prices and star rating
$stmt = $db->prepare("SELECT * FROM room_prices");
$stmt->execute();
$roomPrices = $stmt->fetchAll(PDO::FETCH_KEY_PAIR) ?: ['Budget Bale' => 1, 'Comfort Stack' => 2, 'Luxury Loft' => 4];

$stmt = $db->prepare("SELECT * FROM feature_prices");
$stmt->execute();
$featurePrices = $stmt->fetchAll(PDO::FETCH_KEY_PAIR) ?: ['Sauna' => 2, 'Minibar' => 1, 'Yatzy' => 1];

$starRating = $db->query("SELECT star_rating FROM hotel_settings WHERE id = 1")->fetchColumn() ?: 5;

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hotel Booking</title>
    <link rel="stylesheet" href="app/view/style.css">
</head>

<body>
    <header>
        <div class="header-content">
            <a href="index.php" class="logo">Haystack Hotel</a>
        </div>
        <div class="star-container">
            <div class="stars">
                <?php for ($i = 0; $i < $starRating; $i++): ?>
                    <span class="star">★</span>
                <?php endfor; ?>
            </div>
            <div class="star-text">
                <p>A hotel of the Lyrheden Group</p>
            </div>
        </div>
        <div class="header-admin">
            <a href='/haystack-hotel/app/view/admin.php'>Hotel manager account</a>
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
            <img src="assets/budget-bale.png" alt="Budget Room">
            <div class="room-card-content">
                <h3>Budget Bale</h3>
                <p>Perfect for solo travelers. A simple haystack experience.</p>
            </div>
        </div>
        <div class="room-card">
            <img src="assets/comfort-stack.png" alt="Standard Room">
            <div class="room-card-content">
                <h3>Comfort stack</h3>
                <p>A cozy setup with soft blankets and rustic charm.</p>
            </div>
        </div>
        <div class="room-card">
            <img src="assets/luxury-loft.png" alt="Luxury Room">
            <div class="room-card-content">
                <h3>Luxury Loft</h3>
                <p>An elevated experience with premium bedding on a haystack.</p>
            </div>
        </div>
    </section>

    <div class="calendar-headings">
        <h4 id="budget-heading">Budget Bale room availability:</h4>
        <h4 id="standard-heading">Comfort Stack room availability:</h4>
        <h4 id="luxury-heading">Luxury Loft room availability:</h4>
    </div>

    <section class="calendars">
        <div class="calendar" id="budget-calendar">
            <p class="month-label">January 2025</p>
            <!-- Calendar grid content -->
        </div>
        <div class="calendar" id="standard-calendar">
            <p class="month-label">January 2025</p>
            <!-- Calendar grid content -->
        </div>
        <div class="calendar" id="luxury-calendar">
            <p class="month-label">January 2025</p>
            <!-- Calendar grid content -->
        </div>
    </section>

    <h2>Hotel Booking</h2>
    <form method="POST" action="/haystack-hotel/app/logic/database.php">

        <label for="arrival_date">Arrival Date:</label>
        <input type="date" id="arrival_date" name="arrival_date" required min="2025-01-01" max="2025-01-31"><br><br>

        <label for="departure_date">Departure Date:</label>
        <input type="date" id="departure_date" name="departure_date" required min="2025-01-01" max="2025-01-31"><br><br>

        <label for="room_type">Room Type:</label>
        <select id="room_type" name="room_type" required>
            <option value="Budget Bale" data-cost="<?= $roomPrices['Budget Bale'] ?>">Budget Bale (Cost: <?= $roomPrices['Budget Bale'] ?>)</option>
            <option value="Comfort Stack" data-cost="<?= $roomPrices['Comfort Stack'] ?>">Comfort Stack (Cost: <?= $roomPrices['Comfort Stack'] ?>)</option>
            <option value="Luxury Loft" data-cost="<?= $roomPrices['Luxury Loft'] ?>">Luxury Loft (Cost: <?= $roomPrices['Luxury Loft'] ?>)</option>
        </select><br><br>

        <h3>Features:</h3>
        <div class="features">
            <input type="checkbox" id="sauna" name="features[]" value="Sauna" data-cost="<?= $featurePrices['Sauna'] ?>">
            <label for="sauna">Sauna (Cost: <?= $featurePrices['Sauna'] ?>)</label><br>
            <input type="checkbox" id="minibar" name="features[]" value="Minibar" data-cost="<?= $featurePrices['Minibar'] ?>">
            <label for="minibar">Minibar (Cost: <?= $featurePrices['Minibar'] ?>)</label><br>
            <input type="checkbox" id="yatzy" name="features[]" value="Yatzy" data-cost="<?= $featurePrices['Yatzy'] ?>">
            <label for="yatzy">Yatzy (Cost: <?= $featurePrices['Yatzy'] ?>)</label><br><br>
        </div>

        <h4>Transfer code:</h4>
        <input type="text" id="transfer_code" name="transfer_code" required><br><br>

        <div class="checkout">

            <div class="cost-display">
                <span><b>Total cost:</b> </span><span id="total_cost"></span>
                <p id="discount_text"></p>
            </div>

            <div class="special-offer">
                <h6>Limited offer:</h6>
                <p>Book three or more nights and get an exclusive 30% discount on your entire stay.</p>
            </div>

        </div>
        <br>

        <button type="submit">Book Now</button>
    </form>

    <script src="app/view/script.js"></script>
</body>

</html>