<?php

require __DIR__ . '/../../vendor/autoload.php'; // Include Composer autoloader

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

$client = new Client(); // Create a Guzzle client

$url = 'https://www.yrgopelago.se/centralbank/transferCode'; // API endpoint

try {
    // Send a POST request
    $response = $client->post($url, [
        'headers' => [
            'Content-Type' => 'application/json',
        ],
        'json' => [ // The 'json' option automatically encodes to JSON
            'transferCode' => '0fbb28c2-cb34-4017-8d72-e2e39892e86f',
            'totalcost' => 11,
        ],
    ]);

    // Decode the JSON response
    $responseData = json_decode($response->getBody(), true);

    // Output the response
    echo "Response from API:\n";
    print_r($responseData);
} catch (RequestException $e) {
    // Handle request errors
    echo "Error occurred:\n";

    if ($e->hasResponse()) {
        echo $e->getResponse()->getBody();
    } else {
        echo $e->getMessage();
    }
}
