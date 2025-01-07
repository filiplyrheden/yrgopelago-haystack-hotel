<?php

require __DIR__ . '/../../app/logic/dotenv.php';
require __DIR__ . '/../../vendor/autoload.php';

echo "Welcome $hotelManager!";

$database = 'sqlite:' . __DIR__ . '/../../app/database/haystack.db';
$db = new PDO($database);

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Update room prices
        $stmt = $db->prepare("CREATE TABLE IF NOT EXISTS room_prices (
            room_type TEXT PRIMARY KEY,
            price REAL NOT NULL
        )");
        $stmt->execute();

        $roomTypes = ['Budget', 'Standard', 'Luxury'];
        foreach ($roomTypes as $type) {
            $price = $_POST["price_$type"] ?? 0;
            $stmt = $db->prepare("INSERT OR REPLACE INTO room_prices (room_type, price) VALUES (:type, :price)");
            $stmt->execute(['type' => $type, 'price' => $price]);
        }

        // Update feature prices
        $stmt = $db->prepare("CREATE TABLE IF NOT EXISTS feature_prices (
            feature_name TEXT PRIMARY KEY,
            price REAL NOT NULL
        )");
        $stmt->execute();

        $features = ['Sauna', 'Minibar', 'Yatzy'];
        foreach ($features as $feature) {
            $price = $_POST["price_feature_$feature"] ?? 0;
            $stmt = $db->prepare("INSERT OR REPLACE INTO feature_prices (feature_name, price) VALUES (:name, :price)");
            $stmt->execute(['name' => $feature, 'price' => $price]);
        }

        // Update discount settings
        $stmt = $db->prepare("CREATE TABLE IF NOT EXISTS discount_settings (
            id INTEGER PRIMARY KEY CHECK (id = 1),
            min_days INTEGER NOT NULL,
            discount_percentage REAL NOT NULL
        )");
        $stmt->execute();

        $minDays = $_POST['min_days'] ?? 3;
        $discountPercentage = $_POST['discount_percentage'] ?? 30;
        $stmt = $db->prepare("INSERT OR REPLACE INTO discount_settings (id, min_days, discount_percentage) 
                            VALUES (1, :min_days, :discount_percentage)");
        $stmt->execute(['min_days' => $minDays, 'discount_percentage' => $discountPercentage]);

        echo "<p class='success'>Settings updated successfully!</p>";
    } catch (PDOException $e) {
        echo "<p class='error'>Error updating settings: " . $e->getMessage() . "</p>";
    }
}

// Fetch current values
try {
    $roomPrices = $db->query("SELECT * FROM room_prices")->fetchAll(PDO::FETCH_KEY_PAIR) ?: [
        'Budget' => 1,
        'Standard' => 2,
        'Luxury' => 4
    ];

    $featurePrices = $db->query("SELECT * FROM feature_prices")->fetchAll(PDO::FETCH_KEY_PAIR) ?: [
        'Sauna' => 2,
        'Minibar' => 1,
        'Yatzy' => 1
    ];

    $discountSettings = $db->query("SELECT * FROM discount_settings WHERE id = 1")->fetch() ?: [
        'min_days' => 3,
        'discount_percentage' => 30
    ];
} catch (PDOException $e) {
    // Use default values if tables don't exist yet
    $roomPrices = ['Budget' => 1, 'Standard' => 2, 'Luxury' => 4];
    $featurePrices = ['Sauna' => 2, 'Minibar' => 1, 'Yatzy' => 1];
    $discountSettings = ['min_days' => 3, 'discount_percentage' => 30];
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - Price Management</title>
    <link rel="stylesheet" href="app/view/admin_style.css">
</head>

<body>
    <div class="container">
        <h1>Hotel Management</h1>

        <form method="POST">
            <div class="form-section">
                <h2>Room Prices</h2>
                <?php foreach ($roomPrices as $type => $price): ?>
                    <div class="form-group">
                        <label for="price_<?= $type ?>"><?= $type ?> Room:</label>
                        <input type="number"
                            id="price_<?= $type ?>"
                            name="price_<?= $type ?>"
                            value="<?= htmlspecialchars((string)$price) ?>"
                            step="1"
                            min="0"
                            required>
                    </div>
                <?php endforeach; ?>
            </div>

            <div class="form-section">
                <h2>Feature Prices</h2>
                <?php foreach ($featurePrices as $feature => $price): ?>
                    <div class="form-group">
                        <label for="price_feature_<?= $feature ?>"><?= $feature ?>:</label>
                        <input type="number"
                            id="price_feature_<?= $feature ?>"
                            name="price_feature_<?= $feature ?>"
                            value="<?= htmlspecialchars((string)$price) ?>"
                            step="1"
                            min="0"
                            required>
                    </div>
                <?php endforeach; ?>
            </div>

            <div class="form-section">
                <h2>Discount Settings</h2>
                <div class="form-group">
                    <label for="min_days">Minimum days for discount:</label>
                    <input type="number"
                        id="min_days"
                        name="min_days"
                        value="<?= htmlspecialchars((string)$discountSettings['min_days']) ?>"
                        min="1"
                        required>
                </div>
                <div class="form-group">
                    <label for="discount_percentage">Discount percentage:</label>
                    <input type="number"
                        id="discount_percentage"
                        name="discount_percentage"
                        value="<?= htmlspecialchars((string)$discountSettings['discount_percentage']) ?>"
                        min="0"
                        max="100"
                        step="1"
                        required>
                </div>
            </div>

            <button type="submit">Save Changes</button>
        </form>

        <a href="/../../index.php">Return to booking page</a>

    </div>
</body>

</html>