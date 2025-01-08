<?php

require 'utils.php';

use BRI\Util\GenerateRandomString;

// url path values
$baseUrl = 'https://sandbox.partner.api.bri.co.id'; //base url

try {
  list($clientId, $clientSecret, $privateKey) = getCredentials();

  list($accessToken, $timestamp) = getAccessToken(
    $clientId,
    $privateKey,
    $baseUrl
  );

  // change variables accordingly
  $partnerId = ''; //partner id
  $channelId = ''; // channel id

  $partnerReferenceNo = (new GenerateRandomString())->generate(12);
  $url = '';
  $type = ''; // PAY_RETURN/PAY_NOTIFY
  $isDeepLink = ''; // Y/N
  $value = '';
  $currency = '';
  $chargeToken = '';
  $bankCardToken = '';
  $otpStatus = '';
  $settlementAccount = (new GenerateRandomString())->generate(10);//'020601000109305';
  $merchantTrxId = (new GenerateRandomString())->generate(10); //'0206010001';
  $remarks = '';

  $validateInputs = sanitizeInput([
    'partnerId' => $partnerId,
    'channelId' => $channelId,
    'partnerReferenceNo' => $partnerReferenceNo,
    'url' => $url,
    'type' => $type,
    'isDeepLink' => $isDeepLink,
    'value' => $value,
    'currency' => $currency,
    'chargeToken' => $chargeToken,
    'bankCardToken' => $bankCardToken,
    'otpStatus' => $otpStatus,
    'settlementAccount' => $settlementAccount,
    'merchantTrxId' => $merchantTrxId,
    'remarks' => $remarks
  ]);

  file_put_contents('partnerReferenceNo.txt', $validateInputs['partnerReferenceNo']);

  $body = [
    'partnerReferenceNo' => $validateInputs['partnerReferenceNo'],
    'urlParam' => [
      (object) [
        'url' => $validateInputs['url'],
        'type' => $validateInputs['type'],
        'isDeepLink' => $validateInputs['isDeepLink']
      ]
    ],
    'amount' => (object) [
      'value' => $validateInputs['value'],
      'currency' => $validateInputs['currency'],
    ],
    'chargeToken' => $validateInputs['chargeToken'],
    'bankCardToken' => $validateInputs['bankCardToken'],
    'additionalInfo' => (object) [
      'otpStatus' => $validateInputs['otpStatus'],
      'settlementAccount' => $validateInputs['settlementAccount'],
      'merchantTrxId' => $validateInputs['merchantTrxId'],
      'remarks' => $validateInputs['remarks']
    ]
  ];

  $response = fetchPayment(
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

  if (empty($jsonPost['referenceNo'])) {
    return;
  }

  file_put_contents('referenceNo.txt', $jsonPost['referenceNo']);
} catch (Exception $e) {
  error_log('Error: ' . $e->getMessage());
  exit(1);
}
