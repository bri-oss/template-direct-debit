<?php

require __DIR__ . '/../vendor/autoload.php';

Dotenv\Dotenv::createUnsafeImmutable(__DIR__ . '/..' . '')->load();

require __DIR__ . '/../../briapi-sdk/autoload.php';

use BRI\Util\GetAccessToken;
use BRI\DirectDebit\DirectDebit;

$clientId = $_ENV['CONSUMER_KEY']; // customer key
$clientSecret = $_ENV['CONSUMER_SECRET']; // customer secret
$pKeyId = $_ENV['PRIVATE_KEY']; // private key

// url path values
$baseUrl = 'https://sandbox.partner.api.bri.co.id'; //base url

// change variables accordingly
$partnerId = 'cM8GIPvoEpzLaHQfvL1e9g'; //partner id
$channelId = '12345'; // channel id

if (!file_exists('partnerReferenceNo.txt') || !file_exists('referenceNo.txt')) {
  echo "Please payment direct debit first";
  return;
}

$getAccessToken = new GetAccessToken();

[$accessToken, $timestamp] = $getAccessToken->get(
  $clientId,
  $pKeyId,
  $baseUrl
);

$directDebit = new DirectDebit();

$originalPartnerReferenceNo = trim(file_get_contents('partnerReferenceNo.txt'));
$originalReferenceNo = trim(file_get_contents('referenceNo.txt'));
$partnerRefundNo = trim(file_get_contents('partnerReferenceNo.txt'));
$value = '10000.00';
$currency = 'IDR';
$reason = 'test';
$callbackUrl = 'https://5fdc5f1948321c00170119e0.mockapi.io/api/v1/simulation/simulation';
$settlementAccount = '020601000109305';

$body = [
  'originalPartnerReferenceNo' => $originalPartnerReferenceNo,
  'originalReferenceNo' => $originalReferenceNo,
  'partnerRefundNo' => $partnerRefundNo,
  'refundAmount' => (object) [
    'value' => $value,
    'currency' => $currency
  ],
  'reason' => $reason,
  'additionalInfo' => (object) [
    'callbackUrl' => $callbackUrl,
    'settlementAccount' => $settlementAccount
  ]
];

$response = $directDebit->refundPayment(
  $clientSecret = $clientSecret, 
  $partnerId = $partnerId,
  $baseUrl,
  $accessToken, 
  $channelId,
  $timestamp,
  $body
);

echo $response;
