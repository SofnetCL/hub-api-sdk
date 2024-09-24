<?php

namespace HubSdk;

use Exception;

class HttpApiException extends Exception
{
    protected int $httpStatusCode;

    public function __construct(string $message, int $httpStatusCode = 0, Exception $previous = null)
    {
        $this->httpStatusCode = $httpStatusCode;
        parent::__construct($message, 0, $previous);
    }

    public function getHttpStatusCode(): int
    {
        return $this->httpStatusCode;
    }

    public function __toString(): string
    {
        return __CLASS__ . ": [{$this->httpStatusCode}]: {$this->message}\n";
    }
}
