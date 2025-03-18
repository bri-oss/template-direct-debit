<?php

require 'utils.php';

header("Strict-Transport-Security: max-age=31536000; includeSubDomains; preload");
header("Content-Security-Policy: default-src 'self'; script-src 'self';");
header("X-Content-Type-Options: nosniff");

// url path values
$baseUrl = 'https://sandbox.partner.api.bri.co.id';

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

  if (!file_exists('partnerReferenceNo.txt') || !file_exists('referenceNo.txt')) {
    throw new Exception("Please payment direct debit first");
  }

  // change variables accordingly
  $partnerId = ''; //partner id
  $channelId = ''; // channel id

  $originalPartnerReferenceNo = trim(file_get_contents('partnerReferenceNo.txt'));
  $originalReferenceNo = trim(file_get_contents('referenceNo.txt'));
  $partnerRefundNo = trim(file_get_contents('partnerReferenceNo.txt'));
  $value = '';
  $currency = '';
  $reason = '';
  $callbackUrl = '';
  $settlementAccount = '';

  $validateInputs = sanitizeInput([
    'partnerId' => $partnerId,
    'channelId' => $channelId,
    'originalPartnerReferenceNo' => $originalPartnerReferenceNo,
    'originalReferenceNo' => $originalReferenceNo,
    'partnerRefundNo' => $partnerRefundNo,
    'value' => $value,
    'currency' => $currency,
    'reason' => $reason,
    'callbackUrl' => $callbackUrl,
    'settlementAccount' => $settlementAccount
  ]);

  $body = [
    'originalPartnerReferenceNo' => $validateInputs['originalPartnerReferenceNo'],
    'originalReferenceNo' => $validateInputs['originalReferenceNo'],
    'partnerRefundNo' => $validateInputs['partnerRefundNo'],
    'refundAmount' => (object) [
      'value' => $validateInputs['value'],
      'currency' => $validateInputs['currency']
    ],
    'reason' => $validateInputs['reason'],
    'additionalInfo' => (object) [
      'callbackUrl' => $validateInputs['callbackUrl'],
      'settlementAccount' => $validateInputs['settlementAccount']
    ]
  ];

  $response = fetchRefundPayment(
    $clientSecret, 
    $partnerId,
    $baseUrl,
    $accessToken, 
    $channelId,
    $timestamp,
    $body
  );

  echo $response;
} catch (InvalidArgumentException $e) {
  // Handle specific exception
  error_log("Invalid argument: " . $e->getMessage());
} catch (RuntimeException $e) {
  // Handle runtime exception
  error_log("Runtime exception: " . $e->getMessage());
} catch (Exception $e) {
  // Fallback for unexpected exceptions
  error_log("Generic exception: " . $e->getMessage());
  exit(1);
}
