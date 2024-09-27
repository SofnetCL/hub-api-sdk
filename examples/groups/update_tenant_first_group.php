<?php
require 'vendor/autoload.php';

use HubSdk\HttpApiClient;

$hubApiUrl = 'http://localhost:8101';

$hubApiClient = new HttpApiClient();
$hubApiClient->setBaseUrl($hubApiUrl);

$keyResponse = $hubApiClient->get('key');
$apiKey = $keyResponse['key'];

$hubClient = new HubSdk\HubClient($apiKey, $hubApiUrl);

$groups = $hubClient->groups()->all();

$tenantSubdomain = 'tenant1';

if (!count($groups)) {
    echo "No groups found \n";
    die();
}
$group = $groups[0];

$group = $hubClient->groups()->get($group->getCode());
$group->setTenantSubdomain($tenantSubdomain);

try {
    $hubClient->groups()->update($group);
} catch (Exception $e) {
    echo $e->getMessage();
    die();
}

$group = $hubClient->groups()->get($group->getCode());
print_r($group);
