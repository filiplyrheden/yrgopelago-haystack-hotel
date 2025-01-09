<?php

declare(strict_types=1);

require __DIR__ . '/../../vendor/autoload.php';
require __DIR__ . '/dotenv.php';

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use InvalidArgumentException;

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

        // Convert StreamInterface to string before decoding
        $responseBody = $response->getBody()->getContents();

        // Decode JSON response
        return json_decode($responseBody, true);
    } catch (RequestException $e) {
        // Handle request errors
        $errorMessage = $e->hasResponse()
            ? $e->getResponse()->getBody()->getContents()
            : $e->getMessage();

        // Return an error response
        return ['status' => 'error', 'message' => $errorMessage];
    }
}
