<?php

namespace HubSdk;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

class HttpApiClient
{
    protected array $headers = [];
    protected Client $httpClient;
    protected string $baseUrl;

    public function __construct(?Client $httpClient = null)
    {
        $this->httpClient = $httpClient ?? new Client();
    }

    public function setHeaders(array $headers): void
    {
        $this->headers = $headers;
    }

    public function setBaseUrl(string $baseUrl): void
    {
        $this->baseUrl = $baseUrl;
    }

    public function get(string $endpoint, array $queryParams = []): array
    {
        try {
            $response = $this->httpClient->get($this->buildUrl($endpoint), [
                'headers' => $this->getHeaders(),
                'query' => $queryParams,
            ]);

            if ($response->getStatusCode() !== 200) {
                throw new HttpApiException(
                    "API GET ERROR: response was not 200, was: " . $response->getStatusCode(),
                    $response->getStatusCode()
                );
            }

            return json_decode($response->getBody()->getContents(), true);
        } catch (RequestException $e) {
            throw new HttpApiException("Error en la API GET: " . $e->getMessage(), $e->getResponse()->getStatusCode());
        }
    }

    public function post(string $endpoint, array $data = []): array
    {
        try {
            $response = $this->httpClient->post($this->buildUrl($endpoint), [
                'headers' => $this->getHeaders(),
                'json' => $data,
            ]);

            if ($response->getStatusCode() !== 201 && $response->getStatusCode() !== 200) {
                throw new HttpApiException(
                    "API POST ERROR: response was not 201 or 200, was: " . $response->getStatusCode(),
                    $response->getStatusCode()
                );
            }

            return json_decode($response->getBody()->getContents(), true);
        } catch (RequestException $e) {
            throw new HttpApiException("Error en la API POST: " . $e->getMessage(), $e->getResponse()->getStatusCode());
        }
    }

    public function put(string $endpoint, array $data = []): array
    {
        try {
            $response = $this->httpClient->put($this->buildUrl($endpoint), [
                'headers' => $this->getHeaders(),
                'json' => $data,
            ]);

            if ($response->getStatusCode() !== 200 && $response->getStatusCode() !== 204) {
                throw new HttpApiException(
                    "API PUT ERROR: response was not 200 or 204, was: " . $response->getStatusCode(),
                    $response->getStatusCode()
                );
            }

            return json_decode($response->getBody()->getContents(), true);
        } catch (RequestException $e) {
            throw new HttpApiException("Error en la API PUT: " . $e->getMessage(), $e->getResponse()->getStatusCode());
        }
    }

    public function delete(string $endpoint): array
    {
        try {
            $response = $this->httpClient->delete($this->buildUrl($endpoint), [
                'headers' => $this->getHeaders(),
            ]);

            if ($response->getStatusCode() !== 204) {
                throw new HttpApiException(
                    "API DELETE ERROR: response was not 204, was: " . $response->getStatusCode(),
                    $response->getStatusCode()
                );
            }

            return json_decode($response->getBody()->getContents(), true);
        } catch (RequestException $e) {
            throw new HttpApiException("Error en la API DELETE: " . $e->getMessage(), $e->getResponse()->getStatusCode());
        }
    }

    protected function getHeaders(): array
    {
        return $this->headers;
    }

    protected function buildUrl(string $endpoint): string
    {
        return rtrim($this->baseUrl, '/') . '/' . ltrim($endpoint, '/');
    }
}
