<?php
// requires module php5-curl

$x_auth_token = 'your_api_token';
$api_url = 'https://api.smtplw.com.br/v1';

$from = "sender@domain.com";
$to = array("to@domain.com");
$subject = "subject";
$body = "any body";

$headers = array(
  "x-auth-token: $x_auth_token",
  "Content-type: application/json"
);

$data_string = array(
  'from'    => $from,
  'to'      => $to,
  'subject' => $subject,
  'body'    => $body
);

$ch = curl_init("$api_url/messages");

curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_HEADER, true);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data_string));
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);

$response = curl_exec($ch);
$curlInfo = curl_getinfo($ch);
curl_close($ch);

switch($curlInfo['http_code']) {
  case '201':
    $status = 'OK';
    if (preg_match('@^Location: (.*)$@m', $response, $matches)) {
      $location = trim($matches[1]);
    }
    // Add other actions here, if necessary.
    break;
  default:
    $status = "Error: $curlInfo[http_code]";
    break;
}

echo "\nStatus: {$status}\n";

if ($location) {
  echo "\nLocation: {$location}\n\n";
}

?>