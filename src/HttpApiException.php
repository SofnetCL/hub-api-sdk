<?php

namespace HubSdk;

use Exception;

class HttpApiException extends Exception
{
    protected int $httpStatusCode;
    protected mixed $request;

    public function __construct(string $message, int $httpStatusCode = 0, mixed $request = null, Exception $previous = null)
    {
        $this->httpStatusCode = $httpStatusCode;
        $this->request = $request;
        parent::__construct($message, 0, $previous);
    }

    public function getHttpStatusCode(): int
    {
        return $this->httpStatusCode;
    }

    public function getRequest(): mixed
    {
        return $this->request;
    }

    public function __toString(): string
    {
        $error = __CLASS__ . ": [{$this->httpStatusCode}]: {$this->message}\n";
        if ($this->request) {
            $error .= "Request: " . json_encode($this->request, JSON_PRETTY_PRINT) . "\n";
        }

        return $error;
    }
}
