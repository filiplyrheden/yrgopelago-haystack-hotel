<?php

require __DIR__ . '/../../vendor/autoload.php';
require __DIR__ . '/dotenv.php';

function depositFunds(string $transferCode, string $hotelManager): array
{
    try {
        // Deposit the transfer code
        $depositClient = new GuzzleHttp\Client();
        $depositResponse = $depositClient->request('POST', 'https://www.yrgopelag.se/centralbank/deposit', ['form_params' => [
            'user' => $hotelManager,
            'transferCode' => $transferCode,
        ]]);
        $bankResponseDeposit = json_decode($depositResponse->getBody()->getContents(), true);
        return $bankResponseDeposit;
    } catch (\Exception $e) {
        return ['error' => $e->getMessage()];
    }
}
