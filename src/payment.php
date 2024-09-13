<?php

require __DIR__ . '/../vendor/autoload.php';

Dotenv\Dotenv::createUnsafeImmutable(__DIR__ . '/..' . '')->load();

require __DIR__ . '/../../briapi-sdk/autoload.php';

use BRI\Util\GetAccessToken;
use BRI\DirectDebit\DirectDebit;
use BRI\Util\GenerateRandomString;

$clientId = $_ENV['CONSUMER_KEY']; // customer key
$clientSecret = $_ENV['CONSUMER_SECRET']; // customer secret
$pKeyId = $_ENV['PRIVATE_KEY']; // private key

// url path values
$baseUrl = 'https://sandbox.partner.api.bri.co.id'; //base url

// change variables accordingly
$partnerId = 'cM8GIPvoEpzLaHQfvL1e9g'; //partner id
$channelId = '12345'; // channel id

$getAccessToken = new GetAccessToken();

[$accessToken, $timestamp] = $getAccessToken->get(
  $clientId,
  $pKeyId,
  $baseUrl
);

$directDebit = new DirectDebit();

$partnerReferenceNo = (new GenerateRandomString())->generate(12);
$url = 'https://5fdc5f1948321c00170119e0.mockapi.io/api/v1/simulation/simulation';
$type = 'PAY_NOTIFY'; // PAY_RETURN/PAY_NOTIFY
$isDeepLink = 'N'; // Y/N
$value = '10000.00';
$currency = 'IDR';
$chargeToken = 'null';
$bankCardToken = 'card_.eyJpYXQiOjE3MDgwNTAzNTYsImlzcyI6IkJhbmsgQlJJIC0gRENFIiwianRpIjoiNmY2MmE4ZjUtMGUwMS00NjFjLWJlZmQtYjk3ZWE5YjNmMmIwIiwicGFydG5lcklkIjoi77-9Iiwic2VydmljZU5hbWUiOiJERF9FWFRFUk5BTF9TRVJWSUNFIn0.HR4P9PecyfCZLJ-ibeuxuuWtHzWHrzgunjxiEQJZEjZHO2fQqrMgaO8IUnmACtNJilGOpIQAc7Jsa5W_tCF4KmIpC5jB-tDw40tpqImZ9Famt_hzgacrDcByw2jT9UAPMH444kGAQa7z44PV6jcHdQoaIAfiOkChHw-b11Vg4LyETbsEExvOcL2hKomG_JXpDq5bYmuHcJ2SJ8lRnGomi-7oz_dyM0_wUe1fmE6UyLnvEFz6o6q8nXtm_3g29cLP_4uw5BT54DuSXrRdmw4J7PK3zl2qUnM7CBpYVRLr74iCx9SLGYIMMROE7aGe_DkNfK-dnLKgcvIaN0q-rnLbhg';
$otpStatus = 'NO';
$settlementAccount = (new GenerateRandomString())->generate(15); // '020601000109305';
$merchantTrxId = (new GenerateRandomString())->generate(10); //'0206010001';
$remarks = 'test';

file_put_contents('partnerReferenceNo.txt', $partnerReferenceNo);

$body = [
  'partnerReferenceNo' => $partnerReferenceNo,
  'urlParam' => [
    (object) [
      'url' => $url,
      'type' => $type,
      'isDeepLink' => $isDeepLink
    ]
  ],
  'amount' => (object) [
    'value' => $value,
    'currency' => $currency,
  ],
  'chargeToken' => $chargeToken,
  'bankCardToken' => $bankCardToken,
  'additionalInfo' => (object) [
    'otpStatus' => $otpStatus,
    'settlementAccount' => $settlementAccount,
    'merchantTrxId' => $merchantTrxId,
    'remarks' => $remarks
  ]
];

$response = $directDebit->payment(
  $clientSecret = $clientSecret, 
  $partnerId = $partnerId, 
  $baseUrl,
  $accessToken, 
  $channelId,
  $timestamp,
  $body
);

echo $response;

$jsonPost = json_decode($response, true);

file_put_contents('referenceNo.txt', $jsonPost['referenceNo']);
