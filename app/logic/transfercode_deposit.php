<?php

require __DIR__ . '/../../vendor/autoload.php';
require __DIR__ . '/dotenv.php';

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

function depositFunds(string $transferCode, string $hotelManager): array
{
    $client = new Client();
    $url = 'https://www.yrgopelago.se/centralbank/deposit';

    try {
        $response = $client->post($url, [
            'headers' => [
                'Content-Type' => 'application/x-www-form-urlencoded',
            ],
            'form_params' => [
                'user' => $hotelManager,
                'transferCode' => $transferCode,
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
