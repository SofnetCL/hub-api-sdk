<?php

namespace HubSdk;

class HubClient
{
    private HttpApiClient $httpApiClient;

    public function __construct(string $apiKey, string $apiUrl)
    {
        $this->httpApiClient = new HttpApiClient();
        $this->httpApiClient->setHeaders([
            'Authorization' => "ApiKey $apiKey",
        ]);
        $this->httpApiClient->setBaseUrl($apiUrl);
    }

    /**
     * @return Device[]
     */
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

    /**
     * @return Operation[]
     */
    public function getDeviceOperations(string $serial): array
    {
        $response = $this->httpApiClient->get("/devices/$serial/operations");
        $operations = $response['data'];

        return array_map(function ($operation) {
            return new Operation(
                $operation['id'],
                $operation['command_class'],
                $operation['content'],
                $operation['status'],
                $operation['created_at'],
                $operation['executed_at'],
            );
        }, $operations);
    }

    public function rebootDevice(string $serial): bool
    {
        return $this->executeDeviceOperation($serial, DeviceOperation::Reboot);
    }

    public function reloadDevice(string $serial): bool
    {
        return $this->executeDeviceOperation($serial, DeviceOperation::Reload);
    }

    public function executeDeviceOperation(string $serial, DeviceOperation $operation): bool
    {
        $operation_ = $operation->value;
        $response = $this->httpApiClient->post("/devices/$serial/operations/$operation_");

        if ($response['data'] === 'Operation enqueued') {
            return true;
        }

        return false;
    }
}
