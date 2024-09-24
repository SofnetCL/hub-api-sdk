<?php

namespace HubSdk;

class Operation
{
    public function __construct(
        private int $id,
        private string $command,
        private string $content,
        private string $status,
        private string $createdAt,
        private ?string $executedAt,
    ) {}

    public function getId(): int
    {
        return $this->id;
    }

    public function getCommand(): string
    {
        $prefixToRemove = 'App\\Devices\\Commands\\';

        return str_replace($prefixToRemove, '', $this->command);
    }

    public function getContent(): string
    {
        return $this->content;
    }

    public function getStatus(): OperationStatus
    {
        return new OperationStatus($this->status);
    }

    public function getCreatedAt(): string
    {
        return $this->createdAt;
    }

    public function getExecutedAt(): ?string
    {
        return $this->executedAt;
    }
}
