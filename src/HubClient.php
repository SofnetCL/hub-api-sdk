<?php

namespace HubSdk;

use HubSdk\Domains\Group;
use HubSdk\Domains\Device;
use HubSdk\Domains\User;

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

    public function groups(): Group
    {
        return new Group($this->httpApiClient);
    }

    public function devices(): Device
    {
        return new Device($this->httpApiClient);
    }

    public function users(): User
    {
        return new User($this->httpApiClient);
    }
}
