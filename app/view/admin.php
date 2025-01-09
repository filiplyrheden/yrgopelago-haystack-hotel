<?php

require __DIR__ . '/../../app/logic/dotenv.php';

// Check if the form was submitted and the api-key is the same as the one in the .env file
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($_POST['password'] === $apiKey) {
        header('Location: /app/view/admin_panel.php');
        exit();
    } else {
        echo 'Access denied.';
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin login</title>
</head>

<body>

    <h1>Please enter API key:</h1>
    <form method="POST" action="/app/view/admin.php">
        <label for="password">Password:</label>
        <input type="password" name="password" id="password">
        <button type="submit">Login</button>
    </form>

</body>

</html>