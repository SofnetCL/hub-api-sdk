<?php

namespace HubSdk;

class Device
{
    public function __construct(
        private string $serial,
        private ?string $alias,
        private ?string $model,
        private ?string $group,
        private ?string $pushVersion,
        private ?string $firmwareVersion,
        private string $timezone,
        private string $status,
    ) {}

    public function getSerial(): string
    {
        return $this->serial;
    }

    public function getAlias(): string
    {
        return $this->alias;
    }

    public function setAlias(string $alias): void
    {
        $this->alias = $alias;
    }

    public function getModel(): ?string
    {
        return $this->model;
    }

    public function setModel(string $model): void
    {
        $this->model = $model;
    }

    public function getGroup(): ?string
    {
        return $this->group;
    }

    public function setGroup(string $group): void
    {
        $this->group = $group;
    }

    public function getPushVersion(): ?string
    {
        return $this->pushVersion;
    }

    public function getFirmwareVersion(): ?string
    {
        return $this->firmwareVersion;
    }

    public function getTimezone(): string
    {
        return $this->timezone;
    }

    public function getStatus(): DeviceStatus
    {
        return new DeviceStatus($this->status);
    }

    public function setStatus(DeviceStatus $status): void
    {
        $this->status = $status->value;
    }

    public function toArray(): array
    {
        return [
            'alias' => $this->alias,
            'model' => $this->model,
            'group' => $this->group,
            'push_version' => $this->pushVersion,
            'firmware_version' => $this->firmwareVersion,
            'timezone' => $this->timezone,
            'status' => $this->status,
        ];
    }
}
