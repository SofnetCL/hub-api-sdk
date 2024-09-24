<?php

namespace HubSdk;

class HubClient
{
    private HttpApiClient $httpApiClient;

    public function __construct(string $apiKey, string $apiUrl)
    {
        $this->httpApiClient = new HttpApiClient($apiKey, $apiUrl);
    }

    public function getDevices(): array
    {
        $response = $this->httpApiClient->get('/devices');
        $devices = $response['data'];

        return array_map(function ($device) {
            return new Device(
                $device['serial'],
                $device['alias'],
                $device['model'],
                $device['group'],
                $device['push_version'],
                $device['firmware_version'],
                $device['timezone'],
                $device['status'],
            );
        }, $devices);
    }
}
