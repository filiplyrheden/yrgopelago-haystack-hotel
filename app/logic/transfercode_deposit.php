<?php

require __DIR__ . '/../../vendor/autoload.php';
require __DIR__ . '/dotenv.php';

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

function depositFunds(string $transferCode, int $days)
{
    $client = new Client(); // Create a Guzzle client
    $url = 'https://www.yrgopelago.se/centralbank/deposit'; // API endpoint

    try {
        // Send a POST request
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

        // Decode the JSON response
        $responseData = json_decode($response->getBody(), true);

        return $responseData;
    } catch (RequestException $e) {
        // Handle request errors
        if ($e->hasResponse()) {
            return $e->getResponse()->getBody()->getContents();
        } else {
            return $e->getMessage();
        }
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
