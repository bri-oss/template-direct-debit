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

  // change variables accordingly
  $partnerId = ''; //partner id
  $channelId = ''; // channel id

  $originalPartnerReferenceNo = '';
  $originalReferenceNo = '';
  $serviceCode = '';
  
  $validateInputs = sanitizeInput([
    'partnerId' => $partnerId,
    'channelId' => $channelId,
    'originalPartnerReferenceNo' => $originalPartnerReferenceNo,
    'originalReferenceNo' => $originalReferenceNo,
    'serviceCode' => $serviceCode
  ]);

  $body = [
    'originalPartnerReferenceNo' => $validateInputs['originalPartnerReferenceNo'],
    'originalReferenceNo' => $validateInputs['originalReferenceNo'],
    'serviceCode' => $validateInputs['serviceCode']
  ];

  $response = paymentStatus(
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
