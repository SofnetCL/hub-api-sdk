<?php

namespace HubSdk\Domains;

use HubSdk\Group as GroupEntity;
use HubSdk\HttpApiClient;

class Group
{
    public function __construct(
        private HttpApiClient $httpApiClient
    ) {}

    /**
     * @return GroupEntity[]
     */
    public function all(): array
    {
        $response = $this->httpApiClient->get('groups');

        return array_map(function ($group) {
            return new GroupEntity(
                $group['code'],
                $group['name'],
                $group['tenant_subdomain'],
                $group['timezone'],
                $group['syncing'],
                $group['users'] ?? [],
                $group['devices'] ?? [],
            );
        }, $response);
    }

    public function get(string $code): GroupEntity
    {
        $response = $this->httpApiClient->get("groups/$code");

        return new GroupEntity(
            $response['code'],
            $response['name'],
            $response['tenant_subdomain'],
            $response['timezone'],
            $response['syncing'],
            $response['users'] ?? [],
            $response['devices'] ?? [],
        );
    }

    public function create(string $code, string $name, ?string $timezone = null, ?array $devices = []): bool
    {
        $data = [
            'code' => $code,
            'name' => $name,
            'timezone' => $timezone,
            'devices' => $devices,
        ];

        // remove null values
        $data = array_filter($data, fn($value) => $value !== null && $value !== '');

        $response = $this->httpApiClient->post('groups', $data);
        $responseMessage = $response['message'] ?? '';

        return $responseMessage === 'Group created';
    }

    public function update(GroupEntity $group): bool
    {
        $code = $group->getCode();

        $updateData = [
            'name' => $group->getName(),
            'tenant_subdomain' => $group->getTenantSubdomain(),
            'timezone' => $group->getTimezone(),
            'devices' => $group->getDevicesSerials(),
        ];

        $updateData = array_filter($updateData, fn($value) => $value !== null || $value !== '' || $value !== []);

        $response = $this->httpApiClient->patch("groups/$code", $updateData);

        $responseMessage = $response['message'] ?? '';

        return $responseMessage === 'Group updated';
    }

    public function addUser(string $code, string $pin): bool
    {
        $response = $this->httpApiClient->post("groups/$code/users", [
            'pin' => $pin,
        ]);

        $responseMessage = $response['message'] ?? '';

        return $responseMessage === 'User added to group';
    }
}
