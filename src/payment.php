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
$partnerId = ''; //partner id
$channelId = ''; // channel id

$getAccessToken = new GetAccessToken();

[$accessToken, $timestamp] = $getAccessToken->get(
  $clientId,
  $pKeyId,
  $baseUrl
);

$directDebit = new DirectDebit();

$partnerReferenceNo = (new GenerateRandomString())->generate(12);
$url = '';
$type = ''; // PAY_RETURN/PAY_NOTIFY
$isDeepLink = 'N'; // Y/N
$value = '';
$currency = '';
$chargeToken = '';
$bankCardToken = '';
$otpStatus = '';
$settlementAccount = (new GenerateRandomString())->generate(15); // '020601000109305';
$merchantTrxId = (new GenerateRandomString())->generate(10); //'0206010001';
$remarks = '';

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
  $clientSecret,
  $partnerId,
  $baseUrl,
  $accessToken,
  $channelId,
  $timestamp,
  $body
);

echo $response;

$jsonPost = json_decode($response, true);

file_put_contents('referenceNo.txt', $jsonPost['referenceNo']);
