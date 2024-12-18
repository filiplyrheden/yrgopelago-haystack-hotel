<?php

require __DIR__ . '/../../vendor/autoload.php';
require __DIR__ . '/dotenv.php';

function depositFunds(string $transferCode, string $hotelManager, int $days): array
{
    try {
        // Deposit the transfer code
        $depositClient = new GuzzleHttp\Client();
        $depositResponse = $depositClient->request('POST', 'https://www.yrgopelag.se/centralbank/deposit', ['form_params' => [
            'user' => $hotelManager,
            'transferCode' => $transferCode,
            'numberOfDays' => $days
        ]]);
        $bankResponseDeposit = json_decode($depositResponse->getBody()->getContents(), true);
        file_put_contents('deposit_response.json', json_encode($bankResponseDeposit), FILE_APPEND); //save the response as json in a separate file for debugging reasons
        return $bankResponseDeposit;
    } catch (\Exception $e) {
        return ['error' => $e->getMessage()];
    }
}
