<?php

namespace HubSdk\Domains;

use HubSdk\Device as DeviceEntity;
use HubSdk\DeviceOperation;
use HubSdk\HttpApiClient;
use HubSdk\Operation;

class Device
{
    public function __construct(
        private HttpApiClient $httpApiClient
    ) {}

    /**
     * @return DeviceEntity[]
     */
    public function all(): array
    {
        $devices = $this->httpApiClient->get('devices');

        return array_map(
            fn($device) => new DeviceEntity(
                $device['serial'],
                $device['alias'],
                $device['model'],
                $device['group'],
                $device['push_version'],
                $device['firmware_version'],
                $device['timezone'],
                $device['status'],
            ),
            $devices
        );
    }

    /**
     * @return Operation[]
     */
    public function getOperations(string $serial): array
    {
        $operations = $this->httpApiClient->get("/devices/$serial/operations");

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

    public function reboot(string $serial): bool
    {
        return $this->executeDeviceOperation($serial, DeviceOperation::Reboot);
    }

    public function reload(string $serial): bool
    {
        return $this->executeDeviceOperation($serial, DeviceOperation::Reload);
    }

    public function checkInfo(string $serial): bool
    {
        return $this->executeDeviceOperation($serial, DeviceOperation::Info);
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
