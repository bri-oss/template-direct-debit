<?php

use BRI\DirectDebit\DirectDebit;
use BRI\Util\GetAccessToken;

require __DIR__ . '/../../briapi-sdk/autoload.php';

$clientId = '';
$clientSecret = '';

// url path values
$baseUrl = 'https://api.bridex.qore.page/mock'; //base url

$getAccessToken = new GetAccessToken();

$accessToken = $getAccessToken->getMockOutbound(
  $clientId,
  $clientSecret,
  $baseUrl
);


$directDebit = new DirectDebit();

$response = $directDebit->refundNotify(
  $baseUrl,
  $clientId,
  $clientSecret,
  $accessToken
);

echo $response;
