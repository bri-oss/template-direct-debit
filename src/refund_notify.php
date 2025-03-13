<?php

require 'utils.php';

header("Strict-Transport-Security: max-age=31536000; includeSubDomains; preload");
header("Content-Security-Policy: default-src 'self'; script-src 'self';");
header("X-Content-Type-Options: nosniff");

// url path values
$baseUrl = 'https://api.briapidevstudio.dev.bbri.io/mock'; //base url

try {
  if (!str_starts_with($baseUrl, 'https://')) {
    throw new Exception('Base URL must use HTTPS');
  }

  list($clientId, $clientSecret, $privateKey) = getCredentials();

  $accessToken = getMockAccessToken(
    $clientId,
    $baseUrl,
    $privateKey
  );

  $response = fetchRefundNotify(
    $baseUrl,
    $clientId,
    $clientSecret,
    $accessToken
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
