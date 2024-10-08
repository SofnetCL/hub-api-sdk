<?php
require 'vendor/autoload.php';

use HubSdk\HttpApiClient;

$hubApiUrl = 'http://localhost:8101';

$hubApiClient = new HttpApiClient();
$hubApiClient->setBaseUrl($hubApiUrl);

$keyResponse = $hubApiClient->get('key');
$apiKey = $keyResponse['key'];

$hubClient = new HubSdk\HubClient($apiKey, $hubApiUrl);

$devices = $hubClient->devices()->all();

if (!count($devices)) {
    echo "No devices found \n";
    die();
}

$device = $devices[0];

$operations = $hubClient->devices()->getOperations($device->getSerial());

print_r($operations);
