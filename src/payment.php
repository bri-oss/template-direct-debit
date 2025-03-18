<?php

require 'utils.php';

use BRI\Util\VarNumber;

header("Strict-Transport-Security: max-age=31536000; includeSubDomains; preload");
header("Content-Security-Policy: default-src 'self'; script-src 'self';");
header("X-Content-Type-Options: nosniff");

// url path values
$baseUrl = 'https://sandbox.partner.api.bri.co.id'; //base url

try {
  if (!str_starts_with($baseUrl, 'https://')) {
    throw new Exception('Base URL must use HTTPS');
  }

  list($clientId, $clientSecret, $privateKey) = getCredentials();

  list($accessToken, $timestamp) = getAccessToken(
    $clientId,
    $privateKey,
    $baseUrl
  );

  // change variables accordingly
  $partnerId = ''; //partner id
  $channelId = ''; // channel id

  $partnerReferenceNo = (new VarNumber())->generateVar(7);
  $url = '';
  $type = ''; // PAY_RETURN/PAY_NOTIFY
  $isDeepLink = ''; // Y/N
  $value = '';
  $currency = '';
  $chargeToken = '';
  $bankCardToken = '';
  $otpStatus = '';
  $settlementAccount = ''; //(new GenerateRandomString())->generate(10);//'020601000109305';
  $merchantTrxId = '';//(new GenerateRandomString())->generate(10); //'0206010001';
  $remarks = '';

  if (!preg_match('/^[a-zA-Z0-9]+$/', $partnerReferenceNo)) {
    throw new Exception('Invalid partnerReferenceNo');
  }

  if (!filter_var($url, FILTER_VALIDATE_URL)) {
    throw new Exception('Invalid URL');
  }

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
    'remarks' => strip_tags($remarks)
  ]);

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
      'merchantTrxId' => $merchantTrxId,
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
    throw new Exception("referenceNo not found");
  }

  if (empty($jsonPost['partnerReferenceNo'])) {
    throw new Exception("partnerReferenceNo not found");
  }

  file_put_contents('referenceNo.txt', htmlspecialchars($jsonPost['referenceNo'], ENT_QUOTES, 'UTF-8'), LOCK_EX);
  file_put_contents('partnerReferenceNo.txt', htmlspecialchars($jsonPost['partnerReferenceNo'], ENT_QUOTES, 'UTF-8'), LOCK_EX);
} catch (InvalidArgumentException $e) {
  // Handle specific exception
  error_log("\nInvalid argument: " . $e->getMessage());
} catch (RuntimeException $e) {
  // Handle runtime exception
  error_log("\nRuntime exception: " . $e->getMessage());
} catch (Exception $e) {
  // Fallback for unexpected exceptions
  error_log("\nGeneric exception: " . $e->getMessage());
  exit(1);
}
