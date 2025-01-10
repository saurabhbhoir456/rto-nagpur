<?php
require 'vendor/autoload.php';
$client = new GuzzleHttp\Client();
$response = $client->get('https://www.smsgatewayhub.com/smsapi/mis.aspx', [
    'query' => [
        'user' => 'rtovardha',
        'password' => 'Disha@8765',
        'fromdate' => '01/08/2025',
        'todate' => '01/08/2025',
        'format' => 'json'
    ]
]);
echo $response->getBody();
?>
