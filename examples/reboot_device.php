<?php
require 'vendor/autoload.php';

use HubSdk\HttpApiClient;

$hubApiUrl = 'http://localhost:8101';

$hubApiClient = new HttpApiClient();
$hubApiClient->setBaseUrl($hubApiUrl);

$keyResponse = $hubApiClient->get('key');
$apiKey = $keyResponse['key'];

$hubClient = new HubSdk\HubClient($apiKey, $hubApiUrl);

$devices = $hubClient->getDevices();

$operation = $hubClient->rebootDevice($devices[0]->getSerial());

print_r($operation);
