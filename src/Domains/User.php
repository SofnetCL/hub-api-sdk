<?php

namespace HubSdk\Domains;

use HubSdk\Device;
use HubSdk\User as UserEntity;
use HubSdk\HttpApiClient;

class User
{
    public function __construct(
        private HttpApiClient $httpApiClient
    ) {}

    /**
     * @return UserEntity[]
     */
    public function all(Device $device): array
    {
        $users = $this->httpApiClient->get("/devices/{$device->getSerial()}/users");

        return array_map(
            fn($user) => new UserEntity(
                $user['pin'],
                $user['name'],
                $user['privilege'],
                $user['password']
            ),
            $users
        );
    }

    /**
     * @return UserEntity
     */
    public function get(Device $device, string $userPin): UserEntity
    {
        $user = $this->httpApiClient->get("/devices/{$device->getSerial()}/users/{$userPin}");

        return new UserEntity(
            $user['pin'],
            $user['name'],
            $user['privilege'],
            $user['password']
        );
    }

    public function enroll(Device $device, string $userPin): bool
    {
        $response = $this->httpApiClient->post("/devices/{$device->getSerial()}/users/{$userPin}/enroll");

        if ($response['message'] === 'enrolled') {
            return true;
        }

        return false;
    }

    public function create(Device $device, string $pin, string $name, string $privilege, string $password): UserEntity
    {
        $response = $this->httpApiClient->post("/devices/{$device->getSerial()}/users", [
            'pin' => $pin,
            'name' => $name,
            'privilege' => $privilege,
            'password' => $password,
        ]);

        return new UserEntity(
            $response['pin'],
            $response['name'],
            $response['privilege'],
            $response['password']
        );
    }
}
