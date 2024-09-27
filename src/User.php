<?php

namespace HubSdk;

class User
{
    public function __construct(
        private string $pin,
        private string $name,
        private string $privilege,
        private string $password,
    ) {}

    public function getPin(): string
    {
        return $this->pin;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getPrivilege(): string
    {
        return $this->privilege;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): void
    {
        $this->password = $password;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function setPrivilege(string $privilege): void
    {
        $this->privilege = $privilege;
    }
}
