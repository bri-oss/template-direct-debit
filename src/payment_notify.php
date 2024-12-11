<?php

require __DIR__ . '/../../briapi-sdk/autoload.php';

use BRI\DirectDebit\DirectDebit;
use BRI\Util\GetAccessToken;

require __DIR__ . '/../vendor/autoload.php';
Dotenv\Dotenv::createUnsafeImmutable(__DIR__ . '/..' . '')->load();

$clientId = 'YOWoKgXf5KcATtetyq7NbfxOz6FR65Un';
$clientSecret = 'super_secret';
$privateKey = $_ENV['PRIVATE_KEY'];

// url path values
$baseUrl = 'https://api.bridex.qore.page/mock'; //base url

$getAccessToken = new GetAccessToken();

$accessToken = $getAccessToken->getMockOutbound(
  $clientId,
  $baseUrl,
  $privateKey
);

$directDebit = new DirectDebit();

$response = $directDebit->paymentNotify(
  $baseUrl,
  $clientId,
  $clientSecret,
  $accessToken
);

echo $response;
