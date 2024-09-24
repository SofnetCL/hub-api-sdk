<?php

namespace HubSdk\Tests;

use HubSdk\HubClient;
use HubSdk\HttpApiClient;
use HubSdk\Device;
use PHPUnit\Framework\TestCase;

class HubClientTest extends TestCase
{
    private HubClient $hubClient;
    private $httpApiClientMock;

    protected function setUp(): void
    {
        // Crear un mock de HttpApiClient
        $this->httpApiClientMock = $this->createMock(HttpApiClient::class);

        // Inyectar el mock en HubClient usando reflexión
        $this->hubClient = new HubClient('fake_api_key', 'https://api.example.com');
        $reflection = new \ReflectionClass($this->hubClient);
        $httpApiClientProperty = $reflection->getProperty('httpApiClient');
        $httpApiClientProperty->setAccessible(true);
        $httpApiClientProperty->setValue($this->hubClient, $this->httpApiClientMock);
    }

    public function testGetDevicesReturnsDevices()
    {
        $mockResponse = [
            'data' => [
                [
                    'serial' => '12345',
                    'alias' => 'Device 1',
                    'model' => 'Model A',
                    'group' => 'Group 1',
                    'push_version' => '1.0.0',
                    'firmware_version' => '1.0.0',
                    'timezone' => 'UTC',
                    'status' => 'active',
                ],
            ]
        ];

        $this->httpApiClientMock->method('get')
            ->willReturn($mockResponse);

        // Llama al método getDevices
        $devices = $this->hubClient->getDevices();

        // Verifica que se devuelvan los objetos Device correctos
        $this->assertCount(1, $devices);
        $this->assertInstanceOf(Device::class, $devices[0]);
        $this->assertEquals('12345', $devices[0]->getSerial());
        $this->assertEquals('Device 1', $devices[0]->getAlias());
        // Verifica otros campos según sea necesario
    }
}
