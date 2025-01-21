<?php

require 'utils.php';

// url path values
$baseUrl = 'https://sandbox.partner.api.bri.co.id'; //base url

try {
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

} catch (Exception $e) {
  error_log('Error: ' . $e->getMessage());
  exit(1);
}
