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

$getAccessToken = new GetAccessToken();

[$accessToken, $timestamp] = $getAccessToken->get(
  $clientId,
  $pKeyId,
  $baseUrl
);

$directDebit = new DirectDebit();

$originalPartnerReferenceNo = '815027979003';
$originalReferenceNo = '574929794216';
$serviceCode = '54';

$body = [
  'originalPartnerReferenceNo' => $originalPartnerReferenceNo,
  'originalReferenceNo' => $originalReferenceNo,
  'serviceCode' => $serviceCode
];


$response = $directDebit->paymentStatus(
  $clientSecret = $clientSecret, 
  $partnerId = $partnerId,
  $baseUrl,
  $accessToken, 
  $channelId,
  $timestamp,
  $body
);

echo $response;


