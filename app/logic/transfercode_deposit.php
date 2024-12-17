<?php

require __DIR__ . '/../../vendor/autoload.php'; // Include Composer autoloader

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

$client = new Client(); // Create a Guzzle client

$url = 'https://www.yrgopelago.se/centralbank/deposit'; // API endpoint

try {
    // Send a POST request
    $response = $client->post($url, [
        'headers' => [
            'Content-Type' => 'application/x-www-form-urlencoded',
        ],
        'form_params' => [
            'user' => 'Filip',
            'transferCode' => '271d59a7-4ca5-4799-87cd-8a48f5eaef31',
            'numberOfDays' => 1,
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
