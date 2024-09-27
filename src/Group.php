<?php

namespace HubSdk;

class Group
{
    public function __construct(
        private string $code,
        private string $name,
        private ?string $tenantSubdomain,
        private string $timezone,
        private bool $syncing,
        private ?array $users,
        private ?array $devices,
    ) {}

    public function getCode(): string
    {
        return $this->code;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getSyncing(): bool
    {
        return $this->syncing;
    }

    public function getTenantSubdomain(): ?string
    {
        return $this->tenantSubdomain;
    }

    public function setTenantSubdomain(string $tenantSubdomain): void
    {
        $this->tenantSubdomain = $tenantSubdomain;
    }

    public function setTimeZone(string $timezone): void
    {
        $this->timezone = $timezone;
    }

    public function getTimezone(): string
    {
        return $this->timezone;
    }

    /**
     * @return User[]
     */
    public function getUsers(): array
    {
        return array_map(
            fn(array $user) => new User(
                $user['pin'],
                $user['name'],
                $user['privilege'],
                $user['password'],
            ),
            $this->users
        );
    }

    /**
     * @return Device[]
     */
    public function getDevices(): array
    {
        return array_map(
            fn(array $device) => new Device(
                $device['serial_number'],
                $device['alias'],
                $device['model'],
                $device['group'],
                $device['pushVersion'],
                $device['firmwareVersion'],
                $device['timezone'],
                $device['status'],
            ),
            $this->devices
        );
    }

    public function getDevicesSerials(): array
    {
        return array_map(
            fn(array $device) => $device['serial_number'],
            $this->devices
        );
    }

    public function removeDevice(string $serial): bool
    {
        $this->devices = array_filter(
            $this->devices,
            fn(array $device) => $device['serial_number'] !== $serial
        );

        return true;
    }

    public function addDevice(string $serial): bool
    {
        $this->devices[] = $serial;

        return true;
    }
}
