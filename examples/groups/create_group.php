<?php
require 'vendor/autoload.php';

use HubSdk\HttpApiClient;

$hubApiUrl = 'http://localhost:8101';

$hubApiClient = new HttpApiClient();
$hubApiClient->setBaseUrl($hubApiUrl);

$keyResponse = $hubApiClient->get('key');
$apiKey = $keyResponse['key'];

$hubClient = new HubSdk\HubClient($apiKey, $hubApiUrl);

$groups = $hubClient->groups()->create(
    code: 'group1',
    name: 'Group 1',
);

print_r($groups);
