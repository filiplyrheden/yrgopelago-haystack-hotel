<?php

require __DIR__ . '/../../vendor/autoload.php';

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

function isTransferCodeValid(string $transferCode, float $totalCost): bool
{
    $client = new Client(); // Create a Guzzle client
    $url = 'https://www.yrgopelago.se/centralbank/transferCode'; // API endpoint

    try {
        // Send a POST request
        $response = $client->post($url, [
            'headers' => [
                'Content-Type' => 'application/json',
            ],
            'json' => [
                'transferCode' => $transferCode,
                'totalcost' => $totalCost,
            ],
        ]);

        // Decode the JSON response
        $responseData = json_decode($response->getBody(), true);

        // Check if the response status is 'success'
        return isset($responseData['status']) && $responseData['status'] === 'success';
    } catch (RequestException $e) {
        // Log or handle request errors as needed
        echo "Error occurred:\n";
        if ($e->hasResponse()) {
            echo $e->getResponse()->getBody();
        } else {
            echo $e->getMessage();
        }
        return false;
    }
}

// Example usage
// $transferCode = '0fbb28c2-cb34-4017-8d72-e2e39892e86f';
// $totalCost = 10;

// if (isTransferCodeValid($transferCode, $totalCost)) {
//     echo "The transfer code is valid.\n";
// } else {
//     echo "The transfer code is invalid.\n";
// }
