<?php

require __DIR__ . '/../../vendor/autoload.php';
require __DIR__ . '/dotenv.php';

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

function depositFunds(string $transferCode, int $days)
{
    $client = new Client();
    $url = 'https://www.yrgopelago.se/centralbank/deposit';

    try {
        $response = $client->post($url, [
            'headers' => [
                'Content-Type' => 'application/x-www-form-urlencoded',
            ],
            'form_params' => [
                'user' => $_ENV['user'],
                'transferCode' => $transferCode,
                'numberOfDays' => $days,
            ],
        ]);

        // Decode JSON response
        return json_decode($response->getBody(), true);
    } catch (RequestException $e) {
        // Handle request errors
        $errorMessage = $e->hasResponse()
            ? $e->getResponse()->getBody()->getContents()
            : $e->getMessage();

        // Return an error response
        return ['status' => 'error', 'message' => $errorMessage];
    }
}

// Example usage
// $transferCode = '271d59a7-4ca5-4799-87cd-8a48f5eaef31';
// $days = 1;
// $result = depositFunds($transferCode, $days);

// if (is_array($result)) {
//     echo "Transaction successful:\n";
//     print_r($result);
// } else {
//     echo "Transaction failed: $result\n";
// }
